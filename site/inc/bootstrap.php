<?php
declare(strict_types=1);
session_start();

$cfgFile = __DIR__ . '/../config.php';
if (!file_exists($cfgFile)) {
    http_response_code(500);
    exit('Configuration manquante : copiez config.php.example en config.php.');
}
$CONFIG = require $cfgFile;

function db(): PDO {
    static $pdo = null;
    global $CONFIG;
    if (!$pdo) {
        $pdo = new PDO(
            "mysql:host={$CONFIG['db_host']};dbname={$CONFIG['db_name']};charset=utf8mb4",
            $CONFIG['db_user'], $CONFIG['db_pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function cfg(string $k) { global $CONFIG; return $CONFIG[$k] ?? null; }
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function htg($n): string { return number_format((int)$n, 0, ',', ' ') . ' HTG'; }

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Session expirée, rechargez la page.');
    }
}

function normalize_phone(string $p): string { return preg_replace('/\D+/', '', $p); }
// Numéro pour wa.me : ajoute 509 si le numéro haïtien est à 8 chiffres.
function wa_number(string $p): string {
    $p = normalize_phone($p);
    return strlen($p) === 8 ? '509' . $p : $p;
}
function wa_link(string $phone, string $text): string {
    return 'https://wa.me/' . wa_number($phone) . '?text=' . rawurlencode($text);
}

function current_provider(): ?array {
    if (empty($_SESSION['pid'])) return null;
    $st = db()->prepare('SELECT * FROM providers WHERE id = ?');
    $st->execute([$_SESSION['pid']]);
    return $st->fetch() ?: null;
}
function require_login(): array {
    $p = current_provider();
    if (!$p) { header('Location: /login.php'); exit; }
    return $p;
}
function require_admin(): array {
    $p = require_login();
    if (!$p['is_admin']) { http_response_code(403); exit('Accès refusé.'); }
    return $p;
}
function is_visible(array $p): bool {
    return $p['status'] === 'active' && $p['paid_until'] && $p['paid_until'] >= date('Y-m-d');
}
function flash(?string $msg = null): ?string {
    if ($msg !== null) { $_SESSION['flash'] = $msg; return null; }
    $m = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $m;
}


function photo_url(int $id): ?string {
    return is_file(__DIR__ . '/../assets/pros/' . $id . '.jpg') ? '/assets/pros/' . $id . '.jpg' : null;
}
function avatar_html(string $name, int $id, int $size = 56): string {
    if (is_file(__DIR__ . '/../assets/pros/' . $id . '.jpg')) {
        return '<img class="avatar" src="/assets/pros/' . $id . '.jpg" alt="' . e($name) . '" width="' . $size . '" height="' . $size . '" loading="lazy" style="width:' . $size . 'px;height:' . $size . 'px">';
    }
    $cols = ['#F7931E', '#4C8BF5', '#2E9E5B', '#8E5BD6', '#E8590C', '#D6457A'];
    $parts = preg_split('/\s+/', trim(preg_replace('/\(.*?\)/u', '', $name)));
    $ini = '';
    foreach (array_slice($parts, 0, 2) as $w) { $ini .= mb_strtoupper(mb_substr($w, 0, 1)); }
    return '<span class="avatar" style="width:' . $size . 'px;height:' . $size . 'px;font-size:' . (int)($size * 0.36) . 'px;background:' . $cols[$id % count($cols)] . '">' . e($ini) . '</span>';
}
function header_html(string $title = '', string $path = ''): void {
    $me = current_provider();
    $name = cfg('site_name');
    $fl = flash();
    echo '<!doctype html><html lang="fr"><head><meta charset="utf-8">'
       . '<meta name="viewport" content="width=device-width,initial-scale=1">'
       . '<meta name="theme-color" content="#F7931E">'
       . '<title>' . e($title ? "$title · $name" : "$name · Tout arranger près de chez vous") . '</title>'
       . '<link rel="stylesheet" href="' . $path . '/assets/style.css?v=' . (int)@filemtime(__DIR__ . '/../assets/style.css') . '"></head><body>'
       . '<header class="top"><a class="logo" href="/"><img src="' . $path . '/assets/logo.png?v=2" alt="' . e($name) . '" width="120" height="40"></a><nav>'
       . '<a class="hide-m" href="/providers.php">Services</a>';
    if ($me) {
        if ($me['is_admin']) echo '<a href="/admin/">Admin</a>';
        echo '<a class="hide-m" href="/dashboard.php">Mon espace</a><a href="/logout.php">Sortir</a>';
    } else {
        echo '<a class="hide-m" href="/login.php">Connexion</a><a class="btn small" href="/register.php">Offrir un service</a>';
    }
    echo '</nav></header><main>';
    if ($fl) echo '<div class="flash">' . e($fl) . '</div>';
}
function footer_html(): void {
    echo '</main><footer><p>' . e(cfg('site_name')) . ' · Pignon, Hinche, Saint-Raphaël et environs</p>'
       . '<p><a href="' . e(wa_link(cfg('support_whatsapp'), 'Bonjour Fiksitil')) . '">Contacter le support (WhatsApp)</a></p></footer>'
       . bottom_nav() . '</body></html>';
}

function bottom_nav(): string {
    $me = current_provider();
    $cur = basename(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH)) ?: 'index.php';
    $items = [
        ['/', 'index.php', '🏠', 'Accueil'],
        ['/providers.php', 'providers.php', '🔎', 'Services'],
        [$me ? '/dashboard.php' : '/register.php', $me ? 'dashboard.php' : 'register.php', '🛠️', $me ? 'Mon espace' : 'Offrir'],
        [$me ? '/logout.php' : '/login.php', 'login.php', '👤', $me ? 'Sortir' : 'Connexion'],
    ];
    $h = '<nav class="tabbar">';
    foreach ($items as [$href, $file, $ico, $label]) {
        $on = ($cur === $file || ($file === 'index.php' && $cur === '')) ? ' class="on"' : '';
        $h .= '<a' . $on . ' href="' . $href . '"><span>' . $ico . '</span>' . $label . '</a>';
    }
    return $h . '</nav>';
}

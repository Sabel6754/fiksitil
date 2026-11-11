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

function header_html(string $title = '', string $path = ''): void {
    $me = current_provider();
    $name = cfg('site_name');
    $fl = flash();
    echo '<!doctype html><html lang="fr"><head><meta charset="utf-8">'
       . '<meta name="viewport" content="width=device-width,initial-scale=1">'
       . '<meta name="theme-color" content="#F7931E">'
       . '<title>' . e($title ? "$title · $name" : "$name · Tout arranger près de chez vous") . '</title>'
       . '<link rel="stylesheet" href="' . $path . '/assets/style.css"></head><body>'
       . '<header class="top"><a class="logo" href="/"><img src="' . $path . '/assets/logo.png" alt="' . e($name) . '" width="120" height="40"></a><nav>'
       . '<a href="/providers.php">Services</a>';
    if ($me) {
        if ($me['is_admin']) echo '<a href="/admin/">Admin</a>';
        echo '<a href="/dashboard.php">Mon espace</a><a href="/logout.php">Sortir</a>';
    } else {
        echo '<a href="/login.php">Connexion</a><a class="btn small" href="/register.php">Offrir un service</a>';
    }
    echo '</nav></header><main>';
    if ($fl) echo '<div class="flash">' . e($fl) . '</div>';
}
function footer_html(): void {
    echo '</main><footer><p>' . e(cfg('site_name')) . ' · Pignon, Hinche, Saint-Raphaël et environs</p>'
       . '<p><a href="' . e(wa_link(cfg('support_whatsapp'), 'Bonjour Fiksitil')) . '">Contacter le support (WhatsApp)</a></p></footer></body></html>';
}

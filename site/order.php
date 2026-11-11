<?php
require __DIR__ . '/inc/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /'); exit; }
csrf_check();

$pid = (int)($_POST['provider_id'] ?? 0);
$st = db()->prepare('SELECT * FROM providers WHERE id=? AND is_admin=0'); $st->execute([$pid]); $p = $st->fetch();
if (!$p || !is_visible($p)) { http_response_code(404); exit('Professionnel indisponible.'); }

$f = [];
foreach (['client_name','client_zone','address','when_text','note'] as $k) $f[$k] = trim($_POST[$k] ?? '');
$phone = normalize_phone($_POST['client_phone'] ?? '');
if ($f['client_name'] === '' || $f['address'] === '' || $f['when_text'] === '' || strlen($phone) < 8 || strlen($phone) > 15
    || !in_array($f['client_zone'], cfg('zones'), true)) {
    header('Location: /provider.php?id=' . $pid . '&err=' . rawurlencode('Vérifiez vos informations (nom, téléphone valide, adresse, date).'));
    exit;
}
// Anti-spam simple : 5 demandes max par session et par heure.
$_SESSION['orders'] = array_filter($_SESSION['orders'] ?? [], fn($t) => $t > time() - 3600);
if (count($_SESSION['orders']) >= 5) { exit('Trop de demandes. Réessayez plus tard.'); }
$_SESSION['orders'][] = time();

$commission = (int)round($p['price'] * cfg('commission_pct') / 100);
$ins = db()->prepare('INSERT INTO orders (provider_id,client_name,client_phone,client_zone,address,when_text,note,price,commission) VALUES (?,?,?,?,?,?,?,?,?)');
$ins->execute([$pid, $f['client_name'], $phone, $f['client_zone'], $f['address'], $f['when_text'], $f['note'], $p['price'], $commission]);
$_SESSION['last_order'] = (int)db()->lastInsertId();
header('Location: /order_done.php');

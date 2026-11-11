<?php
require __DIR__ . '/inc/bootstrap.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $st = db()->prepare('SELECT * FROM providers WHERE phone=?');
    $st->execute([normalize_phone($_POST['phone'] ?? '')]); $p = $st->fetch();
    if ($p && password_verify($_POST['password'] ?? '', $p['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['pid'] = (int)$p['id'];
        header('Location: ' . ($p['is_admin'] ? '/admin/' : '/dashboard.php')); exit;
    }
    $err = 'Téléphone ou mot de passe incorrect.';
}
header_html('Connexion');
?>
<h1>Connexion</h1>
<?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>
<form class="f card" method="post">
  <?= csrf_field() ?>
  <label>Téléphone <input name="phone" type="tel" required></label>
  <label>Mot de passe <input name="password" type="password" required></label>
  <button class="btn">Se connecter</button>
</form>
<p>Pas encore de profil ? <a href="/register.php">Offrir un service</a></p>
<?php footer_html();

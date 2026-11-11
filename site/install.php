<?php
// À ouvrir UNE seule fois pour créer le compte administrateur, puis à SUPPRIMER du serveur.
require __DIR__ . '/inc/bootstrap.php';
$has = (int)db()->query('SELECT COUNT(*) FROM providers WHERE is_admin=1')->fetchColumn();
if ($has) { http_response_code(403); exit('Déjà installé. Supprimez install.php.'); }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $phone = normalize_phone($_POST['phone'] ?? ''); $pass = $_POST['password'] ?? '';
    if (strlen($phone) < 8 || strlen($pass) < 8) $err = 'Téléphone valide et mot de passe de 8 caractères minimum.';
    else {
        db()->prepare("INSERT INTO providers (name,phone,password_hash,category_id,zone,price,status,is_admin) VALUES ('Admin',?,?,1,'Pignon',0,'active',1)")
          ->execute([$phone, password_hash($pass, PASSWORD_DEFAULT)]);
        exit('Admin créé. SUPPRIMEZ maintenant install.php, puis <a href="/login.php">connectez-vous</a>.');
    }
}
header_html('Installation');
?>
<h1>Créer l'administrateur</h1>
<?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>
<form class="f card" method="post"><?= csrf_field() ?>
  <label>Téléphone admin <input name="phone" required></label>
  <label>Mot de passe (8+) <input name="password" type="password" required minlength="8"></label>
  <button class="btn">Créer</button>
</form>
<?php footer_html();

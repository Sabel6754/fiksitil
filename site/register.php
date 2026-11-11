<?php
require __DIR__ . '/inc/bootstrap.php';
$err = '';
$cats = db()->query('SELECT * FROM categories ORDER BY id')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? ''); $phone = normalize_phone($_POST['phone'] ?? '');
    $pass = $_POST['password'] ?? ''; $cat = (int)($_POST['category_id'] ?? 0);
    $zone = $_POST['zone'] ?? ''; $price = (int)($_POST['price'] ?? 0);
    $note = trim($_POST['price_note'] ?? ''); $desc = trim($_POST['description'] ?? '');
    $years = (int)($_POST['years'] ?? 0); if ($years < 0 || $years > 60) $years = 0;
    if ($name === '' || strlen($phone) < 8 || strlen($phone) > 15) $err = 'Nom et téléphone valides requis.';
    elseif (strlen($pass) < 6) $err = 'Mot de passe : 6 caractères minimum.';
    elseif (!in_array($zone, cfg('zones'), true) || $price < 1 || !$cat) $err = 'Choisissez une catégorie, une zone et un prix.';
    else {
        $chk = db()->prepare('SELECT id FROM providers WHERE phone=?'); $chk->execute([$phone]);
        if ($chk->fetch()) $err = 'Ce numéro est déjà inscrit. Connectez-vous.';
        else {
            db()->prepare('INSERT INTO providers (name,phone,password_hash,category_id,zone,price,price_note,description,years) VALUES (?,?,?,?,?,?,?,?,?)')
              ->execute([$name, $phone, password_hash($pass, PASSWORD_DEFAULT), $cat, $zone, $price, $note, $desc, $years ?: null]);
            session_regenerate_id(true);
            $_SESSION['pid'] = (int)db()->lastInsertId();
            $photoErr = save_photo($_FILES['photo'] ?? [], $_SESSION['pid']);
            flash('Profil créé ! Payez votre place pour apparaître sur le site.' . ($photoErr ? ' Photo non enregistrée : ' . $photoErr . " Vous pourrez l'ajouter dans Mon espace." : ''));
            header('Location: /dashboard.php'); exit;
        }
    }
}
header_html('Offrir un service');
?>
<h1>Offrir un service</h1>
<p class="muted">Créez votre profil gratuitement. Il apparaît sur le site après paiement de votre place (<?= htg(cfg('plan_price')) ?> / <?= (int)cfg('plan_days') ?> jours).</p>
<?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>
<form class="f card" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <label>Nom (ou nom de l'entreprise) <input name="name" required maxlength="100" value="<?= e($_POST['name'] ?? '') ?>"></label>
  <label>Téléphone / WhatsApp <input name="phone" type="tel" required maxlength="20" value="<?= e($_POST['phone'] ?? '') ?>"></label>
  <label>Mot de passe <input name="password" type="password" required minlength="6"></label>
  <label>Votre service <select name="category_id"><?php foreach ($cats as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['icon'] . ' ' . $c['name']) ?></option><?php endforeach; ?></select></label>
  <label>Zone <select name="zone"><?php foreach (cfg('zones') as $z): ?><option><?= e($z) ?></option><?php endforeach; ?></select></label>
  <label>Votre prix (HTG) <input name="price" type="number" min="1" required value="<?= e($_POST['price'] ?? '') ?>"></label>
  <label>Précision sur le prix (optionnel) <input name="price_note" maxlength="80" placeholder="Ex : par coupe, déplacement inclus"></label>
  <label>Années d'expérience <input name="years" type="number" min="0" max="60" value="<?= e($_POST['years'] ?? '') ?>" placeholder="Ex : 5"></label>
  <label>Votre photo (recommandée : un portrait, visage visible) <input name="photo" type="file" accept="image/jpeg,image/png,image/webp"></label>
  <label>Présentez-vous <textarea name="description" rows="4" placeholder="Expérience, ce que vous faites, matériel…"><?= e($_POST['description'] ?? '') ?></textarea></label>
  <button class="btn">Créer mon profil</button>
</form>
<?php footer_html();

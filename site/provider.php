<?php
require __DIR__ . '/inc/bootstrap.php';
$st = db()->prepare("SELECT p.*, c.name AS cat_name, c.icon FROM providers p JOIN categories c ON c.id=p.category_id WHERE p.id=? AND p.is_admin=0");
$st->execute([(int)($_GET['id'] ?? 0)]); $p = $st->fetch();
if (!$p || !is_visible($p)) { http_response_code(404); header_html('Introuvable'); echo '<p>Ce professionnel n\'est pas disponible.</p>'; footer_html(); exit; }
$err = $_GET['err'] ?? '';
header_html($p['name']);
?>
<div class="card">
  <h1><?= e($p['icon']) ?> <?= e($p['name']) ?></h1>
  <p class="muted"><?= e($p['cat_name']) ?> · 📍 <?= e($p['zone']) ?></p>
  <p><span class="price"><?= htg($p['price']) ?></span> <?= e($p['price_note']) ?></p>
  <p><?= nl2br(e($p['description'])) ?></p>
</div>

<h2>Commander à domicile</h2>
<?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>
<form class="f card" method="post" action="/order.php">
  <?= csrf_field() ?>
  <input type="hidden" name="provider_id" value="<?= (int)$p['id'] ?>">
  <label>Votre nom <input name="client_name" required maxlength="100"></label>
  <label>Votre téléphone (WhatsApp si possible) <input name="client_phone" type="tel" required maxlength="20" placeholder="Ex : 37 00 00 00"></label>
  <label>Votre zone <select name="client_zone"><?php foreach (cfg('zones') as $z): ?><option><?= e($z) ?></option><?php endforeach; ?></select></label>
  <label>Adresse ou point de repère <input name="address" required maxlength="255" placeholder="Ex : près de l'église, maison bleue"></label>
  <label>Quand ? <input name="when_text" required maxlength="100" placeholder="Ex : demain à 10h"></label>
  <label>Précisions (optionnel) <textarea name="note" rows="3"></textarea></label>
  <p class="muted">Prix annoncé : <?= htg($p['price']) ?>. Le professionnel vous confirmera le prix final.</p>
  <button class="btn">Envoyer la demande</button>
</form>
<?php footer_html();

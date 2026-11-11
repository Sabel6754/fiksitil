<?php
require __DIR__ . '/inc/bootstrap.php';
$cats = db()->query('SELECT * FROM categories ORDER BY id')->fetchAll();
$top = db()->query("SELECT p.*, c.name AS cat_name, c.icon FROM providers p JOIN categories c ON c.id=p.category_id
    WHERE p.status='active' AND p.paid_until >= CURDATE() AND p.is_admin=0 ORDER BY p.paid_until DESC, p.id DESC LIMIT 3")->fetchAll();
header_html();
?>
<form class="sbar" action="/providers.php" method="get">
  <span>🔎</span>
  <input name="q" placeholder="De quel service avez-vous besoin ?" aria-label="Recherche">
  <select name="zone" aria-label="Zone"><option value="">Zones</option>
    <?php foreach (cfg('zones') as $z): ?><option><?= e($z) ?></option><?php endforeach; ?>
  </select>
  <button>Chercher</button>
</form>

<section class="hero">
  <span class="badge">Populaire</span>
  <h1>Avec Fiksitil, on arrange tout.</h1>
  <p>Coiffeur, plombier, électricien, Starlink, caméras… un professionnel près de chez vous.</p>
  <a class="btn light" href="/providers.php">Réserver</a>
</section>
<div class="dots"><i></i><i></i><i></i></div>

<div class="secthead"><h2>Nos services</h2><a href="/providers.php">Tout voir</a></div>
<div class="chips">
<?php foreach ($cats as $i => $c): ?>
  <a class="chip<?= $i === 0 ? ' on' : '' ?>" href="/providers.php?cat=<?= (int)$c['id'] ?>"><?= e($c['icon']) ?> <?= e($c['name']) ?></a>
<?php endforeach; ?>
</div>

<?php if ($top): ?>
<div class="secthead"><h2>Pros recommandés</h2><a href="/providers.php">Tout voir</a></div>
<?php foreach ($top as $p): ?>
  <a href="/provider.php?id=<?= (int)$p['id'] ?>" style="text-decoration:none;color:inherit">
  <div class="card pro">
    <?= avatar_html($p['name'], (int)$p['id']) ?>
    <div class="info"><h3><?= e($p['name']) ?></h3>
      <div class="trade"><?= e($p['icon']) ?> <?= e($p['cat_name']) ?> · 📍 <?= e($p['zone']) ?></div></div>
    <span class="price"><?= htg($p['price']) ?></span>
  </div></a>
<?php endforeach; ?>
<?php endif; ?>

<h2>Comment ça marche ?</h2>
<ol class="steps">
  <li>Choisissez un service et un professionnel de votre zone.</li>
  <li>Envoyez votre demande : nom, téléphone, adresse. Pas besoin de compte.</li>
  <li>Le professionnel vous contacte et vient chez vous. Vous payez en MonCash, NatCash ou en espèces.</li>
</ol>

<h2>Vous offrez un service ?</h2>
<div class="card">
  <p>Gagnez de nouveaux clients dans la zone. Votre place coûte <b><?= htg(cfg('plan_price')) ?></b> pour <?= (int)cfg('plan_days') ?> jours. Vous fixez vous-même votre prix.</p>
  <a class="btn" href="/register.php">Créer mon profil</a>
</div>
<?php footer_html();

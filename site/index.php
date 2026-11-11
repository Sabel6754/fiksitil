<?php
require __DIR__ . '/inc/bootstrap.php';
$cats = db()->query('SELECT * FROM categories ORDER BY id')->fetchAll();
header_html();
?>
<section class="hero">
  <h1>Avec Fiksitil, on arrange tout.</h1>
  <p>Coiffeur, plombier, électricien, Starlink, caméras, frigo… Trouvez un professionnel près de chez vous, sans passer par un ami d'ami.</p>
  <a class="btn light" href="/providers.php">Trouver un service</a>
</section>

<form class="search" action="/providers.php" method="get">
  <input name="q" placeholder="De quel service avez-vous besoin ?">
  <select name="zone"><option value="">Toutes les zones</option>
    <?php foreach (cfg('zones') as $z): ?><option><?= e($z) ?></option><?php endforeach; ?>
  </select>
  <span></span><button class="btn">Chercher</button>
</form>

<h2>Catégories</h2>
<div class="grid">
<?php foreach ($cats as $c): ?>
  <a class="cat" href="/providers.php?cat=<?= (int)$c['id'] ?>"><span><?= e($c['icon']) ?></span><?= e($c['name']) ?></a>
<?php endforeach; ?>
</div>

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

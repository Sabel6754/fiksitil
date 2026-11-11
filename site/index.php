<?php
require __DIR__ . '/inc/bootstrap.php';
$cats = db()->query('SELECT * FROM categories ORDER BY id')->fetchAll();
$visible = "p.status='active' AND p.paid_until >= CURDATE() AND p.is_admin=0";
$top = db()->query("SELECT p.*, c.name AS cat_name, c.icon FROM providers p JOIN categories c ON c.id=p.category_id
    WHERE $visible ORDER BY p.paid_until DESC, p.id DESC LIMIT 6")->fetchAll();
$nPros = (int)db()->query("SELECT COUNT(*) FROM providers p WHERE $visible")->fetchColumn();
$zones = array_values(array_filter(cfg('zones'), fn($z) => $z !== 'Autre zone'));
$collage = array_values(array_filter([7, 5, 3], fn($i) => photo_url($i)));
header_html('', '', true);
?>
<section class="hero2">
  <div class="hero2-in">
    <div>
      <span class="eyebrow"><i>Nouveau</i> Service à domicile</span>
      <h1>Un problème à la maison ? <em>On vous arrange ça.</em></h1>
      <p class="lead">Coiffeur, plombier, électricien, Starlink, caméras, frigo… Trouvez un professionnel de votre zone et commandez en 2 minutes, sans créer de compte.</p>
      <form class="sbar" action="/providers.php" method="get">
        <span>🔎</span>
        <input name="q" placeholder="De quel service avez-vous besoin ?" aria-label="Recherche">
        <select name="zone" aria-label="Zone"><option value="">Zone</option>
          <?php foreach (cfg('zones') as $z): ?><option><?= e($z) ?></option><?php endforeach; ?>
        </select>
        <button>Chercher</button>
      </form>
      <div class="trust"><span>Sans compte à créer</span><span>Pros de votre zone</span><span>MonCash, NatCash ou espèces</span></div>
    </div>
    <?php if (count($collage) === 3): ?>
    <div class="collage" aria-hidden="true">
      <div class="ph p1"><img src="<?= e(photo_url($collage[0])) ?>" alt="" width="320" height="320"></div>
      <div class="ph p2"><img src="<?= e(photo_url($collage[1])) ?>" alt="" width="320" height="320"></div>
      <div class="ph p3"><img src="<?= e(photo_url($collage[2])) ?>" alt="" width="320" height="320"></div>
      <div class="float f1"><b>✓</b>Profils validés</div>
      <div class="float f2"><b>📍</b><?= e($zones[0] ?? 'Près de vous') ?></div>
    </div>
    <?php endif; ?>
  </div>
</section>

<div class="statsbar">
  <div><b><?= $nPros ?></b><span>Professionnels</span></div>
  <div><b><?= count($cats) ?></b><span>Services</span></div>
  <div><b><?= count($zones) ?></b><span>Zones</span></div>
</div>

<section class="section">
  <div class="secthead"><div><h2>Que cherchez-vous ?</h2><p>Choisissez un service pour voir les pros disponibles.</p></div><a href="/providers.php">Tout voir →</a></div>
  <div class="cats">
  <?php foreach ($cats as $c): ?>
    <a class="cat2" href="/providers.php?cat=<?= (int)$c['id'] ?>"><i><?= e($c['icon']) ?></i><?= e($c['name']) ?></a>
  <?php endforeach; ?>
  </div>
</section>

<?php if ($top): ?>
<section class="section">
  <div class="secthead"><div><h2>Pros à la une</h2><p>Des professionnels près de chez vous, prêts à intervenir.</p></div><a href="/providers.php">Voir tous →</a></div>
  <div class="pgrid">
  <?php foreach ($top as $p) echo pro_card($p); ?>
  </div>
</section>
<?php endif; ?>

<section class="section" id="how">
  <div class="secthead"><div><h2>Comment ça marche</h2><p>Trois étapes, sans prise de tête.</p></div></div>
  <div class="how">
    <div class="step"><div class="n">1</div><h3>Choisissez</h3><p>Parcourez les services et ouvrez le profil d'un pro de votre zone : tarif, description, photo.</p></div>
    <div class="step"><div class="n">2</div><h3>Commandez</h3><p>Envoyez votre demande avec votre nom, votre téléphone et votre adresse. Pas de compte à créer.</p></div>
    <div class="step"><div class="n">3</div><h3>Le pro vient chez vous</h3><p>Il vous contacte et intervient. Vous payez en MonCash, NatCash ou en espèces.</p></div>
  </div>
</section>

<section class="cta-band">
  <div>
    <h2>Vous offrez un service ?</h2>
    <p>Recevez de nouveaux clients dans votre zone. Vous fixez votre prix, nous vous envoyons les demandes.</p>
    <ul>
      <li>Votre place : <?= htg(cfg('plan_price')) ?> pour <?= (int)cfg('plan_days') ?> jours</li>
      <li>Profil visible par tous les clients de la zone</li>
      <li>Commandes reçues directement dans votre espace</li>
    </ul>
  </div>
  <a class="btn" href="/register.php">Créer mon profil</a>
</section>
<?php footer_html();

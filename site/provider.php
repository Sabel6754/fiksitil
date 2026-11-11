<?php
require __DIR__ . '/inc/bootstrap.php';
$st = db()->prepare("SELECT p.*, c.name AS cat_name, c.icon FROM providers p JOIN categories c ON c.id=p.category_id WHERE p.id=? AND p.is_admin=0");
$st->execute([(int)($_GET['id'] ?? 0)]); $p = $st->fetch();
if (!$p || !is_visible($p)) { http_response_code(404); header_html('Introuvable'); echo '<p>Ce professionnel n\'est pas disponible.</p>'; footer_html(); exit; }
$err = $_GET['err'] ?? '';
$first = explode(' ', $p['name'])[0];
$photo = photo_url((int)$p['id']);
header_html($p['name'], '', true);
?>
<div class="pf-hero">
  <div class="pf-bar">
    <a class="pf-back" href="/providers.php" aria-label="Retour">←</a>
    <div class="pf-title"><b><?= e($p['name']) ?></b></div>
    <span class="pf-back" style="visibility:hidden"></span>
  </div>
  <div class="pf-main">
    <div class="pf-text">
      <span class="pf-trade"><?= e($p['icon']) ?> <?= e($p['cat_name']) ?></span>
      <h1><?= e($p['name']) ?></h1>
      <p class="muted">📍 <?= e($p['zone']) ?></p>
      <span class="price big"><?= htg($p['price']) ?></span>
    </div>
    <div class="pf-photo">
      <?php if ($photo): ?><img src="<?= e($photo) ?>" alt="<?= e($p['name']) ?>" width="170" height="170">
      <?php else: ?><?= avatar_html($p['name'], (int)$p['id'], 150) ?><?php endif; ?>
    </div>
  </div>
  <div class="pf-tiles">
    <div class="pf-tile"><em>📍</em><b><?= e($p['zone']) ?></b><span>Zone</span></div>
    <div class="pf-tile alt"><em>💰</em><b><?= htg($p['price']) ?></b><span><?= e($p['price_note'] ?: 'Tarif') ?></span></div>
    <?php if (!empty($p['years'])): ?>
    <div class="pf-tile"><em>🏅</em><b><?= (int)$p['years'] ?> <?= $p['years'] > 1 ? 'ans' : 'an' ?></b><span>d'expérience</span></div>
    <?php else: ?>
    <div class="pf-tile"><em><?= e($p['icon']) ?></em><b><?= e($p['cat_name']) ?></b><span>Service</span></div>
    <?php endif; ?>
  </div>
</div>

<div class="pf-sheet">
  <div class="pf-tabs" role="tablist">
    <button type="button" class="on" data-tab="about">À propos</button>
    <button type="button" data-tab="rates">Tarifs</button>
    <button type="button" data-tab="order">Commander</button>
  </div>

  <section id="tab-about" class="pf-panel">
    <h2>À propos de <?= e($p['name']) ?></h2>
    <p class="muted"><?= nl2br(e($p['description'])) ?></p>
    <div class="pf-who">
      <?= avatar_html($p['name'], (int)$p['id'], 48) ?>
      <div><b><?= e($p['name']) ?></b><div class="muted"><?= e($p['cat_name']) ?></div></div>
    </div>
  </section>

  <section id="tab-rates" class="pf-panel" hidden>
    <div class="pf-fees">
      <div class="pf-fee"><span class="muted">Tarif</span><b><?= htg($p['price']) ?></b><small class="muted"><?= e($p['price_note']) ?></small></div>
      <div class="pf-fee"><span class="muted">Zone</span><b><?= e($p['zone']) ?></b><small class="muted">et environs</small></div>
    </div>
    <p class="muted">Le professionnel vous confirme le prix final avant de commencer. Paiement en MonCash, NatCash ou en espèces.</p>
  </section>

  <section id="tab-order" class="pf-panel" hidden>
    <?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>
    <form class="f" method="post" action="/order.php">
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
  </section>
</div>

<button type="button" class="btn cta" id="cta" data-tab="order">Commander <?= e($first) ?></button>

<script>
(function () {
  var tabs = document.querySelectorAll('.pf-tabs button'), cta = document.getElementById('cta');
  function show(name) {
    tabs.forEach(function (b) { b.classList.toggle('on', b.dataset.tab === name); });
    document.querySelectorAll('.pf-panel').forEach(function (s) { s.hidden = s.id !== 'tab-' + name; });
    cta.style.display = name === 'order' ? 'none' : '';
    if (name === 'order') window.scrollTo({ top: document.querySelector('.pf-sheet').offsetTop - 60, behavior: 'smooth' });
  }
  tabs.forEach(function (b) { b.addEventListener('click', function () { show(b.dataset.tab); }); });
  cta.addEventListener('click', function () { show('order'); });
  <?php if ($err): ?>show('order');<?php endif; ?>
})();
</script>
<?php footer_html();

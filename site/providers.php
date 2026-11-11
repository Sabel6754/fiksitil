<?php
require __DIR__ . '/inc/bootstrap.php';
$q = trim($_GET['q'] ?? ''); $zone = $_GET['zone'] ?? ''; $cat = (int)($_GET['cat'] ?? 0);

$sql = "SELECT p.*, c.name AS cat_name, c.icon FROM providers p JOIN categories c ON c.id=p.category_id
        WHERE p.status='active' AND p.paid_until >= CURDATE() AND p.is_admin=0";
$args = [];
if ($cat)  { $sql .= ' AND p.category_id=?'; $args[] = $cat; }
if ($zone) { $sql .= ' AND p.zone=?'; $args[] = $zone; }
if ($q !== '') { $sql .= ' AND (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)'; $l = "%$q%"; array_push($args, $l, $l, $l); }
$sql .= ' ORDER BY p.paid_until DESC, p.id DESC';
$st = db()->prepare($sql); $st->execute($args); $list = $st->fetchAll();
$cats = db()->query('SELECT * FROM categories ORDER BY id')->fetchAll();

header_html('Services');
?>
<h1>Services</h1>
<form class="sbar" method="get">
  <span>🔎</span>
  <input name="q" value="<?= e($q) ?>" placeholder="Rechercher…" aria-label="Recherche">
  <?php if ($cat): ?><input type="hidden" name="cat" value="<?= $cat ?>"><?php endif; ?>
  <select name="zone" aria-label="Zone" onchange="this.form.submit()"><option value="">Zones</option>
    <?php foreach (cfg('zones') as $z): ?><option <?= $zone === $z ? 'selected' : '' ?>><?= e($z) ?></option><?php endforeach; ?>
  </select>
  <button>Filtrer</button>
</form>
<div class="chips">
  <a class="chip<?= !$cat ? ' on' : '' ?>" href="/providers.php?zone=<?= urlencode($zone) ?>">Tous</a>
  <?php foreach ($cats as $c): ?>
    <a class="chip<?= $cat === (int)$c['id'] ? ' on' : '' ?>" href="/providers.php?cat=<?= (int)$c['id'] ?>&amp;zone=<?= urlencode($zone) ?>"><?= e($c['icon']) ?> <?= e($c['name']) ?></a>
  <?php endforeach; ?>
</div>
<?php foreach ($list as $p): ?>
  <a href="/provider.php?id=<?= (int)$p['id'] ?>" style="text-decoration:none;color:inherit">
  <div class="card pro">
    <?= avatar_html($p['name'], (int)$p['id']) ?>
    <div class="info"><h3><?= e($p['name']) ?></h3>
      <div class="trade"><?= e($p['icon']) ?> <?= e($p['cat_name']) ?> · 📍 <?= e($p['zone']) ?></div></div>
    <span class="price"><?= htg($p['price']) ?></span>
  </div></a>
<?php endforeach; ?>
<?php if (!$list): ?><p class="muted">Aucun professionnel trouvé pour le moment. Essayez une autre zone ou catégorie.</p><?php endif; ?>
<?php footer_html();

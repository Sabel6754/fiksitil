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

header_html('Services', '', true);
?>
<div class="pagehead">
  <h1>Trouvez le bon professionnel</h1>
  <p><?= count($list) ?> professionnel<?= count($list) > 1 ? 's' : '' ?> disponible<?= count($list) > 1 ? 's' : '' ?><?= $zone ? ' à ' . e($zone) : '' ?>.</p>
</div>
<form class="sbar" method="get" style="max-width:none">
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
<div class="pgrid">
<?php foreach ($list as $p) echo pro_card($p); ?>
</div>
<?php if (!$list): ?>
<div class="empty"><b>Aucun professionnel trouvé</b><p class="muted">Essayez une autre zone ou un autre service.</p><a class="btn" href="/providers.php">Voir tous les pros</a></div>
<?php endif; ?>
<?php footer_html();

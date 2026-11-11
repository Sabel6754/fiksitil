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
<form class="search" method="get">
  <input name="q" value="<?= e($q) ?>" placeholder="Rechercher…">
  <select name="cat"><option value="">Toutes catégories</option>
    <?php foreach ($cats as $c): ?><option value="<?= (int)$c['id'] ?>" <?= $cat === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
  </select>
  <select name="zone"><option value="">Toutes zones</option>
    <?php foreach (cfg('zones') as $z): ?><option <?= $zone === $z ? 'selected' : '' ?>><?= e($z) ?></option><?php endforeach; ?>
  </select>
  <button class="btn">Filtrer</button>
</form>
<?php foreach ($list as $p): ?>
  <a href="/provider.php?id=<?= (int)$p['id'] ?>" style="text-decoration:none;color:inherit">
  <div class="card row">
    <div><h3><?= e($p['icon']) ?> <?= e($p['name']) ?></h3>
      <div class="muted"><?= e($p['cat_name']) ?> · 📍 <?= e($p['zone']) ?></div></div>
    <span class="price"><?= htg($p['price']) ?></span>
  </div></a>
<?php endforeach; ?>
<?php if (!$list): ?><p class="muted">Aucun professionnel trouvé pour le moment. Essayez une autre zone ou catégorie.</p><?php endif; ?>
<?php footer_html();

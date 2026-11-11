<?php
require __DIR__ . '/../inc/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0); $a = $_POST['action'] ?? '';
    if ($a === 'activate') {
        // Prolonge à partir de la date d'expiration si elle est dans le futur, sinon à partir d'aujourd'hui.
        db()->prepare("UPDATE providers SET status='active', paid_until = DATE_ADD(GREATEST(COALESCE(paid_until, CURDATE()), CURDATE()), INTERVAL ? DAY) WHERE id=? AND is_admin=0")
            ->execute([(int)cfg('plan_days'), $id]);
        flash('Profil activé / prolongé.');
    } elseif ($a === 'suspend') {
        db()->prepare("UPDATE providers SET status='suspended' WHERE id=? AND is_admin=0")->execute([$id]);
    } elseif ($a === 'commission_paid') {
        db()->prepare('UPDATE orders SET commission_paid=1 WHERE id=?')->execute([$id]);
    }
    header('Location: /admin/'); exit;
}

$provs = db()->query('SELECT p.*, c.name AS cat FROM providers p JOIN categories c ON c.id=p.category_id WHERE p.is_admin=0 ORDER BY p.status="pending" DESC, p.id DESC')->fetchAll();
$orders = db()->query('SELECT o.*, p.name AS pname FROM orders o JOIN providers p ON p.id=o.provider_id ORDER BY o.id DESC LIMIT 100')->fetchAll();
$totalDue = db()->query("SELECT COALESCE(SUM(commission),0) FROM orders WHERE status='done' AND commission_paid=0")->fetchColumn();

header_html('Admin');
?>
<h1>Administration</h1>
<div class="card"><b>Commissions à encaisser : <?= htg($totalDue) ?></b></div>

<h2>Prestataires</h2>
<?php foreach ($provs as $p): $vis = is_visible($p); ?>
<div class="card">
  <div class="row"><b><?= e($p['name']) ?></b><span class="tag <?= $vis ? 'ok' : 'bad' ?>"><?= e($p['status']) ?><?= $vis ? ' · jusqu\'au ' . e(date('d/m/Y', strtotime($p['paid_until']))) : '' ?></span></div>
  <p class="muted"><?= e($p['cat']) ?> · <?= e($p['zone']) ?> · <?= htg($p['price']) ?> · 📞 <?= e($p['phone']) ?></p>
  <form method="post" class="row" style="justify-content:flex-start"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
    <button class="btn small" name="action" value="activate">Activer / prolonger <?= (int)cfg('plan_days') ?> j</button>
    <button class="btn small danger" name="action" value="suspend">Suspendre</button>
  </form>
</div>
<?php endforeach; ?>

<h2>Demandes</h2>
<table><tr><th>#</th><th>Pro</th><th>Client</th><th>Prix</th><th>Comm.</th><th>Statut</th></tr>
<?php foreach ($orders as $o): ?>
<tr><td><?= (int)$o['id'] ?></td><td><?= e($o['pname']) ?></td><td><?= e($o['client_name']) ?><br><span class="muted"><?= e($o['client_phone']) ?></span></td>
<td><?= htg($o['price']) ?></td>
<td><?= htg($o['commission']) ?>
  <?php if ($o['status'] === 'done' && !$o['commission_paid']): ?>
  <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$o['id'] ?>"><button class="btn small" name="action" value="commission_paid">Payée</button></form>
  <?php elseif ($o['commission_paid']): ?>✔<?php endif; ?></td>
<td><?= e($o['status']) ?></td></tr>
<?php endforeach; ?></table>
<?php footer_html();

<?php
require __DIR__ . '/inc/bootstrap.php';
$me = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $map = ['accept' => 'accepted', 'done' => 'done', 'decline' => 'declined'];
    $act = $_POST['action'] ?? '';
    if (isset($map[$act])) {
        db()->prepare('UPDATE orders SET status=? WHERE id=? AND provider_id=?')->execute([$map[$act], (int)$_POST['order_id'], $me['id']]);
    }
    header('Location: /dashboard.php'); exit;
}

$st = db()->prepare('SELECT * FROM orders WHERE provider_id=? ORDER BY id DESC LIMIT 100');
$st->execute([$me['id']]); $orders = $st->fetchAll();
$due = 0; foreach ($orders as $o) if ($o['status'] === 'done' && !$o['commission_paid']) $due += $o['commission'];
$visible = is_visible($me);
$labels = ['new'=>'Nouvelle','accepted'=>'Acceptée','done'=>'Terminée','declined'=>'Refusée','cancelled'=>'Annulée'];
$payMsg = "Bonjour, je paie ma place Fiksitil ({$me['name']}, {$me['phone']}). Voici ma preuve de paiement.";

header_html('Mon espace');
?>
<h1>Mon espace</h1>
<div class="card">
  <div class="row"><b><?= e($me['name']) ?></b>
    <span class="tag <?= $visible ? 'ok' : 'bad' ?>"><?= $visible ? 'Visible sur le site' : 'Non visible' ?></span></div>
  <?php if ($visible): ?>
    <p class="muted">Votre place est valable jusqu'au <?= e(date('d/m/Y', strtotime($me['paid_until']))) ?>.</p>
  <?php else: ?>
    <p><b>Pour apparaître sur le site</b>, payez votre place : <b><?= htg(cfg('plan_price')) ?></b> pour <?= (int)cfg('plan_days') ?> jours.</p>
    <ol class="steps">
      <li>Envoyez <?= htg(cfg('plan_price')) ?> par MonCash au <b><?= e(cfg('moncash_number')) ?></b> ou NatCash au <b><?= e(cfg('natcash_number')) ?></b>.</li>
      <li>Envoyez la preuve par WhatsApp. Nous activons votre profil rapidement.</li>
    </ol>
    <a class="btn" href="<?= e(wa_link(cfg('support_whatsapp'), $payMsg)) ?>">Envoyer la preuve de paiement</a>
    <?php if ($me['status'] === 'suspended'): ?><p class="err">Votre profil est suspendu. Contactez le support.</p><?php endif; ?>
  <?php endif; ?>
</div>

<?php if ($due > 0): ?>
<div class="card"><b>Commission à régler : <?= htg($due) ?></b>
  <p class="muted"><?= (int)cfg('commission_pct') ?>% sur les services terminés. Réglez par MonCash/NatCash et envoyez la preuve au support.</p></div>
<?php endif; ?>

<h2>Demandes de clients</h2>
<?php foreach ($orders as $o): ?>
  <div class="card">
    <div class="row"><b>#<?= (int)$o['id'] ?> · <?= e($o['client_name']) ?></b><span class="tag"><?= e($labels[$o['status']]) ?></span></div>
    <p class="muted">📍 <?= e($o['client_zone']) ?> · <?= e($o['address']) ?><br>🕒 <?= e($o['when_text']) ?> · <?= htg($o['price']) ?><br>📞 <?= e($o['client_phone']) ?></p>
    <?php if ($o['note']): ?><p><?= nl2br(e($o['note'])) ?></p><?php endif; ?>
    <div class="row" style="justify-content:flex-start;flex-wrap:wrap">
      <a class="btn small light" href="tel:<?= e($o['client_phone']) ?>">Appeler</a>
      <a class="btn small light" href="<?= e(wa_link($o['client_phone'], "Bonjour {$o['client_name']}, c'est {$me['name']} (Fiksitil) pour votre demande n°{$o['id']}.")) ?>">WhatsApp</a>
      <?php if (in_array($o['status'], ['new','accepted'], true)): ?>
      <form method="post" style="display:flex;gap:6px"><?= csrf_field() ?><input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
        <?php if ($o['status'] === 'new'): ?>
          <button class="btn small" name="action" value="accept">Accepter</button>
          <button class="btn small danger" name="action" value="decline">Refuser</button>
        <?php else: ?>
          <button class="btn small dark" name="action" value="done">Marquer terminé</button>
        <?php endif; ?>
      </form>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
<?php if (!$orders): ?><p class="muted">Aucune demande pour le moment.</p><?php endif; ?>
<?php footer_html();

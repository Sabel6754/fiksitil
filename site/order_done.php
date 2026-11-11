<?php
require __DIR__ . '/inc/bootstrap.php';
$id = (int)($_SESSION['last_order'] ?? 0);
$st = db()->prepare('SELECT o.*, p.name AS pname, p.phone AS pphone FROM orders o JOIN providers p ON p.id=o.provider_id WHERE o.id=?');
$st->execute([$id]); $o = $st->fetch();
if (!$o) { header('Location: /'); exit; }
$msg = "Bonjour {$o['pname']}, c'est {$o['client_name']} (via Fiksitil). Demande n°{$o['id']} : {$o['when_text']}, à {$o['address']} ({$o['client_zone']}). Tél : {$o['client_phone']}.";
header_html('Demande envoyée');
?>
<div class="card">
  <h1>✅ Demande envoyée</h1>
  <p>Demande n°<?= (int)$o['id'] ?> envoyée à <b><?= e($o['pname']) ?></b>. Il vous contactera au <?= e($o['client_phone']) ?>.</p>
  <p>Pour aller plus vite, prévenez-le directement :</p>
  <a class="btn" href="<?= e(wa_link($o['pphone'], $msg)) ?>">Écrire sur WhatsApp</a>
  <p class="muted">Le prix final est confirmé avec le professionnel. Paiement après le service : MonCash, NatCash ou espèces.</p>
</div>
<a href="/">← Retour à l'accueil</a>
<?php footer_html();

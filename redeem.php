<?php
require_once __DIR__.'/includes/authorization.php';
require_once __DIR__.'/includes/db.php';
require_once __DIR__.'/includes/validation.php';
// The requirements document allows both Admin and Customer redemption.
$id = validateCardId($_GET['card_id'] ?? null);
$card = false;
if ($id !== null) {
    $s = $pdo->prepare('SELECT * FROM GIFTCARD WHERE cardId=?');
    $s->execute([$id]);
    $card = $s->fetch();
}
if (!$card) {
    http_response_code($id === null ? 400 : 404);
}
require_once __DIR__.'/includes/header.php';
require_once __DIR__.'/includes/nav.php';
?>
<main><h2>Redeem Gift Card</h2>
<?php if (!$card): ?>
<p>Invalid or nonexistent gift card.</p>
<?php else: ?>
<p><?= escape($card['cardName']) ?> —
$<?= escape($card['cardValue']) ?> —
<?= escape($card['points']) ?> points</p>
<form method="post" action="/process-redemption.php">
<input type="hidden" name="card_id" value="<?= escape($id) ?>">
<input type="hidden" name="csrf" value="<?= escape(csrfToken()) ?>">
<button>Confirm Redemption</button>
</form>
<?php endif; ?>
<p><a href="/card-list.php">Cancel / Return to List</a></p>
</main>
<?php require_once __DIR__.'/includes/footer.php'; ?>

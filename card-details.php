<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$cardId = validateCardId($_GET['card_id'] ?? null);
$card = false;
$error = '';

if ($cardId === null) {
    http_response_code(400);
    $error = 'Invalid gift card ID.';
} else {
    $stmt = $pdo->prepare(
        'SELECT * FROM GIFTCARD WHERE cardId = :id'
    );
    $stmt->execute(['id' => $cardId]);
    $card = $stmt->fetch();

    if ($card === false) {
        http_response_code(404);
        $error = 'Gift card not found.';
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main>
    <h2>Gift Card Details</h2>

    <?php if ($error !== ''): ?>
        <p class="error"><?= escape($error) ?></p>
    <?php else: ?>
        <img
            class="card-image"
            src="/images/gift-card.svg"
            alt="<?= escape($card['cardName']) ?>"
        >

        <dl>
            <dt>Card ID</dt>
            <dd><?= escape($card['cardId']) ?></dd>

            <dt>Card Name</dt>
            <dd><?= escape($card['cardName']) ?></dd>

            <dt>Card Type</dt>
            <dd><?= escape($card['cardType']) ?></dd>

            <dt>Card Value</dt>
            <dd>$<?= escape(number_format((float) $card['cardValue'], 2)) ?></dd>

            <dt>Points Required</dt>
            <dd><?= escape(number_format((int) $card['points'])) ?></dd>
        </dl>

        <div class="actions">
            <a href="/card-update.php?card_id=<?= escape($cardId) ?>">
                Update Gift Card
            </a>
            <a href="/card-delete.php?card_id=<?= escape($cardId) ?>">
                Delete Gift Card
            </a>
        </div>
    <?php endif; ?>

    <p><a href="/card-list.php">Return to Gift Card List</a></p>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
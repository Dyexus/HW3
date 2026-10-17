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

if (
    $card !== false &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    if (($_POST['confirm'] ?? null) === 'yes') {
        $stmt = $pdo->prepare(
            'DELETE FROM GIFTCARD WHERE cardId = :id'
        );
        $stmt->execute(['id' => $cardId]);

        header('Location: /card-list.php', true, 303);
        exit;
    }

    http_response_code(400);
    $error = 'Deletion was not confirmed.';
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main>
    <h2>Delete Gift Card</h2>

    <?php if ($error !== ''): ?>
        <p class="error"><?= escape($error) ?></p>
        <p><a href="/card-list.php">Return to Gift Card List</a></p>
    <?php else: ?>
        <img
            class="card-image"
            src="/images/gift-card.svg"
            alt="<?= escape($card['cardName']) ?>"
        >

        <p>
            Delete
            <strong><?= escape($card['cardName']) ?></strong>
            (ID: <?= escape($card['cardId']) ?>)?
        </p>

        <p>
            Value: $<?= escape(number_format((float) $card['cardValue'], 2)) ?>
            · Points: <?= escape(number_format((int) $card['points'])) ?>
        </p>

        <form method="post">
            <button type="submit" name="confirm" value="yes">
                Confirm Delete
            </button>
            <a href="/card-details.php?card_id=<?= escape($cardId) ?>">
                Cancel
            </a>
        </form>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$cardId = validateCardId($_GET['card_id'] ?? null);
$error = '';
$errors = [];
$card = false;

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

if ($card !== false) {
    $data = [
        'cardName' => $card['cardName'],
        'cardType' => $card['cardType'],
        'cardValue' => $card['cardValue'],
        'points' => $card['points']
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = validateGiftCard($_POST);
        $data = $result['data'];
        $errors = $result['errors'];

        if ($errors === []) {
            $stmt = $pdo->prepare(
                'UPDATE GIFTCARD
                 SET cardName = :cardName,
                     cardType = :cardType,
                     cardValue = :cardValue,
                     points = :points
                 WHERE cardId = :id'
            );
            $stmt->execute(array_merge($data, ['id' => $cardId]));

            header(
                'Location: /card-details.php?card_id=' . $cardId,
                true,
                303
            );
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main>
    <h2>Update Gift Card</h2>

    <?php if ($error !== ''): ?>
        <p class="error"><?= escape($error) ?></p>
        <p><a href="/card-list.php">Return to Gift Card List</a></p>
    <?php else: ?>
        <?php require_once __DIR__ . '/includes/card-form.php'; ?>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
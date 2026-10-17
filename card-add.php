<?php
require_once __DIR__ . '/includes/authorization.php';
requireRole('Admin');

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$data = [
    'cardName' => '',
    'cardType' => '',
    'cardValue' => '',
    'points' => ''
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = validateGiftCard($_POST);
    $data = $result['data'];
    $errors = $result['errors'];

    if ($errors === []) {
        $stmt = $pdo->prepare(
            'INSERT INTO GIFTCARD (cardName, cardType, cardValue, points)
             VALUES (:cardName, :cardType, :cardValue, :points)'
        );
        $stmt->execute($data);

        header('Location: /card-list.php', true, 303);
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main>
    <h2>Add Gift Card</h2>

    <?php if ($errors !== []): ?>
        <ul class="error">
            <?php foreach ($errors as $error): ?>
                <li><?= escape($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="/card-add.php">
        <label for="cardName">Card Name</label>
        <input
            id="cardName"
            name="cardName"
            maxlength="100"
            value="<?= escape($data['cardName']) ?>"
            required
        >

        <label for="cardType">Card Type</label>
        <select id="cardType" name="cardType" required>
            <option value="">Select a type</option>
            <?php foreach (CARD_TYPES as $type): ?>
                <option
                    value="<?= escape($type) ?>"
                    <?= $data['cardType'] === $type ? 'selected' : '' ?>
                ><?= escape($type) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="cardValue">Card Value ($)</label>
        <input
            type="number"
            id="cardValue"
            name="cardValue"
            min="0.01"
            step="0.01"
            value="<?= escape($data['cardValue']) ?>"
            required
        >

        <label for="points">Points Required</label>
        <input
            type="number"
            id="points"
            name="points"
            min="1"
            step="1"
            value="<?= escape($data['points']) ?>"
            required
        >

        <button type="submit">Add Gift Card</button>
        <a href="/card-list.php">Cancel</a>
    </form>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
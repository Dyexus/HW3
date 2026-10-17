<?php if ($errors !== []): ?>
    <ul class="error">
        <?php foreach ($errors as $error): ?>
            <li><?= escape($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
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

    <button type="submit">Save Changes</button>
    <a href="/card-details.php?card_id=<?= escape($cardId) ?>">Cancel</a>
</form>
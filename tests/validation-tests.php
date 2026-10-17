<?php
require_once __DIR__ . '/../includes/validation.php';

$valid = [
    'cardName' => 'Amazon $25',
    'cardType' => 'Retail',
    'cardValue' => '25',
    'points' => '2500'
];

$tests = [
    ['Valid gift card', $valid, null],
    ['Blank card name', array_replace($valid, [
        'cardName' => '   '
    ]), 'cardName'],
    ['Invalid card type', array_replace($valid, [
        'cardType' => 'AlteredType'
    ]), 'cardType'],
    ['Zero card value', array_replace($valid, [
        'cardValue' => '0'
    ]), 'cardValue'],
    ['Negative card value', array_replace($valid, [
        'cardValue' => '-10'
    ]), 'cardValue'],
    ['Fractional points', array_replace($valid, [
        'points' => '2.5'
    ]), 'points'],
    ['Zero points', array_replace($valid, [
        'points' => '0'
    ]), 'points'],
    ['Non-numeric points', array_replace($valid, [
        'points' => 'abc'
    ]), 'points']
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';
?>

<main>
    <h2>Validation Tests</h2>

    <ul>
        <?php foreach ($tests as [$name, $input, $expectedField]): ?>
            <?php
            $result = validateGiftCard($input);
            $passed = $expectedField === null
                ? $result['errors'] === []
                : array_key_exists($expectedField, $result['errors']);
            ?>
            <li>
                <?= escape($name) ?>:
                <strong><?= $passed ? 'PASS' : 'FAIL' ?></strong>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
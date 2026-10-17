<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$cards = $pdo->query(
    'SELECT cardId, cardName, points FROM GIFTCARD ORDER BY cardId'
)->fetchAll();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<main>
    <h2>Gift Card List</h2>

    <p><a href="/card-add.php">+ Add Gift Card</a></p>

    <?php if ($cards === []): ?>
        <p>No gift cards available.</p>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($cards as $card): ?>
                <article class="gift-card">
                    <a href="/card-details.php?card_id=<?= escape($card['cardId']) ?>">
                        <img
                            class="card-image"
                            src="/images/gift-card.svg"
                            alt="<?= escape($card['cardName']) ?>"
                        >
                        <h3><?= escape($card['cardName']) ?></h3>
                    </a>

                    <p>
                        <?= escape(number_format((int) $card['points'])) ?>
                        points required
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php
require_once __DIR__.'/includes/authorization.php';
require_once __DIR__.'/includes/db.php';
require_once __DIR__.'/includes/validation.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('POST required.');
}
checkCsrf();
$id = validateCardId($_POST['card_id'] ?? null);
if ($id === null) {
    http_response_code(400);
    exit('Invalid gift card ID.');
}
$message = '';
$active = false;
try {
    // Acquire the SQLite write lock before reading the balance.
    $pdo->exec('BEGIN IMMEDIATE');
    $active = true;
    $s = $pdo->prepare('SELECT * FROM GIFTCARD WHERE cardId=?');
    $s->execute([$id]);
    $card = $s->fetch();
    if (!$card) throw new RuntimeException('Gift card not found.');

    $s = $pdo->prepare(
        'SELECT * FROM ACCOUNT WHERE userId=?
         ORDER BY accountId LIMIT 1'
    );
    $s->execute([$_SESSION['userId']]);
    $account = $s->fetch();
    if (!$account) throw new RuntimeException('No rewards account found.');
    if ($account['points'] < $card['points']) {
        throw new RuntimeException('Insufficient points.');
    }

    $s = $pdo->prepare(
        'UPDATE ACCOUNT SET points=points-?
         WHERE accountId=? AND points>=?'
    );
    $s->execute([
        $card['points'], $account['accountId'], $card['points']
    ]);
    if ($s->rowCount() !== 1) {
        throw new RuntimeException('Insufficient points.');
    }

    $s = $pdo->prepare(
        'INSERT INTO REDEMPTION(date,accountId,cardId,pointsRedeemed)
         VALUES (?,?,?,?)'
    );
    $s->execute([
        gmdate('c'), $account['accountId'], $id, $card['points']
    ]);
    $pdo->exec('COMMIT');
    $active = false;
    $balance = $account['points'] - $card['points'];
    // Rotate token to reject resubmission of the same confirmation.
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    $message = 'Redeemed '.$card['cardName'].
        '. Updated balance: '.$balance.' points.';
} catch (Throwable $e) {
    if ($active) $pdo->exec('ROLLBACK');
    $message = $e instanceof PDOException
        ? 'Redemption failed. No points were deducted.'
        : $e->getMessage();
}
require_once __DIR__.'/includes/header.php';
require_once __DIR__.'/includes/nav.php';
?>
<main><h2>Redemption Result</h2>
<p><?= escape($message) ?></p>
<a href="/card-list.php">Return to Gift Card List</a>
</main>
<?php require_once __DIR__.'/includes/footer.php'; ?>

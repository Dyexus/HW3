<?php
require_once __DIR__.'/includes/authorization.php';
requireRole('Customer');
require_once __DIR__.'/includes/db.php';
require_once __DIR__.'/includes/validation.php';
$s = $pdo->prepare(
    'SELECT u.userName,u.firstName,u.lastName,a.accountId,
    a.accountType,a.points FROM USER u
    JOIN ACCOUNT a ON a.userId=u.userId WHERE u.userId=?'
);
$s->execute([$_SESSION['userId']]);
$accounts = $s->fetchAll();
require_once __DIR__.'/includes/header.php';
require_once __DIR__.'/includes/nav.php';
?>
<main><h2>My Profile</h2>
<?php foreach ($accounts as $a): ?>
<p><?= escape($a['firstName'].' '.$a['lastName']) ?>
(<?= escape($a['userName']) ?>)</p>
<p>Account <?= escape($a['accountId']) ?>:
<?= escape($a['accountType']) ?> —
<strong><?= escape($a['points']) ?> points</strong></p>
<?php endforeach; ?>
</main>
<?php require_once __DIR__.'/includes/footer.php'; ?>

<?php
require_once __DIR__.'/includes/authorization.php';
requireRole('Admin');
require_once __DIR__.'/includes/db.php';
require_once __DIR__.'/includes/validation.php';
$error = '';
$values = ['userName'=>'','firstName'=>'','lastName'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    foreach ($values as $key => $_) {
        $v = $_POST[$key] ?? '';
        $values[$key] = is_string($v) ? trim($v) : '';
    }
    $password = $_POST['password'] ?? '';
    foreach ($values as $v) {
        if ($v === '' || strlen($v) > 100) {
            $error = 'All name fields are required (maximum 100 bytes).';
        }
    }
    if (!is_string($password) || strlen($password) < 6 ||
        strlen($password) > 72) {
        $error = 'Use a password of 6–72 bytes.';
    }
    if ($error === '') {
        try {
            $pdo->beginTransaction();
            $s = $pdo->prepare('SELECT userId FROM USER WHERE userName=?');
            $s->execute([$values['userName']]);
            if ($s->fetch()) {
                throw new RuntimeException('Username already in use.');
            }
            $s = $pdo->prepare(
                'INSERT INTO USER
                (userName,password,firstName,lastName,role)
                VALUES (?,?,?,?,?)'
            );
            $s->execute([
                $values['userName'],
                password_hash($password, PASSWORD_DEFAULT),
                $values['firstName'], $values['lastName'], 'Customer'
            ]);
            $id = $pdo->lastInsertId();
            $s = $pdo->prepare(
                'INSERT INTO ACCOUNT(userId,accountType,points)
                VALUES (?,?,?)'
            );
            $s->execute([$id, 'Rewards', 0]);
            $pdo->commit();
            header('Location: /cust-add.php?created=1', true, 303);
            exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = $e instanceof PDOException
                ? 'Unable to create customer. Check the username.'
                : $e->getMessage();
        }
    }
}
require_once __DIR__.'/includes/header.php';
require_once __DIR__.'/includes/nav.php';
?>
<main><h2>Add Customer</h2>
<?php if (isset($_GET['created'])): ?>
<p>Customer created with a Rewards account and 0 points.</p>
<?php endif; ?>
<p class="error"><?= escape($error) ?></p>
<form method="post">
<input type="hidden" name="csrf" value="<?= escape(csrfToken()) ?>">
<?php foreach ($values as $key => $value): ?>
<label><?= escape($key) ?>
<input name="<?= escape($key) ?>" value="<?= escape($value) ?>"
maxlength="100" required>
</label>
<?php endforeach; ?>
<label>Password
<input type="password" name="password" minlength="6"
maxlength="72" required autocomplete="new-password">
</label>
<button>Create Customer</button>
</form></main>
<?php require_once __DIR__.'/includes/footer.php'; ?>

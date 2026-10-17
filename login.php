<?php
require_once __DIR__ . '/includes/header.php';
?>
<main>
<h2>Login</h2>
<?php if (isset($_GET['error'])): ?>
<p class="error">Invalid username or password.</p>
<?php endif; ?>
<form method="post" action="/authenticate.php">
<label>Username<input name="userName" required></label>
<label>Password<input type="password" name="password" required></label>
<button>Login</button>
</form>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

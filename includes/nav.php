<nav>
<a href="/card-list.php">Gift Card List</a>
<?php if (($_SESSION['role'] ?? '') === 'Admin'): ?>
<a href="/card-add.php">Add Gift Card</a>
<a href="/cust-add.php">Add Customer</a>
<?php else: ?>
<a href="/customer-profile.php">My Profile</a>
<?php endif; ?>
<a href="/logout.php">Logout</a>
</nav>

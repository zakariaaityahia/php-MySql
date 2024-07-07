<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Your role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor' || $_SESSION['role'] == 'author') : ?>
        <p><a href="../users/users.php">Manage Users</a></p>
    <?php endif; ?>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.html');
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SESSION['role'] == 'admin') {
        $stmt = $pdo->query('SELECT id, username, role FROM users');
    }
    else {
        $stmt = $pdo->prepare('SELECT id, username, role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
    }

    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
</head>
<body>
    <h2>User List</h2>
    <?php if ($_SESSION['role'] == 'admin'): ?>
        <p><a href="../register_user/register.html">Add New User</a></p>
    <?php endif; ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['user_id'] == $user['id']): ?>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] == 'admin' && $_SESSION['user_id'] != $user['id']): ?>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="../dashboard/dashboard.php">Back to Dashboard</a></p>
</body>
</html>

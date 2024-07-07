<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.html');
    exit;
}

$user_id = $_GET['id'];

if ($_SESSION['role'] != 'admin' && $_SESSION['user_id'] != $user_id) {
    header('Location: ../dashboard/dashboard.php');
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $role = $_POST['role'];

        if ($password) {
            $stmt = $pdo->prepare('UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?');
            $stmt->execute([$username, $password, $role, $user_id]);
        }
        else {
            $stmt = $pdo->prepare('UPDATE users SET username = ?, role = ? WHERE id = ?');
            $stmt->execute([$username, $role, $user_id]);
        }
        
        header('Location: users.php');
        exit;
    }
    else {
        $stmt = $pdo->prepare('SELECT username, role FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    }
} 
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form action="edit_user.php?id=<?php echo $user_id; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <br>
        <label for="password">New Password (leave blank to keep current):</label>
        <input type="password" id="password" name="password">
        <br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="editor" <?php if ($user['role'] == 'editor') echo 'selected'; ?>>Editor</option>
            <option value="author" <?php if ($user['role'] == 'author') echo 'selected'; ?>>Author</option>
            <option value="guest" <?php if ($user['role'] == 'guest') echo 'selected'; ?>>Guest</option>
        </select>
        <br>
        <input type="submit" value="Save">
    </form>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hashedPassword, $role]);

        header('Location: ../register_user/register.html');
    }
    catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

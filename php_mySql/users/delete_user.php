<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login/login.html');
    exit;
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$userId]);

        header('Location: users.php');
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: users.php');
    exit;
}
?>

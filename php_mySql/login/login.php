<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', ''); // Remplacez 'root' et '' par votre utilisateur et mot de passe MySQL

        // Configuration de PDO pour afficher les erreurs
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer les informations de l'utilisateur
        $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            // Stockage des informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirection vers une page sécurisée
            header('Location: ../dashboard/dashboard.php');
            exit;
        }
        else {
            echo 'Invalid username or password';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

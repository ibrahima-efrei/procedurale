<?php
session_start();
require 'bdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($username) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            echo "Les mots de passe ne correspondent pas.";
            exit;
        }

        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            echo "Nom d'utilisateur déjà pris.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $insertQuery = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->execute(['username' => $username, 'password' => $hashed_password]);

            echo "Inscription réussie !";
            header('Location: ./users.php');
            exit();
        }
    } else {
        echo "Tous les champs doivent être remplis.";
    }
}
?>
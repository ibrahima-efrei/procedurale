<?php
session_start();
require 'bdd.php';

if (!isset($_SESSION['connected_users']) || empty($_SESSION['connected_users'])) {
    header('Location: ./index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['connected_users'][0]['id_user'];
    $newPassword = trim($_POST['new_password']);

    if (!empty($newPassword)) {
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id_user = :user_id");
        $stmt->execute(['password' => password_hash($newPassword, PASSWORD_DEFAULT), 'user_id' => $userId]);

        header('Location: ./index.php');
        exit;
    } else {
        echo "Le mot de passe ne peut pas être vide.";
    }
}

require 'templates/reset_password.html.twig';
?>
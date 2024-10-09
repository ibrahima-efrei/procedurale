<?php
session_start();
require 'bdd.php';
if (!isset($_SESSION['connected_users']) || empty($_SESSION['connected_users'])) {
    header('Location: ./login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userId = $_SESSION['connected_users'][0]['id_user'];
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (:user_id, :message)");
        $stmt->execute(['user_id' => $userId, 'message' => $message]);
        
        header('Location: ./home.php');
        exit;
    } else {
        echo "Le message ne peut pas être vide.";
    }
} else {
    header('Location: ./home.php');
    exit;
}
?>
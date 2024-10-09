<?php
session_start();
require 'bdd.php';

if (!isset($_SESSION['connected_users']) || empty($_SESSION['connected_users'])) {
    header('Location: ./login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        'id' => $id,
        'user_id' => $_SESSION['connected_users'][0]['id_user']
    ]);

    header('Location: ./home.php');
    exit;
} else {
    header('Location: ./home.php');
    exit;
}
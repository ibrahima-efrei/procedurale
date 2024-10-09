<?php
session_start();
require 'bdd.php';

if (!isset($_SESSION['connected_users']) || empty($_SESSION['connected_users'])) {
    header('Location: ./login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $messageId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT id, message FROM messages WHERE id = :id");
    $stmt->execute(['id' => $messageId]);
    $message = $stmt->fetch();

    if (!$message) {
        echo "Message non trouvé.";
        exit;
    }

    require 'vendor/autoload.php';
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    echo $twig->render('edit.html.twig', ['message' => $message]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['message'])) {
    $messageId = $_POST['id'];
    $newMessage = trim($_POST['message']);

    if (!empty($newMessage)) {
        $stmt = $pdo->prepare("UPDATE messages SET message = :message WHERE id = :id");
        $stmt->execute(['message' => $newMessage, 'id' => $messageId]);

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
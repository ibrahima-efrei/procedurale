<?php
session_start();
require 'bdd.php';

if (!isset($_SESSION['connected_users']) || empty($_SESSION['connected_users'])) {
    header('Location: ./login.php');
    exit;
}

$userId = $_SESSION['connected_users'][0]['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message'])) {
        $message = trim($_POST['message']);
        if (!empty($message)) {
            $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (:user_id, :message)");
            $stmt->execute(['user_id' => $userId, 'message' => $message]);
            header('Location: ./home.php');
            exit;
        } else {
            echo "Le message ne peut pas être vide.";
        }
    }

    if (isset($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];
        $messages = $pdo->prepare("SELECT id, message FROM messages WHERE id = :id AND user_id = :user_id");
        $messages->execute(['id' => $editId, 'user_id' => $userId]);
        $messageToEdit = $messages->fetch(PDO::FETCH_ASSOC);
        if ($messageToEdit) {
            $messagesArray = $pdo->query("SELECT id, message, created_at FROM messages WHERE user_id = $userId ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($messagesArray as &$msg) {
                $msg['is_editing'] = $msg['id'] === $editId;
            }
        }
    }
}

$messages = $pdo->prepare("SELECT id, message, created_at FROM messages WHERE user_id = :user_id ORDER BY created_at DESC");
$messages->execute(['user_id' => $userId]);
$messages = $messages->fetchAll(PDO::FETCH_ASSOC);

require_once 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);
echo $twig->render('home.html.twig', [
    'username' => $_SESSION['connected_users'][0]['username'],
    'messages' => $messages,
]);
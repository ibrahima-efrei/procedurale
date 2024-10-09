<?php
session_start();
require 'bdd.php';
require 'vendor/autoload.php';

if (!isset($_SESSION['connected_users']) || empty($_SESSION['connected_users'])) {
    header('Location: index.php');
    exit();
}

header('Location: ./home.php');
exit();
?>
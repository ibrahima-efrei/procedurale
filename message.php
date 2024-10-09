<?php
session_start();
require 'bdd.php'; 
$query = "SELECT * FROM users";

try {
    $stmt = $pdo->query($query);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h1>Liste des utilisateurs</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($user['id_user']) . "</td>";
        echo "<td>" . htmlspecialchars($user['nom']) . "</td>";
        echo "<td>" . htmlspecialchars($user['prenom']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>"; 
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}
?>
<?php


// Inclure la configuration de la base de données
require_once 'config.php';

// Vérifier si l'ID de l'employé est fourni
$previous= $_SERVER["HTTP_REFERER"];
if (isset($_GET['id'])) {
    $att_id = $_GET['id'];

    // Préparer la requête pour supprimer l'employé
    $stmt = $pdo->prepare("DELETE FROM attribution WHERE id_att = ? ");
    $stmt->execute([$att_id]);

   
    // Rediriger après suppression
    header("Location: $previous"); // Rediriger vers la page d'affichage des employés
    exit();
} else {
    // Si aucun ID n'est fourni, rediriger vers la liste des employés
    header("Location: $previous");
    exit();
}

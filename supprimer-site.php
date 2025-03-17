<?php


// Inclure la configuration de la base de données
require_once 'config.php';

// Vérifier si l'ID de l'employé est fourni
if (isset($_GET['id'])) {
    $site_id = $_GET['id'];

    // Préparer la requête pour supprimer l'employé
    $stmt = $pdo->prepare("DELETE FROM bj_site WHERE id = ? ");
    $stmt->execute([$site_id]);

    // Rediriger après suppression
    header("Location: manage-site.php"); // Rediriger vers la page d'affichage des employés
    exit();
} else {
    // Si aucun ID n'est fourni, rediriger vers la liste des employés
    header("Location: manage-site.php");
    exit();
}

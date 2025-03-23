<?php
// delete_permis_feu.php

// Connexion à la base de données
require_once 'config.php';

// Récupérer l'ID de l'élément à supprimer
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête SQL pour supprimer l'élément
    $sql = "DELETE FROM permis_feu WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Redirection vers la page précédente
    header('Location: permis.php?success=deleted');
    exit;
} else {
    // Si l'ID n'est pas fourni, rediriger avec un message d'erreur
    header('Location: permis.php?error=delete_failed');
    exit;
}
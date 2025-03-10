<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    // Si l'utilisateur n'est pas authentifié, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

// Vérifier si l'utilisateur a le rôle 'entreprise'
if ($_SESSION['user_role'] !== 'entreprise') {
    header("Location: index.php");
    exit();
}

// Inclure la configuration de la base de données
require_once 'config.php';

// Vérifier si l'ID de l'employé est fourni
if (isset($_GET['id'])) {
    $employe_id = $_GET['id'];

    // Préparer la requête pour supprimer l'employé
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ? AND entreprise_id = ?");
    $stmt->execute([$employe_id, $_SESSION['user_id']]);

    // Rediriger après suppression
    header("Location: manage-employes.php"); // Rediriger vers la page d'affichage des employés
    exit();
} else {
    // Si aucun ID n'est fourni, rediriger vers la liste des employés
    header("Location: manage-employes.php");
    exit();
}

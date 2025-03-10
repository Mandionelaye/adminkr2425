<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: ../login.php");
    exit();
}

// Vérifier si l'utilisateur a le rôle 'agent'
if ($_SESSION['user_role'] !== 'agent') {
    header("Location: agent");
    exit();
}

// Inclure les fichiers de configuration
require_once 'config.php';

// Initialisation des variables
$response = [];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signature']) && !empty($_POST['signature'])) {
        $signatureData = $_POST['signature'];
        $utilisateurId = $_SESSION['user_id'];

        // Insertion dans la base de données
        try {
            $stmt = $pdo->prepare("INSERT INTO signatures (utilisateur_id, signature_data) VALUES (:utilisateur_id, :signature_data)");
            $stmt->bindParam(':utilisateur_id', $utilisateurId);
            $stmt->bindParam(':signature_data', $signatureData);

            if ($stmt->execute()) {
                $response = ['success' => 'Signature enregistrée avec succès.'];
            } else {
                $response = ['error' => 'Erreur lors de l\'enregistrement de la signature.'];
            }
        } catch (Exception $e) {
            $response = ['error' => 'Une erreur est survenue : ' . $e->getMessage()];
        }
    } else {
        $response = ['error' => 'Aucune signature reçue ou signature vide.'];
    }

    // Réponse JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
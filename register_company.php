<?php
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nomEntreprise = trim($_POST['nom-entreprise']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    $document = $_FILES['document'];
    $motDePasse = trim($_POST['mot_de_passe']); // Récupérer le mot de passe

    // Initialiser un message vide
    $message = '';

    // Vérifier que tous les champs obligatoires sont remplis
    if (empty($nomEntreprise) || empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($adresse) || empty($document['name']) || empty($motDePasse)) {
        $message = "Tous les champs sont obligatoires.";
        header("Location: inscription.php?message=" . urlencode($message));
        exit();
    }

    // Vérifier et enregistrer le document
    $uploadDir = 'uploads/documents/';
    $allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf'];
    $fileExtension = strtolower(pathinfo($document['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        $message = "Format de document non valide. Seuls les fichiers PNG, JPG, JPEG, et PDF sont acceptés.";
        header("Location: inscription.php?message=" . urlencode($message));
        exit();
    }

    // Générer un nom de fichier unique
    $newFileName = uniqid() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $newFileName;

    // Créer le répertoire si nécessaire
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!move_uploaded_file($document['tmp_name'], $uploadPath)) {
        $message = "Erreur lors de l'enregistrement du document.";
        header("Location: inscription.php?message=" . urlencode($message));
        exit();
    }

    // Hacher le mot de passe avant de l'insérer
    $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);

    // Insérer les données dans la base de données
    $pdo->beginTransaction();
    try {
        // Insertion dans la table entreprises
        $stmtEntreprise = $pdo->prepare("INSERT INTO entreprises (nom, adresse, document, date_creation) VALUES (?, ?, ?, NOW())");
        $stmtEntreprise->execute([$nomEntreprise, $adresse, $newFileName]);
        $entrepriseId = $pdo->lastInsertId();

        // Insertion dans la table utilisateurs avec le mot de passe haché
        $stmtUtilisateur = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, entreprise_id, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $role = 'entreprise'; // Rôle par défaut
        $stmtUtilisateur->execute([$nom, $prenom, $email, $motDePasseHache, $telephone, $role, $entrepriseId]);

        $pdo->commit();
        $message = "Compte créé avec succès !";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Erreur lors de la création du compte : " . $e->getMessage();
    }

    // Redirection avec un message de confirmation ou d'erreur
    header("Location: login.php?message=" . urlencode($message));
    exit();
}
?>

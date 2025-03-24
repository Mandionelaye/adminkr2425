<?php
session_start();
require_once 'config.php';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    // Récupérer les données du formulaire
    $date_heure = $_POST['date_heure'] ?? date("d/m/Y H:i:s");
    $lieu = $_POST['lieu'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $age = $_POST['age'] ?? '';
    $autre = $_POST['autre'] ?? '';
    
    // Récupérer les cases à cocher (0 si non cochée, 1 si cochée)
    $malaise = isset($_POST['malaise']) ? 1 : 0;
    $chute = isset($_POST['chute']) ? 1 : 0;
    $blessure = isset($_POST['blessure']) ? 1 : 0;
    $choc = isset($_POST['choc']) ? 1 : 0;
    
    $obva = isset($_POST['obva']) ? 1 : 0;
    $hemorragie = isset($_POST['hemorragie']) ? 1 : 0;
    $inconscience = isset($_POST['inconscience']) ? 1 : 0;
    $arret_ventilatoire = isset($_POST['arret_ventilatoire']) ? 1 : 0;
    $arret_cardio = isset($_POST['arret_cardio']) ? 1 : 0;
    
    // Conscience
    $pc_depuis = $_POST['pc_depuis'] ?? '';
    $reponds_questions = isset($_POST['reponds_questions']) ? 1 : 0;
    $ouvre_yeux = isset($_POST['ouvre_yeux']) ? 1 : 0;
    $desoriente = isset($_POST['desoriente']) ? 1 : 0;
    $agite = isset($_POST['agite']) ? 1 : 0;
    $confus = isset($_POST['confus']) ? 1 : 0;
    $somnolent = isset($_POST['somnolent']) ? 1 : 0;
    $ne_serre_pas = isset($_POST['ne_serre_pas']) ? 1 : 0;
    $ne_bouge_pas = isset($_POST['ne_bouge_pas']) ? 1 : 0;
    $ne_reagit_pas = isset($_POST['ne_reagit_pas']) ? 1 : 0;
    $pupilles_egales = isset($_POST['pupilles_egales']) ? 1 : 0;
    $pupilles_inegales = isset($_POST['pupilles_inegales']) ? 1 : 0;
    
    // Ventilation
    $freq_respiratoire = $_POST['freq_respiratoire'] ?? '';
    $respiration_normale = isset($_POST['respiration_normale']) ? 1 : 0;
    $respiration_reguliere = isset($_POST['respiration_reguliere']) ? 1 : 0;
    $respiration_superficielle = isset($_POST['respiration_superficielle']) ? 1 : 0;
    $respiration_difficile = isset($_POST['respiration_difficile']) ? 1 : 0;
    $respiration_bruyante = isset($_POST['respiration_bruyante']) ? 1 : 0;
    $gargouillement = isset($_POST['gargouillement']) ? 1 : 0;
    $sifflements = isset($_POST['sifflements']) ? 1 : 0;
    $ronflements = isset($_POST['ronflements']) ? 1 : 0;
    $sueurs = isset($_POST['sueurs']) ? 1 : 0;
    $cyanose = isset($_POST['cyanose']) ? 1 : 0;
    
    // Circulation
    $freq_cardiaque = $_POST['freq_cardiaque'] ?? '';
    $regulier = isset($_POST['regulier']) ? 1 : 0;
    $irregulier = isset($_POST['irregulier']) ? 1 : 0;
    $paleur_visage = isset($_POST['paleur_visage']) ? 1 : 0;
    $froideur_membres = isset($_POST['froideur_membres']) ? 1 : 0;
    $douleur = $_POST['douleur'] ?? 0;
    
    try {
        // Vérifier si c'est une mise à jour ou une nouvelle entrée
        if (isset($_POST['id'])) {
            // Mise à jour d'un enregistrement existant
            $id = $_POST['id'];
            
            $sql = "UPDATE fiches_bilan SET 
                    date_heure = :date_heure, lieu = :lieu, gender = :gender, nom = :nom, prenom = :prenom, age = :age, 
                    malaise = :malaise, chute = :chute, blessure = :blessure, choc = :choc, autre = :autre,
                    obva = :obva, hemorragie = :hemorragie, inconscience = :inconscience, arret_ventilatoire = :arret_ventilatoire, arret_cardio = :arret_cardio,
                    pc_depuis = :pc_depuis, reponds_questions = :reponds_questions, ouvre_yeux = :ouvre_yeux, desoriente = :desoriente, 
                    agite = :agite, confus = :confus, somnolent = :somnolent, ne_serre_pas = :ne_serre_pas, ne_bouge_pas = :ne_bouge_pas, 
                    ne_reagit_pas = :ne_reagit_pas, pupilles_egales = :pupilles_egales, pupilles_inegales = :pupilles_inegales,
                    freq_respiratoire = :freq_respiratoire, respiration_normale = :respiration_normale, respiration_reguliere = :respiration_reguliere, 
                    respiration_superficielle = :respiration_superficielle, respiration_difficile = :respiration_difficile, respiration_bruyante = :respiration_bruyante,
                    gargouillement = :gargouillement, sifflements = :sifflements, ronflements = :ronflements, sueurs = :sueurs, cyanose = :cyanose,
                    freq_cardiaque = :freq_cardiaque, regulier = :regulier, irregulier = :irregulier, paleur_visage = :paleur_visage, 
                    froideur_membres = :froideur_membres, douleur = :douleur
                    WHERE id = :id";
                    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            // Nouvelle entrée
            $sql = "INSERT INTO fiches_bilan (
                    date_heure, lieu, gender, nom, prenom, age, 
                    malaise, chute, blessure, choc, autre,
                    obva, hemorragie, inconscience, arret_ventilatoire, arret_cardio,
                    pc_depuis, reponds_questions, ouvre_yeux, desoriente, 
                    agite, confus, somnolent, ne_serre_pas, ne_bouge_pas, 
                    ne_reagit_pas, pupilles_egales, pupilles_inegales,
                    freq_respiratoire, respiration_normale, respiration_reguliere, 
                    respiration_superficielle, respiration_difficile, respiration_bruyante,
                    gargouillement, sifflements, ronflements, sueurs, cyanose,
                    freq_cardiaque, regulier, irregulier, paleur_visage, 
                    froideur_membres, douleur)
                    VALUES (
                    :date_heure, :lieu, :gender, :nom, :prenom, :age, 
                    :malaise, :chute, :blessure, :choc, :autre,
                    :obva, :hemorragie, :inconscience, :arret_ventilatoire, :arret_cardio,
                    :pc_depuis, :reponds_questions, :ouvre_yeux, :desoriente, 
                    :agite, :confus, :somnolent, :ne_serre_pas, :ne_bouge_pas, 
                    :ne_reagit_pas, :pupilles_egales, :pupilles_inegales,
                    :freq_respiratoire, :respiration_normale, :respiration_reguliere, 
                    :respiration_superficielle, :respiration_difficile, :respiration_bruyante,
                    :gargouillement, :sifflements, :ronflements, :sueurs, :cyanose,
                    :freq_cardiaque, :regulier, :irregulier, :paleur_visage, 
                    :froideur_membres, :douleur)";
                    
            $stmt = $pdo->prepare($sql);
        }
        
        // Bind tous les paramètres
        $stmt->bindParam(':date_heure', $date_heure);
        $stmt->bindParam(':lieu', $lieu);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':malaise', $malaise, PDO::PARAM_INT);
        $stmt->bindParam(':chute', $chute, PDO::PARAM_INT);
        $stmt->bindParam(':blessure', $blessure, PDO::PARAM_INT);
        $stmt->bindParam(':choc', $choc, PDO::PARAM_INT);
        $stmt->bindParam(':autre', $autre);
        $stmt->bindParam(':obva', $obva, PDO::PARAM_INT);
        $stmt->bindParam(':hemorragie', $hemorragie, PDO::PARAM_INT);
        $stmt->bindParam(':inconscience', $inconscience, PDO::PARAM_INT);
        $stmt->bindParam(':arret_ventilatoire', $arret_ventilatoire, PDO::PARAM_INT);
        $stmt->bindParam(':arret_cardio', $arret_cardio, PDO::PARAM_INT);
        $stmt->bindParam(':pc_depuis', $pc_depuis);
        $stmt->bindParam(':reponds_questions', $reponds_questions, PDO::PARAM_INT);
        $stmt->bindParam(':ouvre_yeux', $ouvre_yeux, PDO::PARAM_INT);
        $stmt->bindParam(':desoriente', $desoriente, PDO::PARAM_INT);
        $stmt->bindParam(':agite', $agite, PDO::PARAM_INT);
        $stmt->bindParam(':confus', $confus, PDO::PARAM_INT);
        $stmt->bindParam(':somnolent', $somnolent, PDO::PARAM_INT);
        $stmt->bindParam(':ne_serre_pas', $ne_serre_pas, PDO::PARAM_INT);
        $stmt->bindParam(':ne_bouge_pas', $ne_bouge_pas, PDO::PARAM_INT);
        $stmt->bindParam(':ne_reagit_pas', $ne_reagit_pas, PDO::PARAM_INT);
        $stmt->bindParam(':pupilles_egales', $pupilles_egales, PDO::PARAM_INT);
        $stmt->bindParam(':pupilles_inegales', $pupilles_inegales, PDO::PARAM_INT);
        $stmt->bindParam(':freq_respiratoire', $freq_respiratoire);
        $stmt->bindParam(':respiration_normale', $respiration_normale, PDO::PARAM_INT);
        $stmt->bindParam(':respiration_reguliere', $respiration_reguliere, PDO::PARAM_INT);
        $stmt->bindParam(':respiration_superficielle', $respiration_superficielle, PDO::PARAM_INT);
        $stmt->bindParam(':respiration_difficile', $respiration_difficile, PDO::PARAM_INT);
        $stmt->bindParam(':respiration_bruyante', $respiration_bruyante, PDO::PARAM_INT);
        $stmt->bindParam(':gargouillement', $gargouillement, PDO::PARAM_INT);
        $stmt->bindParam(':sifflements', $sifflements, PDO::PARAM_INT);
        $stmt->bindParam(':ronflements', $ronflements, PDO::PARAM_INT);
        $stmt->bindParam(':sueurs', $sueurs, PDO::PARAM_INT);
        $stmt->bindParam(':cyanose', $cyanose, PDO::PARAM_INT);
        $stmt->bindParam(':freq_cardiaque', $freq_cardiaque);
        $stmt->bindParam(':regulier', $regulier, PDO::PARAM_INT);
        $stmt->bindParam(':irregulier', $irregulier, PDO::PARAM_INT);
        $stmt->bindParam(':paleur_visage', $paleur_visage, PDO::PARAM_INT);
        $stmt->bindParam(':froideur_membres', $froideur_membres, PDO::PARAM_INT);
        $stmt->bindParam(':douleur', $douleur, PDO::PARAM_INT);
        
        // Exécuter la requête
        if ($stmt->execute()) {
            $_SESSION['message'] = "Fiche bilan enregistrée avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de l'enregistrement";
        }
        
        // Rediriger vers la même page pour éviter la resoumission du formulaire
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données: " . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Supprimer une fiche
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    try {
        // Préparer la requête de suppression
        $sql = "DELETE FROM fiches_bilan WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Exécuter la requête
        if ($stmt->execute()) {
            $_SESSION['message'] = "Fiche supprimée avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression";
        }
        
        // Rediriger vers la liste
        header("Location: " . $_SERVER['PHP_SELF'] . "?list=1");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données: " . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF'] . "?list=1");
        exit();
    }
}

// Initialiser les variables
$date = date("d/m/Y H:i:s");
$lieu = "";
$gender = "M";
$nom = "";
$prenom = "";
$age = "";
$autre = "";

// Si on édite un enregistrement existant
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $sql = "SELECT * FROM fiches_bilan WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['date_heure'];
            $lieu = $row['lieu'];
            $gender = $row['gender'];
            $nom = $row['nom'];
            $prenom = $row['prenom'];
            $age = $row['age'];
            $autre = $row['autre'];
            
            // Récupérer les autres champs
            $malaise = $row['malaise'];
            $chute = $row['chute'];
            $blessure = $row['blessure'];
            $choc = $row['choc'];
            
            $obva = $row['obva'];
            $hemorragie = $row['hemorragie'];
            $inconscience = $row['inconscience'];
            $arret_ventilatoire = $row['arret_ventilatoire'];
            $arret_cardio = $row['arret_cardio'];
            
            $pc_depuis = $row['pc_depuis'];
            $reponds_questions = $row['reponds_questions'];
            $ouvre_yeux = $row['ouvre_yeux'];
            $desoriente = $row['desoriente'];
            $agite = $row['agite'];
            $confus = $row['confus'];
            $somnolent = $row['somnolent'];
            $ne_serre_pas = $row['ne_serre_pas'];
            $ne_bouge_pas = $row['ne_bouge_pas'];
            $ne_reagit_pas = $row['ne_reagit_pas'];
            $pupilles_egales = $row['pupilles_egales'];
            $pupilles_inegales = $row['pupilles_inegales'];
            
            $freq_respiratoire = $row['freq_respiratoire'];
            $respiration_normale = $row['respiration_normale'];
            $respiration_reguliere = $row['respiration_reguliere'];
            $respiration_superficielle = $row['respiration_superficielle'];
            $respiration_difficile = $row['respiration_difficile'];
            $respiration_bruyante = $row['respiration_bruyante'];
            $gargouillement = $row['gargouillement'];
            $sifflements = $row['sifflements'];
            $ronflements = $row['ronflements'];
            $sueurs = $row['sueurs'];
            $cyanose = $row['cyanose'];
            
            $freq_cardiaque = $row['freq_cardiaque'];
            $regulier = $row['regulier'];
            $irregulier = $row['irregulier'];
            $paleur_visage = $row['paleur_visage'];
            $froideur_membres = $row['froideur_membres'];
            $douleur = $row['douleur'];
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données: " . $e->getMessage();
    }
}

// Déterminer si on affiche le formulaire ou la liste
$showList = isset($_GET['list']) || (!isset($_GET['id']) && !isset($_GET['new']));

// Titre de la page
$pageTitle = $showList ? "Liste des Fiches Bilan" : "Fiche Bilan";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
      <!-- Required meta tags -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width" />
      <meta
         name="description"
         content="Dashboard de gestion pour les entreprises, incluant la gestion des utilisateurs, des rondes, des statistiques et des abonnements." />
      <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon/favicon.ico" />

        <!-- Libs CSS -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
        <link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="./assets/css/theme.min.css">

        
      <link rel="stylesheet" href="./assets/libs/apexcharts/dist/apexcharts.css" />
      <title>Gestion des bilans</title>
        <style>
            .alert {
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 5px;
            }
            .alert-success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .alert-error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            table th, table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            table td {
                color: black;
            }
            .header-container {
                display: flex;
                justify-content: space-between; /* Align title and button */
                align-items: center;
                margin-bottom: 20px;
            }
            .btn-create {
                background-color:rgb(79 70 229);
                color: white;
                padding: 8px 16px;
                border: none;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
            }
            .btn-create:hover {
                background-color:rgb(79 70 229);
            }
            .container{
                padding: 10px;
            }

            /* Style pour les boutons d'action */
            table th {
                background-color:rgb(79 70 229); /* Bleu */
                color: white;
                text-align: left;
                padding: 8px;
            }
            .btn-modifier, .btn-edit {
                background-color:rgb(14, 120, 240); /* Bleu */
                color: white;
                border: none;
                padding: 5px 10px;
                border-radius: 3px;
                cursor: pointer;
                text-decoration: none;
                font-size: 14px;
                margin-right: 5px;
            }

            .btn-modifier:hover, .btn-edit:hover {
                background-color:rgb(12, 105, 206); /* Bleu foncé au survol */
            }
            
            .btn-delete {
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 5px 10px;
                border-radius: 3px;
                cursor: pointer;
                text-decoration: none;
                font-size: 14px;
            }
            
            .btn-delete:hover {
                background-color: #c82333;
            }
            
            .btn-new, .btn-save {
                background-color: rgba(98, 75, 255, 1);
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 5px;
                cursor: pointer;
                text-decoration: none;
                font-weight: bold;
                display: inline-block;
                margin-bottom: 20px;
            }
            
            .btn-new:hover, .btn-save:hover {
                background-color: rgba(98, 75, 255, 1);
            }
            
            /* Styles pour le formulaire de bilan */
            .form-container {
                max-width: 1000px;
                margin: 0 auto;
                background-color: white;
                border-radius: 5px;
                overflow: hidden;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            
            /* En-tête du formulaire */
            .form-header {
                background-color: #e6f0ff;
                padding: 15px;
                text-align: center;
                position: relative;
            }
            
            .form-header h1 {
                margin: 0;
                font-size: 24px;
                color: #333;
            }
            
            .identifier {
                font-size: 12px;
                color: #666;
                position: absolute;
                right: 15px;
                top: 15px;
            }
            
            /* Section date et lieu */
            .date-section {
                display: flex;
                background-color: #e6f0ff;
                border-bottom: 1px solid #ccc;
            }
            
            .date-field, .lieu-field {
                flex: 1;
                padding: 10px 15px;
            }
            
            .date-field {
                border-right: 1px solid #ccc;
            }
            
            .date-section label {
                display: block;
                font-size: 12px;
                margin-bottom: 5px;
                color: #333;
            }
            
            .date-section input {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            
            /* Section victime */
            .victime-section {
                background-color: #ffe6e6;
                padding: 15px;
                border-bottom: 1px solid #ccc;
            }
            
            .victime-section h2 {
                text-align: center;
                margin: 0 0 15px 0;
                font-size: 18px;
                color: #333;
            }
            
            .gender-row {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
            }
            
            .gender-row label {
                margin-right: 15px;
                font-weight: bold;
            }
            
            .radio-group {
                display: flex;
                gap: 20px;
            }
            
            .identity-row {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            
            .form-group input {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            
            /* Section bilan circonstanciel */
            .circonstanciel-section {
                background-color: #e6f0ff;
                padding: 15px;
                border-bottom: 1px solid #ccc;
            }
            
            .circonstanciel-section h2 {
                text-align: center;
                margin: 0 0 15px 0;
                font-size: 18px;
                color: #333;
            }
            
            .checkbox-row {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-bottom: 15px;
            }
            
            .checkbox-group {
                display: flex;
                align-items: center;
                gap: 5px;
            }
            
            .autre-row label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            
            .autre-row input {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            
            /* Section bilan d'urgence vitale */
            .urgence-section {
                background-color: #e6f0ff;
                padding: 15px;
                border-bottom: 1px solid #ccc;
            }
            
            .urgence-title {
                text-align: center;
                margin: 0 0 15px 0;
                font-size: 18px;
                color: #d9534f;
                font-weight: bold;
            }
            
            .critical label {
                color: #d9534f;
                font-weight: bold;
            }
            
            /* Colonnes */
            .three-columns {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
                margin-top: 15px;
            }
            
            .column {
                border: 1px solid #ccc;
                border-radius: 4px;
                overflow: hidden;
            }
            
            .column-header {
                background-color: #d4edda;
                padding: 8px;
                text-align: center;
                font-weight: bold;
                border-bottom: 1px solid #ccc;
            }
            
            .column-content {
                padding: 10px;
            }
            
            .field-row {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            
            .field-row label {
                flex: 1;
                font-size: 12px;
            }
            
            .field-row .small-input {
                width: 50px;
                padding: 5px;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin: 0 5px;
            }
            
            .subheader {
                font-size: 12px;
                color: #007bff;
                margin: 10px 0 5px;
                font-weight: bold;
            }
            
            /* Échelle de douleur */
            .douleur-section {
                margin-top: 15px;
            }
            
            .douleur-scale {
                display: flex;
                justify-content: space-between;
                margin-top: 5px;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 10px;
                background-color: #f9f9f9;
            }
            
            .douleur-item {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            /* Actions du formulaire */
            .form-actions {
                display: flex;
                justify-content: center;
                padding: 15px;
                background-color: #fff;
                border-top: 1px solid #ddd;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .three-columns {
                    grid-template-columns: 1fr;
                }
                
                .identity-row {
                    grid-template-columns: 1fr;
                }
                
                .date-section {
                    flex-direction: column;
                }
                
                .date-field {
                    border-right: none;
                    border-bottom: 1px solid #ccc;
                }
            }
        </style>
    </head>
<body>
<main>
       
       <div id="app-layout" class="overflow-x-hidden flex">

              

          <!-- start navbar -->
          <?php require_once 'layout/sidebar.php'; ?>
      <!-- Overlay pour mobile -->
            <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
            <!-- Style CSS pour espacer les éléments du menu -->
            <style>
                .navbar-nav {
                    gap: 1.2rem; /* Espacement global entre les éléments */
                    padding-left: 0;
                }

                .nav-item {
                    margin-bottom: 1.2rem; /* Marges verticales entre les éléments */
                }

                .nav-item a {
                    display: flex;
                    align-items: center; /* Aligner les icônes et le texte sur la même ligne */
                    padding: 0.8rem 1.2rem; /* Un peu de padding pour rendre les éléments plus cliquables */
                }

                .nav-link {
                    font-size: 1rem;
                    color: white; /* Couleur du texte en blanc */
                    transition: color 0.3s ease;
                }

                .navbar-vertical .navbar-nav .nav-item .nav-link{
                    color: white !important;
                }

                .nav-link.active {
                    color: #ffc107; /* Couleur des éléments actifs (jaune) */
                }

                .nav-link:hover {
                    color: #d1d1d1; /* Couleur de survol des éléments (gris clair) */
                }

                .navbar-vertical {
                    box-shadow: 2px 0px 6px rgba(0, 0, 0, 0.1); /* Ombre douce pour donner un effet de profondeur */
                }
                
                .container {
                    padding: 16px;
                    }

                    .header-container {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 16px;
                    }

                    .header-container h3 {
                        margin: 0;
                    }

                    .header-container .btn-create {
                        background-color: rgb(79 70 229 / var(--tw-bg-opacity, 1));;
                        color: #fff;
                        padding: 8px 16px;
                        text-decoration: none;
                        border-radius: 4px;
                    }

                    .table-responsive {
                        overflow-x: auto; /* Ajout d'un défilement horizontal */
                        -webkit-overflow-scrolling: touch; /* Défilement fluide pour les appareils mobiles */
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        text-align: left;
                    }

                    table th, table td {
                        padding: 8px 12px;
                        border: 1px solid #ddd;
                    }


                    .btn-modifier {
                        color: #fff;
                        background-color: rgb(79 70 229);
                        padding: 6px 12px;
                        text-decoration: none;
                        border-radius: 4px;
                    }

                    .alert {
                        padding: 12px;
                        margin-bottom: 16px;
                        border-radius: 4px;
                    }

                    .alert-success {
                        background-color: #d4edda;
                        color: #155724;
                    }

                    .alert-error {
                        background-color: #f8d7da;
                        color: #721c24;
                    }


                    @media (max-width: 768px) {
                        .header-container h3 {
                            display: none; /* Masquer le titre sur mobile */
                    }

                    .header-container .btn-create {
                        margin-left: auto; /* S'assure que le bouton reste aligné à droite */
                    }
                    }


            </style>
                  <div id="app-layout-content" class="min-h-screen w-full min-w-[100vw] md:min-w-0 ml-[15.625rem] [transition:margin_0.25s_ease-out]">
               <!-- début de la barre de navigation -->
                <div class="header">
                    <!-- navbar -->
                    <nav class="bg-white px-6 py-[10px] flex items-center justify-between shadow-sm">
                        <a id="nav-toggle" href="#" class="text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </a>
                        <div class="ml-3 hidden md:hidden lg:block">
                            <!-- formulaire de recherche -->
                            <form class="flex items-center">
                                <input
                                type="search"
                                class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3 disabled:opacity-50 disabled:pointer-events-none"
                                placeholder="Rechercher" />
                            </form>
                        </div>
                        <!-- navigation principale -->
                        <ul class="flex ml-auto items-center">
                            <li class="dropdown stopevent mr-2">
                                <a class="text-gray-600" href="#" role="button" id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                                </svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-lg lg:left-auto lg:right-0" aria-labelledby="dropdownNotification">
                                <div>
                                    <div class="border-b px-3 pt-2 pb-3 flex justify-between items-center">
                                        <span class="text-lg text-gray-800 font-semibold">Notifications</span>
                                        <a href="#">
                                            <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            </span>
                                        </a>
                                    </div>
                                    <!-- liste des notifications -->
                                    <ul class="h-56" data-simplebar="">
                                        <!-- élément de notification -->
                                        <li class="bg-gray-100 px-3 py-2 border-b">
                                            <a href="#">
                                            <h5 class="mb-1">Notification 1</h5>
                                            <p class="mb-0">Contenu de la notification 1</p>
                                            </a>
                                        </li>
                                        <li class="px-3 py-2 border-b">
                                            <a href="#">
                                            <h5 class="mb-1">Notification 2</h5>
                                            <p class="mb-0">Contenu de la notification 2</p>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="border-top px-3 py-2 text-center">
                                        <a href="#" class="text-gray-800 font-semibold">Voir toutes les notifications</a>
                                    </div>
                                </div>
                                </div>
                            </li>
                            <!-- Liste des actions utilisateur -->
                            <li class="dropdown ml-2">
                                <a class="rounded-full" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="w-10 h-10 relative">
                                    <img alt="avatar" src="./assets/images/avatar/avatar.jpg" class="rounded-full" />
                                    <div class="absolute border-gray-200 border-2 rounded-full right-0 bottom-0 bg-green-600 h-3 w-3"></div>
                                </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="dropdownUser">
                                <div class="px-4 pb-0 pt-2">
                                    <div class="leading-4">
                                        <h5 class="mb-1">Unitech Group</h5>
                                        <a href="#">Voir mon profil</a>
                                    </div>
                                    <div class="border-b mt-3 mb-2"></div>
                                </div>

                                <ul class="list-unstyled">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="w-4 h-4" data-feather="user"></i>
                                            Modifier le profil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="w-4 h-4" data-feather="activity"></i>
                                            Journal d'activité
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="logout.php">
                                            <i class="w-4 h-4" data-feather="power"></i>
                                            Se déconnecter
                                        </a>
                                    </li>

                                </ul>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- fin de la barre de navigation -->
                <div class="bg-indigo-600 px-8 pt-4 pb-4 flex justify-between items-center mb-3">
                    <h3 class="text-white mb-1 font-normal">Gestion des bilans</h3>
                </div>

                <!-- Barre sous le texte -->
                <hr class="border-t border-indigo-400 my-1">

                <!-- Contenu principal -->
                <div class="container table-responsive">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['message']; ?></div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-error"><?php echo $_SESSION['error']; ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <?php if ($showList): ?>
                        <!-- Liste des fiches -->
                        <div class="list-container">
                            <div class="header-container">
                                <h1>Liste des Fiches Bilan</h1>
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?new=1" class="btn-new bg-indigo-600">Nouvelle Fiche</a>
                            </div>
                            
                            <table class=" w-full table-auto border-separate border-spacing-0 rounded-lg overflow-hidden">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Age</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $sql = "SELECT id, date_heure, nom, prenom, age FROM fiches_bilan ORDER BY date_heure DESC";
                                        $stmt = $pdo->query($sql);
                                        
                                        if ($stmt && $stmt->rowCount() > 0):
                                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['date_heure']); ?></td>
                                            <td><?php echo htmlspecialchars($row['nom']); ?></td>
                                            <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                                            <td>
                                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>" class="btn-edit">Éditer</a>
                                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette fiche?')">Supprimer</a>
                                            </td>
                                        </tr>
                                    <?php
                                            endwhile;
                                        else:
                                    ?>
                                        <tr>
                                            <td colspan="6">Aucune fiche trouvée</td>
                                        </tr>
                                    <?php 
                                        endif;
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="6">Erreur de base de données: ' . $e->getMessage() . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Formulaire -->
                        <div class="form-container">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <?php if(isset($_GET['id'])): ?>
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <?php endif; ?>
                                
                                <!-- FICHE BILAN Header -->
                                <div class="form-header">
                                    <h1>FICHE BILAN</h1>
                                    <!-- <span class="identifier">Identifiant de MC_Bilan_Premier_Secours</span> -->
                                </div>
                                
                                <!-- Date Section -->
                                <div class="date-section">
                                    <div class="date-field">
                                        <label>Date heure prise en compte</label>
                                        <input type="text" name="date_heure" value="<?php echo htmlspecialchars($date); ?>" readonly>
                                    </div>
                                    <div class="lieu-field">
                                        <label>Lieu</label>
                                        <input type="text" name="lieu" value="<?php echo htmlspecialchars($lieu); ?>">
                                    </div>
                                </div>
                                
                                <!-- VICTIME Section -->
                                <div class="victime-section">
                                    <h2>VICTIME</h2>
                                    <div class="gender-row">
                                        <label>Civilité</label>
                                        <div class="radio-group">
                                            <input type="radio" id="gender-m" name="gender" value="M" <?php if($gender == 'M') echo 'checked'; ?>>
                                            <label for="gender-m">M</label>
                                            
                                            <input type="radio" id="gender-f" name="gender" value="F" <?php if($gender == 'F') echo 'checked'; ?>>
                                            <label for="gender-f">F</label>
                                        </div>
                                    </div>
                                    
                                    <div class="identity-row">
                                        <div class="form-group">
                                            <label for="nom">Nom</label>
                                            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="prenom">Prénom</label>
                                            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="age">AGE</label>
                                            <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- BILAN CIRCONSTANCIEL -->
                                <div class="circonstanciel-section">
                                    <h2>BILAN CIRCONSTANCIEL</h2>
                                    <div class="checkbox-row">
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="malaise" name="malaise" value="1" <?php if(isset($malaise) && $malaise) echo 'checked'; ?>>
                                            <label for="malaise">Malaise</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="chute" name="chute" value="1" <?php if(isset($chute) && $chute) echo 'checked'; ?>>
                                            <label for="chute">Chute</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="blessure" name="blessure" value="1" <?php if(isset($blessure) && $blessure) echo 'checked'; ?>>
                                            <label for="blessure">Blessure</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="choc" name="choc" value="1" <?php if(isset($choc) && $choc) echo 'checked'; ?>>
                                            <label for="choc">Choc</label>
                                        </div>
                                    </div>
                                    
                                    <div class="autre-row">
                                        <label for="autre">AUTRE</label>
                                        <input type="text" id="autre" name="autre" value="<?php echo isset($autre) ? htmlspecialchars($autre) : ''; ?>">
                                    </div>
                                </div>
                                
                                <!-- BILAN D'URGENCE VITALE -->
                                <div class="urgence-section">
                                    <h2 class="urgence-title">BILAN D'URGENCE VITALE</h2>
                                    
                                    <div class="checkbox-row">
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="obva" name="obva" value="1" <?php if(isset($obva) && $obva) echo 'checked'; ?>>
                                            <label for="obva">OBVA</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="hemorragie" name="hemorragie" value="1" <?php if(isset($hemorragie) && $hemorragie) echo 'checked'; ?>>
                                            <label for="hemorragie">Hémorragie</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="inconscience" name="inconscience" value="1" <?php if(isset($inconscience) && $inconscience) echo 'checked'; ?>>
                                            <label for="inconscience">Inconscience</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="arret_ventilatoire" name="arret_ventilatoire" value="1" <?php if(isset($arret_ventilatoire) && $arret_ventilatoire) echo 'checked'; ?>>
                                            <label for="arret_ventilatoire">Arrêt ventilatoire</label>
                                        </div>
                                        
                                        <div class="checkbox-group critical">
                                            <input type="checkbox" id="arret_cardio" name="arret_cardio" value="1" <?php if(isset($arret_cardio) && $arret_cardio) echo 'checked'; ?>>
                                            <label for="arret_cardio">Arrêt cardio-ventilatoire</label>
                                        </div>
                                    </div>
                                    
                                    <!-- Three columns section -->
                                    <div class="three-columns">
                                        <!-- Conscience Column -->
                                        <div class="column">
                                            <div class="column-header">Conscience</div>
                                            <div class="column-content">
                                                <div class="field-row">
                                                    <label for="pc_depuis">PC depuis</label>
                                                    <input type="text" id="pc_depuis" name="pc_depuis" class="small-input" value="<?php echo isset($pc_depuis) ? htmlspecialchars($pc_depuis) : ''; ?>">
                                                    <span>Min</span>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="reponds_questions" name="reponds_questions" value="1" <?php if(isset($reponds_questions) && $reponds_questions) echo 'checked'; ?>>
                                                    <label for="reponds_questions">Réponds aux questions</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="ouvre_yeux" name="ouvre_yeux" value="1" <?php if(isset($ouvre_yeux) && $ouvre_yeux) echo 'checked'; ?>>
                                                    <label for="ouvre_yeux">Ouvre les yeux</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="desoriente" name="desoriente" value="1" <?php if(isset($desoriente) && $desoriente) echo 'checked'; ?>>
                                                    <label for="desoriente">Désorienté: temps espace</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="agite" name="agite" value="1" <?php if(isset($agite) && $agite) echo 'checked'; ?>>
                                                    <label for="agite">Agité</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="confus" name="confus" value="1" <?php if(isset($confus) && $confus) echo 'checked'; ?>>
                                                    <label for="confus">Confus</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="somnolent" name="somnolent" value="1" <?php if(isset($somnolent) && $somnolent) echo 'checked'; ?>>
                                                    <label for="somnolent">Somnolent</label>
                                                </div>
                                                
                                                <div class="subheader">Motricité, Sensibilité</div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="ne_serre_pas" name="ne_serre_pas" value="1" <?php if(isset($ne_serre_pas) && $ne_serre_pas) echo 'checked'; ?>>
                                                    <label for="ne_serre_pas">Ne serre pas les mains</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="ne_bouge_pas" name="ne_bouge_pas" value="1" <?php if(isset($ne_bouge_pas) && $ne_bouge_pas) echo 'checked'; ?>>
                                                    <label for="ne_bouge_pas">Ne bouge pas les pieds / ortels</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="ne_reagit_pas" name="ne_reagit_pas" value="1" <?php if(isset($ne_reagit_pas) && $ne_reagit_pas) echo 'checked'; ?>>
                                                    <label for="ne_reagit_pas">Ne réagit pas au pincement</label>
                                                </div>
                                                
                                                <div class="subheader">Pupilles</div>
                                                <div class="checkbox-row">
                                                    <div class="checkbox-group">
                                                        <input type="checkbox" id="pupilles_egales" name="pupilles_egales" value="1" <?php if(isset($pupilles_egales) && $pupilles_egales) echo 'checked'; ?>>
                                                        <label for="pupilles_egales">Égales</label>
                                                    </div>
                                                    
                                                    <div class="checkbox-group">
                                                        <input type="checkbox" id="pupilles_inegales" name="pupilles_inegales" value="1" <?php if(isset($pupilles_inegales) && $pupilles_inegales) echo 'checked'; ?>>
                                                        <label for="pupilles_inegales">Inégales</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Ventilation Column -->
                                        <div class="column">
                                            <div class="column-header">Ventilation</div>
                                            <div class="column-content">
                                                <div class="field-row">
                                                    <label for="freq_respiratoire">Fréq. respiratoire</label>
                                                    <input type="text" id="freq_respiratoire" name="freq_respiratoire" class="small-input" value="<?php echo isset($freq_respiratoire) ? htmlspecialchars($freq_respiratoire) : ''; ?>">
                                                    <span>/ minute</span>
                                                </div>
                                                
                                                <div class="subheader">Respiration</div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="respiration_normale" name="respiration_normale" value="1" <?php if(isset($respiration_normale) && $respiration_normale) echo 'checked'; ?>>
                                                    <label for="respiration_normale">Respiration normale</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="respiration_reguliere" name="respiration_reguliere" value="1" <?php if(isset($respiration_reguliere) && $respiration_reguliere) echo 'checked'; ?>>
                                                    <label for="respiration_reguliere">Régulière</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="respiration_superficielle" name="respiration_superficielle" value="1" <?php if(isset($respiration_superficielle) && $respiration_superficielle) echo 'checked'; ?>>
                                                    <label for="respiration_superficielle">Resp. superficielle</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="respiration_difficile" name="respiration_difficile" value="1" <?php if(isset($respiration_difficile) && $respiration_difficile) echo 'checked'; ?>>
                                                    <label for="respiration_difficile">Resp. difficile</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="respiration_bruyante" name="respiration_bruyante" value="1" <?php if(isset($respiration_bruyante) && $respiration_bruyante) echo 'checked'; ?>>
                                                    <label for="respiration_bruyante">Resp. bruyante</label>
                                                </div>
                                                
                                                <div class="subheader">Bruits ventilatoires</div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="gargouillement" name="gargouillement" value="1" <?php if(isset($gargouillement) && $gargouillement) echo 'checked'; ?>>
                                                    <label for="gargouillement">Gargouillement</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="sifflements" name="sifflements" value="1" <?php if(isset($sifflements) && $sifflements) echo 'checked'; ?>>
                                                    <label for="sifflements">Sifflements</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="ronflements" name="ronflements" value="1" <?php if(isset($ronflements) && $ronflements) echo 'checked'; ?>>
                                                    <label for="ronflements">Ronflements</label>
                                                </div>
                                                
                                                <div class="subheader">Visage</div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="sueurs" name="sueurs" value="1" <?php if(isset($sueurs) && $sueurs) echo 'checked'; ?>>
                                                    <label for="sueurs">Sueurs / transpiration</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="cyanose" name="cyanose" value="1" <?php if(isset($cyanose) && $cyanose) echo 'checked'; ?>>
                                                    <label for="cyanose">Cyanose</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Circulation Column -->
                                        <div class="column">
                                            <div class="column-header">Circulation</div>
                                            <div class="column-content">
                                                <div class="field-row">
                                                    <label for="freq_cardiaque">Fréq. Cardiaque</label>
                                                    <input type="text" id="freq_cardiaque" name="freq_cardiaque" class="small-input" value="<?php echo isset($freq_cardiaque) ? htmlspecialchars($freq_cardiaque) : ''; ?>">
                                                    <span>battement / minute</span>
                                                </div>
                                                
                                                <div class="checkbox-row">
                                                    <div class="checkbox-group">
                                                        <input type="checkbox" id="regulier" name="regulier" value="1" <?php if(isset($regulier) && $regulier) echo 'checked'; ?>>
                                                        <label for="regulier">Régulier</label>
                                                    </div>
                                                    
                                                    <div class="checkbox-group">
                                                        <input type="checkbox" id="irregulier" name="irregulier" value="1" <?php if(isset($irregulier) && $irregulier) echo 'checked'; ?>>
                                                        <label for="irregulier">Irrégulier</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="subheader">Observations</div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="paleur_visage" name="paleur_visage" value="1" <?php if(isset($paleur_visage) && $paleur_visage) echo 'checked'; ?>>
                                                    <label for="paleur_visage">Pâleur visage</label>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <input type="checkbox" id="froideur_membres" name="froideur_membres" value="1" <?php if(isset($froideur_membres) && $froideur_membres) echo 'checked'; ?>>
                                                    <label for="froideur_membres">Froideur des membres</label>
                                                </div>
                                                
                                                <div class="douleur-section">
                                                    <label>Échelle de douleur</label>
                                                    <div class="douleur-scale">
                                                        <?php for($i = 0; $i <= 10; $i++): ?>
                                                        <div class="douleur-item">
                                                            <input type="radio" id="douleur_<?php echo $i; ?>" name="douleur" value="<?php echo $i; ?>" <?php if(isset($douleur) && $douleur == $i) echo 'checked'; ?>>
                                                            <label for="douleur_<?php echo $i; ?>"><?php echo $i; ?></label>
                                                        </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="form-actions">
                                    <button type="submit" name="save" class="btn-save">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Simplebar JS -->
    <script src="https://cdn.jsdelivr.net/npm/simplebar@5.3.9/dist/simplebar.min.js"></script>
    
    <script>
        // Initialiser Feather Icons
        feather.replace();
        
        // Toggle sidebar
        document.getElementById('nav-toggle').addEventListener('click', function() {
            document.querySelector('.navbar-vertical').classList.toggle('show');
        });
        
        // Initialiser Simplebar
        new SimpleBar(document.getElementById('myScrollableElement'));
        
        // Bootstrap Dropdown
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
        
        // Bootstrap Collapse
        var collapseElementList = [].slice.call(document.querySelectorAll('.collapse'));
        var collapseList = collapseElementList.map(function (collapseEl) {
            return new bootstrap.Collapse(collapseEl, {
                toggle: false
            });
        });
    </script>
</body>
</html>
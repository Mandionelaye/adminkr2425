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


// Define constant to allow includes
define('INCLUDED_FROM_ENTREES', true);

// Connexion à la base de données
require_once 'config.php';

// Get list of agents (users with role 'agent')
$agents_query = "SELECT *, CONCAT(nom, ' ',prenom) as agent_nom  FROM utilisateurs WHERE role = 'agent' ORDER BY nom";
$agents_stmt = $pdo->query($agents_query);
$agents = $agents_stmt->fetchAll(PDO::FETCH_ASSOC);


$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valider'])) {
    // Validate required foreign keys
    if (empty($_POST['site_id']) || empty($_POST['agent_id'])) {
        $message = "Erreur : Le site et l'agent sont requis.";
    } else {
        // Récupération et sécurisation des données du formulaire
        $site_id = (int) $_POST['site_id'];
        $agent_id = (int) $_POST['agent_id'];
        $reference = htmlspecialchars(trim($_POST['reference']), ENT_QUOTES);
        $numero = (int) $_POST['numero'];
        $entree_date = $_POST['entree_date'] ?? '';
        $entree_heure = $_POST['entree_heure'] ?? '';
        $sortie_date = $_POST['sortie_date'] ?? '';
        $sortie_heure = $_POST['sortie_heure'] ?? '';
        $identite = in_array($_POST['identite'], ['employe', 'interimaire', 'visiteur']) ? $_POST['identite'] : 'visiteur';
        $nom = htmlspecialchars(trim($_POST['nom']), ENT_QUOTES);
        $prenom = htmlspecialchars(trim($_POST['prenom']), ENT_QUOTES);
        $motif_entree = htmlspecialchars(trim($_POST['motif_entree']), ENT_QUOTES);
        $societe = htmlspecialchars(trim($_POST['societe']), ENT_QUOTES);
        $service = htmlspecialchars(trim($_POST['service']), ENT_QUOTES);
        $personne_visitee = htmlspecialchars(trim($_POST['personne_visitee']), ENT_QUOTES);
        $badge = htmlspecialchars(trim($_POST['badge']), ENT_QUOTES);
        $piece = (int) $_POST['piece'];
        $matricule = htmlspecialchars(trim($_POST['matricule']), ENT_QUOTES);
        $controle = isset($_POST['controle']) ? 1 : 0;
        $plomb = htmlspecialchars(trim($_POST['plomb']), ENT_QUOTES);
        $remorque = htmlspecialchars(trim($_POST['remorque']), ENT_QUOTES);
        $quai = htmlspecialchars(trim($_POST['quai']), ENT_QUOTES);
        $livraison = htmlspecialchars(trim($_POST['livraison']), ENT_QUOTES);
        $probleme = isset($_POST['probleme']) ? 1 : 0;
        $commentaires = htmlspecialchars(trim($_POST['commentaires']), ENT_QUOTES);
        
        // Verify foreign keys exist
        $check_site = $pdo->prepare("SELECT id FROM bj_site WHERE id = ?");
        $check_site->execute([$site_id]);
        
        $check_agent = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ? AND role = 'agent'");
        $check_agent->execute([$agent_id]);
        
        if (!$check_site->fetch()) {
            $message = "Erreur : Site invalide";
        } else if (!$check_agent->fetch()) {
            $message = "Erreur : Agent invalide";
        } else {
            try {
                $sql = "INSERT INTO entree (
                    reference, numero, entree_date, entree_heure, sortie_date, sortie_heure,
                    identite, site_id, agent_id, nom, prenom, motif_entree, societe,
                    service, personne_visitee, badge, piece, matricule, controle,
                    plomb, remorque, quai, livraison, probleme, commentaires
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $reference, $numero, $entree_date, $entree_heure, $sortie_date, $sortie_heure,
                    $identite, $site_id, $agent_id, $nom, $prenom, $motif_entree, $societe,
                    $service, $personne_visitee, $badge, $piece, $matricule, $controle,
                    $plomb, $remorque, $quai, $livraison, $probleme, $commentaires
                ]);

                $message = "Enregistrement réussi !";
            } catch (PDOException $e) {
                $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
    }
}

// Récupération des entrées pour l'affichage
$entries = [];
try {
    $sql = "SELECT *
            FROM permis_feu  
            ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width" />
    <meta name="description" content="Formulaire de permis de feu pour autorisation de travail par points chauds" />
    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon/favicon.ico" />

    <!-- Libs CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
    <link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <!-- Theme CSS -->
    <link rel="stylesheet" href="./assets/css/theme.min.css">
    <title>Permis de Feu - Autorisation de Travail par Points Chauds</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
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
        
        /* Styles pour le formulaire de permis de feu */
        .form-container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .form-header {
            background-color: #e30613;
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 5px 5px 0 0;
        }
        
        .form-header h1 {
            margin: 0;
            font-size: 20px;
        }
        
        .form-subheader {
            background-color: #e30613;
            color: white;
            text-align: center;
            padding: 5px;
            margin-top: -5px;
            border-radius: 0 0 5px 5px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .form-section {
            margin: 15px;
            border: 2px solid #f8d7da;
            border-radius: 5px;
            padding: 15px;
        }
        
        .section-title {
            background-color: #f8d7da;
            padding: 8px 12px;
            margin: -15px -15px 15px -15px;
            border-radius: 3px 3px 0 0;
            font-weight: bold;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
            gap: 15px;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .checkbox-group label {
            margin-left: 5px;
            margin-bottom: 0;
        }
        
        .checkbox-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .signature-line {
            text-align: right;
            margin-top: 10px;
        }
        
        .footer-note {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.5;
            margin: 15px;
        }
        
        .submit-btn {
            background-color: #e30613;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 15px auto;
        }
        
        .submit-btn:hover {
            background-color: #c00;
        }
        
        /* Styles pour la navbar et sidebar */
        .navbar-nav {
            gap: 1.2rem;
            padding-left: 0;
        }

        .nav-item {
            margin-bottom: 1.2rem;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.2rem;
        }

        .nav-link {
            font-size: 1rem;
            color: white;
            transition: color 0.3s ease;
        }

        .navbar-vertical .navbar-nav .nav-item .nav-link {
            color: white !important;
        }

        .nav-link.active {
            color: #ffc107;
        }

        .nav-link:hover {
            color: #d1d1d1;
        }

        .navbar-vertical {
            box-shadow: 2px 0px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-group {
                flex: 100%;
            }
            
            .checkbox-container {
                grid-template-columns: 1fr;
            }
            
            .header-container h3 {
                display: none;
            }
            
            .header-container .btn-create {
                margin-left: auto;
            }
        }
    </style>
</head>
<body>
    <div id="app-layout" class="overflow-x-hidden flex">
        <!-- Sidebar -->
        <?php include 'layout/sidebar.php'; ?>

        <div id="app-layout-content" class="min-h-screen w-full min-w-[100vw] md:min-w-0 ml-[15.625rem]">
            <!-- Header/Navbar -->
            <div class="header">
                    <nav class="bg-white px-6 py-[10px] flex items-center justify-between shadow-sm">
                        <a id="nav-toggle" href="#" class="text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </a>
                        <div class="ml-3 hidden md:hidden lg:block">
                            <!-- Formulaire de recherche -->
                            <form class="flex items-center">
                                <input
                                type="search"
                                class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3 disabled:opacity-50 disabled:pointer-events-none"
                                placeholder="Rechercher" />
                            </form>
                        </div>
                        <!-- Navigation principale -->
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
                                        <!-- Liste des notifications -->
                                        <ul class="h-56" data-simplebar="">
                                            <!-- Élément de notification -->
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
                                        <h5 class="mb-1">Utilisateur</h5>
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
                <div class="bg-indigo-600 px-8 pt-4 pb-4 flex justify-between items-center mb-3">
                    <h3 class="text-white mb-1 font-normal">Gestion des entrées</h3>
                </div>
                 <!-- Barre sous le texte -->
                 <hr class="border-t border-indigo-400 my-1">

                    <!-- Contenu principal -->
        <div class="card shadow-lg">
        <div class="card-body h-screen">
            <!-- Main Content -->
            <div class="list-container">
                            <div class="header-container">
                                <h1>Liste Permis Feu</h1>
                                <a href="add-permis.php" class="btn-new bg-indigo-600 p-4 text-white rounded">Nouvelle Fiche</a>
                            </div>

                <?php if (!empty($message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <?php
if (isset($_GET['success']) && $_GET['success'] === 'deleted') {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">Le permis de feu a été supprimé avec succès.</div>';
}
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'delete_failed') {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">La suppression a échoué.</div>';
    } elseif ($_GET['error'] === 'id_missing') {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">ID manquant pour la suppression.</div>';
    }
}
?>
                
                <!-- Table des entrées -->
                    <table class="w-full table-auto border-separate border-spacing-0 rounded-lg overflow-hidden">
                        <thead >
                            <tr class="bg-indigo-600 text-white">
                                <th class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">N°</th>
                                <th class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">Établissement</th>
                                <th class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">Adresse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">Code Postal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">Ville</th>
                                <th class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($entries as $entry): ?>
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($entry['numero_permis']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($entry['nom_emplois']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($entry['etablissement']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($entry['adresse']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($entry['code_postal']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($entry['ville']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a type="button" data-modal-target="default-modal<?= $entry['id'] ?>" data-modal-toggle="default-modal<?= $entry['id'] ?>" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                        <a type="button" data-modal-target="edit-modal<?= $entry['id'] ?>" data-modal-toggle="edit-modal<?= $entry['id'] ?>"  class="ml-3 text-green-600 hover:text-green-900">Modifier</a>
                                        <a href="supprime-permis.php?id=<?= $entry['id'] ?>"  class="ml-3 text-red-600 hover:text-green-900"
                                          onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce permis de feu ?')"
                                        >sup</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>


    <?php foreach ($entries as $permis): ?>
   <!-- Main modal -->
   <div id="default-modal<?= $permis['id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-[55%] max-h-full">
         <!-- Modal content -->
         <div class="relative bg-white rounded-lg shadow-sm">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
               <h3 class="text-xl font-semibold text-gray-900">
                  Détails du permis de feu
               </h3>
               <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="default-modal">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                  <span class="sr-only">Fermer</span>
               </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4 overflow-y-auto max-h-80">
               <div class="relative overflow-x-auto">
                  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                     <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                           <th scope="col" class="px-6 py-3">Libellé</th>
                           <th scope="col" class="px-6 py-3">Valeur</th>
                        </tr>
                     </thead>
                     <tbody>
                        <!-- Informations générales -->
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Numéro du permis</th>
                           <td class="px-6 py-4"><?= $permis['numero_permis'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Agent</th>
                           <td class="px-6 py-4"><?= $permis['nom_emplois'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Établissement</th>
                           <td class="px-6 py-4"><?= $permis['etablissement'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Adresse</th>
                           <td class="px-6 py-4"><?= $permis['adresse'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Code postal</th>
                           <td class="px-6 py-4"><?= $permis['code_postal'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Ville</th>
                           <td class="px-6 py-4"><?= $permis['ville'] ?></td>
                        </tr>

                        <!-- Donneur d'ordre -->
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Nom du donneur</th>
                           <td class="px-6 py-4"><?= $permis['nom_donneur'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Prénoms du donneur</th>
                           <td class="px-6 py-4"><?= $permis['prenoms_donneur'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Fonction du donneur</th>
                           <td class="px-6 py-4"><?= $permis['fonction_donneur'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Code postal du donneur</th>
                           <td class="px-6 py-4"><?= $permis['code_postal_donneur'] ?></td>
                        </tr>

                        <!-- Responsable -->
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Nom du responsable</th>
                           <td class="px-6 py-4"><?= $permis['nom_responsable'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Prénoms du responsable</th>
                           <td class="px-6 py-4"><?= $permis['prenoms_responsable'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Fonction du responsable</th>
                           <td class="px-6 py-4"><?= $permis['fonction_responsable'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Code postal du responsable</th>
                           <td class="px-6 py-4"><?= $permis['code_postal_responsable'] ?></td>
                        </tr>

                        <!-- Dates et horaires -->
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Date de début</th>
                           <td class="px-6 py-4"><?= $permis['date_debut'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Date de fin</th>
                           <td class="px-6 py-4"><?= $permis['date_fin'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Heure de début (1ère plage)</th>
                           <td class="px-6 py-4"><?= $permis['heure_debut1'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Heure de fin (1ère plage)</th>
                           <td class="px-6 py-4"><?= $permis['heure_fin1'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Heure de début (2ème plage)</th>
                           <td class="px-6 py-4"><?= $permis['heure_debut2'] ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Heure de fin (2ème plage)</th>
                           <td class="px-6 py-4"><?= $permis['heure_fin2'] ?></td>
                        </tr>

                        <!-- Risques et particularités -->
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Risques électriques</th>
                           <td class="px-6 py-4"><?= $permis['electrique'] ? 'Oui' : 'Non' ?></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                           <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Particularités</th>
                           <td class="px-6 py-4"><?= $permis['particularites'] ?></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
               <button data-modal-hide="default-modal<?= $permis['id'] ?>" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Fermer</button>
            </div>
         </div>
      </div>
   </div>
<?php endforeach; ?>


<?php foreach ($entries as $permis): ?>
   <!-- Main modal -->
   <div id="edit-modal<?= $permis['id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-[75%] max-h-full">
         <!-- Modal content -->
         <div class="relative bg-white rounded-lg shadow-sm">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
               <h3 class="text-xl font-semibold text-gray-900">
                  Modifier le permis de feu
               </h3>
               <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="edit-modal<?= $permis['id'] ?>">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                  <span class="sr-only">Fermer</span>
               </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4 overflow-y-auto max-h-80">
               <form action="modifier-permis.php" method="POST">
                  <!-- Champ caché pour l'ID -->
                  <input type="hidden" name="id" value="<?= $permis['id'] ?>">

                  <!-- Informations générales -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     <div>
                        <label for="numero_permis" class="block mb-2 text-sm font-medium text-gray-900">Numéro du permis</label>
                        <input type="text" name="numero_permis" id="numero_permis" value="<?= $permis['numero_permis'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="numero_permis" class="block mb-2 text-sm font-medium text-gray-900">Agent</label>
                        <!-- select nom agent -->
                         <select name="agent" id="agent_id" class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <?php foreach ($agents as $agent):?>
                               <option value="<?php echo $agent['agent_nom'];?>" <?php echo ($agent['agent_nom'] == $permis['nom_emplois'])?'selected' : '';?>><?php echo $agent['agent_nom'];?></option>
                            <?php endforeach;?>
                        </select>
                     </div>
                     <div>
                        <label for="etablissement" class="block mb-2 text-sm font-medium text-gray-900">Établissement</label>
                        <input type="text" name="etablissement" id="etablissement" value="<?= $permis['etablissement'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="adresse" class="block mb-2 text-sm font-medium text-gray-900">Adresse</label>
                        <input type="text" name="adresse" id="adresse" value="<?= $permis['adresse'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="code_postal" class="block mb-2 text-sm font-medium text-gray-900">Code postal</label>
                        <input type="text" name="code_postal" id="code_postal" value="<?= $permis['code_postal'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="ville" class="block mb-2 text-sm font-medium text-gray-900">Ville</label>
                        <input type="text" name="ville" id="ville" value="<?= $permis['ville'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                  </div>

                  <!-- Donneur d'ordre -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                     <div>
                        <label for="nom_donneur" class="block mb-2 text-sm font-medium text-gray-900">Nom du donneur</label>
                        <input type="text" name="nom_donneur" id="nom_donneur" value="<?= $permis['nom_donneur'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="prenoms_donneur" class="block mb-2 text-sm font-medium text-gray-900">Prénoms du donneur</label>
                        <input type="text" name="prenoms_donneur" id="prenoms_donneur" value="<?= $permis['prenoms_donneur'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="fonction_donneur" class="block mb-2 text-sm font-medium text-gray-900">Fonction du donneur</label>
                        <input type="text" name="fonction_donneur" id="fonction_donneur" value="<?= $permis['fonction_donneur'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="code_postal_donneur" class="block mb-2 text-sm font-medium text-gray-900">Code postal du donneur</label>
                        <input type="text" name="code_postal_donneur" id="code_postal_donneur" value="<?= $permis['code_postal_donneur'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                  </div>

                  <!-- Responsable -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                     <div>
                        <label for="nom_responsable" class="block mb-2 text-sm font-medium text-gray-900">Nom du responsable</label>
                        <input type="text" name="nom_responsable" id="nom_responsable" value="<?= $permis['nom_responsable'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="prenoms_responsable" class="block mb-2 text-sm font-medium text-gray-900">Prénoms du responsable</label>
                        <input type="text" name="prenoms_responsable" id="prenoms_responsable" value="<?= $permis['prenoms_responsable'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="fonction_responsable" class="block mb-2 text-sm font-medium text-gray-900">Fonction du responsable</label>
                        <input type="text" name="fonction_responsable" id="fonction_responsable" value="<?= $permis['fonction_responsable'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="code_postal_responsable" class="block mb-2 text-sm font-medium text-gray-900">Code postal du responsable</label>
                        <input type="text" name="code_postal_responsable" id="code_postal_responsable" value="<?= $permis['code_postal_responsable'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                  </div>

                  <!-- Dates et horaires -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                     <div>
                        <label for="date_debut" class="block mb-2 text-sm font-medium text-gray-900">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" value="<?= $permis['date_debut'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="date_fin" class="block mb-2 text-sm font-medium text-gray-900">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" value="<?= $permis['date_fin'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="heure_debut1" class="block mb-2 text-sm font-medium text-gray-900">Heure de début (1ère plage)</label>
                        <input type="time" name="heure_debut1" id="heure_debut1" value="<?= $permis['heure_debut1'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="heure_fin1" class="block mb-2 text-sm font-medium text-gray-900">Heure de fin (1ère plage)</label>
                        <input type="time" name="heure_fin1" id="heure_fin1" value="<?= $permis['heure_fin1'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                     </div>
                     <div>
                        <label for="heure_debut2" class="block mb-2 text-sm font-medium text-gray-900">Heure de début (2ème plage)</label>
                        <input type="time" name="heure_debut2" id="heure_debut2" value="<?= $permis['heure_debut2'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                     </div>
                     <div>
                        <label for="heure_fin2" class="block mb-2 text-sm font-medium text-gray-900">Heure de fin (2ème plage)</label>
                        <input type="time" name="heure_fin2" id="heure_fin2" value="<?= $permis['heure_fin2'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                     </div>
                  </div>

                  <!-- Risques et particularités -->
                  <div class="mt-4">
                     <label for="particularites" class="block mb-2 text-sm font-medium text-gray-900">Particularités</label>
                     <textarea name="particularites" id="particularites" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"><?= $permis['particularites'] ?></textarea>
                  </div>

                  <!-- Boutons de soumission -->
                  <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                     <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Enregistrer</button>
                     <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 ms-3" data-modal-hide="edit-modal<?= $permis['id'] ?>">Annuler</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
<?php endforeach; ?>



<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="./assets/libs/feather-icons/dist/feather.min.js"></script>
    <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
</body>
</html>
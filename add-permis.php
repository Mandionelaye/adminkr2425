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


// Connexion à la base de données (à adapter selon votre configuration)
require_once 'config.php';

// Fonction pour vérifier si un lien est actif
function isActive($path) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return $current_page == $path;
}

// Fonction pour vérifier si un lien parent est actif
function isParentActive($path) {
    $current_page = $_SERVER['PHP_SELF'];
    return strpos($current_page, $path) !== false;
}

$agents_query = "SELECT *, CONCAT(nom, ' ',prenom) as agent_nom  FROM utilisateurs WHERE role = 'agent' ORDER BY nom";
$agents_stmt = $pdo->query($agents_query);
$agents = $agents_stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire lors de la soumission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $numeroPermis = $_POST['numeroPermis'] ?? '';
    $agen = $_POST['agen'] ?? '';
    $etablissement = $_POST['etablissement'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $codePostal = $_POST['codePostal'] ?? '';
    $ville = $_POST['ville'] ?? '';
    
    // Ordre de travail
    $nomDonneur = $_POST['nomDonneur'] ?? '';
    $prenomsDonneur = $_POST['prenomsDonneur'] ?? '';
    $fonctionDonneur = $_POST['fonctionDonneur'] ?? '';
    $codePostalDonneur = $_POST['codePostalDonneur'] ?? '';
    
    // Exécution
    $serviceInterne = isset($_POST['serviceInterne']) ? 1 : 0;
    $entreprise = isset($_POST['entreprise']) ? 1 : 0;
    $adresseExecution = $_POST['adresseExecution'] ?? '';
    $codePostalExecution = $_POST['codePostalExecution'] ?? '';
    $villeExecution = $_POST['villeExecution'] ?? '';
    
    // Second ordre de travail
    $nomDonneur2 = $_POST['nomDonneur2'] ?? '';
    $prenomsDonneur2 = $_POST['prenomsDonneur2'] ?? '';
    $fonctionDonneur2 = $_POST['fonctionDonneur2'] ?? '';
    $codePostalDonneur2 = $_POST['codePostalDonneur2'] ?? '';
    
    // Responsable des travaux
    $nomResponsable = $_POST['nomResponsable'] ?? '';
    $prenomsResponsable = $_POST['prenomsResponsable'] ?? '';
    $fonctionResponsable = $_POST['fonctionResponsable'] ?? '';
    $codePostalResponsable = $_POST['codePostalResponsable'] ?? '';
    
    // Personnel exécutant
    $nomPersonnel1 = $_POST['nomPersonnel1'] ?? '';
    $prenomsPersonnel1 = $_POST['prenomsPersonnel1'] ?? '';
    $nomPersonnel2 = $_POST['nomPersonnel2'] ?? '';
    $prenomsPersonnel2 = $_POST['prenomsPersonnel2'] ?? '';
    $nomPersonnel3 = $_POST['nomPersonnel3'] ?? '';
    $prenomsPersonnel3 = $_POST['prenomsPersonnel3'] ?? '';
    
    // Information chantier
    $dateDebut = $_POST['dateDebut'] ?? '';
    $dateFin = $_POST['dateFin'] ?? '';
    $heureDebut1 = $_POST['heureDebut1'] ?? '';
    $heureFin1 = $_POST['heureFin1'] ?? '';
    $heureDebut2 = $_POST['heureDebut2'] ?? '';
    $heureFin2 = $_POST['heureFin2'] ?? '';
    $batiment = $_POST['batiment'] ?? '';
    $niveau = $_POST['niveau'] ?? '';
    $locaux = $_POST['locaux'] ?? '';
    
    // Nature des travaux
    $electrique = isset($_POST['electrique']) ? 1 : 0;
    $chalumeau = isset($_POST['chalumeau']) ? 1 : 0;
    $decoupage = isset($_POST['decoupage']) ? 1 : 0;
    $lampeSouder = isset($_POST['lampeSouder']) ? 1 : 0;
    $meulage = isset($_POST['meulage']) ? 1 : 0;
    $soudage = isset($_POST['soudage']) ? 1 : 0;
    $autres = isset($_POST['autres']) ? 1 : 0;
    $autresTexte = $_POST['autresTexte'] ?? '';
    
    // Risques particuliers
    $stockage = isset($_POST['stockage']) ? 1 : 0;
    $canalisation = isset($_POST['canalisation']) ? 1 : 0;
    $ventilation = isset($_POST['ventilation']) ? 1 : 0;
    $autresRisques = isset($_POST['autresRisques']) ? 1 : 0;
    
    // Particularités
    $particularites = $_POST['particularites'] ?? '';
    
    // Ici, vous pouvez ajouter le code pour enregistrer les données dans une base de données
    // ou les envoyer par email, etc.
    
    // Exemple de requête SQL (à adapter selon votre structure de base de données)
    
    $sql = "INSERT INTO permis_feu (
        numero_permis, nom_emplois, etablissement, adresse, code_postal, ville,
        nom_donneur, prenoms_donneur, fonction_donneur, code_postal_donneur,
        service_interne, entreprise, adresse_execution, code_postal_execution, ville_execution,
        nom_donneur2, prenoms_donneur2, fonction_donneur2, code_postal_donneur2,
        nom_responsable, prenoms_responsable, fonction_responsable, code_postal_responsable,
        nom_personnel1, prenoms_personnel1, nom_personnel2, prenoms_personnel2, nom_personnel3, prenoms_personnel3,
        date_debut, date_fin, heure_debut1, heure_fin1, heure_debut2, heure_fin2, batiment, niveau, locaux,
        electrique, chalumeau, decoupage, lampe_souder, meulage, soudage, autres, autres_texte,
        stockage, canalisation, ventilation, autres_risques, particularites
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $numeroPermis, $agen, $etablissement, $adresse, $codePostal, $ville,
        $nomDonneur, $prenomsDonneur, $fonctionDonneur, $codePostalDonneur,
        $serviceInterne, $entreprise, $adresseExecution, $codePostalExecution, $villeExecution,
        $nomDonneur2, $prenomsDonneur2, $fonctionDonneur2, $codePostalDonneur2,
        $nomResponsable, $prenomsResponsable, $fonctionResponsable, $codePostalResponsable,
        $nomPersonnel1, $prenomsPersonnel1, $nomPersonnel2, $prenomsPersonnel2, $nomPersonnel3, $prenomsPersonnel3,
        $dateDebut, $dateFin, $heureDebut1, $heureFin1, $heureDebut2, $heureFin2, $batiment, $niveau, $locaux,
        $electrique, $chalumeau, $decoupage, $lampeSouder, $meulage, $soudage, $autres, $autresTexte,
        $stockage, $canalisation, $ventilation, $autresRisques, $particularites
    ]);
    
    
    $message = "Permis de feu enregistré avec succès";
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
    <main>
        <div id="app-layout" class="overflow-x-hidden flex">
            <!-- Sidebar -->
            <?php require_once 'layout/sidebar.php'; ?>
            
            <!-- Overlay pour mobile -->
            <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
            
            <!-- Contenu principal -->
            <div id="app-layout-content" class="min-h-screen w-full min-w-[100vw] md:min-w-0 ml-[15.625rem] [transition:margin_0.25s_ease-out]">
                <!-- Barre de navigation -->
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
                
               

                <!-- Barre sous le texte -->
                <hr class="border-t border-indigo-400 my-1">

                <!-- Contenu principal -->
                <div class="container">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-container">
                    <form method="POST" action="">
                        <?php 
                           $nombreAleatoire = mt_rand(1, 10000);
                        ?>
                        <!-- En-tête du formulaire -->
                        <div class="form-header">
                            <h1>AUTORISATION DE TRAVAIL PAR POINTS CHAUDS</h1>
                            <div style="display: flex; align-items: center;">
                                <span style="margin-right: 10px; font-weight: bold;">N°</span>
                                <input type="number" name="numeroPermis" value="<?= $nombreAleatoire ?>" class="form-control" style="width: 100px; color: black" readonly>
                            </div>
                        </div>
                        
                        <div class="form-subheader">Permis de feu</div>
                        
                            <!-- Établissement -->
                            <div class="form-section">
                                <div class="form-row">
                                    <div class="form-group" style="flex: 2;">
                                        <label>Établissement donneur d'ordre</label>
                                        <input type="text" name="etablissement" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group" style="flex: 2;">
                                        <label>Adresse</label>
                                        <input type="text" name="adresse" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Code Postal</label>
                                        <input type="text" name="codePostal" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Ville</label>
                                        <input type="text" name="ville" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ordre de travail -->
                            <div class="form-section">
                                <div class="section-title">Ordre de travail donné par</div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nomDonneur" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Prénoms :</label>
                                        <input type="text" name="prenomsDonneur" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Fonction</label>
                                        <input type="text" name="fonctionDonneur" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Code Postal</label>
                                        <input type="text" name="codePostalDonneur" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Exécution par -->
                            <div class="form-section">
                                <div class="form-row" style="align-items: center;">
                                    <div style="font-weight: bold; margin-right: 20px;">Exécution par</div>
                                    <div class="checkbox-group" style="margin-right: 20px;">
                                        <input type="checkbox" id="serviceInterne" name="serviceInterne">
                                        <label for="serviceInterne">Service interne</label>
                                    </div>
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="entreprise" name="entreprise">
                                        <label for="entreprise">Entreprise</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group" style="flex: 2;">
                                        <label>Adresse</label>
                                        <input type="text" name="adresseExecution" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Code Postal</label>
                                        <input type="text" name="codePostalExecution" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Ville</label>
                                        <input type="text" name="villeExecution" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Second ordre de travail -->
                            <div class="form-section">
                                <div class="section-title">Ordre de travail donné par</div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nomDonneur2" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Prénoms :</label>
                                        <input type="text" name="prenomsDonneur2" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Fonction</label>
                                        <input type="text" name="fonctionDonneur2" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Code Postal</label>
                                        <input type="text" name="codePostalDonneur2" class="form-control">
                                    </div>
                                </div>
                                <div class="signature-line">
                                    <!-- <label>Signature :</label> -->
                                </div>
                            </div>
                            
                            <!-- Responsable des travaux -->
                            <div class="form-section">
                                <div class="section-title">Responsable des travaux</div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nomResponsable" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Prénoms :</label>
                                        <input type="text" name="prenomsResponsable" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Fonction</label>
                                        <input type="text" name="fonctionResponsable" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Code Postal</label>
                                        <input type="text" name="codePostalResponsable" class="form-control">
                                    </div>
                                </div>
                                <div class="signature-line">
                                    <!-- <label>Signature :</label> -->
                                </div>
                            </div>
                            
                            <!-- Personnel exécutant les travaux -->
                            <div class="form-section">
                                <div class="section-title">Personnel exécutant les travaux</div>
                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nomPersonnel<?php echo $i; ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Prénoms :</label>
                                        <input type="text" name="prenomsPersonnel<?php echo $i; ?>" class="form-control">
                                    </div>
                                    <div class="form-group" style="flex: 0.5; text-align: right;">
                                        <!-- <label>Signature :</label> -->
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                            
                            <!-- Information concernant le chantier -->
                            <div class="form-section">
                                <div style="font-weight: bold; margin-bottom: 15px;">Information concernant le chantier</div>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                    <!-- Durée de validité -->
                                    <div>
                                        <div class="section-title">Durée de validité de la présente autorisation</div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Du</label>
                                                <input type="date" name="dateDebut" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>au :</label>
                                                <input type="date" name="dateFin" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>de :</label>
                                                <input type="time" name="heureDebut1" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>à :</label>
                                                <input type="time" name="heureFin1" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>et de :</label>
                                                <input type="time" name="heureDebut2" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>à :</label>
                                                <input type="time" name="heureFin2" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Localisation des travaux -->
                                    <div>
                                        <div class="section-title">Localisation des travaux</div>
                                        <div class="form-group">
                                            <label>Bâtiment :</label>
                                            <input type="text" name="batiment" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Niveau :</label>
                                            <input type="text" name="niveau" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Locaux :</label>
                                            <input type="text" name="locaux" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                                    <!-- Nature des travaux -->
                                    <div>
                                        <div class="section-title">Nature des travaux</div>
                                        <div class="checkbox-container">
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="electrique" name="electrique">
                                                <label for="electrique">Électrique</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="meulage" name="meulage">
                                                <label for="meulage">Meulage</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="chalumeau" name="chalumeau">
                                                <label for="chalumeau">Chalumeau</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="soudage" name="soudage">
                                                <label for="soudage">Soudage</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="decoupage" name="decoupage">
                                                <label for="decoupage">Découpage</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="autres" name="autres">
                                                <label for="autres">Autres</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="lampeSouder" name="lampeSouder">
                                                <label for="lampeSouder">Lampe à souder</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-top: 10px;">
                                            <input type="text" name="autresTexte" class="form-control" placeholder="Précisez...">
                                        </div>
                                    </div>
                                    
                                    <!-- Risques particuliers -->
                                    <div>
                                        <div class="section-title">Risques particuliers</div>
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="stockage" name="stockage">
                                            <label for="stockage">Stockage matières premières (papier-bois-liquide)</label>
                                        </div>
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="canalisation" name="canalisation">
                                            <label for="canalisation">Canalisation de gaz</label>
                                        </div>
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="ventilation" name="ventilation">
                                            <label for="ventilation">Ventilation</label>
                                        </div>
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="autresRisques" name="autresRisques">
                                            <label for="autresRisques">Autres</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Particularités de ce chantier -->
                            <div class="form-section">
                                <div class="section-title">Particularités de ce chantier</div>
                                <textarea name="particularites" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="form-section">
                                <label for="autresRisques">Agents</label>
                                <select name="agen" id="agen" class="form-control" required>
                                    <option value="">Sélectionner</option>
                                    <?php foreach ($agents as $value) { ?>
                                        <option value="<?= $value["agent_nom"] ?>"><?= $value["agent_nom"] ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                            
                            <!-- Note de bas de page -->
                            <div class="footer-note">
                                <p>
                                    Le permis de feu est établi dans le but d'éviter un incendie ou une explosion suite à des travaux par points chauds.
                                    Il doit être établi chaque fois que l'utilisation d'un chalumeau, d'un poste à l'arc, d'un poste MIG-MAG ou TIG.
                                    Il est délivré par le chef d'entreprise ou son représentant expressément désigné par lui, chaque fois que des travaux de
                                    cet ordre doivent être effectués, soit par le personnel de l'entreprise, soit par celui d'une entreprise extérieure.
                                    Il est demandé à l'entreprise de prendre connaissance de prendre ses propres extincteurs.
                                    Ce permis ne doit pas être délivré pour des travaux effectués à des postes de travail permanent.
                                </p>
                            </div>
                            
                            <button type="submit" class="submit-btn">Soumettre le formulaire</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="./assets/libs/feather-icons/dist/feather.min.js"></script>
    <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script>
        feather.replace();  // Remplace les icônes <i data-feather="..."> par les SVG
        
        // Fonction pour basculer la sidebar sur mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.navbar-vertical');
            const overlay = document.getElementById('overlay');
            
            if (sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.style.display = 'none';
            } else {
                sidebar.classList.add('show');
                overlay.style.display = 'block';
            }
        }
        
        // Écouteur d'événement pour le bouton de bascule de la sidebar
        document.getElementById('nav-toggle').addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });
    </script>
    
    <!-- Theme JS -->
    <script src="./assets/js/theme.min.js"></script>
</body>
</html>
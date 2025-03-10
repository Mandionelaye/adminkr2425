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

// Initialiser les variables d'erreur

$message = "";



// Traitement du formulaire de création de planning
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $adresse = $_POST['adresse'];
    $ouverture = $_POST['ouverture'];
    $fermeture = $_POST['fermature'];


     // Valider les champs
     if (empty($name) || empty($phone) || empty($adresse) || empty($ouverture) || empty($fermeture) ) {
        $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
        $_SESSION['message_type'] = "error";
        header("Location: add-site.php");
        exit();
    }
     
    // enregistrer un site
    $stmt = $pdo->prepare("INSERT INTO bj_site (name, phone, adresse, ouverture, fermeture)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $adresse, $ouverture, $fermeture]);
        $site_id = $pdo->lastInsertId();


    $agent_id = $site_id;
    $dates = $_POST['dates']; // Tableau des jours sélectionnés
    $entreprise_id = $_SESSION['user_id'];

    // Valider les champs
    if (empty($agent_id) || empty($dates)) {
        $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
        $_SESSION['message_type'] = "error";
        header("Location: add-site.php");
        exit();
    }

    try {
        // Insérer l'entrée dans la table `planning`
        $stmt = $pdo->prepare("INSERT INTO planning (entreprise_id, utilisateur_id, date_creation)
                               VALUES (?, ?, NOW())");
        $stmt->execute([$entreprise_id, $agent_id]);
        $planning_id = $pdo->lastInsertId();

        // Traiter chaque jour sélectionné
        foreach ($dates as $date) {
    $debut = $_POST['debut'][$date];
    $fin = $_POST['fin'][$date];
    $pause = $_POST['pause'][$date] ?? "00:00"; // Par défaut 0 minute
    // $site = $_POST['site'][$date];
    
    // Vérifier si plusieurs fonctions sont sélectionnées et les concaténer
     $fonction = !empty($_POST['fonction'][$date]) ? implode(', ', $_POST['fonction'][$date]) : '';
    

    // Vérifier si les heures sont valides
    $debut_time = DateTime::createFromFormat('H:i', $debut);
    $fin_time = DateTime::createFromFormat('H:i', $fin);

    if ($debut_time === false || $fin_time === false) {
        $_SESSION['message'] = "Format d'heure invalide pour la date : $date. Début : $debut, Fin : $fin.";
        $_SESSION['message_type'] = "error";
        header("Location: add-site.php");
        exit();
    }

    // Calculer la durée totale en minutes
    $interval = $debut_time->diff($fin_time);
    $total_minutes = ($interval->h * 60 + $interval->i) - (int) $pause;

    if ($total_minutes < 0) {
        $_SESSION['message'] = "Les horaires ou la durée de pause sont incorrects pour la date : $date.";
        $_SESSION['message_type'] = "error";
        header("Location: add-site.php");
        exit();
    }

    $total_heures = sprintf('%02d:%02d', intdiv($total_minutes, 60), $total_minutes % 60);

    // Insérer dans la table `date_planning`
    $stmt_date = $pdo->prepare("INSERT INTO date_planning (planning_id, jour, debut, fin, pause, fonction, total)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_date->execute([$planning_id, $date, $debut, $fin, $pause, $fonction, $total_heures]);

     }

     $_SESSION['message'] = "Planning créé avec succès !";
     $_SESSION['message_type'] = "success";
     header("Location: add-site.php");
     exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Erreur lors de la création du planning : " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: add-site.php");
        exit();
    }
}

if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
}

?>





<!doctype html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width" />
    <meta name="description"
        content="Dashboard de gestion pour les entreprises, incluant la gestion des utilisateurs, des rondes, des statistiques et des abonnements." />
    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon/favicon.ico" />

    <!-- Libs CSS -->
    <!-- tailwindcss -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
    <link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="./assets/css/theme.min.css">


    <link rel="stylesheet" href="./assets/libs/apexcharts/dist/apexcharts.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Main Courantes</title>
</head>

<body class="bg-gray-100 py-6">
    <main>
        <!-- start the project -->
        <!-- app layout -->
        <div id="app-layout" class="overflow-x-hidden flex">
            <!-- start navbar -->
            <nav class="navbar-vertical navbar">
                <div id="myScrollableElement" class="h-screen" data-simplebar>
                    <!-- Logo de la marque -->
                    <a class="navbar-brand" href="index.php"
                        style="display: flex; justify-content: center; align-items: center; width: 100%;">
                        <img src="./assets/images/logo-2.png" alt="Logo de l'entreprise" style="max-height: 50px;" />
                    </a>

                    <!-- Menu de navigation -->
                    <ul class="navbar-nav flex-col" id="sideNavbar">
                        <!-- Tableau de bord -->
                        <li class="nav-item">
                            <a class="nav-link  active " href="index.php">
                                <i data-feather="home" class="w-4 h-4 mr-2"></i>
                                Tableau de Bord
                            </a>
                        </li>

                        <!-- Gestion des utilisateurs -->
                        <li class="nav-item">
                            <a class="nav-link  collapsed " href="#!" data-bs-toggle="collapse"
                                data-bs-target="#navAgents" aria-expanded="false" aria-controls="navAgents">
                                <i data-feather="users" class="w-4 h-4 mr-2"></i>
                                Employés
                            </a>
                            <div id="navAgents" class="collapse " data-bs-parent="#sideNavbar">
                                <ul class="nav flex-col">
                                    <li class="nav-item">
                                        <a class="nav-link " href="./add-employe.php">Ajouter un employé</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="./manage-employes.php">Gérer les employés</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Gestion des utilisateurs -->
                        <li class="nav-item">
                            <a class="nav-link  collapsed " href="#!" data-bs-toggle="collapse"
                                data-bs-target="#navAgentss" aria-expanded="false" aria-controls="navAgentss">
                                <i data-feather="flag" class="w-4 h-4 mr-2"></i>
                                Sites
                            </a>
                            <div id="navAgentss" class="collapse " data-bs-parent="#sideNavbar">
                                <ul class="nav flex-col">
                                    <li class="nav-item">
                                        <a class="nav-link " href="./add-site.php">Ajouter un site</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="./manage-site.php">Gérer les employés</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Planning -->
                        <li class="nav-item">
                            <a class="nav-link" href="./planning.php">
                                <i data-feather="calendar" class="w-4 h-4 mr-2"></i>
                                Planning
                            </a>
                        </li>

                        <!-- Gestion des mains courantes -->
                        <li class="nav-item">
                            <a class="nav-link " href="./main-courante.php">
                                <i data-feather="user-check" class="w-4 h-4 mr-2"></i>
                                Main Courantes
                            </a>
                        </li>

                        <!-- Gestion des abonnements -->
                        <li class="nav-item">
                            <a class="nav-link " href="./abonnement.php">
                                <i data-feather="credit-card" class="w-4 h-4 mr-2"></i>
                                Abonnement
                            </a>
                        </li>

                        <!-- Statistiques -->
                        <li class="nav-item">
                            <a class="nav-link " href="./stats.php">
                                <i data-feather="bar-chart" class="w-4 h-4 mr-2"></i>
                                Rapports
                            </a>
                        </li>

                        <!-- Notifications -->
                        <li class="nav-item">
                            <a class="nav-link " href="./notifications.php">
                                <i data-feather="bell" class="w-4 h-4 mr-2"></i>
                                Alertes
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <!--end of navbar-->

            <!-- Style CSS pour espacer les éléments du menu -->
            <style>
            .navbar-nav {
                gap: 1.2rem;
                /* Espacement global entre les éléments */
                padding-left: 0;
            }

            .nav-item {
                margin-bottom: 1.2rem;
                /* Marges verticales entre les éléments */
            }

            .nav-item a {
                display: flex;
                align-items: center;
                /* Aligner les icônes et le texte sur la même ligne */
                padding: 0.8rem 1.2rem;
                /* Un peu de padding pour rendre les éléments plus cliquables */
            }

            .nav-link {
                font-size: 1rem;
                color: white;
                /* Couleur du texte en blanc */
                transition: color 0.3s ease;
            }

            .navbar-vertical .navbar-nav .nav-item .nav-link {
                color: white !important;
            }

            .nav-link.active {
                color: #ffc107;
                /* Couleur des éléments actifs (jaune) */
            }

            .nav-link:hover {
                color: #d1d1d1;
                /* Couleur de survol des éléments (gris clair) */
            }

            .navbar-vertical {
                box-shadow: 2px 0px 6px rgba(0, 0, 0, 0.1);
                /* Ombre douce pour donner un effet de profondeur */
            }
            </style>


            <!-- app layout content -->
            <div id="app-layout-content"
                class="min-h-screen w-full min-w-[100vw] md:min-w-0 ml-[15.625rem] [transition:margin_0.25s_ease-out]">
                <!-- début de la barre de navigation -->
                <div class="header">
                    <!-- navbar -->
                    <nav class="bg-white px-6 py-[10px] flex items-center justify-between shadow-sm">
                        <a id="nav-toggle" href="#" class="text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </a>
                        <div class="ml-3 hidden md:hidden lg:block">
                            <!-- formulaire de recherche -->
                            <form class="flex items-center">
                                <input type="search"
                                    class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3 disabled:opacity-50 disabled:pointer-events-none"
                                    placeholder="Rechercher" />
                            </form>
                        </div>
                        <!-- navigation principale -->
                        <ul class="flex ml-auto items-center">
                            <li class="dropdown stopevent mr-2">
                                <a class="text-gray-600" href="#" role="button" id="dropdownNotification"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                                    </svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-lg lg:left-auto lg:right-0"
                                    aria-labelledby="dropdownNotification">
                                    <div>
                                        <div class="border-b px-3 pt-2 pb-3 flex justify-between items-center">
                                            <span class="text-lg text-gray-800 font-semibold">Notifications</span>
                                            <a href="#">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                        <!-- liste des notifications -->
                                        <ul class="h-56" data-simplebar="">
                                            <!-- élément de notification -->
                                            <li class="bg-gray-100 px-3 py-2 border-b">
                                                <a href="#">
                                                    <h5 class="mb-1">Rishi Chopra</h5>
                                                    <p class="mb-0">Mauris blandit erat id nunc blandit, ac eleifend
                                                        dolor pretium.</p>
                                                </a>
                                            </li>
                                            <li class="px-3 py-2 border-b">
                                                <a href="#">
                                                    <h5 class="mb-1">Neha Kannned</h5>
                                                    <p class="mb-0">Proin at elit vel est condimentum elementum id in
                                                        ante.</p>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="border-top px-3 py-2 text-center">
                                            <a href="#" class="text-gray-800 font-semibold">Voir toutes les
                                                notifications</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Liste des actions utilisateur -->
                            <li class="dropdown ml-2">
                                <a class="rounded-full" href="#" role="button" id="dropdownUser"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="w-10 h-10 relative">
                                        <img alt="avatar" src="./assets/images/avatar/avatar.jpg"
                                            class="rounded-full" />
                                        <div
                                            class="absolute border-gray-200 border-2 rounded-full right-0 bottom-0 bg-green-600 h-3 w-3">
                                        </div>
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
                    <h3 class="text-white mb-1 text-2xl font-normal">Ajouter un Site </h3>
                </div>
                <section class='p-4'>

                    <?php
$fonctions_agent = [
    "Agent de prévention et de sécurité",
    "Chef de poste",
    "Agent de sécurité mobile",
    "Agent de sécurité magasin prévention vol",
    "Agent de sécurité événementiel",
    "Convoyeur de fonds et valeurs",
    "Agent de sécurité incendie SSIAP 1",
    "Chef d'équipe de sécurité incendie SSIAP 2",
    "Chef de service SSIAP 3",
    "PAUSEURS (Activité : AGENT DE SURVEILLANCE)",
    "Formation",
    "Opérateur vidéo protection",
    "Opérateur de télésurveillance",
    "Agent de sécurité cynophile",
    "Agent de protection rapprochée",
    "Agent de sûreté aéroportuaire",
    "Rondier en sécurité"
];
?>

                    <?php if (!empty($message)): ?>
                    <div class="mb-4 p-4 text-sm text-blue-800 bg-blue-100 border border-blue-200 rounded">
                        <?php echo $message; ?>
                    </div>
                    <?php endif; ?>

                    <div class="card shadow">
                        <div class="card-body">
                            <form method="POST" class="space-y-6 space-x-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="prenom" class="block text-sm font-medium text-gray-700">Nom</label>
                                        <input type="text" name="name" id="prenom"
                                            class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="nom" class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="tel" name="phone" id="nom"
                                            class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="nom" class="block text-sm font-medium text-gray-700">Adresse du
                                            site</label>
                                        <input type="text" name="adresse" id="nom"
                                            class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="nom" class="block text-sm font-medium text-gray-700">Heure
                                            d'ouverture</label>
                                        <input type="time" name="ouverture" id="nom"
                                            class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="nom" class="block text-sm font-medium text-gray-700">Heure de
                                            Fermeture</label>
                                        <input type="time" name="fermature" id="nom"
                                            class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            required>
                                    </div>
                                </div>

                                <h4 class="mt-6 font-semibold text-lg">Planning du site</h4>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2">Sélectionner les jours</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        <?php $jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"]; ?>
                                        <?php foreach ($jours as $jour): ?>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="dates[]" value="<?php echo $jour; ?>"
                                                class="form-checkbox text-indigo-600" onchange="updateSchedule()">
                                            <span><?php echo $jour; ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div id="schedule-fields"></div>

                                <button type="submit"
                                    class="bg-indigo-600 text-white py-2 px-3 rounded-md">Créer</button>
                            </form>
                        </div>
                    </div>
                </section>



    </main>

    <style>
    /* CSS personnalisé pour masquer le titre sur mobile */
    #main-title {
        display: block;
        /* Affiche le titre par défaut */
    }

    /* Masquer le titre sur les petits écrans (mobile) */
    @media (max-width: 640px) {
        #main-title {
            display: none;
        }
    }

    /* CSS personnalisé pour les alertes */
    .alert {
        /* backgrond */
        padding: 1rem;
        border-radius: 0.375rem;
        /* rounded-lg */
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: fade-in 0.5s ease-in-out;
    }

    .alert-success {
        background-color: #D1F7E2;
        /* Vert clair */
        color: #006400;
        /* Vert foncé */
    }

    .alert-error {
        background-color: #FEE2E2;
        /* Rouge clair */
        color: #B91C1C;
        /* Rouge foncé */
    }

    .alert span {
        font-size: 1.25rem;
    }

    /* Animation pour fade-in */
    @keyframes fade-in {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    td {
        font-weight: bold !important;
        color: black;
    }
    </style>




    <script>
    function updateSchedule() {
        const selectedDays = Array.from(document.querySelectorAll('input[name="dates[]"]:checked')).map(el => el.value);
        const scheduleFieldsContainer = document.getElementById("schedule-fields");
        scheduleFieldsContainer.innerHTML = "";

        const fonctionsAgent = <?php echo json_encode($fonctions_agent); ?>;

        selectedDays.forEach(day => {
            const dayField = document.createElement("div");
            dayField.classList.add("mb-4", "p-4", "bg-gray-100", "rounded-md");

            let fonctionsCheckboxes = "";
            fonctionsAgent.forEach(fonction => {
                fonctionsCheckboxes += `
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="fonction[${day}][]" value="${fonction}" class="form-checkbox text-indigo-600">
                    <span>${fonction}</span>
                </label>
            `;
            });

            dayField.innerHTML = `
            <h3 class="text-lg font-semibold text-gray-700">${day}</h3>
            <label class="block text-gray-700 font-medium mb-2">Heure de début</label>
            <input type="time" name="debut[${day}]" class="w-full p-2 border border-gray-300 rounded" required>
            
            <label class="block text-gray-700 font-medium mb-2">Heure de fin</label>
            <input type="time" name="fin[${day}]" class="w-full p-2 border border-gray-300 rounded" required>
            
            <label class="block text-gray-700 font-medium mb-2">Pause</label>
            <input type="time" name="pause[${day}]" class="w-full p-2 border border-gray-300 rounded" required>
            
            <label class="block text-gray-700 font-medium mb-2">Fonction</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">${fonctionsCheckboxes}</div>
            
        `;

            scheduleFieldsContainer.appendChild(dayField);
        });
    }
    </script>


    <!-- Libs JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="./assets/libs/feather-icons/dist/feather.min.js"></script>
    <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script>
    feather.replace(); // Remplace les icônes <i data-feather="..."> par les SVG
    </script>

    <!-- Theme JS -->
    <script src="./assets/js/theme.min.js"></script>

</body>

</html>
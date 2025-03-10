<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
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

// Récupérer les informations de l'entreprise
$entreprise_id = $_SESSION['user_id']; 

// Utiliser PDO pour la requête
$query = "SELECT * FROM entreprises WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$entreprise_id]);
$entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'entreprise a un abonnement
$abonnement_id =isset($entreprise['abonnement_id'])? $entreprise['abonnement_id'] : "";
$has_abonnement = !empty($abonnement_id);

// Récupérer les abonnements disponibles
$query_abonnements = "SELECT * FROM abonnements";
$stmt_abonnements = $pdo->prepare($query_abonnements);
$stmt_abonnements->execute();
$abonnements = $stmt_abonnements->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si l'abonnement actuel est expiré ou si le rappel doit être fait
$today = new DateTime();
$date_expiration = isset($entreprise['date_expiration'])? new DateTime($entreprise['date_expiration']): $today;
$days_remaining = $today->diff($date_expiration)->days;
$is_expired = $date_expiration < $today;
$should_remind = !$is_expired && $days_remaining <= 10;
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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
    <link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">

    <!-- tailwindcss -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="./assets/css/theme.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <link rel="stylesheet" href="./assets/libs/apexcharts/dist/apexcharts.css" />
    <title>Abonnement</title>
</head>

<body>
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

                <style>
                body {
                    font-family: 'Poppins', sans-serif;
                }

                .pack-header {
                    background: linear-gradient(135deg, #4f46e5, #6d28d9);
                    color: white;
                    padding: 16px;
                    border-radius: 10px 10px 0 0;
                    text-align: center;
                    font-weight: bold;
                }

                .price-bar {
                    background: #f59e0b;
                    color: white;
                    padding: 10px;
                    border-radius: 0 0 10px 10px;
                    font-size: 1.25rem;
                    font-weight: bold;
                    text-align: center;
                }

                .feature-item {
                    display: flex;
                    align-items: center;
                    background: #f3f4f6;
                    padding: 10px 15px;
                    border-radius: 8px;
                    margin-bottom: 8px;
                    font-weight: 500;
                    transition: background-color 0.3s ease;
                }

                .feature-item:hover {
                    background-color: #e0e7ff;
                }

                .feature-item span {
                    margin-right: 8px;
                    font-size: 1.25rem;
                }

                .pack-card {
                    background: #ffffff;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .pack-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
                }

                .subscribe-button {
                    background-color: #4f46e5;
                    color: white;
                    font-weight: bold;
                    padding: 12px 24px;
                    border-radius: 8px;
                    margin-top: 20px;
                    transition: background-color 0.3s ease;
                }

                .subscribe-button:hover {
                    background-color: #4338ca;
                }

                h4 {
                    margin-bottom: 10px !important;
                    font-size: 25px !important;
                    font-weight: bold !important;
                    text-align: center;
                }


                /* Notification d'abonnement actif */
                .abonnement-actif {
                    background-color: #34d399;
                    /* Vert clair */
                    color: white;
                    padding: 16px;
                    margin-bottom: 24px;
                    border-radius: 12px;
                    font-weight: bold;
                    display: flex;
                    align-items: center;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                }

                .abonnement-actif .icon {
                    margin-right: 12px;
                    font-size: 24px;
                }

                .abonnement-actif .text {
                    font-size: 1rem;
                }

                .abonnement-actif .warning {
                    color: #fbbf24;
                    /* Jaune pour avertir */
                }

                .abonnement-actif .expired {
                    color: #f87171;
                    /* Rouge pour signaler l'expiration */
                }

                /* Notification sans abonnement */
                .sans-abonnement {
                    background-color: #fbbf24;
                    /* Jaune */
                    color: #000000;
                    padding: 16px;
                    margin-bottom: 24px;
                    border-radius: 12px;
                    font-weight: bold;
                    display: flex;
                    align-items: center;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                }

                .sans-abonnement .icon {
                    margin-right: 12px;
                    font-size: 24px;
                }

                .sans-abonnement .text {
                    font-size: 1rem;
                }

                /* Notification d'abonnement expiré */
                .abonnement-expire {
                    background-color: #f87171;
                    /* Rouge */
                    color: white;
                    padding: 16px;
                    margin-bottom: 24px;
                    border-radius: 12px;
                    font-weight: bold;
                    display: flex;
                    align-items: center;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                }


                .abonnement-expire .icon {
                    margin-right: 12px;
                    font-size: 24px;
                }

                .abonnement-expire .text {
                    font-size: 1rem;
                }

                /* Styles généraux */
                .text-lg {
                    font-size: 1.125rem;
                }

                .font-bold {
                    font-weight: 700;
                }

                .font-medium {
                    font-weight: 500;
                }

                .shadow-lg {
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
                }


                .transform:hover {
                    transform: scale(1.05);
                }
                </style>

                <div class="bg-indigo-600 px-8 pt-4 pb-4 flex justify-between items-center mb-3 rounded-t-lg">
                    <h3 class="text-white text-xl font-semibold">Abonnements</h3>
                </div>

                <!-- <hr class="border-t border-indigo-400 my-1"> -->

                <!-- <div class="max-w-4xl mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Gérer votre abonnement</h2>
                    <?php if ($has_abonnement): ?>
                    <div class="abonnement-actif">
                        <span class="icon">✅</span>
                        <div>
                            <h3>Abonnement actuel : <?php echo $entreprise['abonnement_id']; ?></h3>
                            <p>Actif jusqu'au <?php echo $date_expiration->format('d/m/Y'); ?>.</p>
                            <?php if ($should_remind): ?>
                            <p class="warning">⚠️ Expire dans <?php echo $days_remaining; ?> jours.</p>
                            <?php elseif ($is_expired): ?>
                            <p class="expired">❌ Votre abonnement a expiré.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="sans-abonnement">
                        <span class="icon">⚠️</span>
                        <p>Vous n'avez pas d'abonnement actif. Veuillez sélectionner un pack ci-dessous.</p>
                    </div>
                    <?php endif; ?>


                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <?php foreach ($abonnements as $abonnement): ?>
                        <div class="pack-card">
                            <div class="pack-header">
                                <h3 class="text-2xl mb-0"><?php echo htmlspecialchars($abonnement['nom']); ?></h3>
                            </div>
                            <div class="price-bar">
                                <?php echo number_format($abonnement['prix'], 0); ?> F CFA
                            </div>
                            <div class="p-6">
                                <div class="feature-item"><span>👥</span>Agents max:
                                    <?php echo $abonnement['max_agents']; ?></div>
                                <div class="feature-item"><span>🛠️</span>Contrôleurs max:
                                    <?php echo $abonnement['max_controleurs']; ?></div>
                                <div class="feature-item"><span>⏳</span>Durée: <?php echo $abonnement['duree']; ?> mois
                                </div>

                                <h4 class="font-medium mt-4 mb-2">🔹 Fonctionnalités :</h4>
                                <ul class="list-none text-left text-gray-700">
                                    <?php
                        $query_fonctionnalites = "SELECT * FROM abonnement_fonctionnalites WHERE abonnement_id = ?";
                        $stmt_fonctionnalites = $pdo->prepare($query_fonctionnalites);
                        $stmt_fonctionnalites->execute([$abonnement['id']]);
                        $fonctionnalites = $stmt_fonctionnalites->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($fonctionnalites as $fonctionnalite):
                        ?>
                                    <li class="feature-item"><span
                                            class="text-green-500 mr-2">✔️</span><?php echo htmlspecialchars($fonctionnalite['fonctionnalite']); ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>

                                <?php if (!$has_abonnement || $abonnement['id'] != $entreprise['abonnement_id']): ?>
                                <form action="#" method="POST">
                                    <input type="hidden" name="abonnement_id" value="<?php echo $abonnement['id']; ?>">
                                    <button type="submit" class="subscribe-button">
                                        S'abonner
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div> -->




                <div class="mx-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="bg-white flex flex-col items-center">
                            <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-4">
                                Gérer votre abonnement
                                </h2>

                              <?php if ($has_abonnement): ?>
                                 <div class="abonnement-actif">
                                       <span class="icon">✅</span>
                                       <div>
                                          <h3>Abonnement actuel : <?php echo $entreprise['abonnement_id']; ?></h3>
                                          <p>Actif jusqu'au <?php echo $date_expiration->format('d/m/Y'); ?>.</p>
                                          <?php if ($should_remind): ?>
                                          <p class="warning">⚠️ Expire dans <?php echo $days_remaining; ?> jours.</p>
                                          <?php elseif ($is_expired): ?>
                                          <p class="expired">❌ Votre abonnement a expiré.</p>
                                          <?php endif; ?>
                                       </div>
                                 </div>
                                 <?php else: ?>
                                 <div class="sans-abonnement">
                                       <span class="icon">⚠️</span>
                                       <p>Vous n'avez pas d'abonnement actif. Veuillez sélectionner un pack ci-dessous.</p>
                                 </div>
                              <?php endif; ?>
                             
                                <div class="grid auto-cols-fr grid-cols-1 md:grid-cols-2 lg:grid-cols-4 w-full max-w-7xl">
                                <?php foreach ($abonnements as $abonnement): 
                                  $prix = number_format($abonnement['prix'], 0);
                                  $parts = explode(',', $prix);
                                 ?>
                                    <div class="max-h-96">
                                        <div class="bg-red-600 text-white w-full h-28 border border-gray-100 ">
                                            <div
                                                class="flex items-center justify-center w-full px-6 pt-5 pb-2 text-gray-900 dark:text-white">
                                                <span class="text-5xl font-extrabold tracking-tight"> <?php echo  $parts[0]; ?></span>
                                                <span class="ms-1 text-xl font-normal">
                                                    <span class="block">,<?php echo  $parts[1]; ?> F</span>
                                                    <span class="block">/<?php echo $abonnement['duree']; ?> mois</span>
                                                </span>
                                            </div>
                                            <p class="text-xl mb-2 font-bold text-center"><?php echo htmlspecialchars($abonnement['nom']); ?></p>
                                        </div>
                               
                                        <ul role="list" class="space-y-3 my-4 p-4 border-b-2 border-gray-100">
                                            <li class="flex items-center">
                                                <span class="text-black text-base" ><i class="bi bi-person-lines-fill"></i></span>
                                                <span
                                                    class="text-base font-normal leading-tight text-gray-500 ms-3">Agents max:
                                                    <?php echo $abonnement['max_agents']; ?></span>
                                            </li>
                                            <li class="flex items-center">
                                                <span class="text-black text-base" ><i class="bi bi-person-fill-gear"></i></span>
                                                <span
                                                    class="text-base font-normal leading-tight text-gray-500 ms-3">Contrôleurs max:
                                                    <?php echo $abonnement['max_controleurs']; ?></span>
                                            </li>
                                          </ul>

                                       <div class="p-4 ">
                                             <div class="relative inline-block text-left w-full">
                                                <button type="button" class="text-white bg-indigo-700 mb-3 rounded-lg px-5 py-2.5 inline-flex justify-center w-full text-center" data-modal-target="default-modal<?= $abonnement['id'] ?>" data-modal-toggle="default-modal<?= $abonnement['id'] ?>">
                                                Fonctionnalités
                                                </button>


                                                <!-- Main modal -->
                                                <div id="default-modal<?= $abonnement['id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                                                        <!-- Modal content -->
                                                        <div class="relative bg-white rounded-lg shadow-sm">
                                                            <!-- Modal header -->
                                                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                                                                <h3 class="text-xl font-semibold text-gray-900 ">
                                                                    Abonnement de <?= number_format($abonnement['prix'], 0); ?>
                                                                </h3>
                                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="default-modal">
                                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                    </svg>
                                                                    <span class="sr-only">Fermer</span>
                                                                </button>
                                                            </div>
                                                            <!-- Modal body -->
                                                            <div class="p-4 md:p-5 space-y-4">
                                                            <p class="text-base leading-relaxed text-gray-500">Fonctionnalités</p>
                                                            <ul role="list" class="space-y-3 p-4 w-full">
                                                                <?php
                                                                    $query_fonctionnalites = "SELECT * FROM abonnement_fonctionnalites WHERE abonnement_id = ?";
                                                                    $stmt_fonctionnalites = $pdo->prepare($query_fonctionnalites);
                                                                    $stmt_fonctionnalites->execute([$abonnement['id']]);
                                                                    $fonctionnalites = $stmt_fonctionnalites->fetchAll(PDO::FETCH_ASSOC);
                                                                    foreach ($fonctionnalites as $fonctionnalite):
                                                                ?>
                                                                <li class="flex items-center">
                                                                    <svg class="shrink-0 w-4 h-4 text-blue-700 dark:text-blue-500"
                                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                                                    </svg>
                                                                    <span
                                                                        class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3"><?php echo htmlspecialchars($fonctionnalite['fonctionnalite']); ?></span>
                                                                </li>

                                                                <?php endforeach; ?>
                                                            </ul>
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b ">
                                                                <button data-modal-hide="default-modal<?= $abonnement['id'] ?>" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center :bg-blue-600">Fermer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>

                                             <?php if (!$has_abonnement || $abonnement['id'] != $entreprise['abonnement_id']): ?>
                                       <form action="#" method="POST">
                                             <input type="hidden" name="abonnement_id" value="<?php echo $abonnement['id']; ?>">
                                             <button type="submit" class="text-white bg-indigo-700 mb-3 rounded-lg px-5 py-2.5 inline-flex justify-center w-full text-center">
                                                S'abonner
                                             </button>
                                       </form>
                                    </div>


                                <?php endif; ?>
                                        
                                    </div>


                               <?php endforeach; ?>

                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>

            </div>


        </div>

        </div>
    </main>

    <script>
    const button = document.getElementById('menu-button');
    const dropdown = document.getElementById('dropdown-menu');
    <?php foreach ($abonnements as $abonnement): ?>
      document.getElementById('menu-button<?= $abonnement['id'] ?>').addEventListener('click', () => {
         document.getElementById('dropdown-menu<?= $abonnement['id'] ?>').classList.toggle('hidden');
    });
    <?php endforeach; ?>
</script>

    <script>
        <?php foreach ($abonnements as $abonnement): ?>
         document.getElementById("dropdownButton<?= $abonnement['id'] ?>").addEventListener("click", () => {
            document.getElementById("dropdownMenu<?= $abonnement['id'] ?>").classList.toggle("hidden"); // Affiche ou cache la liste
        });

        <?php endforeach; ?>

        // Fermer si on clique en dehors
        document.addEventListener("click", (event) => {
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add("hidden");
            }
        });
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
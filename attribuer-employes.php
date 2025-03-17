<?php
session_start();
require_once 'config.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'entreprise') {
    header("Location: login.php");
    exit();
}

// Récupération de l'id_site depuis l'URL
$id_site = isset($_GET['id_site']) ? intval($_GET['id_site']) : null;

// Liste des agents disponibles
$agents = $pdo->query("SELECT id, prenom, nom, telephone FROM utilisateurs WHERE role = 'agent'")->fetchAll();

// Gestion de l'ajout d'une attribution
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_agent'])) {
    $id_agent = $_POST['id_agent'];

    // Vérifier si l'attribution existe déjà
    $check = $pdo->prepare("SELECT * FROM attribution WHERE id_agent = ? AND id_site = ?");
    $check->execute([$id_agent, $id_site]);

    if ($check->rowCount() == 0) {
        // Insérer l'attribution
        $stmt = $pdo->prepare("INSERT INTO attribution (id_agent, id_site) VALUES (?, ?)");
        $stmt->execute([$id_agent, $id_site]);

        // Redirection avec message de succès
        header("Location: attribution.php?id_site=$id_site&success=1");
        exit();
    } else {
        // Redirection avec message d'erreur (agent déjà attribué)
        header("Location: attribution.php?id_site=$id_site&error=duplicate");
        exit();
    }
}
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
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

         <!-- tailwindcss -->
         <script src="https://cdn.tailwindcss.com"></script>
         <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

      <!-- Theme CSS -->
      <link rel="stylesheet" href="./assets/css/theme.min.css">

 
      <link rel="stylesheet" href="./assets/libs/apexcharts/dist/apexcharts.css" />
      <title>ajouter attributions</title>
   </head>
<body>
    <main>
         <!-- start the project -->
         <!-- app layout -->
            <div id="app-layout" class="overflow-x-hidden flex">
                    <!-- start navbar -->
                    <?php require_once 'layout/sidebar.php'; ?>
                        <!--end of navbar-->

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

                                                    .profilImg{
                                                        width: 35px;
                                                        height: 35px;
                                                        border-radius: 50%;
                                                        overflow: hidden;
                                                        object-fit: cover;
                                                    }
                                                </style>
                                                        <!-- app layout content -->
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
                                                            <h5 class="mb-1">Rishi Chopra</h5>
                                                            <p class="mb-0">Mauris blandit erat id nunc blandit, ac eleifend dolor pretium.</p>
                                                            </a>
                                                        </li>
                                                        <li class="px-3 py-2 border-b">
                                                            <a href="#">
                                                            <h5 class="mb-1">Neha Kannned</h5>
                                                            <p class="mb-0">Proin at elit vel est condimentum elementum id in ante.</p>
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
                            <h3 class="text-white mb-1 font-normal">Attribuer un agent</h3>
                            </div>
                        
                            <div class="card shadow-lg">
                              <div class="card-body">
                                         <h5 class="text-2xl font-semibold text-gray-800 mb-6">Liste des Agents</h5>
                                        <!-- Affichage des messages de confirmation ou d'erreur -->
                                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                                            <div class="bg-green-500 text-white p-3 rounded mb-4">
                                                L'attribution a été effectuée avec succès !
                                            </div>
                                        <?php elseif (isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
                                            <div class="bg-yellow-500 text-white p-3 rounded mb-4">
                                                Cet agent est déjà attribué à ce site.
                                            </div>
                                        <?php endif; ?>

                                        <div class="overflow-x-auto w-full">
                                                <table class="min-w-full table-auto border-separate border-spacing-0 rounded-lg overflow-hidden">
                                                    <thead>
                                                        <tr class="bg-indigo-600 text-white">
                                                            <td class="px-2 py-2">Prenom</td>
                                                            <td class="px-2 py-2">Nom</td>
                                                            <td class="px-2 py-2">Telephone</td>
                                                            <td class="px-2 py-2">Actions</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    
                                                        <?php if (count($agents) > 0): ?>
                                                            <?php foreach ($agents as $agent): ?>
                                                                <tr class="hover:bg-gray-100 transition duration-200">
                                                                    <td class="px-2 py-4 text-gray-800"><?= htmlspecialchars($agent['prenom']); ?></td>
                                                                    <td class="px-2 py-4 text-gray-800"><?= htmlspecialchars($agent['nom']); ?></td>
                                                                    <td class="px-2 py-4 text-gray-800"><?= htmlspecialchars($agent['telephone']); ?></td>
                                                                    <td class="px-2 py-4 text-gray-800">
                                                                        <form action="" method="POST" >
                                                                            <input type="hidden" name="id_agent" value="<?= $agent['id']; ?>">
                                                                            <button type="submit" 
                                                                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                                                Attribuer
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="3" class="px-4 py-2 text-center text-gray-600">Aucun agent disponible.</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    
                                                    </tbody>
                                            </table>
                                        </div>
                                </div>        
                            </div>
                    </div>
            </div>
    </main>

</body>
</html>

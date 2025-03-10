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

// Récupérer les messages de la session
$message = "";
$message_type = "";
if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message'], $_SESSION['message_type']);
}

// Requête pour récupérer les plannings avec leurs détails
$stmt = $pdo->prepare("
    SELECT p.id AS planning_id, p.date_creation, u.nom, u.prenom, 
           dp.jour, dp.debut, dp.fin, dp.pause, dp.site, dp.fonction
    FROM planning p
    JOIN utilisateurs u ON p.utilisateur_id = u.id
    JOIN date_planning dp ON dp.planning_id = p.id
    WHERE p.entreprise_id = ?
    ORDER BY p.date_creation DESC
");
$stmt->execute([$_SESSION['user_id']]);
$plannings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
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
<link rel="stylesheet" href="./assets/css/theme.min.css">

 
      <link rel="stylesheet" href="./assets/libs/apexcharts/dist/apexcharts.css" />
      <title>Liste des Plannings</title>
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
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
    }
    .btn-create:hover {
        background-color: #0056b3;
    }
    .container{
        padding: 10px;
    }

    /* Style pour les boutons d'action */
    table th {
        background-color: #007bff; /* Bleu */
        color: white;
        text-align: left;
        padding: 8px;
    }
    .btn-modifier {
        background-color: #007bff; /* Bleu */
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-modifier:hover {
        background-color: #0056b3; /* Bleu foncé au survol */
    }
</style>
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
     <a class="navbar-brand" href="index.php" style="display: flex; justify-content: center; align-items: center; width: 100%;">
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
            <a class="nav-link  collapsed " href="#!" data-bs-toggle="collapse" data-bs-target="#navAgents" aria-expanded="false" aria-controls="navAgents">
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
    background-color: #007BFF;
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
    background-color: #28a745;
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
    <h3 class="text-white mb-1 font-normal">Planification des agents</h3>
</div>

<!-- Barre sous le texte -->
<hr class="border-t border-indigo-400 my-1">

<!-- Contenu principal -->
<div class="container">
    <!-- Titre et bouton alignés à droite -->
    <div class="header-container">
        <h3 class="mb-0">Liste des Plannings</h3>
        <a href="creat-planning.php" class="btn-create">Créer un planning</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= htmlspecialchars($message_type) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Conteneur scrollable -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nom Agent</th>
                    <th>Jour</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Pause</th>
                    <th>Site</th>
                    <th>Fonction</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($plannings)): ?>
                    <tr>
                        <td colspan="8">Aucun planning trouvé.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($plannings as $planning): ?>
                        <tr>
                            <td><?= htmlspecialchars($planning['prenom'] . ' ' . $planning['nom']) ?></td>
                            <td><?= htmlspecialchars($planning['jour']) ?></td>
                            <td><?= htmlspecialchars($planning['debut']) ?></td>
                            <td><?= htmlspecialchars($planning['fin']) ?></td>
                            <td><?= htmlspecialchars($planning['pause']) ?></td>
                            <td><?= htmlspecialchars($planning['site']) ?></td>
                            <td><?= htmlspecialchars($planning['fonction']) ?></td>
                            <td>
                                <a href="modifier_planning.php?id=<?= $planning['planning_id'] ?>" class="btn-modifier">Modifier</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


</div>
</main>

      <!-- Libs JS -->
<script src="./assets/libs/feather-icons/dist/feather.min.js"></script>
<script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./assets/libs/simplebar/dist/simplebar.min.js"></script>
<script>
    feather.replace();  // Remplace les icônes <i data-feather="..."> par les SVG
 </script>
 
<!-- Theme JS -->
<script src="./assets/js/theme.min.js"></script>

   </body>
</html>
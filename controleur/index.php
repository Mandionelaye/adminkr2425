<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    // Si l'utilisateur n'est pas authentifié, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

// Vérifier si l'utilisateur a le rôle 'entreprise'
if ($_SESSION['user_role'] !== 'controleur') {
    header("Location: index.php");
    exit();
}

// Inclure la configuration de la base de données
require_once 'config.php';

// Récupérer les employés de la base de données (seulement agents et contrôleurs)
$entreprise_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE entreprise_id = ? AND role IN ('agent', 'controleur')");
$stmt->execute([$entreprise_id]);
$employes = $stmt->fetchAll();
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
      <title>Gérer les employés</title>
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

         <!-- Gestion des contrôleurs -->
         <li class="nav-item">
            <a class="nav-link  collapsed " href="#!" data-bs-toggle="collapse" data-bs-target="#navControleurs" aria-expanded="false" aria-controls="navControleurs">
               <i data-feather="shield" class="w-4 h-4 mr-2"></i>
               Contrôleurs
            </a>
            <div id="navControleurs" class="collapse " data-bs-parent="#sideNavbar">
               <ul class="nav flex-col">
                  <li class="nav-item">
                     <a class="nav-link " href="./round-check.php">Vérification des Rondes</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link " href="./evaluate-agents.php">Évaluation des Agents</a>
                  </li>
               </ul>
            </div>
         </li>
         
          <!-- Gestion des mains courantes -->
         <li class="nav-item">
            <a class="nav-link " href="./main_courante.php">
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
                     <a class="dropdown-item" href="index.php">
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
                <h3 class="text-white mb-1 font-normal">Gérer les employés</h3>
            </div>
            
            <!-- Barre sous le texte -->
            <hr class="border-t border-indigo-400 my-1">
            
             <div class="card shadow-lg">
            <div class="card-body">
                <h5 class="text-2xl font-semibold text-gray-800 mb-6">Liste des Employés</h5>
                
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full table-auto border-separate border-spacing-0 rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-indigo-600 text-white">
                                <th class="px-6 py-3 text-left">Prénom</th>
                                <th class="px-6 py-3 text-left">Nom</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Téléphone</th>
                                <th class="px-6 py-3 text-left hidden md:table-cell">Rôle</th> <!-- Cacher sur mobile -->
                                <th class="px-6 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employes as $employe): ?>
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($employe['prenom']); ?></td>
                                    <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($employe['nom']); ?></td>
                                    <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($employe['email']); ?></td>
                                    <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($employe['telephone']); ?></td>
                                    <td class="px-6 py-4 text-gray-800 hidden md:table-cell"><?php echo ucfirst(htmlspecialchars($employe['role'])); ?></td> <!-- Cacher sur mobile -->
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-4 justify-start">
                                            <!-- Bouton Modifier avec espacement à droite -->
                                            <a href="modifier-employe.php?id=<?php echo $employe['id']; ?>" class="text-blue-600 hover:text-blue-800 font-semibold transition duration-200 mr-2">Modifier</a>
                                            
                                            <!-- Bouton Supprimer avec confirmation -->
                                            <a href="supprimer-employe.php?id=<?php echo $employe['id']; ?>" 
                                               class="text-red-600 hover:text-red-800 font-semibold transition duration-200" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">Supprimer</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

         </div>
      </main>

      <script src="./assets/libs/apexcharts/dist/apexcharts.min.js"></script>
      <script>
         var options = {
            series: [45, 55], // Pourcentage des incidents résolus vs non résolus
            chart: {
               type: 'pie',
               height: 400,
               animations: {
                  enabled: true,
                  easing: 'easeinout',
                  speed: 800
               }
            },
            labels: ['Incidents Résolus', 'Incidents Non Résolus'],
            colors: ['#4caf50', '#ff5722'],
            dataLabels: {
               enabled: true,
               style: {
                  colors: ['#fff']
               },
               dropShadow: {
                  enabled: true,
                  top: 1,
                  left: 1,
                  blur: 2,
                  opacity: 0.5
               }
            },
            title: {
               text: 'Répartition des Incidents',
               align: 'center',
               style: {
                  fontSize: '18px',
                  color: '#333'
               }
            },
            legend: {
               position: 'bottom',
               horizontalAlign: 'center'
            }
         };
      
         var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
         chart.render();
      </script>
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

<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    // Si l'utilisateur n'est pas authentifié, rediriger vers la page de connexion
    header("Location: ../login.php");
    exit();
}

// Vérifier si l'utilisateur a le rôle 'entreprise'
if ($_SESSION['user_role'] !== 'agent') {
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
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
      <title>Tableau de Bord - Agent</title>
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
            <a class="nav-link active" href="index.php">
               <i data-feather="home" class="w-4 h-4 mr-2"></i>
               Tableau de Bord
            </a>
         </li>
         
          <!-- Signature -->
         <li class="nav-item">
            <a class="nav-link" href="./signature.php">
               <i data-feather="edit-2" class="w-4 h-4 mr-2"></i>
               Signature
            </a>
         </li>

         <!-- Gestion des MAIN COURANTES -->
         <li class="nav-item">
            <a class="nav-link collapsed" href="#!" data-bs-toggle="collapse" data-bs-target="#navAgents" aria-expanded="false" aria-controls="navAgents">
               <i data-feather="clipboard" class="w-4 h-4 mr-2"></i> <!-- Icône mise à jour -->
               Main Courante
            </a>
            <div id="navAgents" class="collapse" data-bs-parent="#sideNavbar">
               <ul class="nav flex-col">
                  <li class="nav-item">
                     <a class="nav-link" href="./creat-main-courante.php">Créer Main Courante</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="./main-courante.php">Mes Main Courantes</a>
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

         <!-- Gestion des abonnements -->
         <li class="nav-item">
            <a class="nav-link" href="./suivi-qualite.php">
               <i data-feather="check-circle" class="w-4 h-4 mr-2"></i> <!-- Icône mise à jour -->
               Suivi
            </a>
         </li>
         
         
         <!-- CGV -->
         <li class="nav-item">
            <a class="nav-link" href="./cgv.php">
               <i data-feather="shield" class="w-4 h-4 mr-2"></i> <!-- Icône différente pour CGV -->
               CGV
            </a>
         </li>

         <!-- CGU -->
         <li class="nav-item">
            <a class="nav-link" href="./cgu.php">
               <i data-feather="file-text" class="w-4 h-4 mr-2"></i> <!-- Icône différente pour CGU -->
               CGU
            </a>
         </li>

      </ul>
   </div>
</nav>
<!-- end of navbar -->

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
   
   /* Style de l'icône de scan */
    ul .mr-4 a {
       display: flex;
       align-items: center;
       justify-content: center;
       padding: 0.5rem;
       background-color: grey;
       border-radius: 50%;
       transition: background-color 0.3s ease, transform 0.3s ease;
    }
    
    ul .mr-4 a:hover {
       background-color: black;
       transform: scale(1.1);
    }
    
    /* Style for the circular QR icon container */
.qr-icon-container {
    display: inline-block;
    width: 50px; /* You can adjust the size */
    height: 50px; /* You can adjust the size */
    border-radius: 50%; /* Makes the container circular */
    background-color: #4CAF50; /* Background color (green, can be changed) */
    color: white; /* Icon color */
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

/* Hover effect for the icon */
.qr-icon-container:hover {
    background-color: #45a049; /* Darker shade on hover */
}

.qr-icon-container i {
    font-size: 24px; /* Size of the icon */
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
      <!-- navigation principale -->
      <ul class="flex ml-auto items-center">
          <!-- Icône de scan -->
       <li class="mr-4">
          <!-- QR Code Scan Icon -->
       <!-- QR Code Scan Icon in a Circle with Background -->
        <a href="scan.php" class="qr-icon-container">
            <i class="fas fa-qrcode"></i>
        </a>
       </li>
       <!-- Icône de notifications -->
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
                    <a class="dropdown-item" href="../logout.php">
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

               <div class="bg-indigo-600 px-8 pt-10 lg:pt-14 pb-16 flex justify-between items-center mb-3">
               <!-- title -->
               <h3 class="text-white mb-2">Bienvenue ! Vous êtes connecté en tant que <?php echo htmlspecialchars($_SESSION['user_role']); ?>.</h3>
            </div>
            
            <div class="-mt-12 mx-6 mb-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2 xl:grid-cols-4">
               <!-- Card de Signature -->
               <a href="./signature.php" class="card shadow w-full p-4">
                  <div class="card-body">
                     <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold">Signature</h4>
                        <div class="bg-indigo-600 bg-opacity-10 rounded-md w-10 h-10 flex items-center justify-center text-center text-indigo-600">
                           <i data-feather="edit-2"></i> <!-- Icône pour Signature -->
                        </div>
                     </div>
                     <p class="mt-2 text-sm text-gray-600">Accédez à la page pour signer des documents en ligne facilement.</p>
                  </div>
               </a>
            
               <!-- Card de Main Courante -->
               <a href="./main-courante.php" class="card shadow w-full p-4">
                  <div class="card-body">
                     <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold">Main Courante</h4>
                        <div class="bg-indigo-600 bg-opacity-10 rounded-md w-10 h-10 flex items-center justify-center text-center text-indigo-600">
                           <i data-feather="clipboard"></i> <!-- Icône pour Main Courante -->
                        </div>
                     </div>
                     <p class="mt-2 text-sm text-gray-600">Consultez et gérez les principales actions et événements enregistrés.</p>
                  </div>
               </a>
               
                <!-- Card de Planning -->
               <a href="./planning.php" class="card shadow w-full p-4">
                  <div class="card-body">
                     <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold">Planning</h4>
                        <div class="bg-indigo-600 bg-opacity-10 rounded-md w-10 h-10 flex items-center justify-center text-center text-indigo-600">
                           <i data-feather="calendar"></i> <!-- Icône pour Planning -->
                        </div>
                     </div>
                     <p class="mt-2 text-sm text-gray-600">Consultez et gérez vos horaires et vos rendez-vous programmés.</p>
                  </div>
               </a>
            
               <!-- Card de Suivi de la qualité des prestations -->
               <a href="./suivi-qualite.php" class="card shadow w-full p-4">
                  <div class="card-body">
                     <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold">Suivi de la qualité des prestations</h4>
                        <div class="bg-indigo-600 bg-opacity-10 rounded-md w-10 h-10 flex items-center justify-center text-center text-indigo-600">
                           <i data-feather="check-circle"></i> <!-- Icône pour Suivi de qualité -->
                        </div>
                     </div>
                     <p class="mt-2 text-sm text-gray-600">Suivez la qualité des prestations et des services fournis.</p>
                  </div>
               </a>
            
               <!-- Card de CGU -->
               <a href="./cgu.php" class="card shadow w-full p-4">
                  <div class="card-body">
                     <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold">CGU</h4>
                        <div class="bg-indigo-600 bg-opacity-10 rounded-md w-10 h-10 flex items-center justify-center text-center text-indigo-600">
                           <i data-feather="shield"></i> <!-- Icône pour CGU -->
                        </div>
                     </div>
                     <p class="mt-2 text-sm text-gray-600">Lisez les conditions d'utilisation de nos services.</p>
                  </div>
               </a>
            
               <!-- Card de CGV -->
               <a href="./cgv.php" class="card shadow w-full p-4">
                  <div class="card-body">
                     <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold">CGV</h4>
                        <div class="bg-indigo-600 bg-opacity-10 rounded-md w-10 h-10 flex items-center justify-center text-center text-indigo-600">
                           <i data-feather="file-text"></i> <!-- Icône pour CGV -->
                        </div>
                     </div>
                     <p class="mt-2 text-sm text-gray-600">Découvrez nos conditions générales de vente pour mieux comprendre nos services.</p>
                  </div>
               </a>
            
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
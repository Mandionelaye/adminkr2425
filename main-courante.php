<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: ./login.php");
    exit();
}

// Vérifier si l'utilisateur a le rôle 'entreprise'
if ($_SESSION['user_role'] !== 'entreprise') {
    header("Location: entreprise");
    exit();
}

// Inclure les fichiers de configuration et d’initialisation de la base de données
require_once 'config.php'; // Assurez-vous que ce fichier contient la connexion à la base de données

// Récupérer les main courantes de l'agent
try {
    // Utilisation de utilisateur_id au lieu de agent_id et ajout de l'ordre par date de création décroissante
        $query = "SELECT mc.*, 
                     c.nom AS categorie_name, 
                     sc.nom AS sous_categorie_name, 
                     sc.couleur AS sous_categorie_couleur, 
                     u.nom AS agent_nom, 
                     u.prenom AS agent_prenom 
              FROM main_courante mc
              JOIN categories c ON mc.categorie_id = c.id
              JOIN sous_categories sc ON mc.sous_categorie_id = sc.id
              JOIN utilisateurs u ON mc.utilisateur_id = u.id
              WHERE u.entreprise_id = :entreprise_id
              ORDER BY mc.date_creation DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['entreprise_id' => $_SESSION['user_id']]);
    $main_courantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des main courantes : " . $e->getMessage();
}

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
<!-- tailwindcss -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
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
</style>
<style>
        .main-photo {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #45a049;
        }
        .btn-back {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #e53935;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .details p {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }
        .details strong {
            font-weight: bold;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .main-photo-container {
            text-align: center;
        }
        .file-link {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .file-link i {
            margin-right: 8px;
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
    <h3 class="text-white mb-1 text-2xl font-normal">Mes Main Courantes</h3>
</div>

<!-- Barre sous le texte -->
<hr class=" my-1">

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <span class="text-xl">✖</span>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<?php

// Vérifier si un message de succès existe dans la session
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">
            <span class="text-xl">✓</span>
            <span>' . htmlspecialchars($_SESSION['success_message']) . '</span>
          </div>';

    // Supprimer le message de la session après l'avoir affiché
    unset($_SESSION['success_message']);
}
?>


<!-- Contenu principal -->
<div id="app-layout-content" class="min-h-screen w-full bg-gray-50">
    <div class="container mx-auto p-6">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
          <h3 id="main-title" class="text-3xl font-semibold text-gray-900 tracking-tight">Mes Main Courantes</h3>
        </div>

        <?php if (!empty($main_courantes)): ?>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="bg-blue-600 text-white text-xs uppercase ">
            <tr>
                <th class="px-6 py-3">Date</th>
                <th class="px-6 py-3">Nom</th>
                <!-- <th class="border border-gray-300 px-4 py-2">Catégorie</th> -->
                <th class="px-6 py-3">Sous-catégorie</th>
                <th class="px-6 py-3">Commentaire</th>
                <th class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($main_courantes as $mc): ?>
    <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50 border-b border-gray-200">
        <td class="px-4 py-2"><?= (new DateTime($mc['date_creation']))->format('d-m-Y H:i') ?></td>
        <td class="px-6 py-4 bg-blue-100"><?= htmlspecialchars($mc['agent_prenom'] . ' ' . $mc['agent_nom']) ?></td>
        <!-- <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($mc['categorie_name']) ?></td> -->
        <td class="px-6 py-4" >
   
    <span style="background-color: <?= htmlspecialchars($mc['sous_categorie_couleur']) ?>;" class="<?= htmlspecialchars($mc['sous_categorie_couleur'])!= '#FFFF00'?'text-white':'text-black' ?> text-1xl font-medium me-2 px-2.5 py-2.5 rounded-lg"
    > <?= htmlspecialchars($mc['sous_categorie_name']) ?></span>
</td>
        <td class="px-6 py-4"><?= htmlspecialchars($mc['commentaire']) ?></td>
        <td class="px-6 py-4">
            <button type="button"
             data-modal-target="static-modal<?= $mc['id'] ?>"
             data-modal-toggle="static-modal<?= $mc['id'] ?>"
               class="inline-flex items-center gap-1 max-w-max px-2 py-1 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition duration-300">
                <i data-feather="eye"></i> Voir plus
            </button>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>

    </table>

        <style>
         .elmDiv{
            width: 100% !important;
         }
        </style>


<?php foreach ($main_courantes as $mc): ?>
<!-- Main modal -->
<div id="static-modal<?= $mc['id'] ?>" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full ">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm elmDiv" >
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 ">
                Détails de la Main Courante
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal<?= $mc['id'] ?>">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Fermé</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4 w-full">
            <div class="flex flex-col items-center justify-between w-full">

               <!-- Colonne de gauche : Informations principales -->
               <div class="card mb-4 w-full">
                  <div class="card-header">
                     <h2 class="text-xl font-semibold">Informations</h2>
                  </div>
                  <div class="details space-y-4">
                     <p><strong>Date :</strong> <?= (new DateTime($mc['date_creation']))->format('d/m/Y H:i') ?></p>
                     <p><strong>Agent :</strong> <?= htmlspecialchars($mc['agent_prenom'] . ' ' . $mc['agent_nom']) ?></p>
                     <p><strong>Catégorie :</strong> <?= htmlspecialchars($mc['categorie_name']) ?></p>
                     <p><strong>Sous-catégorie :</strong> <?= htmlspecialchars($mc['sous_categorie_name']) ?></p>
                     <p><strong>Commentaire :</strong> <?= nl2br(htmlspecialchars($mc['commentaire'])) ?></p>
                  </div>
               </div>

               <!-- Colonne de droite : Photo et fichiers -->
                <?php if($mc['photo'] || $mc['fichier']): ?>
               <div class="card">
                  <div class="card-header">
                     <h2 class="text-xl font-semibold">Documents et Photo</h2>
                  </div>
                  <!-- Affichage de la photo -->
                  <?php if ($mc['photo']): ?>
                     <div class="main-photo-container">
                           <img src="./agent/<?= htmlspecialchars($mc['photo']) ?>" alt="Photo" class="main-photo">
                     </div>
                  <?php endif; ?>

                  <!-- Fichier à télécharger -->
                  <?php if ($mc['fichier']): ?>
                     <div class="file-link">
                        <a href="./agent/<?= htmlspecialchars($mc['fichier']) ?>" target="_blank" 
                        class="btn-custom">
                        <i data-feather="download"></i>
                              Télécharger le fichier
                           </a>
                     </div>
                  <?php endif; ?>
               </div>
               <?php endif; ?>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="static-modal<?= $mc['id'] ?>" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Fermer</button>
            </div>
        </div>
    </div>
</div>

</div>
<?php endforeach; ?>

        <?php else: ?>
            <p class="text-gray-700">Aucune main courante disponible.</p>
        <?php endif; ?>
    </div>
</div>

</div>
</main>

<style>
/* CSS personnalisé pour masquer le titre sur mobile */
    #main-title {
        display: block; /* Affiche le titre par défaut */
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
        border-radius: 0.375rem; /* rounded-lg */
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: fade-in 0.5s ease-in-out;
    }

    .alert-success {
        background-color: #D1F7E2; /* Vert clair */
        color: #006400; /* Vert foncé */
    }

    .alert-error {
        background-color: #FEE2E2; /* Rouge clair */
        color: #B91C1C; /* Rouge foncé */
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
    td{
        font-weight: bold !important;
        color: black;
    }
</style>



      <!-- Libs JS -->
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
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
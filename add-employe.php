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



// Traitement du formulaire d'ajout d'employé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $role = $_POST['role'];
    $matricule = trim($_POST['matricule']);
    $debut_contrat = $_POST['debut_contrat'];
    $fin_contrat = $_POST['fin_contrat'];
    $type_contrat = $_POST['type_contrat'];
    $nationalite = trim($_POST['nationalite']);
    $fonction = $_POST['fonction']; // Ajout de la fonction
    $urgence_nom = trim($_POST['urgence_nom']);
    $urgence_telephone = trim($_POST['urgence_telephone']);
    $date_embauche = $_POST['date_embauche'];
    $delivre_cni = trim($_POST['delivre_cni']);
    $date_val_cni = $_POST['date_val_cni'];
    $num_cni = trim($_POST['num_cni']);
    $langue = trim($_POST['langue']);
    $formation = trim($_POST['formation']);
    $genre = trim($_POST['genre']);
    $vehicule = trim($_POST['vehicule']);
    $permis = trim($_POST['permis']);
    $nombre_heur = $_POST['nombre_heur'];


   
    
     // Vérifie si des fonctions ont été sélectionnées
    $fonction = ($role === 'agent' && !empty($_POST['fonction'])) ? implode(', ', $_POST['fonction']) : null;
    
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $entreprise_id = $_SESSION['user_id'];

    // Gestion de l'upload de l'image
    $photo = null;
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        $photo = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($role) ||
    empty($fonction) || empty($mot_de_passe) || empty($matricule) || 
   empty($debut_contrat) || empty($type_contrat) || empty($nationalite) || 
   empty($urgence_nom) || empty($urgence_telephone) || empty($date_embauche) || 
   empty($delivre_cni) || empty($date_val_cni) || empty($num_cni) || empty($langue) || empty($formation) ||
   empty($genre) || empty($vehicule) || empty($permis) || empty($nombre_heur) 
   ) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        $stmt_check_email = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
        $stmt_check_email->execute([$email]);
        $email_exists = $stmt_check_email->fetchColumn();

        if ($email_exists > 0) {
            $message = "Cet email est déjà utilisé.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, matricule, debut_contrat, fin_contrat, type_contrat, nationalite, fonction, photo, entreprise_id, date_embauche, delivre_cni, date_val_cni, num_cni, langue, formation, genre, vehicule, permis, nombre_heur, date_creation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $telephone, $role, $matricule, $debut_contrat, $fin_contrat, $type_contrat, $nationalite, $fonction, $photo, $entreprise_id, $date_embauche, $delivre_cni, $date_val_cni, $num_cni, $langue, $formation, $genre, $vehicule, $permis, $nombre_heur]);

            $user_id = $pdo->lastInsertId();

            // Ajouter le contact d'urgence
            $stmt_urgence = $pdo->prepare("INSERT INTO en_cas_urgence (utilisateur_id, nom, telephone) VALUES (?, ?, ?)");
            $stmt_urgence->execute([$user_id, $urgence_nom, $urgence_telephone]);

            $message = "Employé ajouté avec succès!";
        }
    }
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

  <!-- Inclure Bootstrap CSS
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    
    <!-- Inclure Bootstrap-Select CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet">

<!-- Libs CSS -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
<link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
<link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">



<!-- Theme CSS -->
<link rel="stylesheet" href="./assets/css/theme.min.css">


 
      <link rel="stylesheet" href="./assets/libs/apexcharts/dist/apexcharts.css" />

      <style>
        /* Stylisation supplémentaire pour l'option */
        .country-flag {
            width: 30px; /* Taille du drapeau */
            height: auto;
            border-radius: 5px;
        }
        .selectpicker {
            width: 100%; /* Définir la largeur du select */
        }
        .ms-6 {
            margin-left: 1.5rem; /* Espacement du nom du pays */
        }
    </style>
      <title>Ajouter un Employé</title>
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
                <h3 class="text-white mb-1 font-normal">Ajouter un Employé</h3>
            </div>
            
            <!-- Barre sous le texte -->
            <hr class="border-t border-indigo-400 my-1">
            
            
                 <div class="card shadow">
                <div class="card-body">
                     <?php 
                    if (!empty($message)) { 
                        // Vérification du type de message pour ajuster les couleurs
                        $alert_class = (strpos($message, 'ajouté avec succès') !== false) ? 'alert-success' : 'alert-error'; 
                        $icon = (strpos($message, 'ajouté avec succès') !== false) ? '✓' : '✖'; 
                        echo "<div class='alert {$alert_class} p-4 mb-4 rounded-lg font-semibold flex items-center space-x-3 transition duration-300'>";
                        echo "<span class='text-xl'>{$icon}</span>";
                        echo "<span>{$message}</span>";
                        echo "</div>";
                    } 
                    ?>
                    
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
                        $langues = [
                           "Français",
                           "Anglais",
                           "Wolof",
                           "Diola",
                           "Poular",  
                           "Sérère"
                        ];

                        $formation = [
                            "Formation Agent de prévention et sécurité",
                            "Formation Agent spécialisé de protection rapprochée",
                            "Formation Agent cynophile",
                            "Formation Ssiap 1",
                            "Formation Ssiap 2",
                            "Formation Ssiap 3",
                            "Formation Vidéoprotection",
                            "Formation Télésurveillance",
                            "Formation SST",
                            "Formation HOBO"
                        ];
                        ?>

                    <form method="POST" class="space-y-6 space-x-6" enctype="multipart/form-data" novalidate="novalidate">
                        <h4 class="mt-6 font-semibold text-lg">Personnel</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="prenom" id="prenom" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                            </div>
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="nom" id="nom" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                            </div>
                        </div>
            
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email professionnelle du salarié</label>
                                <input type="email" name="email" id="email" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                            </div>
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone personnel ou professionnel</label>
                                <input type="text" name="telephone" id="telephone" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                            </div>
                        </div>

                        <div>
                        <label class="block text-sm font-medium text-gray-700">Photo de l'agent - (format JPEG ou PNG)</label>
                        <input type="file" name="photo" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" accept=".jpg,.jpeg,.png">
                     </div>

                     <div class="form-group">
                        <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                        <select name="genre" id="genre" class="form-control w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                            <option value="">Sélectionner</option>
                            <option value="M">Homme</option>
                            <option value="F">Femme</option>
                        </select>
                    </div>

                        <div class=''>
                           <label class="block text-sm font-medium text-gray-700">Nationalité de l’employé</label>
                           <div class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                           <select class="selectpicker form-control show-menu-arrow"  id='pay' name="nationalite"
                                 data-live-search="true" data-style="btn-light" required>
                                 <option value="" disabled selected>Choisissez un pays</option>
                              </select>
                           </div>
                        </div>

                        <h4 class="mt-6 font-semibold text-lg">Carte d'identité</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                           <div>
                              <label class="block text-sm font-medium text-gray-700" >N°</label>
                              <input type="number" name="num_cni" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                           </div>
                           <div>
                              <label class="block text-sm font-medium text-gray-700" >Date de validité</label>
                              <input type="date" name="date_val_cni" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                           </div>
                        </div>

                        <div>
                              <label for="mot_de_passe" class="block text-sm font-medium text-gray-700">Délivrée par</label>
                              <input type="text" name="delivre_cni" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                           <div>
                              <label class="block text-sm font-medium text-gray-700" >Véhicule</label>
                                 <select name="vehicule" id="vehicule" class="form-control w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Voiture">Voiture</option>
                                    <option value="Moto">Moto</option>
                                 </select>
                           </div>
                           <div>
                              <label class="block text-sm font-medium text-gray-700" >Permis de conduire</label>
                              <select name="permis" id="permis" class="form-control w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Permis A">Permis A</option>
                                    <option value="Permis B">Permis B</option>
                                    <option value="Permis C">Permis C</option>
                                    <option value="Permis D">Permis D</option>
                                    <option value="Permis E">Permis E</option>
                                 </select>
                           </div>
                        </div>

            
                        <h4 class="mt-6 font-semibold text-lg">professionnel</h4>
                       <div class="form-group">
                        <label for="role" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="role" id="role" class="form-control w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                            <option value="">Sélectionner son status</option>
                            <option value="agent">Agent</option>
                            <option value="controleur">Contrôleur</option>
                        </select>
                    </div>
                    
                    <div class="form-group hidden" id="fonction-container">
                            <label class="block text-sm font-medium text-gray-700">Poste occupé dans l’entreprises</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                <?php foreach ($fonctions_agent as $fonction) : ?>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="fonction[]" value="<?= htmlspecialchars($fonction) ?>" class="form-checkbox text-indigo-600">
                                        <span><?= htmlspecialchars($fonction) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                     
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                           <div>
                              <label class="block text-sm font-medium text-gray-700">Langue</label>
                              <!-- selecet avec $langues -->
                               <select name="langue" id="langue" class="form-control w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                                   <option value="">Sélectionner une langue</option>
                                   <?php foreach ($langues as $langue) : ?>
                                       <option value="<?= htmlspecialchars($langue) ?>"><?= htmlspecialchars($langue) ?></option>
                                   <?php endforeach; ?>
                               </select>
                           </div>
                           <div>
                              <label class="block text-sm font-medium text-gray-700">Formation Suivies</label>
                               <select name="formation" id="formation" class="form-control w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                                   <option value="">Sélectionner une formation</option>
                                   <?php foreach ($formation as $formation) : ?>
                                       <option value="<?= htmlspecialchars($formation) ?>"><?= htmlspecialchars($formation) ?></option>
                                   <?php endforeach; ?>
                               </select>
                           </div>
                        </div>

                   
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Matricule	Identifiant unique</label>
                    <input type="text" name="matricule" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type de contrat</label>
                    <select name="type_contrat" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                        <option value="Stage">Stage</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Début du contrat </label>
                    <input type="date" name="debut_contrat" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fin du contrat</label>
                    <input type="date" name="fin_contrat" class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                </div>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700">Date d'embauche</label>
                <input type="date" name="date_embauche" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre d’heures /mois</label>
                <input type="time" name="nombre_heur" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
            </div>
             

            <h4 class="mt-6 font-semibold text-lg">Contact d'urgence</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label>Nom Complet</label>
                    <input type="text" name="urgence_nom" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label>Téléphone</label>
                    <input type="text" name="urgence_telephone" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                </div>
            </div>

            
                        <div>
                            <label for="mot_de_passe" class="block text-sm font-medium text-gray-700">Mot de Passe</label>
                            <input type="password" name="mot_de_passe" id="mot_de_passe" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                        </div>
                        
                      
            
                       <div class="flex justify-end">
                    <button type="submit" class="mt-6 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        Ajouter
                    </button>
                </div>

                    </form>
                </div>
            </div>
        <style>
        /* CSS personnalisé pour les messages */
        label{
            color: black !important;
            font-weight: 500;
            font-size: 15px;
        }
        .font-medium {
    margin-top: 20px !important;
}
        .alert {
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

        .imgelm{
         width: 20px;
         height: 20px;
         margin-right: 20px;
        }

        .selectpicker{
         width: 100%;
         padding: none;
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
        </style>

         </div>
      </main>
      
      <script>
document.getElementById('role').addEventListener('change', function() {
    let fonctionContainer = document.getElementById('fonction-container');
    if (this.value === 'agent') {
        fonctionContainer.style.display = 'block';
    } else {
        fonctionContainer.style.display = 'none';
    }
});
</script>

<!-- Inclure jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<!-- Inclure Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Inclure Bootstrap-Select JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
 // Initialisation de Bootstrap-Select
 $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });
</script>

<script>
const pay = document.getElementById('pay');
try {
    const url = './assets/pays.json';

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau : ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues :', data);
            data.map(country => {
                pay.innerHTML += `
                <option class="text-black" data-content="<p class='flex justify-around items-center'> <img src='${country.flags.png}' class='imgelm' /> <span>${country.name.common}</span> </p>" value="${country.name.common}" >${country.name.common}</option>
                `;
            });
        })
        .catch(error => {
            console.error('Erreur lors de la requête :', error);
        });

} catch (error) {
    console.log(error);
}
     </script>

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

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

// Initialiser les variables d'erreur
$message = "";

// Traitement du formulaire de création de planning
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agent_id = $_POST['agent_id'];
    $dates = $_POST['dates']; // Tableau des jours sélectionnés
    $entreprise_id = $_SESSION['user_id'];

    // Valider les champs
    if (empty($agent_id) || empty($dates)) {
        $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
        $_SESSION['message_type'] = "error";
        header("Location: planning.php");
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
    $site = $_POST['site'][$date];
    
    // Vérifier si plusieurs fonctions sont sélectionnées et les concaténer
     $fonction = !empty($_POST['fonction'][$date]) ? implode(', ', $_POST['fonction'][$date]) : '';
    

    // Vérifier si les heures sont valides
    $debut_time = DateTime::createFromFormat('H:i', $debut);
    $fin_time = DateTime::createFromFormat('H:i', $fin);

    if ($debut_time === false || $fin_time === false) {
        $_SESSION['message'] = "Format d'heure invalide pour la date : $date. Début : $debut, Fin : $fin.";
        $_SESSION['message_type'] = "error";
        header("Location: planning.php");
        exit();
    }

    // Calculer la durée totale en minutes
    $interval = $debut_time->diff($fin_time);
    $total_minutes = ($interval->h * 60 + $interval->i) - (int) $pause;

    if ($total_minutes < 0) {
        $_SESSION['message'] = "Les horaires ou la durée de pause sont incorrects pour la date : $date.";
        $_SESSION['message_type'] = "error";
        header("Location: planning.php");
        exit();
    }

    $total_heures = sprintf('%02d:%02d', intdiv($total_minutes, 60), $total_minutes % 60);

    // Insérer dans la table `date_planning`
    $stmt_date = $pdo->prepare("INSERT INTO date_planning (planning_id, jour, debut, fin, pause, site, fonction, total)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_date->execute([$planning_id, $date, $debut, $fin, $pause, $site, $fonction, $total_heures]);
}


        $_SESSION['message'] = "Planning créé avec succès !";
        $_SESSION['message_type'] = "success";
        header("Location: planning.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['message'] = "Erreur lors de la création du planning : " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: planning.php");
        exit();
    }
}

// Récupérer la liste des agents pour l'entreprise
$stmt_agents = $pdo->prepare("SELECT id, nom, prenom FROM utilisateurs WHERE entreprise_id = ? AND role = 'agent'");
$stmt_agents->execute([$_SESSION['user_id']]);
$agents = $stmt_agents->fetchAll(PDO::FETCH_ASSOC);
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
      <title>Modifier le Planning</title>
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
                <h3 class="text-white mb-1 font-normal">Planification des agents</h3>
            </div>
            
            <!-- Barre sous le texte -->
            <hr class="border-t border-indigo-400 my-1">
            
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
            
                <div class="mx-auto max-w-4xl p-6 bg-white shadow-md rounded-md">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Créer un Planning</h1>
                    
                    <?php if (!empty($message)): ?>
                        <div class="mb-4 p-4 text-sm text-blue-800 bg-blue-100 border border-blue-200 rounded">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                
                    <form method="POST">
                     <!-- Agent -->
                     <div class="mb-4">
                        <label for="agent_id" class="block text-gray-700 font-medium mb-2">Agent</label>
                        <select name="agent_id" id="agent_id" class="w-full p-2 border border-gray-300 rounded" required>
                           <option value="">-- Sélectionnez un agent --</option>
                           <?php foreach ($agents as $agent): ?>
                              <option value="<?php echo $agent['id']; ?>">
                                 <?php echo htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']); ?>
                              </option>
                           <?php endforeach; ?>
                        </select>
                     </div>

                     <!-- Choisir les jours de la semaine -->
                     <div class="mb-4">
                        <label for="dates" class="block text-gray-700 font-medium mb-2">Sélectionner les dates</label>
                        <select name="dates[]" id="dates" multiple class="w-full p-2 border border-gray-300 rounded" size="7" required>
                            <option value="Lundi">Lundi</option>
                            <option value="Mardi">Mardi</option>
                            <option value="Mercredi">Mercredi</option>
                            <option value="Jeudi">Jeudi</option>
                            <option value="Vendredi">Vendredi</option>
                            <option value="Samedi">Samedi</option>
                            <option value="Dimanche">Dimanche</option>
                        </select>
                    </div>
                    
                    <div id="schedule-fields">

                     <!-- Heure de début -->
                     <div class="mb-4">
                        <label for="debut" class="block text-gray-700 font-medium mb-2">Heure de début</label>
                        <input type="time" name="debut" id="debut" class="w-full p-2 border border-gray-300 rounded" required>
                     </div>

                     <!-- Heure de fin -->
                     <div class="mb-4">
                        <label for="fin" class="block text-gray-700 font-medium mb-2">Heure de fin</label>
                        <input type="time" name="fin" id="fin" class="w-full p-2 border border-gray-300 rounded" required>
                     </div>

                     <!-- Pause -->
                     <div class="mb-4">
                        <label for="pause" class="block text-gray-700 font-medium mb-2">Pause</label>
                        <input type="time" name="pause" id="pause" class="w-full p-2 border border-gray-300 rounded" required>
                     </div>
                     
                     <!-- Fonction -->
                    <div class="mb-4">
                        <label for="fonction" class="block text-gray-700 font-medium mb-2">Fonction</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                <?php foreach ($fonctions_agent as $fonction) : ?>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="fonction[]" value="<?= htmlspecialchars($fonction) ?>" class="form-checkbox text-indigo-600">
                                        <span><?= htmlspecialchars($fonction) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                    </div>
                    
                    <!-- Site -->
                    <div class="mb-4">
                        <label for="site" class="block text-gray-700 font-medium mb-2">Site</label>
                        <input type="text" name="site" id="site" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>

                 </div>

                     <!-- Soumettre -->
                     <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md">Créer le planning</button>
                  </form>

            </div>

         </div>
      </main>

      <script>
document.getElementById("dates").addEventListener("change", function() {
    const selectedDays = Array.from(this.selectedOptions).map(option => option.value);
    const scheduleFieldsContainer = document.getElementById("schedule-fields");
    
    // Clear previous fields
    scheduleFieldsContainer.innerHTML = "";
    
    selectedDays.forEach(day => {
        // Créer un div pour chaque jour sélectionné
        const dayField = document.createElement("div");
        dayField.classList.add("mb-4");
        dayField.innerHTML = `
            <h3 class="text-lg font-semibold text-gray-700">${day}</h3>
            <label for="debut_${day}" class="block text-gray-700 font-medium mb-2">Heure de début</label>
            <input type="time" name="debut[${day}]" id="debut_${day}" class="w-full p-2 border border-gray-300 rounded" required>
            
            <label for="fin_${day}" class="block text-gray-700 font-medium mb-2">Heure de fin</label>
            <input type="time" name="fin[${day}]" id="fin_${day}" class="w-full p-2 border border-gray-300 rounded" required>
            
            <label for="pause_${day}" class="block text-gray-700 font-medium mb-2">Pause</label>
            <input type="time" name="pause[${day}]" id="pause_${day}" class="w-full p-2 border border-gray-300 rounded" required>

            <label for="site_${day}" class="block text-gray-700 font-medium mb-2">Site</label>
            <input type="text" name="site[${day}]" id="site_${day}" class="w-full p-2 border border-gray-300 rounded" required>
            
            <!-- Ajout du champ Fonction pour chaque jour -->
            <label for="fonction_${day}" class="block text-gray-700 font-medium mb-2">Fonction</label>
            <input type="text" name="fonction[${day}]" id="fonction_${day}" class="w-full p-2 border border-gray-300 rounded" required>
        `;
        
        scheduleFieldsContainer.appendChild(dayField);
    });
});
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

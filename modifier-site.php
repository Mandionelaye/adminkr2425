<?php
require_once 'config.php';
// Vérifier si l'employé existe dans la base de données
$stmt = $pdo->prepare("SELECT * FROM bj_site");
$stmt->execute();
$site = $stmt->fetch();

if (!$site) {
    header("Location: index.php"); // Si l'employé n'existe pas, rediriger vers la page d'accueil
    exit();
}

// Initialiser les variables pour les erreurs et le message de succès
$message = "";
$error_message = "";
$id=$_GET['id'];

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id=$_POST['id'];
    $nom = $_POST['name'];
    $adresse = trim($_POST['adresse']);
    $telephone = trim($_POST['phone']);
    $ouv = trim($_POST['ouverture']);
    $ferm = trim($_POST['fermeture']);
  

    // Valider les champs
    if (empty($nom) || empty($adresse) || empty($telephone) || empty($ouv) || empty($ferm)) {
        $error_message = "Veuillez remplir tous les champs.";
    } else {
        // Préparer la requête de mise à jour
        $stmt = $pdo->prepare("UPDATE bj_site SET name = ?, phone = ?, adresse = ?, ouverture = ?, fermeture = ? WHERE id = ?");
        $stmt->execute([$nom, $telephone, $adresse, $ouv, $ferm,$id]);
     header("Location: manage-site.php");
     $_SESSION['message1']="Site modifié avec succès!";
       
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

        <!-- Libs CSS -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
        <link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link rel="stylesheet" href="./assets/css/theme.min.css">

        
      <link rel="stylesheet" href="./assets/libs/apexcharts/dist/apexcharts.css" />Modifier Employé</title>
   </head>
   <body class="bg-gray-100">
<main>
         <!-- start the project -->
         <!-- app layout -->
         <div id="app-layout" class="overflow-x-hidden flex">
            <!-- start navbar -->
            <?php require_once 'layout/sidebar.php'; ?>
    <!--end of navbar-->

    <!-- Style CSS pour espacer les éléments du menu -->
    <style>
        .font-medium {
            margin-top: 20px !important;
        }
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
                    <h3 class="text-white mb-1 font-normal">Gérer les sites</h3>
                </div>
                
                <!-- Barre sous le texte -->
                <hr class="border-t border-indigo-400 my-1">
                
                <div class="max-w-4xl mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
                    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Modifier le site</h2>

                    <?php if (!empty($message)): ?>
                        <div class="bg-green-200 text-green-800 p-4 mb-6 rounded-lg font-semibold">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_message)): ?>
                        <div class="bg-red-200 text-red-800 p-4 mb-6 rounded-lg font-semibold">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
    

        <!-- Formulaire de modification d'employé -->
        <form method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="text" name="id" value="<?= $id ?>" hidden>
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($site['name']); ?>" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                </div>
                <div>
                    <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                    <input type="text" name="adresse" id="nom" value="<?php echo htmlspecialchars($site['adresse']); ?>" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                </div>
            

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Telephone</label>
                    <input type="phone" name="phone" id="phone" value="<?php echo htmlspecialchars($site['phone']); ?>" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                </div>
                <div>
                    <label for="ouverture" class="block text-sm font-medium text-gray-700">Ouverture</label>
                    <input type="text" name="ouverture" id="ouverture" value="<?php echo htmlspecialchars($site['ouverture']); ?>" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                </div>
        

                    <div>
                        <label for="fermeture" class="block text-sm font-medium text-gray-700">Fermeture</label>
                        <input type="text" name="fermeture" id="ferlmeture" value="<?php echo htmlspecialchars($site['fermeture']); ?>" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                    </div>
                 
            </div>

            <!-- Section des fonctions -->



            <button type="submit" class="mt-6 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 ml-auto block">
                Modifier
            </button>

        </form>
    </div>
</main>
    <script>
        document.getElementById('role').addEventListener('change', function() {
            var role = this.value;
            var fonctionContainer = document.getElementById('fonction-container');
            
            if (role === 'controleur') {
                fonctionContainer.style.display = 'none';
            } else {
                fonctionContainer.style.display = 'block';
            }
        });

        // Vérifie l'état initial en fonction du rôle sélectionné
        document.addEventListener('DOMContentLoaded', function() {
            var role = document.getElementById('role').value;
            var fonctionContainer = document.getElementById('fonction-container');
            
            if (role === 'controleur') {
                fonctionContainer.style.display = 'none';
            } else {
                fonctionContainer.style.display = 'block';
            }
        });
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


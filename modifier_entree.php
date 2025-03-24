<?php
// Connexion à la base de données
require_once 'config.php';

// Vérifier si un ID est fourni
if (!isset($_GET['id'])) {
    header('Location: entrees.php');
    exit();
}

$id = htmlspecialchars($_GET['id']);

// Get list of sites
$sites_query = "SELECT * FROM bj_site ORDER BY name ASC";
$sites_stmt = $pdo->query($sites_query);
$sites = $sites_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get list of agents
$agents_query = "SELECT * FROM utilisateurs WHERE role = 'agent' ORDER BY nom";
$agents_stmt = $pdo->query($agents_query);
$agents = $agents_stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les détails de l'entrée
try {
    $stmt = $pdo->prepare("SELECT * FROM entree WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $entree = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$entree) {
        header('Location: entrees.php');
        exit();
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valider'])) {
    // Validate required foreign keys
    if (empty($_POST['site_id']) || empty($_POST['agent_id'])) {
        $message = "Erreur : Le site et l'agent sont requis.";
    } else {
        // Récupération et sécurisation des données du formulaire
        $site_id = htmlspecialchars($_POST['site_id']);
        $agent_id = htmlspecialchars($_POST['agent_id']);
        $reference = htmlspecialchars($_POST['reference']);
        $numero = htmlspecialchars($_POST['numero']);
        $entree_date = $_POST['entree_date'] ?? '';
        $entree_heure = $_POST['entree_heure'] ?? '';
        $sortie_date = $_POST['sortie_date'] ?? '';
        $sortie_heure = $_POST['sortie_heure'] ?? '';
        $identite = in_array($_POST['identite'], ['employe', 'interimaire', 'visiteur']) ? $_POST['identite'] : 'visiteur';
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $motif_entree = htmlspecialchars($_POST['motif_entree'] ?? '');
        $societe = htmlspecialchars($_POST['societe'] ?? '');
        $service = htmlspecialchars($_POST['service'] ?? '');
        $personne_visitee = htmlspecialchars($_POST['personne_visitee'] ?? '');
        $badge = htmlspecialchars($_POST['badge'] ?? '');
        $piece = htmlspecialchars($_POST['piece'] ?? '');
        $matricule = htmlspecialchars($_POST['matricule'] ?? '');
        $controle = isset($_POST['controle']) ? 1 : 0;
        $plomb = htmlspecialchars($_POST['plomb'] ?? '');
        $remorque = htmlspecialchars($_POST['remorque'] ?? '');
        $quai = htmlspecialchars($_POST['quai'] ?? '');
        $livraison = htmlspecialchars($_POST['livraison'] ?? '');
        $probleme = isset($_POST['probleme']) ? 1 : 0;
        $commentaires = htmlspecialchars($_POST['commentaires'] ?? '');

        try {
            $sql = "UPDATE entree SET 
                site_id = :site_id,
                agent_id = :agent_id,
                reference = :reference,
                numero = :numero,
                entree_date = :entree_date,
                entree_heure = :entree_heure,
                sortie_date = :sortie_date,
                sortie_heure = :sortie_heure,
                identite = :identite,
                nom = :nom,
                prenom = :prenom,
                motif_entree = :motif_entree,
                societe = :societe,
                service = :service,
                personne_visitee = :personne_visitee,
                badge = :badge,
                piece = :piece,
                matricule = :matricule,
                controle = :controle,
                plomb = :plomb,
                remorque = :remorque,
                quai = :quai,
                livraison = :livraison,
                probleme = :probleme,
                commentaires = :commentaires
            WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'site_id' => $site_id,
                'agent_id' => $agent_id,
                'reference' => $reference,
                'numero' => $numero,
                'entree_date' => $entree_date,
                'entree_heure' => $entree_heure,
                'sortie_date' => $sortie_date,
                'sortie_heure' => $sortie_heure,
                'identite' => $identite,
                'nom' => $nom,
                'prenom' => $prenom,
                'motif_entree' => $motif_entree,
                'societe' => $societe,
                'service' => $service,
                'personne_visitee' => $personne_visitee,
                'badge' => $badge,
                'piece' => $piece,
                'matricule' => $matricule,
                'controle' => $controle,
                'plomb' => $plomb,
                'remorque' => $remorque,
                'quai' => $quai,
                'livraison' => $livraison,
                'probleme' => $probleme,
                'commentaires' => $commentaires
            ]);

            $message = "Modification réussie !";
            header("Location: view_entree.php?id=" . $id);
            exit();
        } catch (PDOException $e) {
            $message = "Erreur lors de la modification : " . $e->getMessage();
        }
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
    <meta name="description" content="Formulaire de permis de feu pour autorisation de travail par points chauds" />
    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon/favicon.ico" />

    <!-- Libs CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
    <link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="./assets/css/theme.min.css">
    <title>Permis de Feu - Autorisation de Travail par Points Chauds</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
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
        
        /* Styles pour le formulaire de permis de feu */
        .form-container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .form-header {
            background-color: #e30613;
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 5px 5px 0 0;
        }
        
        .form-header h1 {
            margin: 0;
            font-size: 20px;
        }
        
        .form-subheader {
            background-color: #e30613;
            color: white;
            text-align: center;
            padding: 5px;
            margin-top: -5px;
            border-radius: 0 0 5px 5px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .form-section {
            margin: 15px;
            border: 2px solid #f8d7da;
            border-radius: 5px;
            padding: 15px;
        }
        
        .section-title {
            background-color: #f8d7da;
            padding: 8px 12px;
            margin: -15px -15px 15px -15px;
            border-radius: 3px 3px 0 0;
            font-weight: bold;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
            gap: 15px;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .checkbox-group label {
            margin-left: 5px;
            margin-bottom: 0;
        }
        
        .checkbox-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .signature-line {
            text-align: right;
            margin-top: 10px;
        }
        
        .footer-note {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.5;
            margin: 15px;
        }
        
        .submit-btn {
            background-color: #e30613;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 15px auto;
        }
        
        .submit-btn:hover {
            background-color: #c00;
        }
        
        /* Styles pour la navbar et sidebar */
        .navbar-nav {
            gap: 1.2rem;
            padding-left: 0;
        }

        .nav-item {
            margin-bottom: 1.2rem;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.2rem;
        }

        .nav-link {
            font-size: 1rem;
            color: white;
            transition: color 0.3s ease;
        }

        .navbar-vertical .navbar-nav .nav-item .nav-link {
            color: white !important;
        }

        .nav-link.active {
            color: #ffc107;
        }

        .nav-link:hover {
            color: #d1d1d1;
        }

        .navbar-vertical {
            box-shadow: 2px 0px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-group {
                flex: 100%;
            }
            
            .checkbox-container {
                grid-template-columns: 1fr;
            }
            
            .header-container h3 {
                display: none;
            }
            
            .header-container .btn-create {
                margin-left: auto;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
<div id="app-layout" class="overflow-x-hidden flex">
        <!-- Sidebar -->
        <?php include 'layout/sidebar.php'; ?>

        <div id="app-layout-content" class="min-h-screen w-full min-w-[100vw] md:min-w-0 ml-[15.625rem]">
            <!-- Header/Navbar -->
                <div class="header">
                    <nav class="bg-white px-6 py-[10px] flex items-center justify-between shadow-sm">
                        <a id="nav-toggle" href="#" class="text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </a>
                        <div class="ml-3 hidden md:hidden lg:block">
                            <!-- Formulaire de recherche -->
                            <form class="flex items-center">
                                <input
                                type="search"
                                class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3 disabled:opacity-50 disabled:pointer-events-none"
                                placeholder="Rechercher" />
                            </form>
                        </div>
                        <!-- Navigation principale -->
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
                                        <!-- Liste des notifications -->
                                        <ul class="h-56" data-simplebar="">
                                            <!-- Élément de notification -->
                                            <li class="bg-gray-100 px-3 py-2 border-b">
                                                <a href="#">
                                                <h5 class="mb-1">Notification 1</h5>
                                                <p class="mb-0">Contenu de la notification 1</p>
                                                </a>
                                            </li>
                                            <li class="px-3 py-2 border-b">
                                                <a href="#">
                                                <h5 class="mb-1">Notification 2</h5>
                                                <p class="mb-0">Contenu de la notification 2</p>
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
                                        <h5 class="mb-1">Utilisateur</h5>
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
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight">Modifier l'entrée</h1>
            <p class="text-gray-600">Modification de l'entrée <?php echo htmlspecialchars($entree['reference']); ?></p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
            <div class="bg-gray-100 px-4 py-3 border-b border-gray-300">
                <h2 class="text-gray-700 text-xl font-semibold">FORMULAIRE DE MODIFICATION</h2>
            </div>

            <form method="post" class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="site_id" class="block mb-1">Site</label>
                        <select id="site_id" name="site_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Sélectionner un site</option>
                            <?php foreach ($sites as $site): ?>
                                <option value="<?php echo htmlspecialchars($site['id']); ?>"
                                        <?php echo $site['id'] === $entree['site_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($site['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="agent_id" class="block mb-1">Agent</label>
                        <select id="agent_id" name="agent_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Sélectionner un agent</option>
                            <?php foreach ($agents as $agent): ?>
                                <option value="<?php echo htmlspecialchars($agent['id']); ?>"
                                        <?php echo $agent['id'] === $entree['agent_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($agent['nom'] . ' ' . $agent['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="reference" class="block mb-1">Référence</label>
                        <input type="text" id="reference" name="reference" required
                               value="<?php echo htmlspecialchars($entree['reference']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div>
                        <label for="numero" class="block mb-1">Numéro</label>
                        <input type="text" id="numero" name="numero" required
                               value="<?php echo htmlspecialchars($entree['numero']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="flex items-center gap-2">
                        <label for="entree_date" class="min-w-20">Entrée le</label>
                        <input type="date" id="entree_date" name="entree_date" required
                               value="<?php echo htmlspecialchars($entree['entree_date']); ?>"
                               class="border border-gray-300 rounded px-3 py-2">
                        <input type="time" id="entree_heure" name="entree_heure" required
                               value="<?php echo htmlspecialchars($entree['entree_heure']); ?>"
                               class="border border-gray-300 rounded px-3 py-2">
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="sortie_date" class="min-w-20">Sortie le</label>
                        <input type="date" id="sortie_date" name="sortie_date"
                               value="<?php echo htmlspecialchars($entree['sortie_date']); ?>"
                               class="border border-gray-300 rounded px-3 py-2">
                        <input type="time" id="sortie_heure" name="sortie_heure"
                               value="<?php echo htmlspecialchars($entree['sortie_heure']); ?>"
                               class="border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold mb-2">IDENTITÉ</h3>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="identite" value="employe" required
                                   <?php echo $entree['identite'] === 'employe' ? 'checked' : ''; ?>>
                            <span class="ml-2">Employé</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="identite" value="interimaire"
                                   <?php echo $entree['identite'] === 'interimaire' ? 'checked' : ''; ?>>
                            <span class="ml-2">Intérimaire</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="identite" value="visiteur"
                                   <?php echo $entree['identite'] === 'visiteur' ? 'checked' : ''; ?>>
                            <span class="ml-2">Visiteur</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="nom" class="block mb-1">Nom</label>
                        <input type="text" id="nom" name="nom" required
                               value="<?php echo htmlspecialchars($entree['nom']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="prenom" class="block mb-1">Prénom</label>
                        <input type="text" id="prenom" name="prenom" required
                               value="<?php echo htmlspecialchars($entree['prenom']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="motif_entree" class="block mb-1">Motif d'entrée</label>
                        <input type="text" id="motif_entree" name="motif_entree"
                               value="<?php echo htmlspecialchars($entree['motif_entree']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="societe" class="block mb-1">Société</label>
                        <input type="text" id="societe" name="societe"
                               value="<?php echo htmlspecialchars($entree['societe']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="service" class="block mb-1">Service</label>
                        <input type="text" id="service" name="service"
                               value="<?php echo htmlspecialchars($entree['service']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="personne_visitee" class="block mb-1">Personne visitée</label>
                        <input type="text" id="personne_visitee" name="personne_visitee"
                               value="<?php echo htmlspecialchars($entree['personne_visitee']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold mb-2">COMPLÉMENT</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="badge" class="block mb-1">Badge</label>
                            <input type="text" id="badge" name="badge"
                                   value="<?php echo htmlspecialchars($entree['badge']); ?>"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div>
                            <label for="piece" class="block mb-1">Pièce d'identité</label>
                            <input type="text" id="piece" name="piece"
                                   value="<?php echo htmlspecialchars($entree['piece']); ?>"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="matricule" class="block mb-1">Immatriculation</label>
                        <input type="text" id="matricule" name="matricule"
                               value="<?php echo htmlspecialchars($entree['matricule']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div class="flex items-center justify-center">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="controle" value="1"
                                   <?php echo $entree['controle'] ? 'checked' : ''; ?>>
                            <span>Contrôle EPI effectué</span>
                        </label>
                    </div>
                    <div>
                        <label for="plomb" class="block mb-1">Plomb</label>
                        <input type="text" id="plomb" name="plomb"
                               value="<?php echo htmlspecialchars($entree['plomb']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="remorque" class="block mb-1">Remorque</label>
                        <input type="text" id="remorque" name="remorque"
                               value="<?php echo htmlspecialchars($entree['remorque']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="quai" class="block mb-1">Quai</label>
                        <input type="text" id="quai" name="quai"
                               value="<?php echo htmlspecialchars($entree['quai']); ?>"
                               class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold mb-2">LIVRAISON</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="livraison" class="block mb-1">Référence bon de livraison</label>
                            <input type="text" id="livraison" name="livraison"
                                   value="<?php echo htmlspecialchars($entree['livraison']); ?>"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="probleme" value="1"
                                       <?php echo $entree['probleme'] ? 'checked' : ''; ?>>
                                <span>Signaler un problème</span>
                            </label>
                        </div>
                        <div>
                            <label for="commentaires" class="block mb-1">Commentaires</label>
                            <textarea id="commentaires" name="commentaires" rows="4"
                                      class="w-full border border-gray-300 rounded px-3 py-2"><?php echo htmlspecialchars($entree['commentaires']); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <a href="view_entree.php?id=<?php echo $id; ?>" 
                       class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Annuler
                    </a>
                    <button type="submit" name="valider"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
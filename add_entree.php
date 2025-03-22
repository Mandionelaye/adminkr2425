<?php
// Connexion à la base de données
require_once 'config.php';

// Define constant to allow includes
define('INCLUDED_FROM_ENTREES', true);

// Get list of sites
$sites_query = "SELECT * FROM bj_site ORDER BY nom_site";
$sites_stmt = $pdo->query($sites_query);
$sites = $sites_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get list of agents
$agents_query = "SELECT * FROM utilisateurs WHERE role = 'agent' ORDER BY nom";
$agents_stmt = $pdo->query($agents_query);
$agents = $agents_stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valider'])) {
    // Process form submission (same as in entrees.php)
    // ... (code from entrees.php for form processing)
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width" />
    <meta name="description" content="Nouvelle entrée" />
    <title>Nouvelle entrée</title>
    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon/favicon.ico" />
    <link rel="stylesheet" href="./assets/css/theme.min.css">
    <link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">
    <style>
        /* Same styles as in entrees.php */
        .status-pill {
            background-color: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            display: inline-block;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        .navbar-vertical {
            background-color: #1a237e;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div id="app-layout" class="overflow-x-hidden flex">
        <?php include 'layout/sidebar.php'; ?>

        <div id="app-layout-content" class="min-h-screen w-full min-w-[100vw] md:min-w-0 ml-[15.625rem]">
            <?php include 'layout/navbar.php'; ?>

            <div class="container mx-auto px-4 py-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold tracking-tight">Nouvelle Entrée</h1>
                    <p class="text-gray-600">Formulaire d'enregistrement d'une nouvelle entrée</p>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                    <div class="bg-gray-100 px-4 py-3 border-b border-gray-300">
                        <h2 class="text-gray-700 text-xl font-semibold">FORMULAIRE D'ENTRÉE</h2>
                    </div>

                    <form method="post" class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="site_id" class="block mb-1">Site</label>
                                <select id="site_id" name="site_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="">Sélectionner un site</option>
                                    <?php foreach ($sites as $site): ?>
                                        <option value="<?php echo htmlspecialchars($site['id']); ?>">
                                            <?php echo htmlspecialchars($site['nom_site']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="agent_id" class="block mb-1">Agent</label>
                                <select id="agent_id" name="agent_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="">Sélectionner un agent</option>
                                    <?php foreach ($agents as $agent): ?>
                                        <option value="<?php echo htmlspecialchars($agent['id']); ?>">
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
                                       class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>

                            <div>
                                <label for="numero" class="block mb-1">Numéro</label>
                                <input type="number" id="numero" name="numero" required
                                       class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="flex items-center gap-2">
                                <label for="entree_date" class="min-w-20">Entrée le</label>
                                <input type="date" id="entree_date" name="entree_date" 
                                       value="<?php echo date('Y-m-d'); ?>" required
                                       class="border border-gray-300 rounded px-3 py-2">
                                <input type="time" id="entree_heure" name="entree_heure"
                                       value="<?php echo date('H:i'); ?>" required
                                       class="border border-gray-300 rounded px-3 py-2">
                            </div>

                            <div class="flex items-center gap-2">
                                <label for="sortie_date" class="min-w-20">Sortie le</label>
                                <input type="date" id="sortie_date" name="sortie_date"
                                       class="border border-gray-300 rounded px-3 py-2">
                                <input type="time" id="sortie_heure" name="sortie_heure"
                                       class="border border-gray-300 rounded px-3 py-2">
                            </div>
                        </div>

                        <div class="section-title mt-6">IDENTITÉ</div>
                        <div class="flex gap-4 mb-4">
                            <label class="flex items-center">
                                <input type="radio" name="identite" value="employe" required>
                                <span class="ml-2">Employé</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="identite" value="interimaire">
                                <span class="ml-2">Intérimaire</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="identite" value="visiteur">
                                <span class="ml-2">Visiteur</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nom" class="block mb-1">Nom</label>
                                <input type="text" id="nom" name="nom" required
                                       class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>
                            <div>
                                <label for="prenom" class="block mb-1">Prénom</label>
                                <input type="text" id="prenom" name="prenom" required
                                       class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="societe" class="block mb-1">Société</label>
                                <input type="text" id="societe" name="societe"
                                       class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>
                            <div>
                                <label for="service" class="block mb-1">Service</label>
                                <input type="text" id="service" name="service"
                                       class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>
                        </div>

                        <div class="section-title mt-6">COMPLÉMENT</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="badge" class="block mb-1">Badge</label>
                                <input type="text" id="badge" name="badge"
                                       class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>
                            <div>
                                <label for="matricule" class="block mb-1">Immatriculation</label>
                                <input type="text" id="matricule" name="matricule"
                                       class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 mt-6">
                            <a href="entrees.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Annuler
                            </a>
                            <button type="submit" name="valider"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/libs/feather-icons/dist/feather.min.js"></script>
    <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
</body>
</html>
<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id'])) {
    die("ID non spécifié.");
}

$id = (int)$_GET['id'];

try {
    $query = "SELECT mc.*, c.nom AS categorie_name, sc.nom AS sous_categorie_name, u.nom AS agent_nom, u.prenom AS agent_prenom
              FROM main_courante mc
              JOIN categories c ON mc.categorie_id = c.id
              JOIN sous_categories sc ON mc.sous_categorie_id = sc.id
              JOIN utilisateurs u ON mc.utilisateur_id = u.id
              WHERE mc.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $main_courante = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$main_courante) {
        die("Main courante non trouvée.");
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Main Courante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css">
    <script src="https://unpkg.com/feather-icons"></script>
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
</head>
<body class="bg-gray-100">

    <div class="container">
        <!-- Bouton Fermer en haut à droite -->
        <a href="main-courante.php" class="absolute top-4 right-4 p-2 bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700">
            <i data-feather="x"></i>
        </a>

        <!-- Titre -->
        <h1>Détails de la Main Courante</h1>

        <!-- Détails dans une grille de deux colonnes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Colonne de gauche : Informations principales -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-xl font-semibold">Informations</h2>
                </div>
                <div class="details space-y-4">
                    <p><strong>Date :</strong> <?= (new DateTime($main_courante['date_creation']))->format('d/m/Y H:i') ?></p>
                    <p><strong>Agent :</strong> <?= htmlspecialchars($main_courante['agent_prenom'] . ' ' . $main_courante['agent_nom']) ?></p>
                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($main_courante['categorie_name']) ?></p>
                    <p><strong>Sous-catégorie :</strong> <?= htmlspecialchars($main_courante['sous_categorie_name']) ?></p>
                    <p><strong>Commentaire :</strong> <?= nl2br(htmlspecialchars($main_courante['commentaire'])) ?></p>
                </div>
            </div>

            <!-- Colonne de droite : Photo et fichiers -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-xl font-semibold">Documents et Photo</h2>
                </div>
                <!-- Affichage de la photo -->
                <?php if ($main_courante['photo']): ?>
                    <div class="main-photo-container">
                        <img src="./agent/<?= htmlspecialchars($main_courante['photo']) ?>" alt="Photo" class="main-photo">
                    </div>
                <?php endif; ?>

                <!-- Fichier à télécharger -->
                <?php if ($main_courante['fichier']): ?>
                    <div class="file-link">
                        <i data-feather="download"></i>
                        <a href="./agent/<?= htmlspecialchars($main_courante['fichier']) ?>" target="_blank" 
                           class="btn-custom">
                            Télécharger le fichier
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

    <!-- Initialisation des icônes Feather -->
    <script>
        feather.replace();
    </script>
</body>
</html>

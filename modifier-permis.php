<?php
// update_permis_feu.php
// Connexion à la base de données
require_once 'config.php';

// Récupérer les données du formulaire
$id = $_POST['id'];
$numero_permis = $_POST['numero_permis'];
$agent = $_POST['agent'];
$etablissement = $_POST['etablissement'];
$adresse = $_POST['adresse'];
$code_postal = $_POST['code_postal'];
$ville = $_POST['ville'];
$nom_donneur = $_POST['nom_donneur'];
$prenoms_donneur = $_POST['prenoms_donneur'];
$fonction_donneur = $_POST['fonction_donneur'];
$code_postal_donneur = $_POST['code_postal_donneur'];
$nom_responsable = $_POST['nom_responsable'];
$prenoms_responsable = $_POST['prenoms_responsable'];
$fonction_responsable = $_POST['fonction_responsable'];
$code_postal_responsable = $_POST['code_postal_responsable'];
$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];
$heure_debut1 = $_POST['heure_debut1'];
$heure_fin1 = $_POST['heure_fin1'];
$heure_debut2 = $_POST['heure_debut2'];
$heure_fin2 = $_POST['heure_fin2'];
$particularites = $_POST['particularites'];

// Requête SQL pour mettre à jour l'élément
$sql = "UPDATE permis_feu SET
        numero_permis = :numero_permis,
        nom_emplois = :agent,
        etablissement = :etablissement,
        adresse = :adresse,
        code_postal = :code_postal,
        ville = :ville,
        nom_donneur = :nom_donneur,
        prenoms_donneur = :prenoms_donneur,
        fonction_donneur = :fonction_donneur,
        code_postal_donneur = :code_postal_donneur,
        nom_responsable = :nom_responsable,
        prenoms_responsable = :prenoms_responsable,
        fonction_responsable = :fonction_responsable,
        code_postal_responsable = :code_postal_responsable,
        date_debut = :date_debut,
        date_fin = :date_fin,
        heure_debut1 = :heure_debut1,
        heure_fin1 = :heure_fin1,
        heure_debut2 = :heure_debut2,
        heure_fin2 = :heure_fin2,
        particularites = :particularites
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':numero_permis' => $numero_permis,
    ':agent' => $agent,
    ':etablissement' => $etablissement,
    ':adresse' => $adresse,
    ':code_postal' => $code_postal,
    ':ville' => $ville,
    ':nom_donneur' => $nom_donneur,
    ':prenoms_donneur' => $prenoms_donneur,
    ':fonction_donneur' => $fonction_donneur,
    ':code_postal_donneur' => $code_postal_donneur,
    ':nom_responsable' => $nom_responsable,
    ':prenoms_responsable' => $prenoms_responsable,
    ':fonction_responsable' => $fonction_responsable,
    ':code_postal_responsable' => $code_postal_responsable,
    ':date_debut' => $date_debut,
    ':date_fin' => $date_fin,
    ':heure_debut1' => $heure_debut1,
    ':heure_fin1' => $heure_fin1,
    ':heure_debut2' => $heure_debut2,
    ':heure_fin2' => $heure_fin2,
    ':particularites' => $particularites,
    ':id' => $id
]);

// Redirection vers la page précédente
header('Location: permis.php');
exit;


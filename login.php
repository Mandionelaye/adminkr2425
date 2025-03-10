<?php
session_start();
require_once 'config.php'; // Assurez-vous d'inclure votre configuration PDO

// Définir le message d'erreur ou de succès
$message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role']; // Récupérer le rôle choisi

    // Valider les entrées
    if (empty($email) || empty($password) || empty($role)) {
        $message = "Veuillez entrer un email, un mot de passe et sélectionner un rôle.";
    } else {
        // Requête pour récupérer l'utilisateur par email et vérifier le rôle
        $stmt = $pdo->prepare("SELECT id, email, mot_de_passe, role FROM utilisateurs WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]); // Chercher par email et vérifier le rôle
        $user = $stmt->fetch();

        // Vérifier si l'utilisateur existe
        if ($user) {
            // Vérifier le mot de passe
            if (password_verify($password, $user['mot_de_passe'])) {
                // Authentifier l'utilisateur
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];

                // Message de succès
                $_SESSION['message'] = "Connexion réussie. Bienvenue!";
                header("Location: index.php"); // Rediriger vers index.php sans message dans l'URL
                exit();
            } else {
                $message = "Mot de passe incorrect.";
            }
        } else {
            $message = "Aucun utilisateur trouvé avec cet email et rôle.";
        }
    }
}
?>

<!doctype html>
<html lang="fr">
   <head>
      <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon/favicon.ico" />
      <!-- Libs CSS -->
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
      <link rel="stylesheet" href="./assets/libs/simplebar/dist/simplebar.min.css" />
      <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" rel="stylesheet">
      <!-- Theme CSS -->
      <link rel="stylesheet" href="./assets/css/theme.min.css">
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="description" content="Connexion - Karangue" />
      <title>Connexion - Karangue</title>
   </head>
  <body>
      <div class="flex flex-col items-center justify-center g-0 h-screen px-4">
         <div class="justify-center items-center w-full bg-white rounded-md shadow lg:flex md:mt-0 max-w-md xl:p-0">
            <div class="p-6 w-full sm:p-8 lg:p-8">
               <div class="mb-4" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                  <a href="index.php">
                    <img src="assets/images/logo.png" class="mb-1" alt="Logo" style="width: 150px; height: auto;" />
                  </a>
                  <p class="mb-6">Veuillez entrer vos informations utilisateur.</p>
               </div>      
               <?php if ($message): ?>
               <div class="mb-4 text-center">
                   <p class="text-red-600"><?php echo htmlspecialchars($message); ?></p>
               </div>
               <?php endif; ?>
               <!-- Formulaire de connexion -->
               <form method="POST" action="login.php">
                  <div class="mb-3">
                     <label for="email" class="inline-block mb-2">Email</label>
                     <input
                        type="email"
                        id="email"
                        class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3"
                        name="email"
                        placeholder="Votre adresse email"
                        required />
                  </div>
                  <div class="mb-5">
                     <label for="password" class="inline-block mb-2">Mot de passe</label>
                     <input
                        type="password"
                        id="password"
                        class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3"
                        name="password"
                        placeholder="**************"
                        required />
                  </div>
                    <div class="mb-3">
                     <label for="role" class="inline-block mb-2">Sélectionner votre status</label>
                     <select id="role" name="role" class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3" required>
                        <option value="entreprise">Entreprise</option>
                        <option value="agent">Agent</option>
                        <option value="controleur">Contrôleur</option>
                     </select>
                  </div>
                  <div class="lg:flex justify-between items-center mb-4">
                     <div class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-600 focus:outline-none focus:ring-2" id="rememberme" />
                        <label class="inline-block ms-2" for="rememberme">Se souvenir de moi</label>
                     </div>
                  </div>
                  <div>
                     <div class="grid">
                        <button
                           type="submit"
                           class="btn bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-800 hover:border-indigo-800 active:bg-indigo-800 active:border-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300">
                           Connexion
                        </button>
                     </div>

                     <div class="flex justify-between mt-4">
                        <div class="mb-2">
                           <a href="inscription.php" class="text-indigo-600 hover:text-indigo-600">Créer un compte</a>
                        </div>
                        <div>
                           <a href="forget-password.php" class="text-indigo-600 hover:text-indigo-600">Mot de passe oublié ?</a>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <script src="./assets/libs/feather-icons/dist/feather.min.js"></script>
      <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
      <script src="./assets/libs/simplebar/dist/simplebar.min.js"></script>
      <script>feather.replace();</script>
      <script src="./assets/js/theme.min.js"></script>
   </body>
</html>

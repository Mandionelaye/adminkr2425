<!doctype html>
<html lang="fr">
<head>
    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon/favicon.ico" />
    <link rel="stylesheet" href="./assets/css/theme.min.css">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - Karangue</title>
    <style>
    </style>
</head>
<body>
<div class="flex flex-col items-center justify-center min-h-screen px-4">

    <div class="w-full bg-white rounded-md shadow max-w-xl xl:max-w-2xl">
        <div class="p-6 w-full">
            <div class="mb-4 text-center">
                <a href="index.html" class="flex justify-center items-center">
                    <img src="assets/images/logo.png" class="mb-1" alt="Logo" style="width: 150px;" />
                </a>
                <p class="mb-6">Veuillez entrer vos informations pour créer un compte.</p>
            </div>

            <?php if (isset($_GET['message'])): ?>
                <div class="mb-4 text-center">
                    <p class="text-green-600"><?php echo htmlspecialchars($_GET['message']); ?></p>
                </div>
            <?php endif; ?>

            <form action="register_company.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nom-entreprise" class="inline-block mb-2">Nom de l'entreprise</label>
                    <input type="text" id="nom-entreprise" class="border w-full p-2" name="nom-entreprise" placeholder="Nom de votre entreprise" required />
                </div>
                <div class="lg:flex gap-4">
                    <div class="mb-3">
                        <label for="prenom" class="inline-block mb-2">Nom</label>
                        <input type="text" id="prenom" class="border w-full p-2" name="prenom" placeholder="Votre prénom" required />
                    </div>
                    <div class="mb-3">
                        <label for="nom" class="inline-block mb-2">Prénom</label>
                        <input type="text" id="nom" class="border w-full p-2" name="nom" placeholder="Votre nom" required />
                    </div>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="inline-block mb-2">Téléphone</label>
                    <input type="tel" id="telephone" class="border w-full p-2" name="telephone" placeholder="Exemple : +221 77 123 45 67" required />
                </div>
                <div class="mb-3">
                    <label for="email" class="inline-block mb-2">Email</label>
                    <input type="email" id="email" class="border w-full p-2" name="email" placeholder="Votre adresse email" required />
                </div>
                <div class="mb-3">
                    <label for="adresse" class="inline-block mb-2">Adresse</label>
                    <input type="text" id="adresse" class="border w-full p-2" name="adresse" placeholder="Votre adresse complète" required />
                </div>
                                <div class="mb-3">
                <label for="mot_de_passe" class="inline-block mb-2">Mot de passe</label>
                <input type="password" id="mot_de_passe" class="border w-full p-2" name="mot_de_passe" placeholder="Votre mot de passe" required />
            </div>
                <div class="mb-5">
                    <label for="document" class="inline-block mb-2">Fournir votre NINEA ou l'extrait Kbits</label>
                    <input type="file" id="document" class="border w-full p-2" name="document" accept=".png, .jpg, .jpeg, .pdf" required />
                    <small class="text-gray-500">Formats acceptés : .png, .jpg, .jpeg, .pdf</small>
                </div>
                <div class="mb-5">
                    <div class="flex items-start gap-2">
                        <input type="checkbox" id="agreeCheck" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded" required />
                        <label for="agreeCheck">
                            J'accepte les <a href="#!" class="text-indigo-600">Conditions d'utilisation</a> et la <a href="#!" class="text-indigo-600">Politique de confidentialité</a>.
                        </label>
                    </div>
                </div>
                <div class="grid">
                    <button type="submit" class="btn bg-indigo-600 text-white hover:bg-indigo-800 active:bg-indigo-800 focus:outline-none">
                        Créer un compte
                    </button>
                </div>
                <div class="md:flex md:justify-between mt-4">
                    <div>Déjà membre ? <a href="login.php" class="text-indigo-600">Connexion</a></div>
                    <div><a href="forget-password.html" class="text-indigo-600">Mot de passe oublié ?</a></div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="./assets/js/theme.min.js"></script>
</body>
</html>

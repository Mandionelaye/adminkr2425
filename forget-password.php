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

      <!-- Required meta tags -->
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width" />
      <meta name="description" content="Mot de passe Oublié" />
      <title>Mot de passe Oublié</title>
   </head>
   <body>
      <!-- start forget password -->
      <div class="flex flex-col items-center justify-center g-0 h-screen px-4">
         <!-- card -->
         <div class="justify-center items-center w-full bg-white rounded-md shadow lg:flex md:mt-0 max-w-md xl:p-0">
            <!-- card body -->
            <div class="p-6 w-full sm:p-8 lg:p-8">
               <div class="mb-4" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                  <a href="index.php">
                     <img src="assets/images/logo.png" class="mb-1" alt="Logo" style="width: 150px; height: auto;" />
                  </a>
                  <p class="mb-6">Ne vous inquietez pas, nous vous enverrons un e-mail pour reinitialiser votre mot de passe.</p>
               </div>
               <!-- form -->
               <form>
                  <!-- email -->
                  <div class="mb-3">
                     <label for="email" class="inline-block mb-2">Email</label>
                     <input
                        type="email"
                        id="email"
                        class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3 disabled:opacity-50 disabled:pointer-events-none"
                        name="email"
                        placeholder="Entrez votre email"
                        required="" />
                  </div>
                  <!-- button -->
                  <div class="mb-3 grid">
                     <button
                        type="submit"
                        class="btn bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-800 hover:border-indigo-800 active:bg-indigo-800 active:border-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-600">
                        Reinitialiser le mot de passe
                     </button>
                  </div>
                  <span>
                     Vous n'avez pas de compte ?
                     <a href="login.php" class="text-indigo-600 hover:text-indigo-600">Connexion</a>
                  </span>
               </form>
            </div>
         </div>
      </div>
      <!-- end of forget password -->

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

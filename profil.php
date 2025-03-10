<!doctype html>
<html lang="en">
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
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width" />
      <meta name="description" content="Profile - TailwindCSS HTML Admin Template Free - Dash UI" />
      <title>Profile - TailwindCSS HTML Admin Template Free - Dash UI</title>
   </head>
   <body>
      <main>
         <!-- start profile -->
         <!-- app layout -->
         <div id="app-layout" class="overflow-x-hidden flex">
            <!-- start navbar -->
<nav class="navbar-vertical navbar">
   <div id="myScrollableElement" class="h-screen" data-simplebar>
     <!-- Logo de la marque -->
<a class="navbar-brand" href="./index.html" style="display: flex; justify-content: center; align-items: center; width: 100%;">
   <img src="./assets/images/logo-2.png" alt="Logo de l'entreprise" style="max-height: 50px;" />
</a>

      <!-- Menu de navigation -->
      <ul class="navbar-nav flex-col" id="sideNavbar">
         <!-- Tableau de bord -->
         <li class="nav-item">
            <a class="nav-link " href="./index.html">
               <i data-feather="home" class="w-4 h-4 mr-2"></i>
               Tableau de Bord
            </a>
         </li>

         <!-- Gestion des utilisateurs -->
         <li class="nav-item">
            <a class="nav-link  collapsed " href="#!" data-bs-toggle="collapse" data-bs-target="#navAgents" aria-expanded="false" aria-controls="navAgents">
               <i data-feather="users" class="w-4 h-4 mr-2"></i>
               Agents
            </a>
            <div id="navAgents" class="collapse " data-bs-parent="#sideNavbar">
               <ul class="nav flex-col">
                  <li class="nav-item">
                     <a class="nav-link " href="./add-agent.html">Ajouter un Agent</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link " href="./manage-agents.html">Gérer les Agents</a>
                  </li>
               </ul>
            </div>
         </li>

         <!-- Gestion des contrôleurs -->
         <li class="nav-item">
            <a class="nav-link  collapsed " href="#!" data-bs-toggle="collapse" data-bs-target="#navControleurs" aria-expanded="false" aria-controls="navControleurs">
               <i data-feather="shield" class="w-4 h-4 mr-2"></i>
               Contrôleurs
            </a>
            <div id="navControleurs" class="collapse " data-bs-parent="#sideNavbar">
               <ul class="nav flex-col">
                  <li class="nav-item">
                     <a class="nav-link " href="./round-check.html">Vérification des Rondes</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link " href="./evaluate-agents.html">Évaluation des Agents</a>
                  </li>
               </ul>
            </div>
         </li>

         <!-- Gestion des rondes -->
         <li class="nav-item">
            <a class="nav-link  collapsed " href="#!" data-bs-toggle="collapse" data-bs-target="#navRondes" aria-expanded="false" aria-controls="navRondes">
               <i data-feather="map" class="w-4 h-4 mr-2"></i>
               Les Rondes
            </a>
            <div id="navRondes" class="collapse " data-bs-parent="#sideNavbar">
               <ul class="nav flex-col">
                  <li class="nav-item">
                     <a class="nav-link " href="./plan-rondes.html">Planifier les Rondes</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link " href="./assign-agents.html">Assigner des Agents</a>
                  </li>
               </ul>
            </div>
         </li>

         <!-- Gestion des abonnements -->
         <li class="nav-item">
            <a class="nav-link " href="./abonnement.html">
               <i data-feather="credit-card" class="w-4 h-4 mr-2"></i>
               Abonnement
            </a>
         </li>

        <!-- Statistiques -->
   <li class="nav-item">
      <a class="nav-link " href="./stats.html">
         <i data-feather="bar-chart" class="w-4 h-4 mr-2"></i>
         Rapports
      </a>
   </li>

   <!-- Notifications -->
   <li class="nav-item">
      <a class="nav-link " href="./notifications.html">
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
      <div class="ml-3 hidden md:hidden lg:block">
         <!-- formulaire de recherche -->
         <form class="flex items-center">
            <input
               type="search"
               class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3 disabled:opacity-50 disabled:pointer-events-none"
               placeholder="Rechercher" />
         </form>
      </div>
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
                     <a class="dropdown-item" href="./index.html">
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

               <div class="p-6">
                  <div class="flex items-center mb-4 border-b border-gray-300 pb-4">
                     <!-- title -->
                     <h1 class="inline-block text-xl font-semibold leading-6">Overview</h1>
                  </div>
                  <div class="block">
                     <div class="flex items-center p-5 rounded-t-md shadow bg-cover bg-no-repeat pt-28" style="background-image: url(assets/images/background/profile-cover.jpg)"></div>
                     <div class="bg-white rounded-b-md shadow mb-6">
                        <div class="flex items-center justify-between pt-4 pb-6 px-4">
                           <div class="flex items-center">
                              <!-- avatar -->
                              <div class="w-24 h-24 mr-2 relative flex justify-end items-end -mt-10">
                                 <img src="assets/images/avatar/avatar-1.jpg" class="rounded-full border-4 border-white" alt="" />
                                 <a href="#!" class="absolute top-0 right-0 mr-2" data-bs-toggle="tooltip" data-placement="top" title="" data-original-title="Verified" data-bs-original-title="">
                                    <img src="assets/images/svg/checked-mark.svg" alt="" height="30" width="30" />
                                 </a>
                              </div>
                              <!-- text -->
                              <div class="leading-4">
                                 <h2 class="mb-2 text-lg whitespace-nowrap">
                                    Jitu Chauhan
                                    <a href="#!" class="text-decoration-none" data-bs-toggle="tooltip" data-placement="top" title="" data-original-title="Beginner" data-bs-original-title=""></a>
                                 </h2>
                                 <p class="mb-0 text-gray-500">@imjituchauhan</p>
                              </div>
                           </div>
                           <div>
                              <a
                                 href="#"
                                 class="btn bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-800 hover:border-indigo-800 active:bg-indigo-800 active:border-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 md:visible invisible">
                                 Edit Profile
                              </a>
                           </div>
                        </div>
                        <!-- nav -->
                        <div class=" ">
                           <!-- list -->
                           <ul class="flex flex-no-wrap overflow-auto text-center text-gray-500 border-gray-300 border-t">
                              <li class="mr-2">
                                 <a href="#" class="block p-4 text-indigo-600 border-t-2 font-semibold border-indigo-600 active" aria-current="page">Overview</a>
                              </li>
                              <li class="mr-2">
                                 <a href="#" class="inline-block p-4 text-gray-800 font-semibold border-t-2 border-transparent hover:text-indigo-600 hover:border-indigo-600">Profile</a>
                              </li>
                              <li class="mr-2">
                                 <a href="#" class="inline-block p-4 text-gray-800 font-semibold border-t-2 border-transparent hover:text-indigo-600 hover:border-indigo-600">Files</a>
                              </li>
                              <li class="mr-2">
                                 <a href="#" class="inline-block p-4 text-gray-800 font-semibold border-t-2 border-transparent hover:text-indigo-600 hover:border-indigo-600">Teams</a>
                              </li>
                              <li class="mr-2">
                                 <a href="#" class="inline-block p-4 text-gray-800 font-semibold border-t-2 border-transparent hover:text-indigo-600 hover:border-indigo-600">Followers</a>
                              </li>
                              <li class="mr-2">
                                 <a href="#" class="inline-block p-4 text-gray-800 font-semibold border-t-2 border-transparent hover:text-indigo-600 hover:border-indigo-600">Activity</a>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="mb-6 grid grid-cols-1 gap-x-6 gap-y-8 xl:grid-cols-2">
                     <!-- card -->
                     <div class="card shadow">
                        <!-- card body -->
                        <div class="card-body">
                           <!-- card title -->
                           <h4 class="mb-6">About Me</h4>
                           <h5 class="uppercase tracking-widest text-sm font-semibold">Bio</h5>
                           <!-- text -->
                           <p class="mt-2 mb-6">
                              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspen disse var ius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum
                              nulla, ut commodo diam libero vitae erat.
                           </p>
                           <!-- row -->
                           <div class="mb-5">
                              <!-- text -->
                              <h5 class="uppercase tracking-widest text-sm font-semibold">Position</h5>
                              <p class="mb-0">Theme designer at Bootstrap.</p>
                           </div>
                           <!-- content -->
                           <div class="flex flex-row justify-between mb-5">
                              <div class="flex-1">
                                 <h5 class="uppercase tracking-widest text-sm font-semibold">Phone</h5>
                                 <p class="mb-0">+32112345689</p>
                              </div>
                              <div class="flex-1">
                                 <h5 class="uppercase tracking-widest text-sm font-semibold">Date of Birth</h5>
                                 <p class="mb-0">01.10.1997</p>
                              </div>
                           </div>
                           <div class="flex flex-row justify-between mb-5">
                              <div class="flex-1">
                                 <h5 class="uppercase tracking-widest text-sm font-semibold">Email</h5>
                                 <p class="mb-0">dashui@gmail.com</p>
                              </div>
                              <div class="flex-1">
                                 <h5 class="uppercase tracking-widest text-sm font-semibold">Location</h5>
                                 <p class="mb-0">Ahmedabad, India</p>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- card -->
                     <div class="card shadow">
                        <!-- card body -->
                        <div class="card-body">
                           <!-- card title -->
                           <h4 class="mb-6">Projects Contributions</h4>
                           <div class="md:flex justify-between items-center mb-4">
                              <div class="flex items-center">
                                 <div>
                                    <div class="border p-3 rounded-md">
                                       <img src="assets/images/brand/slack-logo.svg" alt="" class="w-5 h-5" />
                                    </div>
                                 </div>
                                 <!-- text -->
                                 <div class="ml-3">
                                    <h5 class="text-gray-800">
                                       <a href="#">Slack Figma Design UI</a>
                                    </h5>
                                    <p>Project description and details about...</p>
                                 </div>
                              </div>
                              <div class="flex items-center ms-10 ms-md-0 mt-3">
                                 <!-- avatar group -->
                                 <div class="-space-x-3 flex">
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-2.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 border-2 rounded-full border-white" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                 </div>
                                 <div class="ml-3">
                                    <!-- dropdown -->
                                    <div class="dropstart leading-4">
                                       <button class="text-gray-600 p-2 hover:bg-gray-300 rounded-full transition-all" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                          <i data-feather="more-vertical" class="w-4 h-4"></i>
                                       </button>
                                       <!-- list -->
                                       <ul class="dropdown-menu">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="md:flex justify-between items-center mb-4">
                              <div class="flex items-center">
                                 <div>
                                    <!-- icon shape -->
                                    <div class="border p-3 rounded-md">
                                       <img src="assets/images/brand/3dsmax-logo.svg" alt="" class="w-5 h-5" />
                                    </div>
                                 </div>
                                 <!-- text -->
                                 <div class="ml-3">
                                    <h5 class="text-gray-800">
                                       <a href="#">Design 3d Character</a>
                                    </h5>
                                    <p class="mb-0">Project description and details about...</p>
                                 </div>
                              </div>

                              <div class="flex items-center ms-10 ms-md-0 mt-3">
                                 <!-- avatar group -->
                                 <div class="-space-x-3 flex">
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-2.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 border-2 rounded-full border-white" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                 </div>
                                 <div class="ml-3">
                                    <!-- dropdown -->
                                    <div class="dropstart leading-4">
                                       <button class="text-gray-600 p-2 hover:bg-gray-300 rounded-full transition-all" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                          <i data-feather="more-vertical" class="w-4 h-4"></i>
                                       </button>
                                       <ul class="dropdown-menu">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="md:flex justify-between items-center mb-4">
                              <div class="flex items-center">
                                 <div>
                                    <!-- icon shape -->
                                    <div class="border p-3 rounded-md">
                                       <img src="assets/images/brand/github-logo.svg" alt="" class="w-5 h-5" />
                                    </div>
                                 </div>
                                 <!-- text -->
                                 <div class="ml-3">
                                    <h5 class="text-gray-800">
                                       <a href="#">Github Development</a>
                                    </h5>
                                    <p>Project description and details about...</p>
                                 </div>
                              </div>
                              <div class="flex items-center ms-10 ms-md-0 mt-3">
                                 <!-- avatar group -->
                                 <div class="-space-x-3 flex">
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-2.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 border-2 rounded-full border-white" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                 </div>
                                 <div class="ml-3">
                                    <!-- dropdown -->
                                    <div class="dropstart leading-4">
                                       <button class="text-gray-600 p-2 hover:bg-gray-300 rounded-full transition-all" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                          <i data-feather="more-vertical" class="w-4 h-4"></i>
                                       </button>
                                       <ul class="dropdown-menu">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="md:flex justify-between items-center mb-4">
                              <div class="flex items-center">
                                 <!-- icon shape -->
                                 <div>
                                    <div class="border p-3 rounded-md">
                                       <img src="assets/images/brand/dropbox-logo.svg" alt="" class="w-5 h-5" />
                                    </div>
                                 </div>
                                 <!-- text -->
                                 <div class="ml-3">
                                    <h5 class="text-gray-800">
                                       <a href="#">Dropbox Design System</a>
                                    </h5>
                                    <p>Project description and details about...</p>
                                 </div>
                              </div>
                              <div class="flex items-center ms-10 ms-md-0 mt-3">
                                 <!-- avatar group -->
                                 <div class="-space-x-3 flex">
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-2.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 border-2 rounded-full border-white" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                 </div>
                                 <div class="ml-3">
                                    <!-- dropdown -->
                                    <div class="dropstart leading-4">
                                       <button class="text-gray-600 p-2 hover:bg-gray-300 rounded-full transition-all" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                          <i data-feather="more-vertical" class="w-4 h-4"></i>
                                       </button>
                                       <ul class="dropdown-menu">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="md:flex justify-between items-center">
                              <div class="flex items-center">
                                 <!-- icon shape -->
                                 <div>
                                    <div class="border p-3 rounded-md bg-indigo-600">
                                       <img src="assets/images/brand/layers-logo.svg" alt="" class="w-5 h-5" />
                                    </div>
                                 </div>
                                 <!-- text -->
                                 <div class="ml-3">
                                    <h5 class="text-gray-800">
                                       <a href="#">Project Management</a>
                                    </h5>
                                    <p>Project description and details about...</p>
                                 </div>
                              </div>
                              <div class="flex items-center ms-10 ms-md-0 mt-3">
                                 <!-- avatar group -->
                                 <div class="-space-x-3 flex">
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-2.jpg" alt="Profile image" />
                                    <img class="relative inline object-cover w-8 h-8 border-2 rounded-full border-white" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                 </div>
                                 <div class="ml-3">
                                    <!-- dropdown -->
                                    <div class="dropstart leading-4">
                                       <button class="text-gray-600 p-2 hover:bg-gray-300 rounded-full transition-all" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                          <i data-feather="more-vertical" class="w-4 h-4"></i>
                                       </button>
                                       <ul class="dropdown-menu">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="mb-6 grid grid-cols-1 gap-x-6 gap-y-8 xl:grid-cols-2">
                     <!-- card -->
                     <div class="card shadow">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="flex justify-between mb-5 items-center">
                              <!-- avatar -->
                              <div class="flex items-center">
                                 <div>
                                    <img src="assets/images/avatar/avatar-1.jpg" alt="" class="w-12 h-12 rounded-full" />
                                 </div>
                                 <div class="ml-3">
                                    <h5>Jitu Chauhan</h5>
                                    <p>19 minutes ago</p>
                                 </div>
                              </div>
                              <div>
                                 <!-- dropdown -->
                                 <div class="dropstart leading-4">
                                    <button class="text-gray-600 p-2 hover:bg-gray-300 rounded-full transition-all" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                       <i data-feather="more-vertical" class="w-4 h-4"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                       <li><a class="dropdown-item" href="#">Action</a></li>
                                       <li><a class="dropdown-item" href="#">Another action</a></li>
                                       <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <div class="mb-4">
                              <!-- text -->
                              <p class="mb-4">
                                 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspen disse var ius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                 interdum nulla, ut commodo diam libero vitae erat.
                              </p>
                              <img src="assets/images/blog/blog-img-1.jpg" class="rounded-md w-full" alt="" />
                           </div>
                           <!-- icons -->
                           <div class="mb-4 flex gap-4 w-full">
                              <span class="flex items-center">
                                 <i data-feather="heart" class="w-4 h-4"></i>
                                 <span class="ml-1">20 Like</span>
                              </span>
                              <span class="flex items-center">
                                 <i data-feather="message-square" class="w-4 h-4"></i>

                                 <span class="ml-1">12 Comment</span>
                              </span>
                              <span class="flex items-center">
                                 <i data-feather="share-2" class="w-4 h-4"></i>
                                 <span class="ml-1">Share</span>
                              </span>
                           </div>
                           <div class="border-b border-t py-5 flex items-center mb-4">
                              <!-- avatar group -->
                              <div class="-space-x-3 flex">
                                 <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                                 <img class="relative inline object-cover w-8 h-8 rounded-full border-white border-2" src="./assets/images/avatar/avatar-2.jpg" alt="Profile image" />
                                 <img class="relative inline object-cover w-8 h-8 border-2 rounded-full border-white" src="./assets/images/avatar/avatar-1.jpg" alt="Profile image" />
                              </div>
                              <div class="ml-4 text-gray-600">You and 20 more liked this</div>
                           </div>
                           <!-- row -->
                           <div class="md:flex">
                              <div class="flex-shrink-1">
                                 <!-- avatar -->
                                 <img src="assets/images/avatar/avatar-1.jpg" class="w-10 h-10 rounded-full" alt="" />
                              </div>

                              <div class="md:ml-3 flex-grow">
                                 <!-- form -->
                                 <form class="flex gap-3 items-center justify-between w-full">
                                    <div>
                                       <label for="name" class="col-form-label">Name</label>
                                    </div>
                                    <div class="w-full">
                                       <input
                                          type="password"
                                          id="name"
                                          class="bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2" />
                                    </div>
                                    <div>
                                       <button
                                          type="submit"
                                          class="btn bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-800 hover:border-indigo-800 active:bg-indigo-800 active:border-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300">
                                          Post
                                       </button>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div>
                        <!-- card -->
                        <div class="card shadow mb-6">
                           <!-- card body -->
                           <div class="card-body">
                              <!-- card title -->
                              <h4 class="mb-6">My Team</h4>
                              <div class="flex justify-between items-center mb-4">
                                 <div class="flex items-center">
                                    <!-- img -->
                                    <div class="w-10 h-10 d-inline-block">
                                       <img src="assets/images/avatar/avatar-1.jpg" class="rounded-full" alt="" />
                                    </div>
                                    <!-- text -->
                                    <div class="ml-4">
                                       <h5>Dianna Smiley</h5>
                                       <p class="text-base">UI / UX Designer</p>
                                    </div>
                                 </div>
                                 <div class="flex items-center gap-4">
                                    <a href="#">
                                       <i class="mr-2 w-5 h-5" data-feather="phone-call"></i>
                                    </a>
                                    <a href="#">
                                       <i class="w-5 h-5" data-feather="video"></i>
                                    </a>
                                 </div>
                              </div>
                              <div class="flex justify-between items-center mb-4">
                                 <div class="flex items-center">
                                    <!-- img -->
                                    <div class="w-10 h-10 d-inline-block">
                                       <img src="assets/images/avatar/avatar-2.jpg" class="rounded-full" alt="" />
                                    </div>
                                    <!-- content -->
                                    <div class="ml-4">
                                       <h5>Anne Brewer</h5>
                                       <p class="text-base">Senior UX Designer</p>
                                    </div>
                                 </div>
                                 <div class="flex items-center gap-4">
                                    <!-- icons -->
                                    <a href="#">
                                       <i class="mr-2 w-5 h-5" data-feather="phone-call"></i>
                                    </a>
                                    <a href="#">
                                       <i class="w-5 h-5" data-feather="video"></i>
                                    </a>
                                 </div>
                              </div>
                              <div class="flex justify-between items-center mb-4">
                                 <div class="flex items-center">
                                    <!-- img -->
                                    <div class="w-10 h-10 d-inline-block">
                                       <img src="assets/images/avatar/avatar-4.jpg" class="rounded-full" alt="" />
                                    </div>
                                    <!-- content -->
                                    <div class="ml-4">
                                       <h5>Lisa Ewer</h5>
                                       <p class="text-base">Senior UX Designer</p>
                                    </div>
                                 </div>
                                 <div class="flex items-center gap-4">
                                    <!-- icons -->
                                    <a href="#">
                                       <i class="mr-2 w-5 h-5" data-feather="phone-call"></i>
                                    </a>
                                    <a href="#">
                                       <i class="w-5 h-5" data-feather="video"></i>
                                    </a>
                                 </div>
                              </div>
                              <div class="flex justify-between items-center mb-4">
                                 <div class="flex items-center">
                                    <!-- img -->
                                    <div class="w-10 h-10 d-inline-block">
                                       <img src="assets/images/avatar/avatar-3.jpg" class="rounded-full" alt="" />
                                    </div>
                                    <!-- text -->
                                    <div class="ml-4">
                                       <h5>Richard Christmas</h5>
                                       <p class="text-base">Front-End Engineer</p>
                                    </div>
                                 </div>
                                 <div class="flex items-center gap-4">
                                    <!-- icons -->
                                    <a href="#">
                                       <i class="mr-2 w-5 h-5" data-feather="phone-call"></i>
                                    </a>
                                    <a href="#">
                                       <i class="w-5 h-5" data-feather="video"></i>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- card -->
                        <div class="card shadow">
                           <!-- card body -->
                           <div class="card-body">
                              <!-- card title -->
                              <h4 class="mb-6">Activity Feed</h4>
                              <div>
                                 <div class="flex mb-5 flox-row gap-4">
                                    <!-- img -->
                                    <div class="w-10 h-10 d-inline-block">
                                       <img src="assets/images/avatar/avatar-6.jpg" class="rounded-full" alt="" />
                                    </div>
                                    <!-- content -->
                                    <div>
                                       <h5>Dianna Smiley</h5>
                                       <p class="mb-1">Just create a new Project in Dashui...</p>
                                       <p class="text-gray-400">2m ago</p>
                                    </div>
                                 </div>
                                 <div class="flex mb-5 flox-row gap-4">
                                    <!-- img -->
                                    <div class="w-10 h-10 d-inline-block">
                                       <img src="assets/images/avatar/avatar-7.jpg" class="rounded-full" alt="" />
                                    </div>
                                    <!-- content -->
                                    <div>
                                       <h5>Irene Hargrove</h5>
                                       <p class="mb-1">Comment on Bootstrap Tutorial irene...</p>
                                       <p class="text-gray-400">1hour ago</p>
                                    </div>
                                 </div>
                                 <div class="flex mb-5 flox-row gap-4">
                                    <!-- img -->
                                    <div class="w-10 h-10 d-inline-block">
                                       <img src="assets/images/avatar/avatar-9.jpg" class="rounded-full" alt="" />
                                    </div>
                                    <!-- content -->
                                    <div>
                                       <h5>Trevor Bradley</h5>
                                       <p class="mb-1">Just share your article on Social Media..</p>
                                       <p class="text-gray-400">2month ago</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <footer>
   <div class="px-6 border-t border-gray-300 py-3 flex justify-between items-center">
      <p class="m-0 leading-6">
         Made by
         <a href="https://codescandy.com/" target="_blank" class="text-indigo-600">Codescandy</a>
      </p>
      <a href="https://github.com/codescandy/dashui-tailwindcss" target="_blank">
         <svg viewBox="0 0 24 24" aria-hidden="true" class="h-6 w-6 fill-gray-800">
            <path
               fill-rule="evenodd"
               clip-rule="evenodd"
               d="M12 2C6.477 2 2 6.463 2 11.97c0 4.404 2.865 8.14 6.839 9.458.5.092.682-.216.682-.48 0-.236-.008-.864-.013-1.695-2.782.602-3.369-1.337-3.369-1.337-.454-1.151-1.11-1.458-1.11-1.458-.908-.618.069-.606.069-.606 1.003.07 1.531 1.027 1.531 1.027.892 1.524 2.341 1.084 2.91.828.092-.643.35-1.083.636-1.332-2.22-.251-4.555-1.107-4.555-4.927 0-1.088.39-1.979 1.029-2.675-.103-.252-.446-1.266.098-2.638 0 0 .84-.268 2.75 1.022A9.607 9.607 0 0 1 12 6.82c.85.004 1.705.114 2.504.336 1.909-1.29 2.747-1.022 2.747-1.022.546 1.372.202 2.386.1 2.638.64.696 1.028 1.587 1.028 2.675 0 3.83-2.339 4.673-4.566 4.92.359.307.678.915.678 1.846 0 1.332-.012 2.407-.012 2.734 0 .267.18.577.688.48 3.97-1.32 6.833-5.054 6.833-9.458C22 6.463 17.522 2 12 2Z"></path>
         </svg>
      </a>
   </div>
</footer>

            </div>
         </div>
         <!-- end of profile -->
      </main>

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

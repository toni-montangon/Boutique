<?php
/**
 * En-tête de l'administration
 * Fichier: admin/header.php
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier les droits d'accès (si la fonction existe)
if (function_exists('requireAdmin')) {
    requireAdmin();
}

// Page active
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - WatchShop</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Styles personnalisés -->
    <style>
        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: rgb(37, 99, 235);
            border-left: 3px solid rgb(37, 99, 235);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Barre de navigation -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="../index.php" target="_blank" class="text-blue-600 mr-4" title="Voir le site">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800">Administration WatchShop</h1>
                </div>
                
                <div class="flex items-center">
                    <div class="mr-4">
                        <span class="text-gray-600"><?php echo $_SESSION['user_name']; ?></span>
                    </div>
                    <a href="../logout.php" class="text-red-600 hover:text-red-800" title="Déconnexion">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Contenu principal -->
    <div class="flex flex-col md:flex-row flex-grow">
        <!-- Sidebar -->
        <aside class="bg-white shadow w-full md:w-64 md:min-h-screen">
            <nav class="p-4">
                <ul>
                    <li class="mb-2">
                        <a href="index.php" class="sidebar-link flex items-center px-4 py-3 rounded hover:bg-gray-100 <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="products.php" class="sidebar-link flex items-center px-4 py-3 rounded hover:bg-gray-100 <?php echo $currentPage === 'products.php' ? 'active' : ''; ?>">
                            <i class="fas fa-box w-6"></i>
                            <span>Produits</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="categories.php" class="sidebar-link flex items-center px-4 py-3 rounded hover:bg-gray-100 <?php echo $currentPage === 'categories.php' ? 'active' : ''; ?>">
                            <i class="fas fa-tags w-6"></i>
                            <span>Catégories</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="sidebar-link flex items-center px-4 py-3 rounded hover:bg-gray-100">
                            <i class="fas fa-shopping-cart w-6"></i>
                            <span>Commandes</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="sidebar-link flex items-center px-4 py-3 rounded hover:bg-gray-100">
                            <i class="fas fa-users w-6"></i>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Contenu de la page -->
        <main class="flex-grow p-4">
            <?php
            // Afficher les messages d'erreur
            if (isset($_SESSION['error_message'])) {
                echo '<div class="bg-red-100 text-red-700 border border-red-300 p-4 rounded mb-4">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']);
            }
            
            // Afficher les messages de succès
            if (isset($_SESSION['success_message'])) {
                echo '<div class="bg-green-100 text-green-700 border border-green-300 p-4 rounded mb-4">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }
            ?>

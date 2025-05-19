<?php
/**
 * En-tête de toutes les pages
 * Fichier: views/header.php
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion des fichiers nécessaires
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Initialiser le panier dans la session s'il n'existe pas
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculer le nombre d'articles dans le panier
$cartItemCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartItemCount += $item['quantite'];
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WatchShop - Boutique de Montres</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Styles personnalisés -->
    <style>
        /* Styles supplémentaires si nécessaire */
        .product-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="flex flex-col h-full bg-gray-50">
    <!-- En-tête de page -->
    <header class="bg-[#0c4633] text-white">
        <div class="container mx-auto px-4 py-3">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center mb-4 md:mb-0">
                    <a href="index.php" class="text-xl font-bold flex items-center">
                        <i class="fas fa-watch mr-2"></i>
                        WatchShop
                    </a>
                </div>
                
                <!-- Barre de recherche -->
                <div class="w-full md:w-1/3 mb-4 md:mb-0">
                    <form action="shop.php" method="get" class="flex">
                        <input type="text" name="search" id="search" placeholder="Rechercher une montre..." 
                               class="w-full px-4 py-2 rounded-l text-gray-900">
                        <button type="submit" class="bg-[#a37e2c] px-4 py-2 rounded-r hover:bg-[#c19940]">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Navigation -->
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="index.php" class="hover:text-[#a37e2c]">Accueil</a></li>
                        <li><a href="shop.php" class="hover:text-[#a37e2c]">Boutique</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="profile.php" class="hover:text-[#a37e2c]">Profil</a></li>
                            <?php if (isAdmin()): ?>
                                <li><a href="admin/index.php" class="hover:text-[#a37e2c]">Admin</a></li>
                            <?php endif; ?>
                            <li><a href="logout.php" class="hover:text-[#a37e2c]">Déconnexion</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="hover:text-[#a37e2c]">Connexion</a></li>
                        <?php endif; ?>
                        <li>
                            <a href="cart.php" class="hover:text-[#a37e2c] relative">
                                <i class="fas fa-shopping-cart"></i>
                                <?php if ($cartItemCount > 0): ?>
                                    <span class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                        <?php echo $cartItemCount; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Conteneur principal -->
    <main class="flex-grow container mx-auto px-4 py-6">
        <?php
        // Afficher les messages d'erreur
        if (isset($_SESSION['error_message'])) {
            echo showMessage($_SESSION['error_message'], 'error');
            unset($_SESSION['error_message']);
        }
        
        // Afficher les messages de succès
        if (isset($_SESSION['success_message'])) {
            echo showMessage($_SESSION['success_message'], 'success');
            unset($_SESSION['success_message']);
        }
        ?>
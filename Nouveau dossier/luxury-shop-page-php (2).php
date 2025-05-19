<?php
/**
 * Page boutique avec style de luxe
 * Fichier: shop.php (mise à jour)
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Récupérer les catégories pour le filtrage
$categories = getCategories();

// Initialiser les variables de filtrage
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$searchQuery = isset($_GET['search']) ? clean($_GET['search']) : null;

// Récupérer les produits selon les filtres
if ($searchQuery) {
    $products = searchProducts($searchQuery);
    $pageTitle = 'Résultats de recherche pour "' . $searchQuery . '"';
} elseif ($categoryId) {
    $products = getProducts(null, $categoryId);
    $category = getCategoryById($categoryId);
    $pageTitle = 'Collection ' . $category['nom'];
} else {
    $products = getProducts();
    $pageTitle = 'Toutes nos collections';
}

// Inclusion de l'en-tête de luxe
include 'views/luxury-header.php';

// Inclusion de la vue de luxe pour la page boutique
include 'views/luxury-shop.php';

// Inclusion du pied de page de luxe
include 'views/luxury-footer.php';

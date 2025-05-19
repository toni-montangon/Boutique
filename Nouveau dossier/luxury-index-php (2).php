<?php
/**
 * Page d'accueil avec style de luxe
 * Fichier: index.php (mise à jour)
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Obtenir les produits en vedette
$featuredProducts = getProducts(4, null, true);

// Obtenir les dernières nouveautés
$newProducts = getProducts(4);

// Obtenir toutes les catégories
$categories = getCategories();

// Inclusion de l'en-tête de luxe
include 'views/luxury-header.php';

// Inclusion de la vue de luxe pour la page d'accueil
include 'views/luxury-home.php';

// Inclusion du pied de page de luxe
include 'views/luxury-footer.php';

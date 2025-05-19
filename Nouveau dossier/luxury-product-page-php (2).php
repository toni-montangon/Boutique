<?php
/**
 * Page produit avec style de luxe
 * Fichier: product.php (mise à jour)
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Récupérer l'ID du produit depuis l'URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les détails du produit
$product = getProductById($productId);

// Vérifier si le produit existe
if (!$product) {
    // Rediriger vers la boutique si le produit n'existe pas
    $_SESSION['error_message'] = "Le produit demandé n'existe pas.";
    redirect('shop.php');
}

// Récupérer des produits similaires (même catégorie)
$similarProducts = getProducts(4, $product['categorie_id']);

// Traitement du formulaire d'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Vérifier que la quantité est valide
    if ($quantity <= 0) {
        $quantity = 1;
    }
    
    // Créer l'article pour le panier
    $cartItem = [
        'id' => $product['id'],
        'nom' => $product['nom'],
        'prix' => $product['prix'],
        'image' => $product['image'],
        'quantite' => $quantity
    ];
    
    // Vérifier si le produit est déjà dans le panier
    $found = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] === $product['id']) {
            // Mettre à jour la quantité
            $_SESSION['cart'][$key]['quantite'] += $quantity;
            $found = true;
            break;
        }
    }
    
    // Si le produit n'est pas dans le panier, l'ajouter
    if (!$found) {
        $_SESSION['cart'][] = $cartItem;
    }
    
    // Message de succès
    $_SESSION['success_message'] = "Le produit a été ajouté au panier.";
    
    // Rediriger pour éviter la soumission multiple du formulaire
    redirect("product.php?id=$productId");
}

// Inclusion de l'en-tête de luxe
include 'views/luxury-header.php';

// Inclusion de la vue de luxe pour la page produit
include 'views/luxury-product.php';

// Inclusion du pied de page de luxe
include 'views/luxury-footer.php';

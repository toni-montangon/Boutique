<?php
/**
 * Traitement AJAX pour l'ajout au panier
 * Fichier: add_to_cart_ajax.php
 */

// Démarrer la session
session_start();

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Initialiser le panier dans la session s'il n'existe pas
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Vérifier que la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['add_to_cart_ajax'])) {
    // Renvoyer une erreur si la requête n'est pas valide
    echo json_encode([
        'success' => false,
        'message' => 'Requête invalide.'
    ]);
    exit;
}

// Récupérer les données du formulaire
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Vérifier que la quantité est valide
if ($quantity <= 0) {
    $quantity = 1;
}

// Récupérer les détails du produit
$product = getProductById($productId);

// Vérifier si le produit existe
if (!$product) {
    // Renvoyer une erreur si le produit n'existe pas
    echo json_encode([
        'success' => false,
        'message' => 'Le produit demandé n\'existe pas.'
    ]);
    exit;
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

// Calculer le nombre total d'articles dans le panier
$cartItemCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartItemCount += $item['quantite'];
}

// Renvoyer une réponse de succès
echo json_encode([
    'success' => true,
    'message' => 'Le produit a été ajouté au panier.',
    'cart_count' => $cartItemCount
]);

<?php
/**
 * Traitement AJAX pour la recherche de produits
 * Fichier: search_ajax.php
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Vérifier que la requête contient un terme de recherche
if (!isset($_GET['query']) || empty($_GET['query'])) {
    // Renvoyer un tableau vide si aucune requête
    echo json_encode([
        'products' => []
    ]);
    exit;
}

// Nettoyer la requête
$query = clean($_GET['query']);

// Effectuer la recherche dans la base de données
$products = searchProducts($query);

// Renvoyer les résultats au format JSON
echo json_encode([
    'products' => $products
]);

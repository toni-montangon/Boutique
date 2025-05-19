<?php
/**
 * Fonctions utilitaires pour le site
 * Fichier: includes/functions.php
 */

// Inclusion de la connexion à la base de données
require_once 'db.php';

/**
 * Fonction pour nettoyer les entrées utilisateur
 * 
 * @param string $data Données à nettoyer
 * @return string Données nettoyées
 */
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Fonction pour rediriger vers une URL
 * 
 * @param string $url URL de redirection
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Fonction pour afficher un message
 * 
 * @param string $message Le message à afficher
 * @param string $type Le type de message (success, error, info)
 * @return string HTML du message formaté
 */
function showMessage($message, $type = 'info') {
    $alertClass = 'bg-blue-100 text-blue-700 border-blue-300'; // default info style
    
    if ($type === 'success') {
        $alertClass = 'bg-green-100 text-green-700 border-green-300';
    } elseif ($type === 'error') {
        $alertClass = 'bg-red-100 text-red-700 border-red-300';
    } elseif ($type === 'warning') {
        $alertClass = 'bg-yellow-100 text-yellow-700 border-yellow-300';
    }
    
    return "<div class='$alertClass border p-4 mb-4 rounded'>{$message}</div>";
}

/**
 * Fonction pour vérifier si l'utilisateur est connecté
 * 
 * @return boolean True si l'utilisateur est connecté, sinon False
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Fonction pour vérifier si l'utilisateur est un administrateur
 * 
 * @return boolean True si l'utilisateur est admin, sinon False
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Fonction pour formater un prix
 * 
 * @param float $price Le prix à formater
 * @return string Prix formaté
 */
function formatPrice($price) {
    return number_format($price, 2, ',', ' ') . ' €';
}

/**
 * Fonction pour récupérer tous les produits
 * 
 * @param int $limit Limite de résultats (optionnel)
 * @param int $categoryId Filtrer par catégorie (optionnel)
 * @param boolean $featured Produits en vedette uniquement (optionnel)
 * @return array Liste des produits
 */
function getProducts($limit = null, $categoryId = null, $featured = null) {
    $sql = "SELECT p.*, c.nom as categorie_nom 
            FROM products p
            LEFT JOIN categories c ON p.categorie_id = c.id
            WHERE 1=1";
    
    $params = [];
    
    if ($categoryId !== null) {
        $sql .= " AND p.categorie_id = ?";
        $params[] = $categoryId;
    }
    
    if ($featured !== null) {
        $sql .= " AND p.en_vedette = ?";
        $params[] = $featured ? 1 : 0;
    }
    
    $sql .= " ORDER BY p.date_creation DESC";
    
    if ($limit !== null) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
    }
    
    return fetchAll($sql, $params);
}

/**
 * Fonction pour récupérer un produit par son ID
 * 
 * @param int $id ID du produit
 * @return array|false Détails du produit ou false si non trouvé
 */
function getProductById($id) {
    $sql = "SELECT p.*, c.nom as categorie_nom 
            FROM products p
            LEFT JOIN categories c ON p.categorie_id = c.id
            WHERE p.id = ?";
    
    return fetchOne($sql, [$id]);
}

/**
 * Fonction pour rechercher des produits
 * 
 * @param string $query Terme de recherche
 * @return array Résultats de recherche
 */
function searchProducts($query) {
    $sql = "SELECT p.*, c.nom as categorie_nom 
            FROM products p
            LEFT JOIN categories c ON p.categorie_id = c.id
            WHERE p.nom LIKE ? OR p.description LIKE ?";
    
    $searchTerm = "%$query%";
    return fetchAll($sql, [$searchTerm, $searchTerm]);
}

/**
 * Fonction pour récupérer toutes les catégories
 * 
 * @return array Liste des catégories
 */
function getCategories() {
    $sql = "SELECT * FROM categories ORDER BY nom";
    return fetchAll($sql);
}

/**
 * Fonction pour récupérer une catégorie par son ID
 * 
 * @param int $id ID de la catégorie
 * @return array|false Détails de la catégorie ou false si non trouvée
 */
function getCategoryById($id) {
    $sql = "SELECT * FROM categories WHERE id = ?";
    return fetchOne($sql, [$id]);
}

/**
 * Fonction pour calculer le total du panier
 * 
 * @param array $cart Panier (tableau d'articles)
 * @return float Total du panier
 */
function calculateCartTotal($cart) {
    $total = 0;
    
    foreach ($cart as $item) {
        $total += $item['prix'] * $item['quantite'];
    }
    
    return $total;
}

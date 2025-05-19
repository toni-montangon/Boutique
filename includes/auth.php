<?php
/**
 * Gestion de l'authentification
 * Fichier: includes/auth.php
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion des fichiers nécessaires
require_once 'db.php';
require_once 'functions.php';

/**
 * Fonction pour inscrire un nouvel utilisateur
 * 
 * @param string $nom Nom de l'utilisateur
 * @param string $email Email de l'utilisateur
 * @param string $password Mot de passe de l'utilisateur
 * @return array Résultat de l'inscription [success: bool, message: string]
 */
function registerUser($nom, $email, $password) {
    // Nettoyer les entrées
    $nom = clean($nom);
    $email = clean($email);
    
    // Vérifier si l'email existe déjà
    $sql = "SELECT id FROM users WHERE email = ?";
    $user = fetchOne($sql, [$email]);
    
    if ($user) {
        return [
            'success' => false,
            'message' => 'Cet email est déjà utilisé.'
        ];
    }
    
    // Hacher le mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insérer le nouvel utilisateur
    $sql = "INSERT INTO users (nom, email, password) VALUES (?, ?, ?)";
    executeQuery($sql, [$nom, $email, $passwordHash]);
    
    return [
        'success' => true,
        'message' => 'Inscription réussie! Vous pouvez maintenant vous connecter.'
    ];
}

/**
 * Fonction pour connecter un utilisateur
 * 
 * @param string $email Email de l'utilisateur
 * @param string $password Mot de passe de l'utilisateur
 * @return array Résultat de la connexion [success: bool, message: string]
 */
function loginUser($email, $password) {
    // Nettoyer les entrées
    $email = clean($email);
    
    // Récupérer l'utilisateur par email
    $sql = "SELECT * FROM users WHERE email = ?";
    $user = fetchOne($sql, [$email]);
    
    // Vérifier si l'utilisateur existe
    if (!$user) {
        return [
            'success' => false,
            'message' => 'Email ou mot de passe incorrect.'
        ];
    }
    
    // Vérifier le mot de passe
    if (!password_verify($password, $user['password'])) {
        return [
            'success' => false,
            'message' => 'Email ou mot de passe incorrect.'
        ];
    }
    
    // Créer la session utilisateur
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nom'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    
    return [
        'success' => true,
        'message' => 'Connexion réussie!'
    ];
}

/**
 * Fonction pour déconnecter un utilisateur
 */
function logoutUser() {
    // Détruire toutes les variables de session
    $_SESSION = [];
    
    // Détruire la session
    session_destroy();
}

/**
 * Fonction pour récupérer les informations de l'utilisateur connecté
 * 
 * @return array|false Informations de l'utilisateur ou false si non connecté
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return false;
    }
    
    $sql = "SELECT * FROM users WHERE id = ?";
    return fetchOne($sql, [$_SESSION['user_id']]);
}

/**
 * Fonction pour mettre à jour le mot de passe de l'utilisateur
 * 
 * @param int $userId ID de l'utilisateur
 * @param string $currentPassword Mot de passe actuel
 * @param string $newPassword Nouveau mot de passe
 * @return array Résultat de la mise à jour [success: bool, message: string]
 */
function updatePassword($userId, $currentPassword, $newPassword) {
    // Récupérer l'utilisateur
    $sql = "SELECT * FROM users WHERE id = ?";
    $user = fetchOne($sql, [$userId]);
    
    // Vérifier le mot de passe actuel
    if (!password_verify($currentPassword, $user['password'])) {
        return [
            'success' => false,
            'message' => 'Le mot de passe actuel est incorrect.'
        ];
    }
    
    // Hacher le nouveau mot de passe
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Mettre à jour le mot de passe
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    executeQuery($sql, [$newPasswordHash, $userId]);
    
    return [
        'success' => true,
        'message' => 'Mot de passe mis à jour avec succès!'
    ];
}

/**
 * Fonction pour vérifier si un utilisateur a accès à l'espace admin
 * Redirige si l'utilisateur n'est pas admin
 */
function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        // Message d'erreur et redirection
        $_SESSION['error_message'] = "Accès refusé. Vous devez être administrateur pour accéder à cette page.";
        redirect('../login.php');
    }
}

/**
 * Fonction pour vérifier si un utilisateur est connecté
 * Redirige si l'utilisateur n'est pas connecté
 */
function requireLogin() {
    if (!isLoggedIn()) {
        // Message d'erreur et redirection
        $_SESSION['error_message'] = "Vous devez être connecté pour accéder à cette page.";
        redirect('login.php');
    }
}

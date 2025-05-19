<?php
/**
 * Script de déconnexion
 * Fichier: logout.php
 */

// Démarrer la session
session_start();

// Inclusion des fichiers nécessaires
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Déconnecter l'utilisateur
logoutUser();

// Message de succès
$_SESSION['success_message'] = "Vous avez été déconnecté avec succès.";

// Rediriger vers la page d'accueil
redirect('index.php');

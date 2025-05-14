<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: ac.php');
    exit;
}

$utilisateur = $_SESSION['utilisateur'];
?>

<h1>Bienvenue, <?= htmlspecialchars($utilisateur['prenom']) ?> <?= htmlspecialchars($utilisateur['nom']) ?> !</h1>
<p>Votre rôle : <?= htmlspecialchars($utilisateur['role']) ?></p>
<a href="logout.php">Se déconnecter</a>

<?php
// Affichage des erreurs (utile en dev)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Paramètres de connexion à la base de données
$host = 'localhost';
$db = 'boutique';
$user = 'root';
$pass = ''; // à adapter si tu as un mot de passe
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération et sécurisation des données du formulaire
        $nom       = htmlspecialchars(trim($_POST['nom']));
        $prenom    = htmlspecialchars(trim($_POST['prenom']));
        $email     = htmlspecialchars(trim($_POST['email']));
        $mot_de_passe = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $adresse   = htmlspecialchars(trim($_POST['adresse']));
        $telephone = htmlspecialchars(trim($_POST['telephone']));
        $role      = 'utilisateur'; // valeur par défaut

        // Requête d'insertion
        $stmt = $pdo->prepare("
            INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, adresse, telephone, role)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $adresse, $telephone, $role]);

        echo "✅ Inscription réussie !";
    }
} catch (PDOException $e) {
    echo "❌ Erreur lors de l'inscription : " . $e->getMessage();
}
?>

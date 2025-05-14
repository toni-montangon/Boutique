<?php
session_start(); // Démarre une session pour stocker l'utilisateur connecté

// Affichage des erreurs (développement uniquement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$host = 'localhost';
$db = 'boutique';
$user = 'root';
$pass = ''; // à adapter si besoin
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Vérification de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($password, $utilisateur['mot_de_passe'])) {
            // Connexion OK => Stocker les données en session
            $_SESSION['utilisateur'] = [
                'id' => $utilisateur['id_utilisateur'],
                'nom' => $utilisateur['nom'],
                'prenom' => $utilisateur['prenom'],
                'email' => $utilisateur['email'],
                'role' => $utilisateur['role']
            ];

            header('Location: compte.php'); // Redirige vers le compte utilisateur
            exit;
        } else {
            echo "❌ Adresse mail ou mot de passe incorrect.";
        }
    }
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>

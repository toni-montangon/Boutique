<?php
/**
 * Connexion à la base de données
 * Fichier: includes/db.php
 */

// Paramètres de connexion
$host = 'localhost';
$dbname = 'watch_shop';
$username = 'root';
$password = ''; // Généralement vide pour un environnement WAMP local

try {
    // Création de l'objet PDO pour la connexion
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    // Variable globale pour indiquer que la connexion est réussie
    $db_connected = true;
    
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message et terminer le script
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

/**
 * Fonction pour exécuter une requête SQL
 * 
 * @param string $sql La requête SQL à exécuter
 * @param array $params Les paramètres à lier à la requête
 * @return PDOStatement L'objet PDOStatement résultant
 */
function executeQuery($sql, $params = []) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

/**
 * Fonction pour récupérer tous les résultats d'une requête
 * 
 * @param string $sql La requête SQL à exécuter
 * @param array $params Les paramètres à lier à la requête
 * @return array Un tableau associatif contenant tous les résultats
 */
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Fonction pour récupérer une seule ligne de résultat
 * 
 * @param string $sql La requête SQL à exécuter
 * @param array $params Les paramètres à lier à la requête
 * @return array|false Un tableau associatif contenant la ligne ou false si aucun résultat
 */
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch();
}

/**
 * Fonction pour obtenir le dernier ID inséré
 * 
 * @return string Le dernier ID inséré
 */
function getLastInsertId() {
    global $pdo;
    return $pdo->lastInsertId();
}

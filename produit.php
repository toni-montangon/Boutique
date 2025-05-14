<?php
// Connexion à la base de données
$host = 'localhost';
$db = 'boutique';
$user = 'root';
$pass = ''; // à adapter selon ton mot de passe
$mysqli = new mysqli($host, $user, $pass, $db);

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Connexion échouée : " . $mysqli->connect_error);
}

// Requête SQL pour récupérer les montres
$sql = "SELECT * FROM montre ORDER BY date_ajout DESC";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Montres</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        .montre { border: 1px solid #ccc; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: #fff; display: flex; gap: 20px; }
        img { width: 150px; height: auto; border-radius: 5px; }
        .info { max-width: 800px; }
        .nom { font-size: 1.2em; font-weight: bold; }
        .prix { color: green; margin: 5px 0; }
    </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

</head>
<body>
    !-- HEADER fixé en haut -->
<nav class="navbar fixed-top custom-header">
  <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

    <!-- Partie gauche : Burger + Recherche -->
    <div class="d-flex align-items-center">
      <!-- Burger -->
      <button class="navbar-toggler border-0 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#menuBurger"
        aria-controls="menuBurger" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Barre de recherche -->
      <form class="d-none d-md-block">
        <input class="form-control search-bar" type="search" placeholder="Recherche" aria-label="Recherche">
      </form>
    </div>

    <!-- Centre absolu : Logo -->
    <div class="logo-center">
      <img src="assets\paneral.png" width="85" height="85" alt="Logo" class="logo-img">
    </div>

    <!-- Partie droite : Panier -->
    <div class="d-flex align-items-center">
      <a href="#" class="header-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
          <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zM5.102 6l1.313 7h6.17l1.313-7H5.102zM4.285 5h9.43l.72-3.5H3.566L4.285 5z"/>
        </svg>
      </a>
    </div>

  </div>

  <!-- Menu déroulant burger -->
  <div class="collapse" id="menuBurger">
    <div class="bg-menu p-3">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Produit</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<h1>Liste des Montres</h1>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="montre">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>">
            <div class="info">
                <div class="nom"><?= htmlspecialchars($row['nom']) ?></div>
                <div class="prix"><?= number_format($row['prix'], 2, ',', ' ') ?> €</div>
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                <small>Ajoutée le : <?= htmlspecialchars($row['date_ajout']) ?></small>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Aucune montre trouvée.</p>
<?php endif; ?>

<?php $mysqli->close(); ?>
<!-- Footer -->
<footer class="footer-section text-center py-5">
  <div class="container">
    <div class="row">
      <!-- Collections -->
      <div class="col-md-4">
        <h5 class="footer-title">Collections</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="footer-link">Collections n°1</a></li>
          <li><a href="#" class="footer-link">Collections n°2</a></li>
          <li><a href="#" class="footer-link">Collections n°3</a></li>
        </ul>
      </div>

      <!-- Logo et citation -->
      <div class="col-md-4">
        <h5 class="footer-title">Citation</h5>
        <img src="assets\paneral.png" alt="Paneral" class="footer-icon my-2 w-25 h-auto">
        <div class="footer-label">Porter une montre, c’est porter un rappel constant que le temps s’écoule… et que chaque seconde compte.</div>
      </div>

      <!-- Assistance -->
      <div class="col-md-4">
        <h5 class="footer-title">Assistance</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="footer-link">Nous contacter</a></li>
          <li><a href="#" class="footer-link">Mon compte</a></li>
          <li><a href="#" class="footer-link">Guide des tailles</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

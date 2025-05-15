<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="wrapper">

  <!-- HEADER -->
  <nav class="navbar fixed-top custom-header">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <!-- Partie gauche : Burger + Recherche -->
      <div class="d-flex align-items-center">
        <button class="navbar-toggler border-0 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#menuBurger"
          aria-controls="menuBurger" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
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

  <!-- Contenu principal -->
  <div class="container mt-5 pt-5">
    <h1 class="text-center mb-4">Votre Panier</h1>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix Unitaire</th>
            <th>Total</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de produit -->
          <tr>
            <td>Montre Élégante</td>
            <td>1</td>
            <td>150 €</td>
            <td>150 €</td>
            <td>
              <button class="btn btn-danger btn-sm">Supprimer</button>
            </td>
          </tr>
          <!-- Ajouter d'autres produits ici -->
        </tbody>
      </table>
    </div>
    <div class="text-end">
      <h4>Total : 150 €</h4>
      <a href="#" class="btn btn-success">Passer à la caisse</a>
    </div>
  </div>

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

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
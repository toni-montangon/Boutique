# README.md

# Boutique en ligne de montres

Bienvenue dans le projet de boutique en ligne de montres. Ce projet a été conçu pour offrir une expérience d'achat moderne et intuitive, avec une interface responsive et des fonctionnalités avancées.

## Table des matières

- [Technologies utilisées](#technologies-utilisées)
- [Installation](#installation)
- [Fonctionnalités](#fonctionnalités)
- [Structure du projet](#structure-du-projet)
- [Contributions](#contributions)
- [Licence](#licence)

## Technologies utilisées

- PHP (avec PDO pour la gestion de la base de données)
- MySQL
- JavaScript (avec Fetch API pour les requêtes asynchrones)
- CSS (pour le design responsive)
- Docker (pour l'environnement de développement)

## Installation

1. Clonez le dépôt :
   ```
   git clone https://github.com/prenom-nom/boutique-en-ligne.git
   ```

2. Accédez au répertoire du projet :
   ```
   cd boutique-en-ligne
   ```

3. Lancez Docker :
   ```
   docker-compose up -d
   ```

4. Accédez à l'application via votre navigateur à l'adresse `http://localhost:8000`.

5. Importez la base de données en utilisant le fichier `database/boutique.sql` dans PHPMyAdmin.

## Fonctionnalités

- Page d'accueil moderne avec produits phares et dernières nouveautés.
- Design responsive et respect de la charte graphique.
- Recherche avec autocomplétion JavaScript asynchrone.
- Vue boutique avec filtrage dynamique par catégorie/sous-catégorie.
- Page de détails d'un produit avec informations et bouton panier.
- Création de compte, connexion et gestion du profil utilisateur.
- Back-office administrateur pour la gestion des produits et catégories.
- Simulation d'un processus de validation du panier.

## Structure du projet

```
boutique-en-ligne
├── docker
│   ├── Dockerfile
│   ├── docker-compose.yml
│   └── php
│       └── php.ini
├── src
│   ├── assets
│   │   ├── css
│   │   │   └── styles.css
│   │   ├── js
│   │   │   └── scripts.js
│   │   └── images
│   ├── config
│   │   └── database.php
│   ├── controllers
│   │   ├── AdminController.php
│   │   ├── ProductController.php
│   │   └── UserController.php
│   ├── models
│   │   ├── Product.php
│   │   ├── User.php
│   │   └── Category.php
│   ├── views
│   │   ├── admin
│   │   │   ├── dashboard.php
│   │   │   └── products.php
│   │   ├── auth
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   ├── partials
│   │   │   ├── footer.php
│   │   │   └── header.php
│   │   ├── product
│   │   │   ├── details.php
│   │   │   └── list.php
│   │   └── index.php
│   ├── index.php
│   └── routes.php
├── database
│   ├── boutique.sql
│   └── migrations
│       └── create_tables.sql
├── docs
│   ├── bdd.pdf
│   └── maquette.pdf
├── .gitignore
├── README.md
└── composer.json
```

## Contributions

Les contributions sont les bienvenues ! N'hésitez pas à soumettre des demandes de tirage ou à signaler des problèmes.

## Licence

Ce projet est sous licence MIT. Pour plus de détails, veuillez consulter le fichier LICENSE.
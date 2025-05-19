-- Script SQL pour la création de la base de données de la boutique de montres
-- Nom de la base de données : watch_shop

CREATE DATABASE IF NOT EXISTS watch_shop;
USE watch_shop;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin') DEFAULT 'client',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des catégories de produits
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT
);

-- Table des produits
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT 'default.jpg',
    stock INT DEFAULT 0,
    categorie_id INT,
    en_vedette BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table des commandes
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'validee', 'expediee', 'livree', 'annulee') DEFAULT 'en_attente',
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des détails de commandes
CREATE TABLE order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantite INT NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Données de démonstration: Catégories
INSERT INTO categories (nom, description) VALUES
('Montres Classiques', 'Montres élégantes et intemporelles'),
('Montres Sport', 'Montres robustes pour les activités sportives'),
('Montres Connectées', 'Montres intelligentes avec fonctionnalités numériques'),
('Montres Luxe', 'Montres haut de gamme et prestigieuses');

-- Données de démonstration: Produits
INSERT INTO products (nom, description, prix, image, stock, categorie_id, en_vedette) VALUES
('Chronographe Élégance', 'Montre chronographe avec boîtier en acier inoxydable et bracelet en cuir véritable.', 299.99, 'chronographe-elegance.jpg', 15, 1, TRUE),
('SportPro 3000', 'Montre étanche à 200m, idéale pour la plongée et les sports nautiques.', 199.99, 'sportpro-3000.jpg', 22, 2, TRUE),
('SmartTime X1', 'Montre connectée avec suivi d\'activité, notifications et GPS intégré.', 249.99, 'smarttime-x1.jpg', 30, 3, FALSE),
('Prestige Diamond', 'Montre de luxe avec cadran serti de diamants et mécanisme suisse.', 1299.99, 'prestige-diamond.jpg', 5, 4, TRUE),
('Classic Gold', 'Montre classique avec boîtier en or et bracelet métallique.', 349.99, 'classic-gold.jpg', 12, 1, FALSE),
('Runner Plus', 'Montre pour coureurs avec capteur cardiaque et compteur de pas.', 129.99, 'runner-plus.jpg', 25, 2, TRUE),
('TechWatch Pro', 'Montre connectée avec écran AMOLED et autonomie de 7 jours.', 299.99, 'techwatch-pro.jpg', 18, 3, TRUE),
('Vintage Collection', 'Montre au design rétro inspiré des années 50.', 179.99, 'vintage-collection.jpg', 8, 1, FALSE);

-- Utilisateur admin de démonstration (mot de passe: admin123)
INSERT INTO users (nom, email, password, role) VALUES
('Admin', 'admin@watchshop.com', '$2y$10$8tGY3fDmZLszu3pDw1JNgOxhLRrLCQJm4QRvPn7pZ1EvRTLmLIEPy', 'admin');

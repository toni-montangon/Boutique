<?php
/**
 * Page d'accueil
 * Fichier: index.php
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Obtenir les produits en vedette
$featuredProducts = getProducts(4, null, true);

// Obtenir les dernières nouveautés
$newProducts = getProducts(4);

// Obtenir toutes les catégories
$categories = getCategories();

// Inclusion de l'en-tête
include 'views/header.php';
?>

<!-- Bannière principale -->
<section class="relative">
    <div class="h-96 bg-cover bg-center" style="background-image: url('assets/images/banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">WatchShop</h1>
                <p class="text-xl md:text-2xl mb-6">Découvrez notre collection de montres élégantes</p>
                <a href="shop.php" class="bg-[#a37e2c] hover:bg-[#c19940] text-white px-6 py-3 rounded-md text-lg font-semibold transition duration-300">
                    Voir la boutique
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Produits en vedette -->
<section class="py-12 bg-[#e8dcc1] bg-opacity-25">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Produits en vedette</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden product-card transition-all">
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                        <img src="assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2"><?php echo $product['nom']; ?></h3>
                        <p class="text-gray-600 mb-2"><?php echo $product['categorie_nom']; ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-[#a37e2c] font-bold"><?php echo formatPrice($product['prix']); ?></span>
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="text-[#a37e2c] hover:text-[#c19940]">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Catégories -->
<section class="py-12 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Nos catégories</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $categories = getCategories();
            foreach ($categories as $category):
            ?>
                <a href="shop.php?category=<?php echo $category['id']; ?>" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg">
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-semibold mb-2"><?php echo $category['nom']; ?></h3>
                            <p class="text-gray-600 mb-4"><?php echo $category['description']; ?></p>
                            <span class="inline-block bg-[#0c4633] text-white px-4 py-2 rounded hover:bg-[#083a2a]">
                                Découvrir
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Dernières nouveautés -->
<section class="py-12 bg-[#0c4633] bg-opacity-10">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Dernières nouveautés</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($newProducts as $product): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden product-card transition-all">
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                        <img src="assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2"><?php echo $product['nom']; ?></h3>
                        <p class="text-gray-600 mb-2"><?php echo $product['categorie_nom']; ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-[#a37e2c] font-bold"><?php echo formatPrice($product['prix']); ?></span>
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="text-[#a37e2c] hover:text-[#c19940]">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="shop.php" class="bg-[#0c4633] hover:bg-[#083a2a] text-white px-6 py-3 rounded-md text-lg font-semibold transition duration-300">
                Voir tous les produits
            </a>
        </div>
    </div>
</section>

<?php
// Inclusion du pied de page
include 'views/footer.php';
?>
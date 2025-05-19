<?php
/**
 * Fichier: views/shop.php
 * Description: Vue pour la page boutique
 */
?>

<!-- Bannière de la boutique -->
<section class="relative mb-8">
    <div class="h-64 bg-cover bg-center" style="background-image: url('assets/images/shop-banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Boutique</h1>
                <p class="text-lg md:text-xl">
                    <?php echo $pageTitle; ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contenu de la boutique -->
<section class="container mx-auto px-4 pb-12">
    <div class="flex flex-col md:flex-row">
        <!-- Sidebar avec filtres -->
        <div class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-6">
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <h2 class="text-xl font-semibold mb-4">Catégories</h2>
                <ul>
                    <li class="mb-2">
                        <a href="shop.php" class="block p-2 <?php echo !$categoryId ? 'bg-blue-100 text-blue-600 rounded' : 'hover:bg-gray-100 rounded'; ?>">
                            Toutes les catégories
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="mb-2">
                            <a href="shop.php?category=<?php echo $cat['id']; ?>" class="block p-2 <?php echo $categoryId == $cat['id'] ? 'bg-blue-100 text-blue-600 rounded' : 'hover:bg-gray-100 rounded'; ?>">
                                <?php echo $cat['nom']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <h2 class="text-xl font-semibold mb-4">Rechercher</h2>
                <form action="shop.php" method="get">
                    <div class="mb-4">
                        <input type="text" name="search" id="search-sidebar" placeholder="Rechercher..." 
                               class="w-full px-4 py-2 border rounded" value="<?php echo $searchQuery; ?>">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                </form>
            </div>
            
            <!-- Conteneur pour les résultats de recherche en direct -->
            <div id="search-results" class="bg-white rounded-lg shadow-md mt-2 hidden"></div>
        </div>
        
        <!-- Contenu principal avec produits -->
        <div class="w-full md:w-3/4">
            <!-- Résultats de recherche -->
            <?php if (count($products) === 0): ?>
                <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-6">
                    <p>Aucun produit trouvé. Veuillez essayer une autre recherche ou catégorie.</p>
                </div>
            <?php else: ?>
                <div class="mb-6 flex justify-between items-center">
                    <p class="text-gray-600"><?php echo count($products); ?> produits trouvés</p>
                </div>
            <?php endif; ?>
            
            <!-- Grille de produits -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden product-card transition-all" data-category="<?php echo $product['categorie_id']; ?>">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <img src="assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" class="w-full h-48 object-cover">
                        </a>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2"><?php echo $product['nom']; ?></h3>
                            <p class="text-gray-600 mb-2"><?php echo $product['categorie_nom']; ?></p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-bold"><?php echo formatPrice($product['prix']); ?></span>
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

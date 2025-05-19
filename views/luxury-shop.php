<!-- Fichier: views/luxury-shop.php -->
<?php
/**
 * Vue de luxe pour la page boutique
 */
?>

<!-- Bannière de la boutique -->
<div class="relative h-80 bg-cover bg-center" style="background-image: url('assets/images/luxury-collection-banner.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl text-white font-serif mb-4">Nos Collections</h1>
            <p class="text-lg text-platinum max-w-2xl mx-auto">
                <?php echo $pageTitle; ?>
            </p>
        </div>
    </div>
</div>

<!-- Contenu de la boutique -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row">
            <!-- Sidebar avec filtres -->
            <div class="w-full md:w-1/4 mb-8 md:mb-0 md:pr-8">
                <!-- Catégories -->
                <div class="bg-white shadow p-6 mb-8">
                    <h2 class="text-xl font-serif mb-6 pb-2 border-b border-gray-200">Collections</h2>
                    <ul class="space-y-3">
                        <li>
                            <a href="shop.php" class="flex justify-between items-center <?php echo !$categoryId ? 'text-gold font-medium' : 'hover:text-gold'; ?>">
                                <span>Toutes les collections</span>
                                <span class="text-sm text-gray-500">(<?php echo count($products); ?>)</span>
                            </a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="shop.php?category=<?php echo $cat['id']; ?>" class="flex justify-between items-center <?php echo $categoryId == $cat['id'] ? 'text-gold font-medium' : 'hover:text-gold'; ?>">
                                    <span><?php echo $cat['nom']; ?></span>
                                    <?php
                                    // Compter les produits dans cette catégorie
                                    $categoryProductCount = count(fetchAll("SELECT id FROM products WHERE categorie_id = ?", [$cat['id']]));
                                    ?>
                                    <span class="text-sm text-gray-500">(<?php echo $categoryProductCount; ?>)</span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Filtre par prix -->
                <div class="bg-white shadow p-6 mb-8">
                    <h2 class="text-xl font-serif mb-6 pb-2 border-b border-gray-200">Prix</h2>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox text-gold">
                            <span class="ml-2">Moins de 200€</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox text-gold">
                            <span class="ml-2">200€ - 500€</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox text-gold">
                            <span class="ml-2">500€ - 1000€</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox text-gold">
                            <span class="ml-2">Plus de 1000€</span>
                        </label>
                    </div>
                </div>
                
                <!-- Recherche avancée -->
                <div class="bg-white shadow p-6">
                    <h2 class="text-xl font-serif mb-6 pb-2 border-b border-gray-200">Recherche</h2>
                    <form action="shop.php" method="get">
                        <div class="mb-4">
                            <input type="text" name="search" id="search-sidebar" placeholder="Rechercher..." 
                                   class="w-full px-4 py-2 border border-gray-300 focus:border-gold focus:outline-none" value="<?php echo $searchQuery; ?>">
                        </div>
                        <button type="submit" class="w-full bg-deep-black text-white hover:bg-gold hover:text-deep-black transition-colors py-2 uppercase tracking-wider text-sm">
                            Rechercher
                        </button>
                    </form>
                </div>
                
                <!-- Conteneur pour les résultats de recherche en direct -->
                <div id="search-results" class="bg-white shadow mt-2 hidden"></div>
            </div>
            
            <!-- Contenu principal avec produits -->
            <div class="w-full md:w-3/4">
                <!-- Résultats de recherche -->
                <?php if (count($products) === 0): ?>
                    <div class="bg-cream p-8 mb-8 text-center">
                        <p class="text-lg">Aucun produit ne correspond à votre recherche. Veuillez essayer d'autres critères.</p>
                        <a href="shop.php" class="text-gold hover:underline mt-4 inline-block">Voir toutes les collections</a>
                    </div>
                <?php else: ?>
                    <!-- En-tête des résultats -->
                    <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
                        <p class="text-gray-600"><?php echo count($products); ?> produits trouvés</p>
                        <div class="flex items-center">
                            <span class="mr-2 text-sm">Trier par:</span>
                            <select class="border-b border-gray-300 bg-transparent py-1 focus:outline-none focus:border-gold">
                                <option>Nouveautés</option>
                                <option>Prix croissant</option>
                                <option>Prix décroissant</option>
                                <option>Nom (A-Z)</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Grille de produits -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($products as $product): ?>
                            <div class="luxury-product-card fade-in-element animate-on-scroll" data-category="<?php echo $product['categorie_id']; ?>">
                                <?php if ($product['en_vedette']): ?>
                                    <div class="featured-badge">En vedette</div>
                                <?php endif; ?>
                                
                                <div class="luxury-product-image">
                                    <a href="product.php?id=<?php echo $product['id']; ?>">
                                        <img src="assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>">
                                    </a>
                                </div>
                                
                                <div class="luxury-product-info">
                                    <h3 class="luxury-product-name"><?php echo $product['nom']; ?></h3>
                                    <p class="luxury-product-category"><?php echo $product['categorie_nom']; ?></p>
                                    <div class="luxury-product-price"><?php echo formatPrice($product['prix']); ?></div>
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="luxury-product-button">Découvrir</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination (si nécessaire) -->
                    <?php if (count($products) > 12): ?>
                        <div class="mt-12 flex justify-center">
                            <nav class="inline-flex">
                                <a href="#" class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-100">Précédent</a>
                                <a href="#" class="px-4 py-2 border-t border-b border-r border-gray-300 bg-gold text-deep-black">1</a>
                                <a href="#" class="px-4 py-2 border-t border-b border-r border-gray-300 text-gray-700 hover:bg-gray-100">2</a>
                                <a href="#" class="px-4 py-2 border-t border-b border-r border-gray-300 text-gray-700 hover:bg-gray-100">3</a>
                                <a href="#" class="px-4 py-2 border-t border-b border-r border-gray-300 text-gray-700 hover:bg-gray-100">Suivant</a>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Services et avantages -->
<section class="py-12 bg-deep-black text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <i class="fas fa-shipping-fast text-gold text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Livraison Mondiale</h3>
                <p class="text-gray-400">Livraison offerte pour toutes vos commandes, avec suivi de colis en temps réel.</p>
            </div>
            
            <div class="text-center">
                <div class="inline-block mb-4">
                    <i class="fas fa-medal text-gold text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Authenticité Garantie</h3>
                <p class="text-gray-400">Chaque montre est livrée avec son certificat d'authenticité et sa garantie internationale.</p>
            </div>
            
            <div class="text-center">
                <div class="inline-block mb-4">
                    <i class="fas fa-headset text-gold text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Service Premium</h3>
                <p class="text-gray-400">Notre équipe d'experts horlogers est à votre disposition pour vous conseiller.</p>
            </div>
        </div>
    </div>
</section>

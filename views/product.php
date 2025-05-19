<?php
/**
 * Fichier: views/product.php
 * Description: Vue pour la page détail d'un produit
 */
?>

<!-- Fil d'Ariane -->
<nav class="text-sm text-gray-600 mb-6">
    <ol class="flex flex-wrap">
        <li class="mr-2">
            <a href="index.php" class="hover:text-blue-600">Accueil</a>
            <span class="mx-1">/</span>
        </li>
        <li class="mr-2">
            <a href="shop.php" class="hover:text-blue-600">Boutique</a>
            <span class="mx-1">/</span>
        </li>
        <li class="mr-2">
            <a href="shop.php?category=<?php echo $product['categorie_id']; ?>" class="hover:text-blue-600">
                <?php echo $product['categorie_nom']; ?>
            </a>
            <span class="mx-1">/</span>
        </li>
        <li class="font-medium text-gray-900"><?php echo $product['nom']; ?></li>
    </ol>
</nav>

<!-- Détail du produit -->
<section class="mb-12">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
            <!-- Image du produit -->
            <div class="p-6">
                <img src="assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" class="w-full h-auto rounded">
            </div>
            
            <!-- Informations du produit -->
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-4"><?php echo $product['nom']; ?></h1>
                
                <div class="mb-4">
                    <span class="text-2xl font-semibold text-blue-600"><?php echo formatPrice($product['prix']); ?></span>
                    <?php if ($product['stock'] > 0): ?>
                        <span class="ml-4 inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-sm">En stock</span>
                    <?php else: ?>
                        <span class="ml-4 inline-block bg-red-100 text-red-700 px-2 py-1 rounded text-sm">Rupture de stock</span>
                    <?php endif; ?>
                </div>
                
                <div class="mb-6">
                    <span class="text-gray-600">Catégorie: </span>
                    <a href="shop.php?category=<?php echo $product['categorie_id']; ?>" class="text-blue-600 hover:underline">
                        <?php echo $product['categorie_nom']; ?>
                    </a>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Description</h2>
                    <p class="text-gray-700"><?php echo $product['description']; ?></p>
                </div>
                
                <!-- Formulaire d'ajout au panier -->
                <?php if ($product['stock'] > 0): ?>
                    <form action="product.php?id=<?php echo $product['id']; ?>" method="post" class="mb-6">
                        <div class="flex items-center mb-4">
                            <label for="quantity" class="mr-4">Quantité :</label>
                            <div class="flex items-center border rounded">
                                <button type="button" class="px-3 py-1 bg-gray-200 hover:bg-gray-300" onclick="decrementQuantity()">-</button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="w-16 px-3 py-1 text-center border-x">
                                <button type="button" class="px-3 py-1 bg-gray-200 hover:bg-gray-300" onclick="incrementQuantity(<?php echo $product['stock']; ?>)">+</button>
                            </div>
                        </div>
                        
                        <button type="submit" name="add_to_cart" class="w-full bg-blue-600 text-white px-6 py-3 rounded font-semibold hover:bg-blue-700 transition duration-300">
                            <i class="fas fa-shopping-cart mr-2"></i> Ajouter au panier
                        </button>
                    </form>
                    
                    <!-- Bouton AJAX (alternative) -->
                    <button type="button" onclick="addToCartAjax(<?php echo $product['id']; ?>, document.getElementById('quantity').value)" class="w-full bg-green-600 text-white px-6 py-3 rounded font-semibold hover:bg-green-700 transition duration-300 mb-6">
                        <i class="fas fa-bolt mr-2"></i> Ajouter au panier (AJAX)
                    </button>
                <?php else: ?>
                    <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded font-semibold cursor-not-allowed mb-6">
                        <i class="fas fa-ban mr-2"></i> Produit indisponible
                    </button>
                <?php endif; ?>
                
                <!-- Fonctionnalités supplémentaires -->
                <div class="border-t pt-4">
                    <p class="mb-2">
                        <i class="fas fa-truck mr-2 text-gray-600"></i> Livraison gratuite à partir de 50€
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-undo mr-2 text-gray-600"></i> Retours gratuits sous 30 jours
                    </p>
                    <p>
                        <i class="fas fa-lock mr-2 text-gray-600"></i> Paiement sécurisé
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produits similaires -->
<section class="mb-12">
    <h2 class="text-2xl font-bold mb-6">Produits similaires</h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($similarProducts as $similar): ?>
            <?php if ($similar['id'] !== $product['id']): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden product-card transition-all">
                    <a href="product.php?id=<?php echo $similar['id']; ?>">
                        <img src="assets/images/products/<?php echo $similar['image']; ?>" alt="<?php echo $similar['nom']; ?>" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2"><?php echo $similar['nom']; ?></h3>
                        <p class="text-gray-600 mb-2"><?php echo $similar['categorie_nom']; ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-bold"><?php echo formatPrice($similar['prix']); ?></span>
                            <a href="product.php?id=<?php echo $similar['id']; ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- Fichier: views/luxury-product.php -->
<?php
/**
 * Vue de luxe pour la page détail produit
 */
?>

<!-- Fil d'Ariane -->
<div class="container mx-auto px-4 py-6">
    <nav class="text-sm text-gray-400">
        <ol class="flex flex-wrap">
            <li class="mr-2">
                <a href="index.php" class="hover:text-gold">Accueil</a>
                <span class="mx-1">/</span>
            </li>
            <li class="mr-2">
                <a href="shop.php" class="hover:text-gold">Collections</a>
                <span class="mx-1">/</span>
            </li>
            <li class="mr-2">
                <a href="shop.php?category=<?php echo $product['categorie_id']; ?>" class="hover:text-gold">
                    <?php echo $product['categorie_nom']; ?>
                </a>
                <span class="mx-1">/</span>
            </li>
            <li class="font-medium text-gold"><?php echo $product['nom']; ?></li>
        </ol>
    </nav>
</div>

<!-- Détail du produit -->
<section class="container mx-auto px-4 py-12">
    <div class="luxury-product-detail">
        <!-- Galerie d'images -->
        <div class="luxury-product-gallery slide-left-element animate-on-scroll">
            <img src="assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" class="luxury-main-image" id="main-product-image">
            
            <!-- Miniatures (si disponibles) -->
            <div class="thumbnails mt-4">
                <img src="assets/images/products/<?php echo $product['image']; ?>" alt="Vue principale" class="thumbnail active" onclick="changeMainImage(this)">
                <!-- Simulations d'autres angles (à remplacer par de vraies images) -->
                <img src="assets/images/products/placeholder-1.jpg" alt="Vue latérale" class="thumbnail" onclick="changeMainImage(this)">
                <img src="assets/images/products/placeholder-2.jpg" alt="Vue arrière" class="thumbnail" onclick="changeMainImage(this)">
                <img src="assets/images/products/placeholder-3.jpg" alt="Vue détaillée" class="thumbnail" onclick="changeMainImage(this)">
            </div>
        </div>
        
        <!-- Informations produit -->
        <div class="luxury-product-info-detail slide-right-element animate-on-scroll">
            <h1 class="luxury-product-title"><?php echo $product['nom']; ?></h1>
            <p class="luxury-product-subtitle"><?php echo $product['categorie_nom']; ?></p>
            
            <div class="luxury-price-tag"><?php echo formatPrice($product['prix']); ?></div>
            
            <div class="luxury-product-description">
                <?php echo $product['description']; ?>
                
                <!-- Caractéristiques supplémentaires (à adapter selon le produit) -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-3">Caractéristiques</h3>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Boîtier en <?php echo (strpos($product['prix'], '1') === 0) ? 'or 18 carats' : 'acier inoxydable'; ?></li>
                        <li>Verre saphir résistant aux rayures</li>
                        <li>Mouvement <?php echo (strpos($product['prix'], '2') === 0) ? 'automatique suisse' : 'à quartz haute précision'; ?></li>
                        <li>Étanchéité jusqu'à <?php echo (strpos($product['categorie_nom'], 'Sport') !== false) ? '200' : '50'; ?> mètres</li>
                        <li>Bracelet en <?php echo (strpos($product['prix'], '3') === 0) ? 'cuir italien' : 'acier inoxydable'; ?></li>
                    </ul>
                </div>
            </div>
            
            <!-- Sélection de la taille (si applicable) -->
            <div class="mt-6">
                <label class="block text-sm uppercase tracking-wide mb-2">Diamètre du boîtier</label>
                <div class="flex space-x-3 mb-6">
                    <button class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:border-gold hover:text-gold focus:outline-none focus:border-gold focus:text-gold">36</button>
                    <button class="w-10 h-10 rounded-full border border-gold bg-gold text-deep-black flex items-center justify-center">40</button>
                    <button class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:border-gold hover:text-gold focus:outline-none focus:border-gold focus:text-gold">42</button>
                </div>
            </div>
            
            <!-- Formulaire d'ajout au panier -->
            <?php if ($product['stock'] > 0): ?>
                <form action="product.php?id=<?php echo $product['id']; ?>" method="post" id="add-to-cart-form">
                    <div class="quantity-control mb-6">
                        <label class="quantity-label">Quantité</label>
                        <div class="quantity-input">
                            <button type="button" class="quantity-minus" aria-label="Réduire la quantité">−</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                            <button type="button" class="quantity-plus" aria-label="Augmenter la quantité">+</button>
                        </div>
                    </div>
                    
                    <div class="flex flex-col space-y-4">
                        <button type="submit" name="add_to_cart" class="luxury-add-to-cart">
                            Ajouter au panier
                        </button>
                        
                        <button type="button" id="add-to-cart-ajax" data-product-id="<?php echo $product['id']; ?>" class="bg-deep-black border border-gold text-gold hover:bg-gold hover:text-deep-black transition-colors py-3 uppercase text-sm tracking-wider">
                            Achat immédiat
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="mt-4">
                    <button disabled class="w-full py-3 bg-gray-300 text-gray-600 uppercase text-sm tracking-wider cursor-not-allowed">
                        Produit épuisé
                    </button>
                    <p class="mt-2 text-sm text-gray-600">Ce produit est actuellement indisponible. Veuillez nous contacter pour connaître la date de réapprovisionnement.</p>
                </div>
            <?php endif; ?>
            
            <!-- Avantages client -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="flex items-center mb-4">
                    <i class="fas fa-shipping-fast text-gold mr-3"></i>
                    <span>Livraison offerte dans le monde entier</span>
                </div>
                <div class="flex items-center mb-4">
                    <i class="fas fa-shield-alt text-gold mr-3"></i>
                    <span>Garantie internationale de 5 ans</span>
                </div>
                <div class="flex items-center mb-4">
                    <i class="fas fa-sync-alt text-gold mr-3"></i>
                    <span>Retours gratuits sous 30 jours</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-gem text-gold mr-3"></i>
                    <span>Certificat d'authenticité inclus</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Détails techniques -->
<section class="py-16 bg-cream">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold mb-8 text-center">Détails techniques</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gold">Mouvement</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex">
                            <span class="w-1/2 font-medium">Type</span>
                            <span class="w-1/2"><?php echo (strpos($product['prix'], '2') === 0) ? 'Automatique' : 'Quartz'; ?></span>
                        </li>
                        <li class="flex">
                            <span class="w-1/2 font-medium">Origine</span>
                            <span class="w-1/2">Suisse</span>
                        </li>
                        <li class="flex">
                            <span class="w-1/2 font-medium">Précision</span>
                            <span class="w-1/2">+/- 2 secondes par jour</span>
                        </li>
                        <li class="flex">
                            <span class="w-1/2 font-medium">Réserve de marche</span>
                            <span class="w-1/2"><?php echo (strpos($product['prix'], '2') === 0) ? '48 heures' : 'N/A'; ?></span>
                        </li>
                        <li class="flex">
                            <span class="w-1/2 font-medium">Fonctions</span>
                            <span class="w-1/2">Heures, minutes, secondes<?php echo (strpos($product['prix'], '3') === 0) ? ', date' : ''; ?></span>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gold">Boîtier et Bracelet</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex">
                            <span class="w-1/2 font-medium">Matériau</span>
                            <span class="w-1/2"><?php echo (strpos($product['prix'], '1') === 0) ? 'Or 18 carats' : 'Acier inoxydable 316L'; ?></span>
                        </li>
                        <li class="flex">
                            <span class="w-1/2 font-medium">Diamètre</span>
                            <span class="w-1/2">40mm</span>
                        </li>
                        <li class="flex">
                            <span class="w-1/2 font-medium">Épaisseur</span>
                            <span class="w-1/2"><?php echo (strpos($product['prix'], '2') === 0) ? '11mm' : '8mm'; ?></span>
                        </li>
                        <li class="flex">
                            <span class="w-1/2 font-medium">Verre</span>
                            <span class="w-1/2">Saphir avec traitement anti-reflet</span>
                        </li>
                        <li class="flex">
                            <span class="w-1/2 font-medium">Bracelet</span>
                            <span class="w-1/2"><?php echo (strpos($product['prix'], '3') === 0) ? 'Cuir italien' : 'Acier inoxydable'; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produits similaires -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="section-title text-3xl mb-12">Vous aimerez aussi</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php foreach ($similarProducts as $similar): ?>
                <?php if ($similar['id'] !== $product['id']): ?>
                    <div class="luxury-product-card fade-in-element animate-on-scroll">
                        <div class="luxury-product-image">
                            <a href="product.php?id=<?php echo $similar['id']; ?>">
                                <img src="assets/images/products/<?php echo $similar['image']; ?>" alt="<?php echo $similar['nom']; ?>">
                            </a>
                        </div>
                        
                        <div class="luxury-product-info">
                            <h3 class="luxury-product-name"><?php echo $similar['nom']; ?></h3>
                            <p class="luxury-product-category"><?php echo $similar['categorie_nom']; ?></p>
                            <div class="luxury-product-price"><?php echo formatPrice($similar['prix']); ?></div>
                            <a href="product.php?id=<?php echo $similar['id']; ?>" class="luxury-product-button">Découvrir</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Consultation personnalisée -->
<section class="py-16 bg-deep-black text-white text-center">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl mb-4 text-gold">Consultation Personnalisée</h2>
        <p class="max-w-2xl mx-auto mb-8">Nos experts horlogers sont à votre disposition pour vous conseiller dans le choix de votre garde-temps d'exception ou pour toute demande personnalisée.</p>
        <a href="#" class="border border-gold text-gold hover:bg-gold hover:text-deep-black px-8 py-3 uppercase tracking-wider inline-block transition-colors">Prendre rendez-vous</a>
    </div>
</section>

<script>
function changeMainImage(thumbnail) {
    // Mettre à jour l'image principale
    document.getElementById('main-product-image').src = thumbnail.src;
    
    // Mettre à jour la classe active
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    thumbnail.classList.add('active');
}
</script>

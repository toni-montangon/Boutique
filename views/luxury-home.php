<!-- Fichier: views/luxury-home.php -->
<?php
/**
 * Vue de luxe pour la page d'accueil
 */
?>

<!-- Bannière de luxe -->
<section class="luxury-banner" style="background-image: url('assets/images/luxury-banner.jpg');">
    <div class="banner-content slide-in-left">
        <h1>L'Art de l'Horlogerie<br>d'Exception</h1>
        <p>Découvrez notre collection de garde-temps d'exception, alliant tradition horlogère, matériaux précieux et innovation technique.</p>
        <a href="shop.php" class="gold-button">Explorer nos collections</a>
    </div>
</section>

<!-- Notre engagement -->
<section class="py-20 bg-off-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="text-center fade-in-element animate-on-scroll">
                <div class="inline-block p-4 mb-4">
                    <i class="fas fa-gem text-4xl text-gold"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Matériaux d'Exception</h3>
                <p class="text-gray-600">Nous sélectionnons uniquement les meilleurs matériaux — or, platine, titane, saphir et diamants — pour créer des montres d'une beauté intemporelle.</p>
            </div>
            
            <div class="text-center fade-in-element animate-on-scroll">
                <div class="inline-block p-4 mb-4">
                    <i class="fas fa-cog text-4xl text-gold"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Savoir-Faire Artisanal</h3>
                <p class="text-gray-600">Chaque garde-temps est assemblé à la main par nos maîtres horlogers, héritiers d'un savoir-faire centenaire perpétué avec passion.</p>
            </div>
            
            <div class="text-center fade-in-element animate-on-scroll">
                <div class="inline-block p-4 mb-4">
                    <i class="fas fa-certificate text-4xl text-gold"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Certification Officielle</h3>
                <p class="text-gray-600">Toutes nos montres sont accompagnées d'un certificat d'authenticité et bénéficient d'une garantie internationale de 5 ans.</p>
            </div>
        </div>
    </div>
</section>

<!-- Collections en vedette -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <h2 class="section-title text-3xl mb-12">Collections en Vedette</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="luxury-product-card slide-in-right animate-on-scroll">
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
        
        <div class="text-center mt-12">
            <a href="shop.php" class="gold-button">Voir toutes nos collections</a>
        </div>
    </div>
</section>

<!-- Histoire et savoir-faire -->
<section class="py-20 bg-deep-black text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="slide-left-element animate-on-scroll">
                <h2 class="text-3xl mb-6 font-serif text-gold">Notre Histoire</h2>
                <p class="mb-4">Fondée en 1987 par Henri Dupont, maître horloger de renom, la maison Chronos Prestige perpétue l'excellence des traditions horlogères suisses tout en intégrant les innovations techniques contemporaines.</p>
                <p class="mb-6">Chaque montre est le fruit d'une passion transmise de génération en génération, alliant la précision d'un savoir-faire artisanal à des matériaux d'exception soigneusement sélectionnés pour créer des garde-temps d'une élégance intemporelle.</p>
                <a href="#" class="inline-block border border-gold text-gold px-6 py-3 transition hover:bg-gold hover:text-deep-black uppercase tracking-wider text-sm">Découvrir notre histoire</a>
            </div>
            
            <div class="slide-right-element animate-on-scroll">
                <img src="assets/images/watchmaker.jpg" alt="Maître horloger" class="w-full rounded shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Dernières nouveautés -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <h2 class="section-title text-3xl mb-12">Dernières Créations</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($newProducts as $product): ?>
                <div class="luxury-product-card fade-in-element animate-on-scroll">
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
    </div>
</section>

<!-- Témoignages clients -->
<section class="py-20 bg-cream">
    <div class="container mx-auto px-4">
        <h2 class="section-title text-3xl mb-12">Ils nous font confiance</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 shadow-sm fade-in-element animate-on-scroll">
                <div class="flex items-center mb-4">
                    <div class="text-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="italic mb-6">"Une montre d'exception qui m'accompagne chaque jour. La précision du mouvement et la finition sont tout simplement exceptionnelles."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-gray-300 mr-4"></div>
                    <div>
                        <h4 class="font-semibold">Jean-Pierre Moreau</h4>
                        <p class="text-sm text-gray-600">Paris, France</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-8 shadow-sm fade-in-element animate-on-scroll">
                <div class="flex items-center mb-4">
                    <div class="text-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="italic mb-6">"Un service client impeccable et une montre qui dépasse mes attentes. L'élégance de ma Chronos Prestige suscite l'admiration partout où je vais."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-gray-300 mr-4"></div>
                    <div>
                        <h4 class="font-semibold">Sophie Leroy</h4>
                        <p class="text-sm text-gray-600">Genève, Suisse</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-8 shadow-sm fade-in-element animate-on-scroll">
                <div class="flex items-center mb-4">
                    <div class="text-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="italic mb-6">"Un investissement qui en vaut la peine. Ma Chronos Prestige est non seulement belle mais aussi incroyablement précise et fiable après plusieurs années."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-gray-300 mr-4"></div>
                    <div>
                        <h4 class="font-semibold">Michel Dubois</h4>
                        <p class="text-sm text-gray-600">Bruxelles, Belgique</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-16 bg-deep-black text-white text-center">
    <div class="container mx-auto px-4 max-w-3xl">
        <h2 class="text-3xl mb-4">Rejoignez notre cercle exclusif</h2>
        <p class="mb-8">Recevez en avant-première nos nouvelles collections, invitations aux événements privés et conseils d'entretien personnalisés.</p>
        
        <form class="flex flex-col md:flex-row gap-4">
            <input type="email" placeholder="Votre adresse email" class="flex-grow px-4 py-3 bg-transparent border border-gray-700 focus:border-gold focus:outline-none">
            <button type="submit" class="gold-button">S'inscrire</button>
        </form>
        
        <p class="mt-4 text-sm text-gray-400">En vous inscrivant, vous acceptez de recevoir nos communications et confirmez avoir pris connaissance de notre politique de confidentialité.</p>
    </div>
</section>

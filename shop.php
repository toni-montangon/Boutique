<?php
/**
 * Page boutique
 * Fichier: shop.php
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Récupérer les catégories pour le filtrage
$categories = getCategories();

// Initialiser les variables de filtrage
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$searchQuery = isset($_GET['search']) ? clean($_GET['search']) : null;

// Récupérer les produits selon les filtres
if ($searchQuery) {
    $products = searchProducts($searchQuery);
    $pageTitle = 'Résultats de recherche pour "' . $searchQuery . '"';
} elseif ($categoryId) {
    $products = getProducts(null, $categoryId);
    $category = getCategoryById($categoryId);
    $pageTitle = 'Catégorie : ' . $category['nom'];
} else {
    $products = getProducts();
    $pageTitle = 'Tous nos produits';
}

// Inclusion de l'en-tête
include 'views/luxury-header.php';
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
                        <a href="shop.php" class="block p-2 <?php echo !$categoryId ? 'bg-[#e8dcc1] text-[#0c4633] rounded' : 'hover:bg-gray-100 rounded'; ?>">
                            Toutes les catégories
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="mb-2">
                            <a href="shop.php?category=<?php echo $cat['id']; ?>" class="block p-2 <?php echo $categoryId == $cat['id'] ? 'bg-[#e8dcc1] text-[#0c4633] rounded' : 'hover:bg-gray-100 rounded'; ?>">
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
                    <button type="submit" class="w-full bg-[#0c4633] text-white px-4 py-2 rounded hover:bg-[#083a2a]">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                </form>
            </div>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($products as $product): ?>
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
    </div>
</section>

<!-- JavaScript pour la recherche et le filtrage -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Code JavaScript pour la recherche dynamique (sera implémenté plus tard)
});
</script>

<?php
// Inclusion du pied de page
include 'views/luxury-footer.php';
?>
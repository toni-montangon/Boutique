<?php
/**
 * Fichier: admin/dashboard.php
 * Description: Tableau de bord pour l'interface d'administration
 */

// Inclusion des fichiers nécessaires
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Vérifier les droits d'accès
requireAdmin();

// Statistiques pour le tableau de bord
$stats = [
    'products' => count(fetchAll("SELECT id FROM products")),
    'categories' => count(fetchAll("SELECT id FROM categories")),
    'users' => count(fetchAll("SELECT id FROM users")),
    'orders' => count(fetchAll("SELECT id FROM orders"))
];

// Récupérer les dernières commandes
$latestOrders = fetchAll("SELECT o.*, u.nom as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.date DESC LIMIT 5");

// Récupérer les produits les plus vendus
$topProducts = fetchAll("
    SELECT p.*, COUNT(od.id) as order_count, SUM(od.quantite) as total_quantity
    FROM products p
    JOIN order_details od ON p.id = od.product_id
    GROUP BY p.id
    ORDER BY total_quantity DESC
    LIMIT 5
");

// Récupérer les revenus mensuels
$monthlyRevenue = fetchAll("
    SELECT 
        MONTH(o.date) as month,
        YEAR(o.date) as year,
        SUM(o.total) as revenue
    FROM orders o
    WHERE o.statut != 'annulee'
    GROUP BY YEAR(o.date), MONTH(o.date)
    ORDER BY YEAR(o.date) DESC, MONTH(o.date) DESC
    LIMIT 6
");

// Inclusion de l'en-tête admin
include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Tableau de bord</h1>
    
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Produits -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Produits</h3>
                    <p class="text-2xl font-semibold"><?php echo $stats['products']; ?></p>
                </div>
            </div>
            <div class="mt-4">
                <a href="products.php" class="text-blue-600 hover:underline text-sm">
                    Gérer les produits <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <!-- Catégories -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-tags text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Catégories</h3>
                    <p class="text-2xl font-semibold"><?php echo $stats['categories']; ?></p>
                </div>
            </div>
            <div class="mt-4">
                <a href="categories.php" class="text-green-600 hover:underline text-sm">
                    Gérer les catégories <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <!-- Utilisateurs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Utilisateurs</h3>
                    <p class="text-2xl font-semibold"><?php echo $stats['users']; ?></p>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-purple-600 hover:underline text-sm">
                    Voir les utilisateurs <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <!-- Commandes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Commandes</h3>
                    <p class="text-2xl font-semibold"><?php echo $stats['orders']; ?></p>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-orange-600 hover:underline text-sm">
                    Voir les commandes <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Dernières commandes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="p-6 bg-gray-50 border-b">
            <h2 class="text-xl font-semibold">Dernières commandes</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Client</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($latestOrders)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center">Aucune commande trouvée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($latestOrders as $order): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4">#<?php echo $order['id']; ?></td>
                                <td class="px-6 py-4"><?php echo $order['user_name']; ?></td>
                                <td class="px-6 py-4"><?php echo date('d/m/Y H:i', strtotime($order['date'])); ?></td>
                                <td class="px-6 py-4"><?php echo formatPrice($order['total']); ?></td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    
                                    switch ($order['statut']) {
                                        case 'en_attente':
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'En attente';
                                            break;
                                        case 'validee':
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                            $statusText = 'Validée';
                                            break;
                                        case 'expediee':
                                            $statusClass = 'bg-purple-100 text-purple-800';
                                            $statusText = 'Expédiée';
                                            break;
                                        case 'livree':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Livrée';
                                            break;
                                        case 'annulee':
                                            $statusClass = 'bg-red-100 text-red-800';
                                            $statusText = 'Annulée';
                                            break;
                                        default:
                                            $statusText = ucfirst($order['statut']);
                                    }
                                    ?>
                                    <span class="inline-block px-2 py-1 rounded text-xs <?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 mx-1" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-green-600 hover:text-green-800 mx-1" title="Éditer">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-gray-50 border-t text-right">
            <a href="#" class="text-blue-600 hover:underline">
                Voir toutes les commandes <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Produits les plus vendus -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="p-6 bg-gray-50 border-b">
            <h2 class="text-xl font-semibold">Produits les plus vendus</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left">Produit</th>
                        <th class="px-6 py-3 text-left">Prix</th>
                        <th class="px-6 py-3 text-left">Commandes</th>
                        <th class="px-6 py-3 text-left">Quantité vendue</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($topProducts)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center">Aucun produit vendu.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($topProducts as $product): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="../assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" class="w-10 h-10 object-cover mr-3">
                                        <?php echo $product['nom']; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4"><?php echo formatPrice($product['prix']); ?></td>
                                <td class="px-6 py-4"><?php echo $product['order_count']; ?></td>
                                <td class="px-6 py-4"><?php echo $product['total_quantity']; ?></td>
                                <td class="px-6 py-4 text-center">
                                    <a href="products.php?action=edit&id=<?php echo $product['id']; ?>" class="text-blue-600 hover:text-blue-800 mx-1" title="Éditer">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="../product.php?id=<?php echo $product['id']; ?>" target="_blank" class="text-green-600 hover:text-green-800 mx-1" title="Voir en boutique">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-gray-50 border-t text-right">
            <a href="products.php" class="text-blue-600 hover:underline">
                Gérer tous les produits <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Revenus mensuels -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 bg-gray-50 border-b">
            <h2 class="text-xl font-semibold">Revenus mensuels</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php if (empty($monthlyRevenue)): ?>
                    <div class="col-span-3 text-center py-4">Aucune donnée de revenus disponible.</div>
                <?php else: ?>
                    <?php foreach ($monthlyRevenue as $revenue): ?>
                        <div class="bg-gray-50 rounded p-4 text-center">
                            <h3 class="text-lg font-semibold">
                                <?php 
                                setlocale(LC_TIME, 'fr_FR.utf8');
                                echo strftime('%B %Y', mktime(0, 0, 0, $revenue['month'], 1, $revenue['year'])); 
                                ?>
                            </h3>
                            <p class="text-2xl font-bold text-green-600 mt-2"><?php echo formatPrice($revenue['revenue']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Inclusion du pied de page
include 'footer.php';
?>

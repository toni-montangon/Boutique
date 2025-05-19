<?php
/**
 * Gestion des produits
 * Fichier: admin/products.php
 */

// Inclusion des fichiers nécessaires
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Vérifier les droits d'accès
requireAdmin();

// Récupérer toutes les catégories
$categories = getCategories();

// Action par défaut
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// ID du produit pour les actions d'édition ou de suppression
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer le produit pour l'édition
$product = [];
if ($action === 'edit' && $productId > 0) {
    $product = getProductById($productId);
    
    // Rediriger si le produit n'existe pas
    if (!$product) {
        $_SESSION['error_message'] = "Le produit demandé n'existe pas.";
        redirect('products.php');
    }
}

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajouter un produit
    if (isset($_POST['add_product'])) {
        $nom = clean($_POST['nom']);
        $description = clean($_POST['description']);
        $prix = (float)$_POST['prix'];
        $categorieId = (int)$_POST['categorie_id'];
        $stock = (int)$_POST['stock'];
        $enVedette = isset($_POST['en_vedette']) ? 1 : 0;
        
        // Gestion de l'image
        $image = 'default.jpg'; // Image par défaut
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../assets/images/products/';
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Extensions autorisées
            $extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($file_ext, $extensions)) {
                // Générer un nom unique pour éviter les conflits
                $new_file_name = uniqid() . '.' . $file_ext;
                $file_path = $upload_dir . $new_file_name;
                
                // Déplacer le fichier uploadé
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $image = $new_file_name;
                }
            }
        }
        
        // Insérer le produit dans la base de données
        $sql = "INSERT INTO products (nom, description, prix, image, stock, categorie_id, en_vedette) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        executeQuery($sql, [$nom, $description, $prix, $image, $stock, $categorieId, $enVedette]);
        
        // Message de succès
        $_SESSION['success_message'] = "Le produit a été ajouté avec succès.";
        
        // Rediriger
        redirect('products.php');
    }
    
    // Mettre à jour un produit
    if (isset($_POST['update_product'])) {
        $nom = clean($_POST['nom']);
        $description = clean($_POST['description']);
        $prix = (float)$_POST['prix'];
        $categorieId = (int)$_POST['categorie_id'];
        $stock = (int)$_POST['stock'];
        $enVedette = isset($_POST['en_vedette']) ? 1 : 0;
        
        // Préparer la requête de mise à jour
        $params = [$nom, $description, $prix, $stock, $categorieId, $enVedette];
        $sql = "UPDATE products 
                SET nom = ?, description = ?, prix = ?, stock = ?, categorie_id = ?, en_vedette = ?";
        
        // Gestion de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../assets/images/products/';
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Extensions autorisées
            $extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($file_ext, $extensions)) {
                // Générer un nom unique pour éviter les conflits
                $new_file_name = uniqid() . '.' . $file_ext;
                $file_path = $upload_dir . $new_file_name;
                
                // Déplacer le fichier uploadé
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Supprimer l'ancienne image (sauf si c'est l'image par défaut)
                    if ($product['image'] !== 'default.jpg' && file_exists($upload_dir . $product['image'])) {
                        unlink($upload_dir . $product['image']);
                    }
                    
                    // Ajouter l'image à la requête
                    $sql .= ", image = ?";
                    $params[] = $new_file_name;
                }
            }
        }
        
        // Finaliser la requête
        $sql .= " WHERE id = ?";
        $params[] = $productId;
        
        // Exécuter la requête
        executeQuery($sql, $params);
        
        // Message de succès
        $_SESSION['success_message'] = "Le produit a été mis à jour avec succès.";
        
        // Rediriger
        redirect('products.php');
    }
    
    // Supprimer un produit
    if (isset($_POST['delete_product'])) {
        $productIdToDelete = (int)$_POST['product_id'];
        
        // Récupérer l'image du produit
        $productToDelete = getProductById($productIdToDelete);
        
        if ($productToDelete) {
            // Supprimer l'image (sauf si c'est l'image par défaut)
            if ($productToDelete['image'] !== 'default.jpg') {
                $image_path = '../assets/images/products/' . $productToDelete['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            
            // Supprimer le produit de la base de données
            $sql = "DELETE FROM products WHERE id = ?";
            executeQuery($sql, [$productIdToDelete]);
            
            // Message de succès
            $_SESSION['success_message'] = "Le produit a été supprimé avec succès.";
        } else {
            // Message d'erreur
            $_SESSION['error_message'] = "Le produit demandé n'existe pas.";
        }
        
        // Rediriger
        redirect('products.php');
    }
}

// Récupérer tous les produits pour la liste
$products = [];
if ($action === 'list') {
    $products = getProducts();
}

// Inclusion de l'en-tête admin
include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <?php if ($action === 'list'): ?>
        <!-- Liste des produits -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Gestion des produits</h1>
            <a href="products.php?action=add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Ajouter un produit
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Image</th>
                            <th class="px-6 py-3 text-left">Nom</th>
                            <th class="px-6 py-3 text-left">Prix</th>
                            <th class="px-6 py-3 text-left">Catégorie</th>
                            <th class="px-6 py-3 text-left">Stock</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center">Aucun produit trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $item): ?>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo $item['id']; ?></td>
                                    <td class="px-6 py-4">
                                        <img src="../assets/images/products/<?php echo $item['image']; ?>" alt="<?php echo $item['nom']; ?>" class="w-16 h-16 object-cover">
                                    </td>
                                    <td class="px-6 py-4"><?php echo $item['nom']; ?></td>
                                    <td class="px-6 py-4"><?php echo formatPrice($item['prix']); ?></td>
                                    <td class="px-6 py-4"><?php echo $item['categorie_nom']; ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($item['stock'] > 0): ?>
                                            <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                                <?php echo $item['stock']; ?> en stock
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-block bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                                Rupture de stock
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="products.php?action=edit&id=<?php echo $item['id']; ?>" class="text-blue-600 hover:text-blue-800 mx-1" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="../product.php?id=<?php echo $item['id']; ?>" target="_blank" class="text-green-600 hover:text-green-800 mx-1" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="text-red-600 hover:text-red-800 mx-1" title="Supprimer" 
                                                onclick="confirmDelete(<?php echo $item['id']; ?>, '<?php echo addslashes($item['nom']); ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Formulaire de suppression (caché) -->
        <form id="deleteForm" action="products.php" method="post" class="hidden">
            <input type="hidden" name="product_id" id="productIdToDelete">
            <input type="hidden" name="delete_product" value="1">
        </form>
        
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
        <!-- Formulaire d'ajout/édition de produit -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">
                <?php echo $action === 'add' ? 'Ajouter un produit' : 'Modifier le produit'; ?>
            </h1>
            <a href="products.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="products.php<?php echo $action === 'edit' ? '?action=edit&id=' . $productId : ''; ?>" 
                  method="post" enctype="multipart/form-data" class="p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom du produit -->
                    <div>
                        <label for="nom" class="block text-gray-700 font-medium mb-2">Nom du produit *</label>
                        <input type="text" name="nom" id="nom" class="w-full px-4 py-2 border rounded" 
                               value="<?php echo $action === 'edit' ? $product['nom'] : ''; ?>" required>
                    </div>
                    
                    <!-- Prix -->
                    <div>
                        <label for="prix" class="block text-gray-700 font-medium mb-2">Prix (€) *</label>
                        <input type="number" step="0.01" min="0" name="prix" id="prix" class="w-full px-4 py-2 border rounded" 
                               value="<?php echo $action === 'edit' ? $product['prix'] : ''; ?>" required>
                    </div>
                    
                    <!-- Catégorie -->
                    <div>
                        <label for="categorie_id" class="block text-gray-700 font-medium mb-2">Catégorie *</label>
                        <select name="categorie_id" id="categorie_id" class="w-full px-4 py-2 border rounded" required>
                            <option value="">Sélectionner une catégorie</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo $action === 'edit' && $product['categorie_id'] == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo $category['nom']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-gray-700 font-medium mb-2">Stock *</label>
                        <input type="number" min="0" name="stock" id="stock" class="w-full px-4 py-2 border rounded" 
                               value="<?php echo $action === 'edit' ? $product['stock'] : '0'; ?>" required>
                    </div>
                    
                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-gray-700 font-medium mb-2">Image</label>
                        <?php if ($action === 'edit' && $product['image']): ?>
                            <div class="mb-2">
                                <img src="../assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" 
                                     class="w-32 h-32 object-cover border rounded">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" id="image" class="w-full px-4 py-2 border rounded">
                        <p class="text-gray-600 text-sm mt-1">Formats acceptés: JPG, JPEG, PNG, GIF. Laissez vide pour conserver l'image actuelle.</p>
                    </div>
                    
                    <!-- En vedette -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="en_vedette" value="1" class="mr-2" 
                                   <?php echo $action === 'edit' && $product['en_vedette'] ? 'checked' : ''; ?>>
                            <span class="text-gray-700">Produit en vedette</span>
                        </label>
                        <p class="text-gray-600 text-sm mt-1">Les produits en vedette sont affichés sur la page d'accueil.</p>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-gray-700 font-medium mb-2">Description *</label>
                    <textarea name="description" id="description" rows="6" class="w-full px-4 py-2 border rounded" required><?php echo $action === 'edit' ? $product['description'] : ''; ?></textarea>
                </div>
                
                <!-- Boutons -->
                <div class="mt-6 flex justify-end">
                    <a href="products.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 mr-2">
                        Annuler
                    </a>
                    <button type="submit" name="<?php echo $action === 'add' ? 'add_product' : 'update_product'; ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <?php echo $action === 'add' ? 'Ajouter' : 'Mettre à jour'; ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript pour la confirmation de suppression -->
<script>
function confirmDelete(productId, productName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le produit "${productName}" ?`)) {
        document.getElementById('productIdToDelete').value = productId;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php
// Inclusion du pied de page
include 'footer.php';
?>

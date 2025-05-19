<?php
/**
 * Gestion des catégories
 * Fichier: admin/categories.php
 */

// Inclusion des fichiers nécessaires
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Vérifier les droits d'accès
requireAdmin();

// Action par défaut
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// ID de la catégorie pour les actions d'édition ou de suppression
$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer la catégorie pour l'édition
$category = [];
if ($action === 'edit' && $categoryId > 0) {
    $category = getCategoryById($categoryId);
    
    // Rediriger si la catégorie n'existe pas
    if (!$category) {
        $_SESSION['error_message'] = "La catégorie demandée n'existe pas.";
        redirect('categories.php');
    }
}

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajouter une catégorie
    if (isset($_POST['add_category'])) {
        $nom = clean($_POST['nom']);
        $description = clean($_POST['description']);
        
        // Insérer la catégorie dans la base de données
        $sql = "INSERT INTO categories (nom, description) VALUES (?, ?)";
        executeQuery($sql, [$nom, $description]);
        
        // Message de succès
        $_SESSION['success_message'] = "La catégorie a été ajoutée avec succès.";
        
        // Rediriger
        redirect('categories.php');
    }
    
    // Mettre à jour une catégorie
    if (isset($_POST['update_category'])) {
        $nom = clean($_POST['nom']);
        $description = clean($_POST['description']);
        
        // Mettre à jour la catégorie dans la base de données
        $sql = "UPDATE categories SET nom = ?, description = ? WHERE id = ?";
        executeQuery($sql, [$nom, $description, $categoryId]);
        
        // Message de succès
        $_SESSION['success_message'] = "La catégorie a été mise à jour avec succès.";
        
        // Rediriger
        redirect('categories.php');
    }
    
    // Supprimer une catégorie
    if (isset($_POST['delete_category'])) {
        $categoryIdToDelete = (int)$_POST['category_id'];
        
        // Vérifier si des produits sont associés à cette catégorie
        $productsCount = count(fetchAll("SELECT id FROM products WHERE categorie_id = ?", [$categoryIdToDelete]));
        
        if ($productsCount > 0) {
            // Message d'erreur si des produits sont associés
            $_SESSION['error_message'] = "Impossible de supprimer cette catégorie car $productsCount produit(s) y sont associés.";
        } else {
            // Supprimer la catégorie de la base de données
            $sql = "DELETE FROM categories WHERE id = ?";
            executeQuery($sql, [$categoryIdToDelete]);
            
            // Message de succès
            $_SESSION['success_message'] = "La catégorie a été supprimée avec succès.";
        }
        
        // Rediriger
        redirect('categories.php');
    }
}

// Récupérer toutes les catégories pour la liste
$categories = [];
if ($action === 'list') {
    $categories = getCategories();
    
    // Ajouter le nombre de produits pour chaque catégorie
    foreach ($categories as &$category) {
        $productsCount = count(fetchAll("SELECT id FROM products WHERE categorie_id = ?", [$category['id']]));
        $category['products_count'] = $productsCount;
    }
}

// Inclusion de l'en-tête admin
include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <?php if ($action === 'list'): ?>
        <!-- Liste des catégories -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Gestion des catégories</h1>
            <a href="categories.php?action=add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Ajouter une catégorie
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Nom</th>
                            <th class="px-6 py-3 text-left">Description</th>
                            <th class="px-6 py-3 text-left">Produits</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center">Aucune catégorie trouvée.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $item): ?>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo $item['id']; ?></td>
                                    <td class="px-6 py-4"><?php echo $item['nom']; ?></td>
                                    <td class="px-6 py-4"><?php echo substr($item['description'], 0, 100) . (strlen($item['description']) > 100 ? '...' : ''); ?></td>
                                    <td class="px-6 py-4">
                                        <a href="../shop.php?category=<?php echo $item['id']; ?>" target="_blank" class="text-blue-600 hover:underline">
                                            <?php echo $item['products_count']; ?> produit(s)
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="categories.php?action=edit&id=<?php echo $item['id']; ?>" class="text-blue-600 hover:text-blue-800 mx-1" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="text-red-600 hover:text-red-800 mx-1" title="Supprimer" 
                                                onclick="confirmDelete(<?php echo $item['id']; ?>, '<?php echo addslashes($item['nom']); ?>', <?php echo $item['products_count']; ?>)">
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
        <form id="deleteForm" action="categories.php" method="post" class="hidden">
            <input type="hidden" name="category_id" id="categoryIdToDelete">
            <input type="hidden" name="delete_category" value="1">
        </form>
        
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
        <!-- Formulaire d'ajout/édition de catégorie -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">
                <?php echo $action === 'add' ? 'Ajouter une catégorie' : 'Modifier la catégorie'; ?>
            </h1>
            <a href="categories.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="categories.php<?php echo $action === 'edit' ? '?action=edit&id=' . $categoryId : ''; ?>" 
                  method="post" class="p-6">
                
                <!-- Nom de la catégorie -->
                <div class="mb-4">
                    <label for="nom" class="block text-gray-700 font-medium mb-2">Nom de la catégorie *</label>
                    <input type="text" name="nom" id="nom" class="w-full px-4 py-2 border rounded" 
                           value="<?php echo $action === 'edit' ? $category['nom'] : ''; ?>" required>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border rounded"><?php echo $action === 'edit' ? $category['description'] : ''; ?></textarea>
                </div>
                
                <!-- Boutons -->
                <div class="flex justify-end">
                    <a href="categories.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 mr-2">
                        Annuler
                    </a>
                    <button type="submit" name="<?php echo $action === 'add' ? 'add_category' : 'update_category'; ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <?php echo $action === 'add' ? 'Ajouter' : 'Mettre à jour'; ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript pour la confirmation de suppression -->
<script>
function confirmDelete(categoryId, categoryName, productsCount) {
    if (productsCount > 0) {
        alert(`Impossible de supprimer la catégorie "${categoryName}" car ${productsCount} produit(s) y sont associés.`);
        return;
    }
    
    if (confirm(`Êtes-vous sûr de vouloir supprimer la catégorie "${categoryName}" ?`)) {
        document.getElementById('categoryIdToDelete').value = categoryId;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php
// Inclusion du pied de page
include 'footer.php';
?>

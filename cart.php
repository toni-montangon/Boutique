<?php
/**
 * Page du panier
 * Fichier: cart.php
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Initialisation des variables
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = calculateCartTotal($cart);

// Traitement des actions du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mise à jour des quantités
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $productId => $quantity) {
            $productId = (int)$productId;
            $quantity = (int)$quantity;
            
            // Vérifier que la quantité est valide
            if ($quantity <= 0) {
                // Supprimer l'article si la quantité est 0 ou négative
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['id'] === $productId) {
                        unset($_SESSION['cart'][$key]);
                        break;
                    }
                }
            } else {
                // Mettre à jour la quantité
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['id'] === $productId) {
                        $_SESSION['cart'][$key]['quantite'] = $quantity;
                        break;
                    }
                }
            }
        }
        
        // Réindexer le tableau
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        
        // Message de succès
        $_SESSION['success_message'] = "Le panier a été mis à jour.";
        
        // Rediriger pour éviter la soumission multiple du formulaire
        redirect("cart.php");
    }
    
    // Vider le panier
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
        
        // Message de succès
        $_SESSION['success_message'] = "Le panier a été vidé.";
        
        // Rediriger pour éviter la soumission multiple du formulaire
        redirect("cart.php");
    }
    
    // Valider la commande
    if (isset($_POST['checkout'])) {
        // Vérifier si l'utilisateur est connecté
        if (!isLoggedIn()) {
            // Stocker la page de redirection après la connexion
            $_SESSION['redirect_after_login'] = 'cart.php';
            
            // Message d'erreur
            $_SESSION['error_message'] = "Vous devez être connecté pour valider votre commande.";
            
            // Rediriger vers la page de connexion
            redirect("login.php");
        }
        
        // Vérifier si le panier est vide
        if (empty($_SESSION['cart'])) {
            // Message d'erreur
            $_SESSION['error_message'] = "Votre panier est vide. Impossible de valider la commande.";
            
            // Rediriger
            redirect("cart.php");
        }
        
        // Créer la commande
        $userId = $_SESSION['user_id'];
        
        // Insérer la commande dans la base de données
        $sql = "INSERT INTO orders (user_id, total, statut) VALUES (?, ?, 'en_attente')";
        executeQuery($sql, [$userId, $total]);
        
        // Récupérer l'ID de la commande
        $orderId = getLastInsertId();
        
        // Insérer les détails de la commande
        foreach ($_SESSION['cart'] as $item) {
            $sql = "INSERT INTO order_details (order_id, product_id, quantite, prix) VALUES (?, ?, ?, ?)";
            executeQuery($sql, [$orderId, $item['id'], $item['quantite'], $item['prix']]);
            
            // Mettre à jour le stock du produit (optionnel)
            // $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
            // executeQuery($sql, [$item['quantite'], $item['id']]);
        }
        
        // Vider le panier
        $_SESSION['cart'] = [];
        
        // Message de succès
        $_SESSION['success_message'] = "Votre commande a été validée avec succès. Numéro de commande : " . $orderId;
        
        // Rediriger vers la page d'accueil
        redirect("index.php");
    }
}

// Inclusion de l'en-tête
include 'views/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mon panier</h1>
    
    <?php if (empty($cart)): ?>
        <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4">
            <p>Votre panier est vide. <a href="shop.php" class="text-blue-600 hover:underline">Continuer vos achats</a></p>
        </div>
    <?php else: ?>
        <form action="cart.php" method="post">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left">Produit</th>
                            <th class="px-6 py-3 text-center">Prix</th>
                            <th class="px-6 py-3 text-center">Quantité</th>
                            <th class="px-6 py-3 text-center">Total</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                            <tr class="border-t">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="assets/images/products/<?php echo $item['image']; ?>" alt="<?php echo $item['nom']; ?>" class="w-16 h-16 object-cover mr-4">
                                        <div>
                                            <h3 class="font-semibold"><?php echo $item['nom']; ?></h3>
                                            <a href="product.php?id=<?php echo $item['id']; ?>" class="text-blue-600 hover:underline text-sm">Voir produit</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center"><?php echo formatPrice($item['prix']); ?></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <button type="button" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded-l" onclick="decrementQuantity(<?php echo $item['id']; ?>)">-</button>
                                        <input type="number" name="quantity[<?php echo $item['id']; ?>]" id="quantity-<?php echo $item['id']; ?>" value="<?php echo $item['quantite']; ?>" min="0" class="w-12 px-2 py-1 text-center border-x">
                                        <button type="button" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded-r" onclick="incrementQuantity(<?php echo $item['id']; ?>)">+</button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold"><?php echo formatPrice($item['prix'] * $item['quantite']); ?></td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" class="text-red-600 hover:text-red-800" onclick="removeItem(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between mb-8">
                <div class="flex space-x-4 mb-4 md:mb-0">
                    <button type="submit" name="update_cart" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-sync-alt mr-2"></i> Mettre à jour le panier
                    </button>
                    <button type="submit" name="clear_cart" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                        <i class="fas fa-trash mr-2"></i> Vider le panier
                    </button>
                </div>
                <a href="shop.php" class="text-blue-600 hover:underline self-center">
                    <i class="fas fa-arrow-left mr-2"></i> Continuer vos achats
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Récapitulatif</h2>
                    
                    <div class="flex justify-between mb-2">
                        <span>Sous-total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Livraison</span>
                        <span>Gratuit</span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg mt-4 pt-4 border-t">
                        <span>Total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                    
                    <button type="submit" name="checkout" class="w-full mt-6 bg-green-600 text-white px-6 py-3 rounded font-semibold hover:bg-green-700">
                        <i class="fas fa-check-circle mr-2"></i> Valider ma commande
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<!-- JavaScript pour la gestion du panier -->
<script>
function decrementQuantity(productId) {
    const input = document.getElementById(`quantity-${productId}`);
    const value = parseInt(input.value, 10);
    if (value > 0) {
        input.value = value - 1;
    }
}

function incrementQuantity(productId) {
    const input = document.getElementById(`quantity-${productId}`);
    const value = parseInt(input.value, 10);
    input.value = value + 1;
}

function removeItem(productId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article du panier ?')) {
        const input = document.getElementById(`quantity-${productId}`);
        input.value = 0;
        document.forms[0].submit();
    }
}
</script>

<?php
// Inclusion du pied de page
include 'views/footer.php';
?>

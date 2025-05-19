<?php
/**
 * Fichier: views/cart.php
 * Description: Vue pour la page panier
 */
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
                                <td class="px-6 py-4 text-center">
                                    <span id="price-<?php echo $item['id']; ?>" data-price="<?php echo $item['prix']; ?>"><?php echo formatPrice($item['prix']); ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <button type="button" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded-l" onclick="decrementQuantity(<?php echo $item['id']; ?>)">-</button>
                                        <input type="number" name="quantity[<?php echo $item['id']; ?>]" id="quantity-<?php echo $item['id']; ?>" value="<?php echo $item['quantite']; ?>" min="0" class="w-12 px-2 py-1 text-center border-x" oninput="updateItemTotal(<?php echo $item['id']; ?>)">
                                        <button type="button" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded-r" onclick="incrementQuantity(<?php echo $item['id']; ?>)">+</button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold">
                                    <span id="total-<?php echo $item['id']; ?>"><?php echo formatPrice($item['prix'] * $item['quantite']); ?></span>
                                </td>
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
                        <span id="subtotal"><?php echo formatPrice($total); ?></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Livraison</span>
                        <span>Gratuit</span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg mt-4 pt-4 border-t">
                        <span>Total</span>
                        <span id="total"><?php echo formatPrice($total); ?></span>
                    </div>
                    
                    <button type="submit" name="checkout" class="w-full mt-6 bg-green-600 text-white px-6 py-3 rounded font-semibold hover:bg-green-700">
                        <i class="fas fa-check-circle mr-2"></i> Valider ma commande
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

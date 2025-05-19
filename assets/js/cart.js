<?php
/**
 * Fichier: assets/js/cart.js
 * Description: Fonctions JavaScript pour la gestion du panier
 */

/**
 * Fonction pour ajouter un produit au panier via AJAX
 * 
 * @param {number} productId ID du produit
 * @param {number} quantity Quantité à ajouter
 */
function addToCartAjax(productId, quantity = 1) {
    // Vérifier que la quantité est valide
    if (quantity <= 0) {
        quantity = 1;
    }
    
    // Créer les données à envoyer
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('add_to_cart_ajax', true);
    
    // Effectuer la requête AJAX
    fetch('add_to_cart_ajax.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'affichage du panier
                updateCartCounter(data.cart_count);
                
                // Afficher un message de succès
                showNotification(data.message, 'success');
            } else {
                // Afficher un message d'erreur
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout au panier:', error);
            showNotification('Une erreur est survenue. Veuillez réessayer.', 'error');
        });
}

/**
 * Mettre à jour le compteur d'articles dans le panier
 * 
 * @param {number} count Nombre d'articles
 */
function updateCartCounter(count) {
    const counter = document.querySelector('.cart-counter');
    
    if (!counter) return;
    
    if (count > 0) {
        counter.textContent = count;
        counter.classList.remove('hidden');
    } else {
        counter.textContent = '0';
        counter.classList.add('hidden');
    }
}

/**
 * Diminuer la quantité d'un produit dans le panier
 * 
 * @param {number} productId ID du produit
 */
function decrementQuantity(productId) {
    const input = document.getElementById(`quantity-${productId}`);
    
    if (!input) return;
    
    const value = parseInt(input.value, 10);
    if (value > 0) {
        input.value = value - 1;
        updateItemTotal(productId);
    }
}

/**
 * Augmenter la quantité d'un produit dans le panier
 * 
 * @param {number} productId ID du produit
 * @param {number} max Quantité maximum autorisée
 */
function incrementQuantity(productId, max = 100) {
    const input = document.getElementById(`quantity-${productId}`);
    
    if (!input) return;
    
    const value = parseInt(input.value, 10);
    if (value < max) {
        input.value = value + 1;
        updateItemTotal(productId);
    }
}

/**
 * Mettre à jour le total d'un produit dans le panier
 * 
 * @param {number} productId ID du produit
 */
function updateItemTotal(productId) {
    const quantityInput = document.getElementById(`quantity-${productId}`);
    const priceEl = document.getElementById(`price-${productId}`);
    const totalEl = document.getElementById(`total-${productId}`);
    
    if (!quantityInput || !priceEl || !totalEl) return;
    
    const quantity = parseInt(quantityInput.value, 10);
    const price = parseFloat(priceEl.dataset.price);
    const total = quantity * price;
    
    totalEl.textContent = formatPrice(total);
    
    // Mettre à jour le total du panier
    updateCartTotal();
}

/**
 * Mettre à jour le total du panier
 */
function updateCartTotal() {
    const subTotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total');
    let total = 0;
    
    // Calculer le total à partir de tous les sous-totaux
    document.querySelectorAll('[id^="total-"]').forEach(el => {
        const productId = el.id.replace('total-', '');
        const quantityInput = document.getElementById(`quantity-${productId}`);
        const priceEl = document.getElementById(`price-${productId}`);
        
        if (quantityInput && priceEl) {
            const quantity = parseInt(quantityInput.value, 10);
            const price = parseFloat(priceEl.dataset.price);
            total += quantity * price;
        }
    });
    
    // Mettre à jour les éléments d'affichage
    if (subTotalEl) subTotalEl.textContent = formatPrice(total);
    if (totalEl) totalEl.textContent = formatPrice(total);
}

/**
 * Supprimer un produit du panier (mettre sa quantité à 0)
 * 
 * @param {number} productId ID du produit
 */
function removeItem(productId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article du panier ?')) {
        const input = document.getElementById(`quantity-${productId}`);
        
        if (input) {
            input.value = 0;
            updateItemTotal(productId);
            
            // Masquer la ligne du produit (effet visuel avant soumission)
            const row = input.closest('tr');
            if (row) {
                row.style.opacity = '0.5';
            }
        }
    }
}

/**
 * Formater un prix en euros
 * 
 * @param {number} price Prix à formater
 * @return {string} Prix formaté
 */
function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}

/**
 * Afficher une notification à l'utilisateur
 * 
 * @param {string} message Message à afficher
 * @param {string} type Type de notification (success, error, info)
 */
function showNotification(message, type = 'info') {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    let bgColor = 'bg-blue-100 text-blue-700';
    
    if (type === 'success') {
        bgColor = 'bg-green-100 text-green-700';
    } else if (type === 'error') {
        bgColor = 'bg-red-100 text-red-700';
    }
    
    notification.className = `fixed top-4 right-4 ${bgColor} p-4 rounded shadow-md z-50 notification`;
    notification.innerHTML = message;
    
    // Ajouter la notification au document
    document.body.appendChild(notification);
    
    // Supprimer la notification après 3 secondes
    setTimeout(() => {
        notification.classList.add('fade-out');
        
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 3000);
}

// Initialiser les événements lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Si nous sommes sur la page panier, initialiser les totaux
    if (document.querySelector('.cart-counter')) {
        updateCartTotal();
    }
});

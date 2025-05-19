/**
 * Fichier JavaScript principal
 * Fichier: assets/js/main.js
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du site
    initSearchBar();
    initProductFilters();
    initMobileMenu();
});

/**
 * Initialise la barre de recherche avec autocomplétion
 */
function initSearchBar() {
    const searchInput = document.getElementById('search');
    
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Si la requête est trop courte, ne pas effectuer de recherche
        if (query.length < 3) return;
        
        // Effectuer une requête AJAX pour obtenir des suggestions
        fetch(`search_ajax.php?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                // Code pour afficher les suggestions (à implémenter)
            })
            .catch(error => {
                console.error('Erreur lors de la recherche:', error);
            });
    });
}

/**
 * Initialise les filtres de produits sur la page boutique
 */
function initProductFilters() {
    const categoryLinks = document.querySelectorAll('.category-filter');
    
    if (categoryLinks.length === 0) return;
    
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const categoryId = this.dataset.category;
            const productGrid = document.querySelector('.product-grid');
            
            // Ajouter la classe active au lien cliqué
            document.querySelectorAll('.category-filter').forEach(l => {
                l.classList.remove('active');
            });
            this.classList.add('active');
            
            // Si on clique sur "Toutes les catégories", afficher tous les produits
            if (categoryId === 'all') {
                document.querySelectorAll('.product-card').forEach(card => {
                    card.style.display = 'block';
                });
                return;
            }
            
            // Sinon, filtrer les produits par catégorie
            document.querySelectorAll('.product-card').forEach(card => {
                if (card.dataset.category === categoryId) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

/**
 * Initialise le menu mobile pour la version responsive
 */
function initMobileMenu() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (!menuToggle || !mobileMenu) return;
    
    menuToggle.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
}

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
 * Mettre à jour le compteur d'articles du panier
 * 
 * @param {number} count Nombre d'articles dans le panier
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

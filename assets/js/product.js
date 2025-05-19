/**
 * Fichier: assets/js/product.js
 * Description: Fonctions JavaScript spécifiques aux pages produits
 */

document.addEventListener('DOMContentLoaded', function() {
    initProductPage();
    initImageGallery();
    initQuantityControls();
    initRelatedProducts();
});

/**
 * Initialisation de la page produit
 */
function initProductPage() {
    // Vérifier si nous sommes sur une page produit
    if (!document.querySelector('.product-detail')) return;
    
    // Initialiser les événements des boutons
    initAddToCartButtons();
}

/**
 * Initialisation des boutons d'ajout au panier
 */
function initAddToCartButtons() {
    // Bouton standard (formulaire)
    const addToCartForm = document.getElementById('add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            // Le formulaire est soumis normalement, pas besoin de preventDefault
            // Cette fonction est juste pour des traitements supplémentaires si nécessaire
        });
    }
    
    // Bouton AJAX
    const addToCartAjaxBtn = document.getElementById('add-to-cart-ajax');
    if (addToCartAjaxBtn) {
        addToCartAjaxBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const quantity = document.getElementById('quantity').value || 1;
            
            // Appeler la fonction d'ajout au panier AJAX (définie dans cart.js)
            if (typeof addToCartAjax === 'function') {
                addToCartAjax(productId, quantity);
            } else {
                console.error('La fonction addToCartAjax n\'est pas disponible');
            }
        });
    }
}

/**
 * Gestion des contrôles de quantité
 */
function initQuantityControls() {
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) return;
    
    const minusBtn = document.querySelector('.quantity-minus');
    const plusBtn = document.querySelector('.quantity-plus');
    
    if (minusBtn) {
        minusBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value, 10);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    }
    
    if (plusBtn) {
        plusBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value, 10);
            const maxValue = parseInt(quantityInput.getAttribute('max'), 10) || 100;
            
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });
    }
    
    // Validation de la quantité
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value, 10);
            const min = parseInt(this.getAttribute('min'), 10) || 1;
            const max = parseInt(this.getAttribute('max'), 10) || 100;
            
            if (isNaN(value) || value < min) {
                value = min;
            } else if (value > max) {
                value = max;
            }
            
            this.value = value;
        });
    }
}

/**
 * Galerie d'images pour les produits avec plusieurs images
 */
function initImageGallery() {
    const mainImage = document.getElementById('main-product-image');
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    
    if (!mainImage || thumbnails.length === 0) return;
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Mettre à jour l'image principale
            const src = this.getAttribute('src');
            mainImage.setAttribute('src', src);
            
            // Mettre à jour la classe active
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

/**
 * Gestion des produits similaires/reliés
 */
function initRelatedProducts() {
    const relatedProductsContainer = document.querySelector('.related-products');
    if (!relatedProductsContainer) return;
    
    // Ajouter la navigation par flèches si nécessaire pour les écrans plus petits
    if (window.innerWidth < 768) {
        // Rendu mobile : ajouter des flèches de navigation
        const prevBtn = document.createElement('button');
        prevBtn.className = 'nav-btn prev-btn bg-gray-800 bg-opacity-50 text-white rounded-full p-2 absolute left-0 top-1/2 transform -translate-y-1/2';
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        
        const nextBtn = document.createElement('button');
        nextBtn.className = 'nav-btn next-btn bg-gray-800 bg-opacity-50 text-white rounded-full p-2 absolute right-0 top-1/2 transform -translate-y-1/2';
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        
        relatedProductsContainer.style.position = 'relative';
        relatedProductsContainer.appendChild(prevBtn);
        relatedProductsContainer.appendChild(nextBtn);
        
        // Logique de défilement
        const productsGrid = relatedProductsContainer.querySelector('.grid');
        let scrollAmount = 0;
        
        prevBtn.addEventListener('click', function() {
            scrollAmount = Math.max(scrollAmount - 200, 0);
            productsGrid.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });
        
        nextBtn.addEventListener('click', function() {
            scrollAmount = Math.min(scrollAmount + 200, productsGrid.scrollWidth - productsGrid.clientWidth);
            productsGrid.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });
        
        // Convertir le grid en scroll horizontal pour mobile
        productsGrid.style.display = 'flex';
        productsGrid.style.overflowX = 'hidden';
        productsGrid.style.scrollSnapType = 'x mandatory';
        
        const productCards = productsGrid.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.style.flex = '0 0 auto';
            card.style.width = 'calc(80% - 1rem)';
            card.style.marginRight = '1rem';
            card.style.scrollSnapAlign = 'center';
        });
    }
}

/**
 * Fonction pour ajouter un produit aux favoris
 * 
 * @param {number} productId ID du produit
 */
function addToFavorites(productId) {
    // Cette fonction pourrait utiliser localStorage pour stocker les favoris
    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    
    if (!favorites.includes(productId)) {
        favorites.push(productId);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        
        // Mettre à jour l'interface
        const favoriteBtn = document.querySelector('.favorite-btn');
        if (favoriteBtn) {
            favoriteBtn.classList.add('active');
            favoriteBtn.querySelector('i').classList.replace('far', 'fas');
        }
        
        showNotification('Produit ajouté aux favoris', 'success');
    } else {
        // Déjà dans les favoris, le retirer
        favorites = favorites.filter(id => id !== productId);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        
        // Mettre à jour l'interface
        const favoriteBtn = document.querySelector('.favorite-btn');
        if (favoriteBtn) {
            favoriteBtn.classList.remove('active');
            favoriteBtn.querySelector('i').classList.replace('fas', 'far');
        }
        
        showNotification('Produit retiré des favoris', 'info');
    }
}

/**
 * Vérifier si un produit est dans les favoris
 * 
 * @param {number} productId ID du produit
 * @return {boolean} True si le produit est dans les favoris
 */
function isInFavorites(productId) {
    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    return favorites.includes(productId);
}

/**
 * Fonction utilitaire pour débouncer une fonction
 * (empêcher l'exécution trop fréquente)
 * 
 * @param {function} func Fonction à débouncer
 * @param {number} wait Délai d'attente en millisecondes
 * @return {function} Fonction debouncée
 */
function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

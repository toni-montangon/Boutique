/**
 * Fichier JavaScript pour la recherche de produits
 * Fichier: assets/js/search.js
 */

document.addEventListener('DOMContentLoaded', function() {
    initLiveSearch();
});

/**
 * Initialise la recherche en direct sur la page boutique
 */
function initLiveSearch() {
    const searchInput = document.getElementById('search-sidebar');
    const searchResults = document.getElementById('search-results');
    
    if (!searchInput || !searchResults) return;
    
    // Ajouter un délai avant de déclencher la recherche (pour éviter trop de requêtes)
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Effacer le timeout précédent
        clearTimeout(searchTimeout);
        
        // Si la requête est vide, masquer les résultats
        if (query.length === 0) {
            searchResults.innerHTML = '';
            searchResults.classList.add('hidden');
            return;
        }
        
        // Attendre 300ms avant de déclencher la recherche
        searchTimeout = setTimeout(() => {
            // Si la requête est trop courte, ne pas effectuer de recherche
            if (query.length < 2) return;
            
            // Afficher un indicateur de chargement
            searchResults.innerHTML = '<div class="p-4 text-center">Recherche en cours...</div>';
            searchResults.classList.remove('hidden');
            
            // Effectuer une requête AJAX pour obtenir les résultats
            fetch(`search_ajax.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.products.length === 0) {
                        // Aucun résultat
                        searchResults.innerHTML = '<div class="p-4 text-center">Aucun produit trouvé.</div>';
                    } else {
                        // Afficher les résultats
                        displaySearchResults(data.products, searchResults);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    searchResults.innerHTML = '<div class="p-4 text-center text-red-600">Une erreur est survenue.</div>';
                });
        }, 300);
    });
    
    // Masquer les résultats lorsqu'on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
}

/**
 * Affiche les résultats de recherche dans le conteneur spécifié
 * 
 * @param {Array} products Liste des produits à afficher
 * @param {HTMLElement} container Conteneur où afficher les résultats
 */
function displaySearchResults(products, container) {
    // Vider le conteneur
    container.innerHTML = '';
    
    // Créer un élément pour chaque produit
    products.forEach(product => {
        const productElement = document.createElement('div');
        productElement.className = 'border-b last:border-b-0 p-4 hover:bg-gray-50';
        
        productElement.innerHTML = `
            <a href="product.php?id=${product.id}" class="flex items-center">
                <img src="assets/images/products/${product.image}" alt="${product.nom}" class="w-12 h-12 object-cover mr-4">
                <div>
                    <h3 class="font-semibold">${product.nom}</h3>
                    <div class="flex justify-between mt-1">
                        <span class="text-gray-600">${product.categorie_nom}</span>
                        <span class="text-blue-600 font-semibold">${formatPrice(product.prix)}</span>
                    </div>
                </div>
            </a>
        `;
        
        container.appendChild(productElement);
    });
    
    // Afficher le conteneur
    container.classList.remove('hidden');
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

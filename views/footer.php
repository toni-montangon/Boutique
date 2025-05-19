<?php
/**
 * Pied de page pour toutes les pages
 * Fichier: views/footer.php
 */
?>
    </main>
    
    <!-- Pied de page -->
    <footer class="bg-gray-900 text-white py-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Informations de contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <p class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i> 123 Rue de l'Horloge, 75001 Paris</p>
                    <p class="mb-2"><i class="fas fa-phone mr-2"></i> 01 23 45 67 89</p>
                    <p class="mb-2"><i class="fas fa-envelope mr-2"></i> contact@watchshop.com</p>
                </div>
                
                <!-- Liens rapides -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens utiles</h3>
                    <ul>
                        <li class="mb-2"><a href="index.php" class="hover:text-blue-300">Accueil</a></li>
                        <li class="mb-2"><a href="shop.php" class="hover:text-blue-300">Boutique</a></li>
                        <li class="mb-2"><a href="#" class="hover:text-blue-300">À propos</a></li>
                        <li class="mb-2"><a href="#" class="hover:text-blue-300">Conditions générales</a></li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="mb-4">Inscrivez-vous pour recevoir nos offres et nouveautés</p>
                    <form class="flex">
                        <input type="email" placeholder="Votre email" class="px-4 py-2 rounded-l text-gray-900 w-full">
                        <button class="bg-blue-600 px-4 py-2 rounded-r hover:bg-blue-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-700 text-center">
                <p>&copy; <?php echo date('Y'); ?> WatchShop - Tous droits réservés</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript principal -->
    <script src="assets/js/main.js"></script>
</body>
</html>

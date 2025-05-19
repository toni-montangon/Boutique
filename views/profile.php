<?php
/**
 * Fichier: views/profile.php
 * Description: Vue pour la page profil utilisateur
 */
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mon profil</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Menu latéral -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 bg-blue-600 text-white">
                    <h2 class="text-xl font-semibold"><?php echo $user['nom']; ?></h2>
                    <p class="text-blue-100"><?php echo $user['email']; ?></p>
                </div>
                
                <nav class="p-4">
                    <ul>
                        <li class="mb-2">
                            <a href="#info" class="block px-4 py-2 bg-blue-100 text-blue-600 rounded">
                                <i class="fas fa-user mr-2"></i> Informations personnelles
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#password" class="block px-4 py-2 hover:bg-gray-100 rounded">
                                <i class="fas fa-key mr-2"></i> Modifier le mot de passe
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100 rounded">
                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="col-span-1 md:col-span-2">
            <!-- Informations personnelles -->
            <div id="info" class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Informations personnelles</h2>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nom</label>
                        <div class="px-4 py-2 border rounded bg-gray-50"><?php echo $user['nom']; ?></div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <div class="px-4 py-2 border rounded bg-gray-50"><?php echo $user['email']; ?></div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Date d'inscription</label>
                        <div class="px-4 py-2 border rounded bg-gray-50">
                            <?php echo date('d/m/Y', strtotime($user['date_creation'])); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modification du mot de passe -->
            <div id="password" class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Modifier le mot de passe</h2>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                            <ul class="list-disc pl-4">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="profile.php" method="post" id="passwordForm">
                        <div class="mb-4">
                            <label for="current_password" class="block text-gray-700 font-medium mb-2">Mot de passe actuel</label>
                            <input type="password" name="current_password" id="current_password" class="w-full px-4 py-2 border rounded" required>
                            <div class="error-message text-red-600 text-sm mt-1 hidden" id="current_password-error"></div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password" class="block text-gray-700 font-medium mb-2">Nouveau mot de passe</label>
                            <input type="password" name="new_password" id="new_password" class="w-full px-4 py-2 border rounded" required>
                            <div class="error-message text-red-600 text-sm mt-1 hidden" id="new_password-error"></div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirmer le nouveau mot de passe</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="w-full px-4 py-2 border rounded" required>
                            <div class="error-message text-red-600 text-sm mt-1 hidden" id="confirm_password-error"></div>
                        </div>
                        
                        <button type="submit" name="update_password" class="bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700">
                            Modifier le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

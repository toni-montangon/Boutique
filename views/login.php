<?php
/**
 * Fichier: views/login.php
 * Description: Vue pour la page de connexion
 */
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden mt-8 mb-8">
    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-center mb-6">Connexion</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-4">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="login.php" method="post" id="loginForm">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded" 
                       value="<?php echo $email; ?>" required>
                <div class="error-message text-red-600 text-sm mt-1 hidden" id="email-error"></div>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded" 
                       required>
                <div class="error-message text-red-600 text-sm mt-1 hidden" id="password-error"></div>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700">
                Se connecter
            </button>
        </form>
        
        <div class="text-center mt-4">
            <p class="text-gray-600">Pas encore de compte ?
                <a href="register.php" class="text-blue-600 hover:underline">S'inscrire</a>
            </p>
        </div>
    </div>
</div>

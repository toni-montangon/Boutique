<?php
/**
 * Page de profil utilisateur
 * Fichier: profile.php
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Vérifier si l'utilisateur est connecté
requireLogin();

// Récupérer les informations de l'utilisateur
$user = getCurrentUser();

// Initialisation des variables
$errors = [];
$success = '';

// Traitement du formulaire de modification du mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validation des champs
    if (empty($currentPassword)) {
        $errors[] = "Le mot de passe actuel est requis.";
    }
    
    if (empty($newPassword)) {
        $errors[] = "Le nouveau mot de passe est requis.";
    } elseif (strlen($newPassword) < 6) {
        $errors[] = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    }
    
    if ($newPassword !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    
    // Si aucune erreur, procéder à la mise à jour
    if (empty($errors)) {
        $result = updatePassword($user['id'], $currentPassword, $newPassword);
        
        if ($result['success']) {
            // Mise à jour réussie
            $success = $result['message'];
        } else {
            // Erreur lors de la mise à jour
            $errors[] = $result['message'];
        }
    }
}

// Inclusion de l'en-tête
include 'views/header.php';
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

<!-- JavaScript pour la validation côté client -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');
    
    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Masquer tous les messages d'erreur
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        // Validation du mot de passe actuel
        const currentPassword = document.getElementById('current_password');
        if (currentPassword.value === '') {
            showError('current_password', 'Le mot de passe actuel est requis.');
            hasErrors = true;
        }
        
        // Validation du nouveau mot de passe
        const newPassword = document.getElementById('new_password');
        if (newPassword.value === '') {
            showError('new_password', 'Le nouveau mot de passe est requis.');
            hasErrors = true;
        } else if (newPassword.value.length < 6) {
            showError('new_password', 'Le nouveau mot de passe doit contenir au moins 6 caractères.');
            hasErrors = true;
        }
        
        // Validation de la confirmation du mot de passe
        const confirmPassword = document.getElementById('confirm_password');
        if (confirmPassword.value === '') {
            showError('confirm_password', 'La confirmation du mot de passe est requise.');
            hasErrors = true;
        } else if (confirmPassword.value !== newPassword.value) {
            showError('confirm_password', 'Les mots de passe ne correspondent pas.');
            hasErrors = true;
        }
        
        // Empêcher la soumission du formulaire s'il y a des erreurs
        if (hasErrors) {
            e.preventDefault();
        }
    });
    
    // Fonction pour afficher un message d'erreur
    function showError(fieldId, message) {
        const errorEl = document.getElementById(`${fieldId}-error`);
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }
});

// Navigation dans les onglets
document.querySelectorAll('nav a').forEach(link => {
    link.addEventListener('click', function(e) {
        // Retirer la classe active de tous les liens
        document.querySelectorAll('nav a').forEach(l => {
            l.classList.remove('bg-blue-100', 'text-blue-600');
            l.classList.add('hover:bg-gray-100');
        });
        
        // Ajouter la classe active au lien cliqué
        this.classList.add('bg-blue-100', 'text-blue-600');
        this.classList.remove('hover:bg-gray-100');
    });
});
</script>

<?php
// Inclusion du pied de page
include 'views/footer.php';
?>

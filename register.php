<?php
/**
 * Page d'inscription
 * Fichier: register.php
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Initialisation des variables
$nom = $email = '';
$errors = [];

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = clean($_POST['nom']);
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation des champs
    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }
    
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    
    // Si aucune erreur, procéder à l'inscription
    if (empty($errors)) {
        $result = registerUser($nom, $email, $password);
        
        if ($result['success']) {
            // Inscription réussie, rediriger vers la page de connexion
            $_SESSION['success_message'] = $result['message'];
            redirect('login.php');
        } else {
            // Erreur lors de l'inscription
            $errors[] = $result['message'];
        }
    }
}

// Inclusion de l'en-tête
include 'views/header.php';
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden mt-8 mb-8">
    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-center mb-6">Inscription</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-4">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="register.php" method="post" id="registerForm">
            <div class="mb-4">
                <label for="nom" class="block text-gray-700 font-medium mb-2">Nom complet</label>
                <input type="text" name="nom" id="nom" class="w-full px-4 py-2 border rounded" 
                       value="<?php echo $nom; ?>" required>
                <div class="error-message text-red-600 text-sm mt-1 hidden" id="nom-error"></div>
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded" 
                       value="<?php echo $email; ?>" required>
                <div class="error-message text-red-600 text-sm mt-1 hidden" id="email-error"></div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded" 
                       required>
                <div class="error-message text-red-600 text-sm mt-1 hidden" id="password-error"></div>
            </div>
            
            <div class="mb-6">
                <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" id="confirm_password" class="w-full px-4 py-2 border rounded" 
                       required>
                <div class="error-message text-red-600 text-sm mt-1 hidden" id="confirm_password-error"></div>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700">
                S'inscrire
            </button>
        </form>
        
        <div class="text-center mt-4">
            <p class="text-gray-600">Déjà inscrit ?
                <a href="login.php" class="text-blue-600 hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</div>

<!-- JavaScript pour la validation côté client -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    
    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Masquer tous les messages d'erreur
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        // Validation du nom
        const nom = document.getElementById('nom');
        if (nom.value.trim() === '') {
            showError('nom', 'Le nom est requis.');
            hasErrors = true;
        }
        
        // Validation de l'email
        const email = document.getElementById('email');
        if (email.value.trim() === '') {
            showError('email', 'L\'email est requis.');
            hasErrors = true;
        } else if (!isValidEmail(email.value)) {
            showError('email', 'Format d\'email invalide.');
            hasErrors = true;
        }
        
        // Validation du mot de passe
        const password = document.getElementById('password');
        if (password.value === '') {
            showError('password', 'Le mot de passe est requis.');
            hasErrors = true;
        } else if (password.value.length < 6) {
            showError('password', 'Le mot de passe doit contenir au moins 6 caractères.');
            hasErrors = true;
        }
        
        // Validation de la confirmation du mot de passe
        const confirmPassword = document.getElementById('confirm_password');
        if (confirmPassword.value === '') {
            showError('confirm_password', 'La confirmation du mot de passe est requise.');
            hasErrors = true;
        } else if (confirmPassword.value !== password.value) {
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
    
    // Fonction pour valider le format de l'email
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script>

<?php
// Inclusion du pied de page
include 'views/footer.php';
?>

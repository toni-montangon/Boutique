<?php
/**
 * Page de connexion
 * Fichier: login.php
 */

// Inclusion des fichiers nécessaires
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Vérifier si l'utilisateur est déjà connecté
if (isLoggedIn()) {
    redirect('index.php');
}

// Initialisation des variables
$email = '';
$errors = [];

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    
    // Validation des champs
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }
    
    // Si aucune erreur, procéder à la connexion
    if (empty($errors)) {
        $result = loginUser($email, $password);
        
        if ($result['success']) {
            // Connexion réussie, rediriger vers la page d'accueil
            $_SESSION['success_message'] = $result['message'];
            
            // Rediriger vers la page demandée ou la page d'accueil
            $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'index.php';
            unset($_SESSION['redirect_after_login']);
            redirect($redirect);
        } else {
            // Erreur lors de la connexion
            $errors[] = $result['message'];
        }
    }
}

// Inclusion de l'en-tête
include 'views/header.php';
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

<!-- JavaScript pour la validation côté client -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    
    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Masquer tous les messages d'erreur
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
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

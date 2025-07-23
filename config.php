<?php
/**
 * Configuration globale de l'application
 */

// Configuration de l'encodage
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Configuration des erreurs (désactivé en production)
if (getenv('RENDER') || isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'render.com') !== false) {
    // En production sur Render
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '/tmp/php_errors.log');
} else {
    // En développement local
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Fonction pour obtenir l'URL de base
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script);
    
    // Nettoyer le chemin
    if ($path === '/' || $path === '\\') {
        $path = '';
    }
    
    return $protocol . '://' . $host . $path;
}

// Fonction pour générer un lien relatif sûr
function safeLink($file) {
    // Utiliser des chemins relatifs pour éviter les problèmes de déploiement
    return $file;
}

// Fonction pour afficher un bouton de retour à l'accueil
function renderBackToHomeButton($class = 'back-link') {
    return '<a href="' . safeLink('home.html') . '" class="' . $class . '">' .
           '<i class="fas fa-home"></i> Retour à l\'accueil</a>';
}

// Fonction pour afficher les boutons de navigation
function renderNavigationButtons() {
    return '<div class="navigation-buttons">' .
           '<a href="' . safeLink('index.php') . '" class="btn-back">' .
           '<i class="fas fa-arrow-left"></i> Retour</a>' .
           '<a href="' . safeLink('home.html') . '" class="btn-home">' .
           '<i class="fas fa-home"></i> Accueil</a>' .
           '</div>';
}

// Constantes de l'application
define('APP_NAME', 'Analyseur de Discours');
define('APP_VERSION', '1.0.0');
define('APP_YEAR', date('Y'));

// Configuration Python
define('PYTHON_COMMANDS', ['python3', 'python', 'py']);
define('TEMP_DIR', 'temp');

// Créer le dossier temp s'il n'existe pas
if (!is_dir(TEMP_DIR)) {
    mkdir(TEMP_DIR, 0755, true);
}
?>
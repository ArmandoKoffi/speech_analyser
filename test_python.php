<?php
// Inclure la configuration globale
require_once 'config.php';

echo "<html>\n<head>\n<title>Test de l'installation Python</title>\n<meta charset='UTF-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n<link rel='stylesheet' href='css/style.css'>\n<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>\n<link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap' rel='stylesheet'>\n<style>\n.test-container { max-width: 900px; margin: 0 auto; padding: 2rem; }\n.test-section { background-color: var(--card-bg); margin-bottom: 20px; padding: 15px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); }\npre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }\n.success { color: green; font-weight: bold; }\n.error { color: red; font-weight: bold; }\n.back-link { display: inline-flex; align-items: center; margin-bottom: 1.5rem; color: var(--primary-color); text-decoration: none; font-weight: 500; transition: var(--transition); }\n.back-link:hover { color: var(--secondary-color); }\n.back-link i { margin-right: 0.5rem; }\n</style>\n</head>\n<body>\n<div class='container'>\n<header>\n<div class='logo-container'>\n<div class='logo'>\n<i class='fas fa-brain'></i>\n</div>\n<h1>Analyseur de Discours</h1>\n</div>\n<p class='tagline'>Diagnostic Python</p>\n</header>\n<main>\n<a href='home.html' class='back-link'>\n<i class='fas fa-arrow-left'></i> Retour à l'accueil\n</a>\n<div class='test-container'>\n";

echo "<h1>Test de l'installation Python pour l'Analyseur de Discours</h1>";

// Fonction pour exécuter une commande et afficher le résultat
function runCommand($title, $command) {
    echo "<div class='test-section'>";
    echo "<h2>$title</h2>";
    echo "<p>Commande exécutée : <code>$command</code></p>";
    echo "<pre>";
    $output = shell_exec($command . ' 2>&1');
    echo htmlspecialchars($output ?: "Aucune sortie ou commande non trouvée");
    echo "</pre>";
    
    if (empty($output)) {
        echo "<p class='error'>⚠️ La commande n'a pas produit de sortie. Python pourrait ne pas être installé ou accessible.</p>";
    } else if (strpos($output, 'Python') !== false && strpos($output, 'version') !== false) {
        echo "<p class='success'>✅ Python est correctement installé et accessible.</p>";
    } else if (strpos($output, 'not found') !== false || strpos($output, 'introuvable') !== false) {
        echo "<p class='error'>❌ Python n'est pas installé ou n'est pas dans le PATH.</p>";
    }
    
    echo "</div>";
    
    return $output;
}

// Tester différentes commandes Python
$pythonCommands = ["python", "python3", "py"];
$pythonFound = false;

foreach ($pythonCommands as $cmd) {
    $output = runCommand("Test avec la commande '$cmd'", "$cmd --version");
    if (!empty($output) && strpos($output, 'not found') === false && strpos($output, 'introuvable') === false) {
        $pythonFound = true;
    }
}

// Tester l'exécution du script d'analyse
if ($pythonFound) {
    echo "<div class='test-section'>";
    echo "<h2>Test du script d'analyse</h2>";
    
    // Créer un fichier de test temporaire
    $testFile = "temp/test_" . time() . ".txt";
    if (!is_dir("temp")) {
        mkdir("temp", 0755, true);
    }
    file_put_contents($testFile, "Ceci est un test positif avec des mots comme bon et excellent.");
    
    // Tester le script avec chaque commande Python
    foreach ($pythonCommands as $cmd) {
        $command = "$cmd analyze_text.py " . escapeshellarg($testFile) . " sentiment";
        echo "<h3>Test avec $cmd</h3>";
        echo "<p>Commande : <code>$command</code></p>";
        echo "<pre>";
        $output = shell_exec($command . ' 2>&1');
        echo htmlspecialchars($output ?: "Aucune sortie");
        echo "</pre>";
        
        // Vérifier si la sortie est un JSON valide
        $result = json_decode($output, true);
        if ($result !== null) {
            echo "<p class='success'>✅ Le script a été exécuté avec succès et a produit un JSON valide.</p>";
            echo "<p>Résultat de l'analyse : </p>";
            echo "<pre>" . print_r($result, true) . "</pre>";
            break;
        } else {
            echo "<p class='error'>❌ Le script n'a pas produit de JSON valide avec cette commande.</p>";
        }
    }
    
    // Nettoyer
    if (file_exists($testFile)) {
        unlink($testFile);
    }
    
    echo "</div>";
}

// Informations sur le système
echo "<div class='test-section'>";
echo "<h2>Informations sur le système</h2>";

echo "<h3>Version de PHP</h3>";
echo "<pre>" . phpversion() . "</pre>";

echo "<h3>Variables d'environnement PATH</h3>";
echo "<pre>" . htmlspecialchars(getenv('PATH')) . "</pre>";

echo "<h3>Répertoire de travail actuel</h3>";
echo "<pre>" . htmlspecialchars(getcwd()) . "</pre>";

echo "</div>";

// Conseils de dépannage
echo "<div class='test-section'>";
echo "<h2>Conseils de dépannage</h2>";
echo "<ol>";
echo "<li>Si aucune commande Python ne fonctionne, installez Python depuis <a href='https://www.python.org/downloads/' target='_blank'>le site officiel</a> et assurez-vous de cocher l'option 'Add Python to PATH' pendant l'installation.</li>";
echo "<li>Si Python est installé mais n'est pas trouvé, ajoutez-le manuellement au PATH système (voir README).</li>";
echo "<li>Après avoir installé ou configuré Python, redémarrez Apache et XAMPP.</li>";
echo "<li>Vérifiez que le script <code>analyze_text.py</code> est présent dans le répertoire du projet et qu'il a les permissions d'exécution.</li>";
echo "</ol>";
echo "</div>";

echo "</div>";
echo "</div>";
echo "</main>";
echo "<footer>";
echo "<p>&copy; " . date('Y') . " Analyseur de Discours | Projet Simplon</p>";
echo "</footer>";
echo "</div>";
echo "</body>\n</html>";
?>

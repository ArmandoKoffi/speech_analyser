<?php
// Afficher les erreurs PHP pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html>\n<head>\n<title>Test de l'installation Python</title>\n<style>\nbody { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }\nh1 { color: #333; }\npre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }\n.success { color: green; font-weight: bold; }\n.error { color: red; font-weight: bold; }\n.section { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n</style>\n</head>\n<body>\n";

echo "<h1>Test de l'installation Python pour l'Analyseur de Discours</h1>";

// Fonction pour exécuter une commande et afficher le résultat
function runCommand($title, $command) {
    echo "<div class='section'>";
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
    echo "<div class='section'>";
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
echo "<div class='section'>";
echo "<h2>Informations sur le système</h2>";

echo "<h3>Version de PHP</h3>";
echo "<pre>" . phpversion() . "</pre>";

echo "<h3>Variables d'environnement PATH</h3>";
echo "<pre>" . htmlspecialchars(getenv('PATH')) . "</pre>";

echo "<h3>Répertoire de travail actuel</h3>";
echo "<pre>" . htmlspecialchars(getcwd()) . "</pre>";

echo "</div>";

// Conseils de dépannage
echo "<div class='section'>";
echo "<h2>Conseils de dépannage</h2>";
echo "<ol>";
echo "<li>Si aucune commande Python ne fonctionne, installez Python depuis <a href='https://www.python.org/downloads/' target='_blank'>le site officiel</a> et assurez-vous de cocher l'option 'Add Python to PATH' pendant l'installation.</li>";
echo "<li>Si Python est installé mais n'est pas trouvé, ajoutez-le manuellement au PATH système (voir README).</li>";
echo "<li>Après avoir installé ou configuré Python, redémarrez Apache et XAMPP.</li>";
echo "<li>Vérifiez que le script <code>analyze_text.py</code> est présent dans le répertoire du projet et qu'il a les permissions d'exécution.</li>";
echo "</ol>";
echo "</div>";

echo "<p><a href='home.html'>Retour à l'accueil</a></p>";

echo "</body>\n</html>";
?>
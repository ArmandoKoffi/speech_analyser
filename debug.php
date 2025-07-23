<?php
// Script de diagnostic pour Render
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>\n<html lang='fr'>\n<head>\n<meta charset='UTF-8'>\n<title>Diagnostic Render</title>\n</head>\n<body>\n";
echo "<h1>Diagnostic de l'application sur Render</h1>\n";

// Informations PHP
echo "<h2>Informations PHP</h2>\n";
echo "<p>Version PHP: " . phpversion() . "</p>\n";
echo "<p>Répertoire de travail: " . getcwd() . "</p>\n";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>\n";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>\n";
echo "<p>Server Name: " . $_SERVER['SERVER_NAME'] . "</p>\n";

// Test des extensions PHP
echo "<h2>Extensions PHP</h2>\n";
$extensions = ['mbstring', 'json', 'curl'];
foreach ($extensions as $ext) {
    $status = extension_loaded($ext) ? '✅ Chargée' : '❌ Manquante';
    echo "<p>$ext: $status</p>\n";
}

// Test des fichiers
echo "<h2>Fichiers du projet</h2>\n";
$files = ['home.html', 'index.php', 'analyze.php', 'analyze_text.py', 'css/style.css', 'js/script.js'];
foreach ($files as $file) {
    $status = file_exists($file) ? '✅ Existe' : '❌ Manquant';
    echo "<p>$file: $status</p>\n";
}

// Test du dossier temp
echo "<h2>Dossier temporaire</h2>\n";
if (!is_dir('temp')) {
    mkdir('temp', 0755, true);
    echo "<p>✅ Dossier temp créé</p>\n";
} else {
    echo "<p>✅ Dossier temp existe</p>\n";
}

if (is_writable('temp')) {
    echo "<p>✅ Dossier temp accessible en écriture</p>\n";
} else {
    echo "<p>❌ Dossier temp non accessible en écriture</p>\n";
}

// Test Python
echo "<h2>Test Python</h2>\n";
$pythonCommands = ['python3', 'python', 'py'];
foreach ($pythonCommands as $cmd) {
    $output = shell_exec($cmd . ' --version 2>&1');
    if ($output && strpos($output, 'Python') !== false) {
        echo "<p>✅ $cmd: " . trim($output) . "</p>\n";
    } else {
        echo "<p>❌ $cmd: Non disponible</p>\n";
    }
}

// Test du script Python
echo "<h2>Test du script d'analyse</h2>\n";
if (file_exists('analyze_text.py')) {
    $testFile = 'temp/debug_test.txt';
    file_put_contents($testFile, 'Test de diagnostic');
    
    $command = 'python3 analyze_text.py ' . escapeshellarg($testFile) . ' sentiment 2>&1';
    $output = shell_exec($command);
    
    if ($output) {
        $result = json_decode($output, true);
        if ($result !== null) {
            echo "<p>✅ Script Python fonctionne correctement</p>\n";
            echo "<pre>" . htmlspecialchars($output) . "</pre>\n";
        } else {
            echo "<p>❌ Script Python produit une sortie invalide</p>\n";
            echo "<pre>" . htmlspecialchars($output) . "</pre>\n";
        }
    } else {
        echo "<p>❌ Aucune sortie du script Python</p>\n";
    }
    
    if (file_exists($testFile)) {
        unlink($testFile);
    }
} else {
    echo "<p>❌ Script analyze_text.py non trouvé</p>\n";
}

// Variables d'environnement
echo "<h2>Variables d'environnement importantes</h2>\n";
$envVars = ['PATH', 'PYTHONPATH', 'HOME', 'USER'];
foreach ($envVars as $var) {
    $value = getenv($var);
    if ($value) {
        echo "<p>$var: " . htmlspecialchars($value) . "</p>\n";
    } else {
        echo "<p>$var: Non définie</p>\n";
    }
}

echo "<p><a href='home.html'>Retour à l'accueil</a></p>\n";
echo "</body>\n</html>";
?>
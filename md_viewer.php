<?php
/**
 * Simple Markdown Viewer
 * 
 * Ce script permet d'afficher les fichiers Markdown (.md) dans le navigateur
 * avec une mise en forme HTML basique.
 */

// Récupérer le chemin du fichier Markdown demandé
$requested_file = $_SERVER['PATH_TRANSLATED'];

// Vérifier si le fichier existe
if (!file_exists($requested_file)) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Erreur 404: Fichier non trouvé</h1>";
    echo "<p>Le fichier demandé n'existe pas.</p>";
    echo "<p><a href='/Dossier_Projet_Simplon/speech_analyser/home.html'>Retour à l'accueil</a></p>";
    exit;
}

// Lire le contenu du fichier Markdown
$markdown_content = file_get_contents($requested_file);

// Fonction simple pour convertir le Markdown en HTML
function markdown_to_html($markdown) {
    // Convertir les titres
    $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $markdown);
    $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
    $html = preg_replace('/^#### (.+)$/m', '<h4>$1</h4>', $html);
    
    // Convertir les paragraphes
    $html = preg_replace('/^([^\n<].+)$/m', '<p>$1</p>', $html);
    
    // Convertir les listes
    $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
    $html = preg_replace('/^\d+\. (.+)$/m', '<li>$1</li>', $html);
    $html = preg_replace('/((?:<li>.+<\/li>\n)+)/', '<ul>$1</ul>', $html);
    
    // Convertir le texte en gras et italique
    $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
    $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);
    
    // Convertir les liens
    $html = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $html);
    
    // Convertir les blocs de code
    $html = preg_replace('/```(.+?)```/s', '<pre><code>$1</code></pre>', $html);
    $html = preg_replace('/`(.+?)`/', '<code>$1</code>', $html);
    
    // Convertir les séparateurs horizontaux
    $html = preg_replace('/^---$/m', '<hr>', $html);
    
    return $html;
}

// Convertir le Markdown en HTML
$html_content = markdown_to_html($markdown_content);

// Extraire le titre du document (premier titre h1)
preg_match('/<h1>(.+?)<\/h1>/', $html_content, $matches);
$title = isset($matches[1]) ? $matches[1] : 'Document Markdown';

// Afficher le HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .markdown-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .markdown-container h1 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
        }
        
        .markdown-container h2 {
            color: var(--primary-color);
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        
        .markdown-container h3 {
            color: var(--secondary-color);
            margin-top: 1.5rem;
            margin-bottom: 0.8rem;
            font-size: 1.5rem;
        }
        
        .markdown-container p {
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        
        .markdown-container ul, .markdown-container ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }
        
        .markdown-container li {
            margin-bottom: 0.5rem;
        }
        
        .markdown-container code {
            background-color: #f1f3f5;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.9rem;
        }
        
        .markdown-container pre {
            background-color: #f1f3f5;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
        
        .markdown-container pre code {
            background-color: transparent;
            padding: 0;
        }
        
        .markdown-container a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .markdown-container a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .markdown-container hr {
            border: 0;
            height: 1px;
            background-color: #e9ecef;
            margin: 2rem 0;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .back-link:hover {
            color: var(--secondary-color);
        }
        
        .back-link i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-brain"></i>
                </div>
                <h1>Analyseur de Discours</h1>
            </div>
            <p class="tagline">Documentation</p>
        </header>

        <main>
            <a href="home.html" class="back-link">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
            
            <div class="markdown-container">
                <?php echo $html_content; ?>
            </div>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Analyseur de Discours | Projet Simplon</p>
        </footer>
    </div>
</body>
</html>
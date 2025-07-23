<?php
// Inclure la configuration globale
require_once 'config.php';

// Initialiser les variables
$result = [];
$text = "";
$analysisType = "";
$error = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $text = isset($_POST["speech-text"]) ? trim($_POST["speech-text"]) : "";
    $analysisType = isset($_POST["analysis-type"]) ? $_POST["analysis-type"] : "";
    
    // Valider les entrées
    if (empty($text)) {
        $error = "Veuillez entrer un texte à analyser.";
    } else {
        // Sauvegarder le texte dans un fichier temporaire
        $tempFile = TEMP_DIR . "/input_" . time() . ".txt";
        
        // Écrire le texte dans le fichier avec encodage UTF-8 explicite
        file_put_contents($tempFile, $text, LOCK_EX);
        
        // Essayer différentes commandes Python
        $pythonCommands = PYTHON_COMMANDS;
        $output = null;
        $command = "";
        $commandSuccess = false;
        
        foreach ($pythonCommands as $pythonCmd) {
            $command = $pythonCmd . " analyze_text.py " . escapeshellarg($tempFile) . " " . escapeshellarg($analysisType);
            
            // Exécuter le script Python avec capture des erreurs et forcer l'encodage UTF-8
            $output = shell_exec($command . ' 2>&1');
            
            // Détecter et corriger l'encodage si nécessaire
            if ($output !== null) {
                // Vérifier si la sortie contient des caractères mal encodés
                if (strpos($output, '�') !== false || !mb_check_encoding($output, 'UTF-8')) {
                    // Essayer de détecter l'encodage réel
                    $detected_encoding = mb_detect_encoding($output, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
                    if ($detected_encoding && $detected_encoding !== 'UTF-8') {
                        $output = mb_convert_encoding($output, 'UTF-8', $detected_encoding);
                    }
                }
                
                // Vérifier si la sortie est un JSON valide
                $testResult = json_decode($output, true, 512, JSON_UNESCAPED_UNICODE);
                if ($testResult !== null && json_last_error() === JSON_ERROR_NONE) {
                    $commandSuccess = true;
                    break;
                }
            }
        }
        
        // Décoder la sortie JSON du script Python avec support explicite pour les caractères Unicode
        $result = json_decode($output, true, 512, JSON_UNESCAPED_UNICODE);
        
        // Vérifier si l'analyse a réussi
        if ($result === null || json_last_error() !== JSON_ERROR_NONE) {
            // Diagnostiquer le problème JSON
            $json_error = json_last_error_msg();
            
            // Vérifier si Python est installé
            if (strpos($output, 'Python est introuvable') !== false || strpos($output, 'not found') !== false || strpos($output, 'No such file or directory') !== false) {
                $error = "Python n'est pas installé ou n'est pas accessible. Veuillez installer Python pour utiliser cette application.";
            } else {
                $error = "Une erreur s'est produite lors de l'analyse. Erreur JSON: " . $json_error;
            }
            $result = [
                "error" => $error, 
                "raw_output" => $output, 
                "command" => $command, 
                "python_commands_tried" => $pythonCommands,
                "json_error" => $json_error
            ];
        }
        
        // Supprimer le fichier temporaire
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats d'Analyse | <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container results-page">
        <header>
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-brain"></i>
                </div>
                <h1><?php echo APP_NAME; ?></h1>
            </div>
            <p class="tagline">Résultats de votre analyse</p>
        </header>

        <main>
            <section class="results-section">
                <div class="card result-card">
                    <div class="card-header">
                        <h2><i class="fas fa-chart-bar"></i> Résultats de l'analyse</h2>
                        <div class="navigation-buttons">
                            <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
                            <a href="home.html" class="btn-home"><i class="fas fa-home"></i> Accueil</a>
                        </div>
                    </div>
                    
                    <?php if (!empty($error)): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $error; ?>
                            <?php if (isset($result['raw_output']) || isset($result['details'])): ?>
                                <div class="error-details">
                                    <h4>Détails de l'erreur:</h4>
                                    <?php if (isset($result['details'])): ?>
                                        <pre><?php echo htmlspecialchars($result['details']); ?></pre>
                                    <?php else: ?>
                                        <pre><?php echo htmlspecialchars($result['raw_output']); ?></pre>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($result['command'])): ?>
                                        <h4>Commande exécutée:</h4>
                                        <pre><?php echo htmlspecialchars($result['command']); ?></pre>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($result['json_error'])): ?>
                                        <h4>Erreur JSON:</h4>
                                        <pre><?php echo htmlspecialchars($result['json_error']); ?></pre>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($result['python_version'])): ?>
                                        <h4>Version Python:</h4>
                                        <pre><?php echo htmlspecialchars($result['python_version']); ?></pre>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif (!empty($result)): ?>
                        <div class="analysis-summary">
                            <h3>Résumé de l'analyse</h3>
                            <p class="analysis-type">Type d'analyse: <span><?php echo ucfirst($analysisType); ?></span></p>
                            <p class="text-length">Longueur du texte: <span><?php echo mb_strlen($text); ?> caractères</span></p>
                        </div>
                        
                        <?php if (isset($result['sentiment'])): ?>
                            <div class="result-block sentiment-analysis">
                                <h3><i class="fas fa-heart"></i> Analyse de sentiment</h3>
                                <div class="sentiment-meter">
                                    <div class="meter-bar">
                                        <div class="meter-fill" style="width: <?php echo (($result['sentiment']['score'] + 1) / 2) * 100; ?>%;"></div>
                                    </div>
                                    <div class="meter-labels">
                                        <span>Négatif</span>
                                        <span>Neutre</span>
                                        <span>Positif</span>
                                    </div>
                                </div>
                                <p class="sentiment-score">Score: <span><?php echo number_format($result['sentiment']['score'], 2); ?></span></p>
                                <p class="sentiment-label">Sentiment dominant: <span class="label <?php echo strtolower($result['sentiment']['label']); ?>"><?php echo $result['sentiment']['label']; ?></span></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($result['keywords']) && !empty($result['keywords'])): ?>
                            <div class="result-block keywords-analysis">
                                <h3><i class="fas fa-key"></i> Mots-clés extraits</h3>
                                <div class="keywords-cloud">
                                    <?php foreach ($result['keywords'] as $keyword): ?>
                                        <span class="keyword" style="font-size: <?php echo (14 + min($keyword['score'] * 20, 22)); ?>px;">
                                            <?php echo htmlspecialchars($keyword['word']); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($result['summary'])): ?>
                            <div class="result-block summary-analysis">
                                <h3><i class="fas fa-compress-alt"></i> Résumé automatique</h3>
                                <div class="summary-text">
                                    <p><?php echo nl2br(htmlspecialchars($result['summary'])); ?></p>
                                </div>
                                <p class="compression-rate">Taux de compression: <span><?php echo number_format((1 - (mb_strlen($result['summary']) / mb_strlen($text))) * 100, 1); ?>%</span></p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-results">
                            <p>Aucun résultat disponible. Veuillez soumettre un texte pour analyse.</p>
                            <a href="index.php" class="btn-primary"><i class="fas fa-home"></i> Retour à l'accueil</a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo APP_YEAR; ?> <?php echo APP_NAME; ?> | Projet Simplon</p>
        </footer>
    </div>

    <script src="js/script.js"></script>
</body>
</html>

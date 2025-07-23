<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyseur de Discours</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            <p class="tagline">Découvrez les insights cachés dans vos textes</p>
            <div style="text-align: center; margin-top: 1rem;">
                <a href="home.html" style="display: inline-flex; align-items: center; color: var(--primary-color); text-decoration: none; font-weight: 500; transition: var(--transition);">
                    <i class="fas fa-home" style="margin-right: 0.5rem;"></i> Retour à l'accueil
                </a>
            </div>
        </header>

        <main>
            <section class="form-section">
                <div class="card">
                    <h2><i class="fas fa-file-alt"></i> Analysez votre texte</h2>
                    <form action="analyze.php" method="post" id="speech-form">
                        <div class="form-group">
                            <label for="speech-text">Texte à analyser:</label>
                            <textarea id="speech-text" name="speech-text" rows="8" placeholder="Collez votre discours ou texte ici..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="analysis-type">Type d'analyse:</label>
                            <select id="analysis-type" name="analysis-type">
                                <option value="sentiment">Analyse de sentiment</option>
                                <option value="keywords">Extraction de mots-clés</option>
                                <option value="summary">Résumé automatique</option>
                                <option value="complete">Analyse complète</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-search"></i> Analyser
                            </button>
                            <button type="reset" class="btn-secondary">
                                <i class="fas fa-redo"></i> Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="features">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Analyse de sentiment</h3>
                    <p>Détectez le ton émotionnel et l'attitude exprimés dans votre texte.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3>Extraction de mots-clés</h3>
                    <p>Identifiez les termes les plus importants et pertinents de votre texte.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-compress-alt"></i>
                    </div>
                    <h3>Résumé automatique</h3>
                    <p>Obtenez une version condensée qui préserve les points essentiels.</p>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Analyseur de Discours | Projet Simplon</p>
        </footer>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
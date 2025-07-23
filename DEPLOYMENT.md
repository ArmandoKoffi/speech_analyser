# Guide de Déploiement - Speech Analyzer

## Améliorations apportées pour Render

### 1. Configuration centralisée
- **config.php** : Configuration globale de l'application
- Gestion automatique de l'encodage UTF-8
- Détection de l'environnement (local vs production)
- Fonctions utilitaires pour la navigation

### 2. Navigation améliorée
- Tous les liens s'ouvrent dans la même fenêtre
- Boutons "Retour à l'accueil" sur toutes les pages
- Navigation cohérente et intuitive

### 3. Gestion des erreurs
- **debug.php** : Script de diagnostic complet
- Gestion des erreurs 404 et 500
- Logging approprié pour la production

### 4. Optimisations pour Render
- **start.sh** : Script de démarrage optimisé
- **php.ini** : Configuration PHP adaptée
- **.htaccess** : Configuration Apache améliorée
- **Dockerfile** : Image Docker optimisée

## Structure des fichiers

```
speech_analyser/
├── config.php          # Configuration globale
├── debug.php           # Script de diagnostic
├── start.sh            # Script de démarrage
├── php.ini             # Configuration PHP
├── .htaccess           # Configuration Apache
├── Dockerfile          # Configuration Docker
├── render.yaml         # Configuration Render
├── home.html           # Page d'accueil
├── index.php           # Interface principale
├── analyze.php         # Traitement des analyses
├── md_viewer.php       # Visualiseur Markdown
├── test_python.php     # Diagnostic Python
├── analyze_text.py     # Script d'analyse Python
├── css/
│   └── style.css       # Styles CSS
├── js/
│   └── script.js       # Scripts JavaScript
└── temp/               # Dossier temporaire
```

## Fonctionnalités de diagnostic

### debug.php
Script de diagnostic complet qui vérifie :
- Informations PHP et extensions
- Présence des fichiers du projet
- Fonctionnement du dossier temporaire
- Disponibilité de Python
- Test du script d'analyse
- Variables d'environnement

### Accès au diagnostic
- Depuis la page d'accueil : lien "Diagnostic Render"
- URL directe : `/debug.php`
- En cas d'erreur 404/500 (redirection automatique)

## Configuration pour Render

### Variables d'environnement recommandées
```bash
PYTHON_VERSION=3.9
PHP_VERSION=8.1
TZ=Europe/Paris
```

### Commandes de build
```bash
# Installation des dépendances Python
pip install nltk textblob sumy

# Téléchargement des données NLTK
python -c "import nltk; nltk.download('punkt'); nltk.download('stopwords')"
```

## Résolution des problèmes courants

### Erreur HTTP 500
1. Vérifier les logs avec `debug.php`
2. Contrôler les permissions des fichiers
3. Vérifier la configuration PHP

### CSS manquant
1. Vérifier les chemins relatifs
2. Contrôler la configuration des types MIME
3. Vérifier les en-têtes de cache

### Problèmes Python
1. Vérifier l'installation avec `test_python.php`
2. Contrôler les dépendances NLTK
3. Vérifier les permissions du dossier temp

## Navigation

### Pages principales
- **home.html** : Page d'accueil avec liens vers toutes les fonctionnalités
- **index.php** : Interface d'analyse de texte
- **analyze.php** : Affichage des résultats
- **test_python.php** : Diagnostic Python
- **debug.php** : Diagnostic complet

### Boutons de navigation
- Toutes les pages ont un bouton "Retour à l'accueil"
- Les pages de résultats ont des boutons "Retour" et "Accueil"
- Navigation cohérente dans la même fenêtre

## Sécurité

- Protection des fichiers Python (.py)
- En-têtes de sécurité configurés
- Validation des entrées utilisateur
- Gestion sécurisée des fichiers temporaires

## Performance

- Cache des ressources statiques
- Compression des réponses
- Optimisation des requêtes
- Gestion efficace de la mémoire
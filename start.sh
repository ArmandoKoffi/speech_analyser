#!/bin/bash

# Script de démarrage pour Render
echo "Démarrage de l'application Speech Analyzer..."

# Créer le dossier temp s'il n'existe pas
if [ ! -d "temp" ]; then
    mkdir -p temp
    chmod 755 temp
    echo "Dossier temp créé"
fi

# Vérifier les permissions
chmod 644 *.php *.html *.css *.js 2>/dev/null || true
chmod 755 *.py 2>/dev/null || true

# Vérifier que Python est disponible
if command -v python3 &> /dev/null; then
    echo "Python3 trouvé: $(python3 --version)"
elif command -v python &> /dev/null; then
    echo "Python trouvé: $(python --version)"
else
    echo "ATTENTION: Python non trouvé!"
fi

# Vérifier les dépendances Python
echo "Vérification des dépendances Python..."
python3 -c "import nltk, textblob, sumy" 2>/dev/null && echo "Dépendances Python OK" || echo "ATTENTION: Dépendances Python manquantes"

# Démarrer le serveur PHP
echo "Démarrage du serveur PHP sur le port 10000..."
exec php -S 0.0.0.0:10000 -t . 2>&1
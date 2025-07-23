FROM ubuntu:22.04

# Empêche les prompts interactifs (comme celui du fuseau horaire)
ENV DEBIAN_FRONTEND=noninteractive

# Mise à jour et installation des paquets nécessaires
RUN apt-get update && apt-get install -y \
    tzdata \
    php \
    php-cli \
    php-mbstring \
    python3 \
    python3-pip \
    curl \
    unzip && \
    rm -rf /var/lib/apt/lists/*

# Définir un timezone par défaut (optionnel mais conseillé)
ENV TZ=Africa/Abidjan

# Crée un dossier de travail
WORKDIR /app

# Copie les fichiers du projet dans le conteneur
COPY . .

# Créer le dossier temp avec les bonnes permissions
RUN mkdir -p temp && chmod 755 temp

# Vérifier que Python fonctionne
RUN python3 --version

# Tester le script Python
RUN echo "Test de fonctionnement" > temp/test_docker.txt && \
    python3 analyze_text.py temp/test_docker.txt sentiment || echo "Erreur Python détectée"

# Ouvre le port
EXPOSE 10000

# Rendre le script de démarrage exécutable
RUN chmod +x start.sh

# Commande de démarrage
CMD ["./start.sh"]

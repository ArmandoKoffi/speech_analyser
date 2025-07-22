FROM ubuntu:22.04

# Empêche les prompts interactifs (comme celui du fuseau horaire)
ENV DEBIAN_FRONTEND=noninteractive

# Mise à jour et installation des paquets nécessaires
RUN apt-get update && apt-get install -y \
    tzdata \
    php \
    php-cli \
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

# Ouvre le port
EXPOSE 10000

# Commande de démarrage
CMD ["php", "-S", "0.0.0.0:10000"]

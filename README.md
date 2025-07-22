# Analyseur de Discours

## Description

L'Analyseur de Discours est une application web qui permet d'analyser des textes en français pour en extraire des informations pertinentes. Cette application combine PHP et Python pour offrir trois types d'analyses principales :

1. **Analyse de sentiment** : Détermine si le texte exprime un sentiment positif, négatif ou neutre.
2. **Extraction de mots-clés** : Identifie les termes les plus importants et pertinents du texte.
3. **Résumé automatique** : Génère une version condensée du texte qui préserve les points essentiels.

L'application dispose d'une interface utilisateur moderne, fluide et responsive avec des animations et des transitions élégantes.

## Prérequis

- XAMPP (ou équivalent avec PHP 7.0+)
- Python 3.6+ installé et accessible depuis la ligne de commande
- Navigateur web moderne (Chrome, Firefox, Edge, Safari)

## Installation

1. Assurez-vous que XAMPP est installé et que les services Apache sont démarrés.
2. Vérifiez que Python est correctement installé et accessible via la commande `python` dans le terminal.
3. Clonez ou téléchargez ce projet dans le répertoire `htdocs` de XAMPP.
4. Assurez-vous que le dossier `temp` existe dans le répertoire du projet ou que PHP a les droits pour le créer.

### Installation de Python (Windows)

1. Téléchargez Python depuis le [site officiel](https://www.python.org/downloads/windows/).
2. Lors de l'installation, **cochez impérativement la case "Add Python to PATH"**.
3. Complétez l'installation en suivant les instructions.
4. Vérifiez l'installation en ouvrant une invite de commande (cmd) et en tapant :
   ```
   python --version
   ```
   Vous devriez voir s'afficher la version de Python installée.

#### Si Python est déjà installé mais non reconnu

1. Ouvrez le Panneau de configuration > Système > Paramètres système avancés
2. Cliquez sur "Variables d'environnement"
3. Dans la section "Variables système", sélectionnez la variable "Path" et cliquez sur "Modifier"
4. Cliquez sur "Nouveau" et ajoutez le chemin vers le dossier d'installation de Python (par exemple `C:\Python39` et `C:\Python39\Scripts`)
5. Cliquez sur "OK" pour fermer toutes les fenêtres
6. Redémarrez votre invite de commande et XAMPP

## Structure du projet

```
speech_analyser/
├── index.php            # Page d'accueil avec formulaire
├── analyze.php          # Script de traitement et affichage des résultats
├── analyze_text.py      # Script Python pour l'analyse de texte
├── css/
│   └── style.css        # Styles et animations CSS
├── js/
│   └── script.js        # Fonctionnalités JavaScript interactives
└── temp/                # Dossier pour les fichiers temporaires (créé automatiquement)
```

## Utilisation

### Lancement de l'application

1. Démarrez XAMPP Control Panel et assurez-vous que le service Apache est actif (bouton "Start").
2. Ouvrez votre navigateur et accédez à l'URL suivante :
   ```
   http://localhost/Dossier_Projet_Simplon/speech_analyser/
   ```

### Analyse d'un texte

1. Sur la page d'accueil, entrez ou collez le texte que vous souhaitez analyser dans le champ prévu à cet effet.
2. Sélectionnez le type d'analyse souhaité dans le menu déroulant :
   - **Analyse de sentiment** : Évalue le ton émotionnel du texte.
   - **Extraction de mots-clés** : Identifie les termes les plus importants.
   - **Résumé automatique** : Crée une version condensée du texte.
   - **Analyse complète** : Effectue les trois analyses en même temps.
3. Cliquez sur le bouton "Analyser" pour lancer l'analyse.
4. Les résultats s'afficheront sur une nouvelle page avec des visualisations adaptées à chaque type d'analyse.

## Fonctionnalités détaillées

### Analyse de sentiment

L'analyse de sentiment utilise un dictionnaire de mots positifs et négatifs pour évaluer le ton émotionnel du texte. Le résultat comprend :

- Un score de sentiment (entre -1 et 1)
- Une classification (Positif, Neutre, Négatif)
- Une visualisation sous forme de jauge

### Extraction de mots-clés

L'extraction de mots-clés identifie les termes les plus significatifs du texte en :

- Filtrant les mots vides (articles, prépositions, etc.)
- Comptant la fréquence des mots restants
- Calculant un score d'importance pour chaque mot
- Affichant les résultats sous forme de nuage de mots-clés

### Résumé automatique

Le résumé automatique crée une version condensée du texte en :

- Divisant le texte en phrases
- Sélectionnant les phrases les plus importantes (début, milieu et fin du texte)
- Affichant le taux de compression (pourcentage de réduction)

## Personnalisation

### Modification des dictionnaires de sentiment

Vous pouvez personnaliser les dictionnaires de mots positifs et négatifs utilisés pour l'analyse de sentiment en modifiant les listes dans le fichier `analyze_text.py` :

```python
positive_words = ["bon", "excellent", "super", ...]
negative_words = ["mauvais", "terrible", "horrible", ...]
```

### Ajustement du taux de compression pour le résumé

Vous pouvez modifier le taux de compression du résumé automatique en changeant la valeur du paramètre `compression_rate` dans la fonction `generate_summary` du fichier `analyze_text.py` :

```python
def generate_summary(text, compression_rate=0.3):
```

### Modification de l'interface utilisateur

L'interface utilisateur peut être personnalisée en modifiant les fichiers CSS et JavaScript :

- `css/style.css` : Styles, couleurs, animations
- `js/script.js` : Comportements interactifs, effets visuels

## Dépannage

### Problèmes courants

1. **Erreur "Python n'est pas installé ou n'est pas accessible"** : 
   - Vérifiez que Python est correctement installé en suivant les instructions dans la section "Installation de Python".
   - Assurez-vous que le chemin vers Python est bien dans la variable PATH du système.
   - Redémarrez Apache après l'installation ou la configuration de Python.
   - Si vous utilisez XAMPP, essayez de redémarrer complètement XAMPP après avoir installé Python.

2. **Erreur "Une erreur s'est produite lors de l'analyse"** :
   - Vérifiez les détails de l'erreur affichés sur la page.
   - Assurez-vous que le dossier `temp` existe et est accessible en écriture.
   - Vérifiez que le script Python (`analyze_text.py`) est bien présent et a les permissions d'exécution.

3. **Erreur de permission** : 
   - Vérifiez que PHP a les droits d'écriture dans le dossier du projet pour créer le répertoire `temp`.
   - Sur Windows, assurez-vous que l'utilisateur qui exécute Apache a accès au dossier du projet.

4. **Aucun résultat ne s'affiche** : 
   - Vérifiez que le texte soumis n'est pas vide.
   - Essayez avec un texte plus court pour tester.
   - Consultez les logs d'erreur pour plus d'informations.

### Logs d'erreur

En cas de problème, consultez les logs d'erreur :

- **Logs Apache** : `C:\xampp\apache\logs\error.log`
- **Logs PHP** : Vérifiez la configuration dans `php.ini` pour localiser les logs PHP

### Test de l'installation Python

Pour vérifier si Python est correctement configuré pour être utilisé par PHP :

1. Créez un fichier `test_python.php` dans le dossier du projet avec le contenu suivant :
   ```php
   <?php
   echo "<h1>Test de l'installation Python</h1>";
   echo "<pre>";
   echo shell_exec('python --version 2>&1');
   echo "</pre>";
   ?>
   ```

2. Accédez à ce fichier via votre navigateur :
   ```
   http://localhost/Dossier_Projet_Simplon/speech_analyser/test_python.php
   ```

3. Si Python est correctement installé et accessible, vous devriez voir sa version s'afficher.

## Exemple de test

Voici un exemple de texte que vous pouvez utiliser pour tester l'application :

```
Le développement durable est un concept qui vise à concilier les aspects économiques, sociaux et environnementaux du développement. Il s'agit de répondre aux besoins du présent sans compromettre la capacité des générations futures à répondre à leurs propres besoins. Cette approche implique une utilisation raisonnée des ressources naturelles, une réduction des émissions de gaz à effet de serre et une meilleure répartition des richesses. De nombreuses entreprises et gouvernements s'engagent aujourd'hui dans cette voie, conscients des défis environnementaux et sociaux auxquels notre planète est confrontée. Cependant, malgré ces efforts, beaucoup reste à faire pour assurer un avenir durable à notre planète et à ses habitants.
```

## Licence

Ce projet est distribué sous licence MIT. Voir le fichier LICENSE pour plus d'informations.

## Auteur

Ce projet a été développé dans le cadre d'une formation Simplon.

---

© 2025 Analyseur de Discours | Projet Simplon

#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
import json
import re
import random
import os
from collections import Counter

# Forcer l'encodage UTF-8 pour les entrées/sorties
if sys.version_info[0] >= 3:
    import io
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
    sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8')
else:
    import codecs
    sys.stdout = codecs.getwriter('utf-8')(sys.stdout)
    sys.stderr = codecs.getwriter('utf-8')(sys.stderr)

# Compatibilité Python 2/3
PY3 = sys.version_info[0] == 3

# Fonction pour analyser le sentiment
def analyze_sentiment(text):
    # Nettoyer le texte
    text = clean_text(text)
    
    # Dictionnaires de mots positifs et négatifs (simplifiés pour l'exemple)
    positive_words = [
        "bon", "excellent", "super", "génial", "heureux", "content", "satisfait", "agréable", 
        "merveilleux", "fantastique", "incroyable", "parfait", "réussi", "succès", "aimer", 
        "adorer", "plaisir", "joie", "espoir", "optimiste", "positif", "confiant", "beau", 
        "brillant", "intelligent", "efficace", "utile", "bénéfique", "favorable", "avantage"
    ]
    
    negative_words = [
        "mauvais", "terrible", "horrible", "affreux", "triste", "malheureux", "déçu", "désagréable", 
        "catastrophique", "épouvantable", "détestable", "échec", "raté", "détester", "haïr", 
        "souffrance", "peine", "désespoir", "pessimiste", "négatif", "méfiant", "laid", 
        "stupide", "inefficace", "inutile", "nuisible", "défavorable", "désavantage", "problème", "difficile"
    ]
    
    # Convertir en minuscules et tokeniser
    words = re.findall(r'\b\w+\b', text.lower())
    
    # Compter les mots positifs et négatifs
    positive_count = sum(1 for word in words if word in positive_words)
    negative_count = sum(1 for word in words if word in negative_words)
    
    # Calculer le score de sentiment (-1 à 1)
    total_count = positive_count + negative_count
    if total_count > 0:
        score = (positive_count - negative_count) / total_count
    else:
        score = 0
    
    # Déterminer le label
    if score > 0.25:
        label = "Positif"
    elif score < -0.25:
        label = "Négatif"
    else:
        label = "Neutre"
    
    return {
        "score": score,
        "label": label,
        "positive_count": positive_count,
        "negative_count": negative_count
    }

# Fonction pour nettoyer le texte et normaliser les caractères
def clean_text(text):
    if not PY3 and isinstance(text, str):
        # Pour Python 2, convertir str en unicode
        try:
            text = text.decode('utf-8')
        except UnicodeDecodeError:
            try:
                text = text.decode('latin-1')
            except UnicodeDecodeError:
                pass
    
    # Normaliser les caractères accentués si nécessaire
    return text

# Fonction pour extraire les mots-clés
def extract_keywords(text, max_keywords=15):
    # Nettoyer le texte
    text = clean_text(text)
    
    # Liste de mots vides (stop words) en français
    stop_words = [
        "le", "la", "les", "un", "une", "des", "et", "est", "il", "elle", "ils", "elles",
        "nous", "vous", "je", "tu", "on", "ce", "cette", "ces", "son", "sa", "ses", "mon",
        "ma", "mes", "ton", "ta", "tes", "notre", "nos", "votre", "vos", "leur", "leurs",
        "du", "de", "d'", "l'", "au", "aux", "à", "en", "dans", "sur", "sous", "avec",
        "pour", "par", "que", "qui", "quoi", "dont", "où", "comment", "pourquoi", "quand",
        "plus", "moins", "très", "trop", "peu", "beaucoup", "aussi", "ainsi", "alors", "mais",
        "ou", "donc", "car", "si", "ni", "ne", "pas", "non", "oui", "comme", "tout", "tous",
        "toute", "toutes", "autre", "autres", "même", "être", "avoir", "faire", "dire", "voir",
        "aller", "savoir", "pouvoir", "vouloir", "falloir", "devoir", "venir", "prendre", "donner"
    ]
    
    # Tokeniser et filtrer les mots vides
    words = re.findall(r'\b\w+\b', text.lower())
    filtered_words = [word for word in words if word not in stop_words and len(word) > 2]
    
    # Compter les occurrences
    word_counts = Counter(filtered_words)
    
    # Extraire les mots-clés les plus fréquents
    keywords = []
    for word, count in word_counts.most_common(max_keywords):
        # Calculer un score basé sur la fréquence
        score = min(count / (len(filtered_words) * 0.1), 1.0)
        keywords.append({"word": word, "score": score, "count": count})
    
    return keywords

# Fonction pour générer un résumé automatique
def generate_summary(text, compression_rate=0.3):
    # Nettoyer le texte
    text = clean_text(text)
    
    # Diviser le texte en phrases
    sentences = re.split(r'(?<=[.!?])\s+', text)
    
    # Calculer le nombre de phrases à conserver
    num_sentences = max(1, int(len(sentences) * compression_rate))
    
    # Méthode simple: prendre les premières phrases et quelques phrases du milieu et de la fin
    if len(sentences) <= num_sentences:
        return text
    
    summary_sentences = []
    
    # Prendre la première phrase
    summary_sentences.append(sentences[0])
    
    # Prendre quelques phrases du milieu
    middle_start = len(sentences) // 4
    middle_end = 3 * len(sentences) // 4
    middle_step = max(1, (middle_end - middle_start) // (num_sentences - 2))
    
    for i in range(middle_start, middle_end, middle_step):
        if len(summary_sentences) < num_sentences - 1:
            summary_sentences.append(sentences[i])
    
    # Prendre la dernière phrase
    if len(sentences) > 1:
        summary_sentences.append(sentences[-1])
    
    # Joindre les phrases en un texte
    summary = " ".join(summary_sentences)
    
    return summary

# Fonction principale
def main():
    # Vérifier les arguments
    if len(sys.argv) < 3:
        error_msg = {"error": "Arguments insuffisants. Usage: python analyze_text.py <fichier_texte> <type_analyse>"}
        print(json.dumps(error_msg, ensure_ascii=False))
        sys.exit(1)
    
    input_file = sys.argv[1]
    analysis_type = sys.argv[2]
    
    try:
        # Vérifier si le fichier existe
        if not os.path.exists(input_file):
            raise Exception("Le fichier '{}' n'existe pas".format(input_file))
        
        # Lire le fichier texte avec plusieurs essais d'encodage
        text = None
        encodings_to_try = ['utf-8', 'utf-8-sig', 'latin-1', 'cp1252']
        
        for encoding in encodings_to_try:
            try:
                with open(input_file, 'r', encoding=encoding) as f:
                    text = f.read()
                break
            except (UnicodeDecodeError, UnicodeError):
                continue
        
        if text is None:
            # Dernier recours: lire en mode binaire et essayer de décoder
            with open(input_file, 'rb') as f:
                raw_data = f.read()
                for encoding in encodings_to_try:
                    try:
                        text = raw_data.decode(encoding)
                        break
                    except (UnicodeDecodeError, UnicodeError):
                        continue
        
        if text is None:
            raise Exception("Impossible de décoder le fichier avec les encodages supportés")
        
        # Initialiser le résultat
        result = {}
        
        # Effectuer l'analyse selon le type demandé
        if analysis_type == "sentiment" or analysis_type == "complete":
            result["sentiment"] = analyze_sentiment(text)
        
        if analysis_type == "keywords" or analysis_type == "complete":
            result["keywords"] = extract_keywords(text)
        
        if analysis_type == "summary" or analysis_type == "complete":
            result["summary"] = generate_summary(text)
        
        # Retourner le résultat en JSON avec encodage UTF-8 explicite
        print(json.dumps(result, ensure_ascii=False, separators=(',', ':')))
        
    except Exception as e:
        # Créer un message d'erreur détaillé
        error_details = str(e)
        error_msg = {
            "error": "Une erreur s'est produite lors de l'analyse", 
            "details": error_details,
            "python_version": "Python 3" if PY3 else "Python 2"
        }
        
        # Afficher l'erreur en JSON avec encodage UTF-8
        print(json.dumps(error_msg, ensure_ascii=False, separators=(',', ':')))

if __name__ == "__main__":
    main()

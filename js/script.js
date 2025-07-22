document.addEventListener('DOMContentLoaded', function() {
    // Animations au chargement de la page
    animateElements();
    
    // Initialiser le formulaire
    initForm();
    
    // Initialiser les animations des résultats si on est sur la page de résultats
    if (document.querySelector('.results-section')) {
        initResultsAnimations();
    }
});

// Fonction pour animer les éléments au chargement
function animateElements() {
    // Ajouter des classes d'animation aux éléments
    const elements = document.querySelectorAll('.card, .feature-card');
    elements.forEach((el, index) => {
        // Ajouter un délai progressif pour créer un effet en cascade
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 100 * index);
    });
}

// Fonction pour initialiser le formulaire
function initForm() {
    const form = document.getElementById('speech-form');
    const textarea = document.getElementById('speech-text');
    const analysisType = document.getElementById('analysis-type');
    
    if (form) {
        // Ajouter un compteur de caractères
        const charCounter = document.createElement('div');
        charCounter.className = 'char-counter';
        charCounter.innerHTML = '0 caractères';
        textarea.parentNode.appendChild(charCounter);
        
        // Mettre à jour le compteur de caractères
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCounter.innerHTML = count + ' caractères';
            
            // Changer la couleur en fonction de la longueur
            if (count > 500) {
                charCounter.style.color = '#28a745';
            } else if (count > 0) {
                charCounter.style.color = '#6c757d';
            } else {
                charCounter.style.color = '#dc3545';
            }
        });
        
        // Ajouter une animation au survol des options du select
        analysisType.addEventListener('focus', function() {
            this.classList.add('active');
        });
        
        analysisType.addEventListener('blur', function() {
            this.classList.remove('active');
        });
        
        // Ajouter un effet de loading lors de la soumission
        form.addEventListener('submit', function(e) {
            // Vérifier si le textarea est vide
            if (textarea.value.trim() === '') {
                e.preventDefault();
                showError('Veuillez entrer un texte à analyser.');
                textarea.focus();
                return false;
            }
            
            // Créer et afficher l'animation de chargement
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analyse en cours...';
            submitBtn.disabled = true;
            
            // Créer un élément de loading
            const loadingEl = document.createElement('div');
            loadingEl.className = 'loading';
            loadingEl.innerHTML = `
                <div class="loading-spinner"></div>
                <p>Analyse en cours, veuillez patienter...</p>
            `;
            
            // Insérer après le formulaire
            this.parentNode.appendChild(loadingEl);
            loadingEl.style.display = 'block';
            
            // Ajouter une classe pour l'animation de transition
            document.querySelector('.container').classList.add('page-transition');
        });
    }
}

// Fonction pour afficher une erreur
function showError(message) {
    // Vérifier si un message d'erreur existe déjà
    let errorEl = document.querySelector('.form-error');
    
    if (!errorEl) {
        // Créer un nouvel élément d'erreur
        errorEl = document.createElement('div');
        errorEl.className = 'form-error error-message';
        const form = document.getElementById('speech-form');
        form.parentNode.insertBefore(errorEl, form);
    }
    
    // Mettre à jour le message
    errorEl.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    
    // Animer l'apparition
    errorEl.style.animation = 'none';
    setTimeout(() => {
        errorEl.style.animation = 'slideInLeft 0.5s ease-out';
    }, 10);
    
    // Faire disparaître après 5 secondes
    setTimeout(() => {
        errorEl.style.opacity = '0';
        setTimeout(() => {
            errorEl.remove();
        }, 500);
    }, 5000);
}

// Fonction pour initialiser les animations des résultats
function initResultsAnimations() {
    // Animer la barre de sentiment
    const meterFill = document.querySelector('.meter-fill');
    if (meterFill) {
        setTimeout(() => {
            meterFill.style.width = meterFill.getAttribute('style').split(':')[1];
        }, 300);
    }
    
    // Animer les mots-clés
    const keywords = document.querySelectorAll('.keyword');
    keywords.forEach((keyword, index) => {
        keyword.style.opacity = '0';
        keyword.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            keyword.style.opacity = '1';
            keyword.style.transform = 'translateY(0)';
        }, 100 + (index * 50));
        
        // Ajouter un effet de survol
        keyword.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.05)';
        });
        
        keyword.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Animer le résumé
    const summaryText = document.querySelector('.summary-text');
    if (summaryText) {
        summaryText.style.opacity = '0';
        summaryText.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            summaryText.style.opacity = '1';
            summaryText.style.transform = 'translateX(0)';
        }, 500);
    }
}

// Fonction pour créer des effets de parallaxe
window.addEventListener('scroll', function() {
    const scrollPosition = window.scrollY;
    
    // Effet de parallaxe sur les cartes de fonctionnalités
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach((card, index) => {
        const speed = 0.05 + (index * 0.01);
        const yPos = -scrollPosition * speed;
        card.style.transform = `translateY(${yPos}px)`;
    });
    
    // Effet de parallaxe sur le logo
    const logo = document.querySelector('.logo');
    if (logo) {
        const rotation = scrollPosition * 0.05;
        logo.style.transform = `rotate(${rotation}deg)`;
    }
});

// Fonction pour ajouter des effets de survol
document.addEventListener('mousemove', function(e) {
    const cards = document.querySelectorAll('.card, .feature-card');
    
    cards.forEach(card => {
        // Calculer la position relative de la souris par rapport à la carte
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // Vérifier si la souris est sur la carte
        if (x >= 0 && x <= rect.width && y >= 0 && y <= rect.height) {
            // Calculer la rotation en fonction de la position de la souris
            const rotateX = (y / rect.height - 0.5) * 5;
            const rotateY = (x / rect.width - 0.5) * -5;
            
            // Appliquer la transformation
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            card.style.boxShadow = `${rotateY * -1}px ${rotateX}px 20px rgba(0, 0, 0, 0.1)`;
        } else {
            // Réinitialiser la transformation lorsque la souris quitte la carte
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
            card.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
        }
    });
});

// Réinitialiser les transformations lorsque la souris quitte les cartes
document.addEventListener('mouseleave', function() {
    const cards = document.querySelectorAll('.card, .feature-card');
    cards.forEach(card => {
        card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
        card.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
    });
});
// Smooth scrolling pour les ancres
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Animation d'apparition au scroll
const observerOptions = {
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Appliquer l'animation aux sections
document.querySelectorAll('section').forEach(section => {
    section.style.opacity = '0';
    section.style.transform = 'translateY(20px)';
    section.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
    observer.observe(section);
});

// --- SÉCURISATION DU FORMULAIRE ---
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des paramètres URL pour les messages de succès/erreur
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('sent') === '1') {
        showMessage('Message envoyé avec succès! Je vous répondrai dans les plus brefs délais.', 'success');
        // Nettoyer l'URL sans recharger la page
        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (urlParams.get('error') === '1') {
        showMessage('Une erreur s\'est produite lors de l\'envoi du message.', 'error');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const messageTextarea = document.querySelector('textarea[name="message"]');
    const charCountElement = document.getElementById('charCount');
    
    // Compteur de caractères pour le message
    if (messageTextarea && charCountElement) {
        messageTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCountElement.textContent = currentLength;
            
            // Change la couleur si proche de la limite
            if (currentLength > 1800) {
                charCountElement.style.color = '#e74c3c';
            } else if (currentLength > 1500) {
                charCountElement.style.color = '#f39c12';
            } else {
                charCountElement.style.color = '#2ecc71';
            }
        });
    }
    
    // Protection contre les doubles soumissions et validation
    if (contactForm) {
        let isSubmitting = false;
        
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Empêcher les doubles soumissions
            if (isSubmitting) {
                return false;
            }
            
            // Validation côté client
            if (!validateForm()) {
                return false;
            }
            
            // Protection anti-bot (vérifier le champ honeypot)
            const honeypot = document.querySelector('input[name="honeypot"]');
            if (honeypot && honeypot.value !== '') {
                showMessage('Erreur de validation', 'error');
                return false;
            }
            
            isSubmitting = true;
            showLoading(true);
            
            // Envoyer le formulaire
            const formData = new FormData(this);
            
            fetch('contact.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Response received:', data); // Debug
                try {
                    const jsonData = JSON.parse(data);
                    if (jsonData.status === 'success') {
                        showMessage('✅ Message envoyé avec succès!', 'success');
                        contactForm.reset();
                        if (charCountElement) charCountElement.textContent = '0';
                    } else {
                        showMessage('❌ ' + (jsonData.message || 'Erreur lors de l\'envoi'), 'error');
                        console.error('Erreur détaillée:', jsonData);
                    }
                } catch (e) {
                    console.log('JSON parse failed, raw response:', data); // Debug
                    console.error('Response parsing error:', e);
                    
                    // Analyser les erreurs spécifiques dans la réponse
                    if (data.includes('Fatal error') || data.includes('Parse error')) {
                        showMessage('❌ Erreur serveur détectée. Vérifiez la console.', 'error');
                    } else if (data.includes('PHPMailer') || data.includes('Failed to open stream')) {
                        showMessage('❌ Erreur de configuration email', 'error');
                    } else if (data.includes('success') || data.includes('envoyé') || data.includes('✅')) {
                        showMessage('✅ Message envoyé avec succès!', 'success');
                        contactForm.reset();
                        if (charCountElement) charCountElement.textContent = '0';
                    } else {
                        showMessage('❌ Erreur serveur: ' + (data.length > 100 ? data.substring(0, 100) + '...' : data), 'error');
                    }
                }
            })
            .catch(error => {
                showMessage('Erreur de connexion', 'error');
            })
            .finally(() => {
                isSubmitting = false;
                showLoading(false);
            });
        });
    }
    
    function validateForm() {
        const nom = document.querySelector('input[name="nom"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const objet = document.querySelector('input[name="objet"]').value.trim();
        const message = document.querySelector('textarea[name="message"]').value.trim();
        
        // Validations détaillées
        if (!nom) {
            showMessage('❌ Le nom est obligatoire', 'error');
            return false;
        }
        if (nom.length < 2) {
            showMessage('❌ Le nom doit contenir au moins 2 caractères', 'error');
            return false;
        }
        if (nom.length > 50) {
            showMessage('❌ Le nom ne peut pas dépasser 50 caractères', 'error');
            return false;
        }
        if (!/^[a-zA-ZÀ-ÿ\s\-']+$/.test(nom)) {
            showMessage('❌ Le nom contient des caractères non valides', 'error');
            return false;
        }
        
        if (!email) {
            showMessage('❌ L\'email est obligatoire', 'error');
            return false;
        }
        if (!email.includes('@') || !email.includes('.')) {
            showMessage('❌ Format d\'email invalide', 'error');
            return false;
        }
        
        if (!objet) {
            showMessage('❌ L\'objet est obligatoire', 'error');
            return false;
        }
        if (objet.length < 5) {
            showMessage('❌ L\'objet doit contenir au moins 5 caractères', 'error');
            return false;
        }
        if (objet.length > 100) {
            showMessage('❌ L\'objet ne peut pas dépasser 100 caractères', 'error');
            return false;
        }
        
        if (!message) {
            showMessage('❌ Le message est obligatoire', 'error');
            return false;
        }
        if (message.length < 10) {
            showMessage('❌ Le message doit contenir au moins 10 caractères', 'error');
            return false;
        }
        if (message.length > 2000) {
            showMessage('❌ Le message ne peut pas dépasser 2000 caractères', 'error');
            return false;
        }
        
        return true;
    }
    
    function showLoading(show) {
        const btnText = document.querySelector('.btn-text');
        const btnLoading = document.querySelector('.btn-loading');
        const submitButton = document.getElementById('submitBtn');
        
        if (show) {
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
            submitButton.disabled = true;
        } else {
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
            submitButton.disabled = false;
        }
    }
    
    function showMessage(message, type) {
        // Supprimer les anciennes notifications
        const existingNotifications = document.querySelectorAll('.alert-notification');
        existingNotifications.forEach(notif => notif.remove());

        // Créer une notification avec le style du site
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-notification`;
        notification.innerHTML = message;
        
        // Style de positionnement pour la notification flottante
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        `;
        
        document.body.appendChild(notification);
        
        // Suppression automatique après 6 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(-50%) translateY(-20px)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 6000);
    }
});

// --- FONCTIONS POPUP EMAIL ---
        function openEmailPopup() {
            const popup = document.getElementById('emailPopup');
            popup.classList.add('show');
            document.body.style.overflow = 'hidden'; // Empêche le scroll
        }

        function closeEmailPopup() {
            const popup = document.getElementById('emailPopup');
            popup.classList.remove('show');
            document.body.style.overflow = 'auto'; // Remet le scroll
            
            // Masquer le message de copie s'il est visible
            const copyMessage = document.getElementById('copyMessage');
            copyMessage.classList.remove('show');
        }

        function copyEmail() {
            const email = 'dufeuilletheo@gmail.com';
            
            // Copier dans le presse-papiers
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(email).then(() => {
                    showCopyMessage();
                }).catch(err => {
                    console.error('Erreur lors de la copie:', err);
                    fallbackCopyTextToClipboard(email);
                });
            } else {
                // Méthode de fallback pour les navigateurs plus anciens
                fallbackCopyTextToClipboard(email);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                showCopyMessage();
            } catch (err) {
                console.error('Erreur lors de la copie:', err);
            }
            
            document.body.removeChild(textArea);
        }

        function showCopyMessage() {
            const copyMessage = document.getElementById('copyMessage');
            copyMessage.classList.add('show');
            
            // Masquer le message après 3 secondes
            setTimeout(() => {
                copyMessage.classList.remove('show');
            }, 3000);
        }

        // Fermer le popup en cliquant en dehors
        document.getElementById('emailPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEmailPopup();
            }
        });

        // Fermer le popup avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const popup = document.getElementById('emailPopup');
                if (popup.classList.contains('show')) {
                    closeEmailPopup();
                }
            }
        });
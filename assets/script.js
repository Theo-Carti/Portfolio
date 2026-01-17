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

        // Simulation de soumission de formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button');
            const originalText = btn.innerText;
            
            btn.innerText = "TRANSMISSION EN COURS...";
            btn.style.backgroundColor = "#2ecc71";
            btn.style.borderColor = "#2ecc71";
            
            setTimeout(() => {
                alert("Message transmis avec succès au serveur.");
                btn.innerText = originalText;
                btn.style.backgroundColor = "";
                btn.style.borderColor = "";
                this.reset();
            }, 1500);
        });
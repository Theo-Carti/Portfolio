<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Théo Dufeuille</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

    <header>
        <div class="container nav-container">
            <div class="logo"><i class="fa-solid fa-microchip"></i>Portfolio</div>
            <nav class="nav-links">
                <a href="#about">Profil</a>
                <a href="#skills">Compétences</a>
                <a href="#projects">Projets</a>
                <a href="#Parcours">Parcours</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <section id="hero">
        <div class="hero-content">
            <h1 class="hero-title">Théo Dufeuille</h1>
            <h2 class="hero-subtitle">Étudiant alternant en Génie Electrique et Informatique Industrielle</h2>
            <p class="hero-tagline">« Concevoir, automatiser et électrifier les systèmes intelligents de demain »</p>
            <div class="cta-group">
                <a href="#contact" class="btn btn-accent">Contact</a>
                <a href="#projects" class="btn">Découvrir mes Projets</a>
            </div>
        </div>
    </section>

    <section id="about" class="container">
        <h2 class="section-title"><span>01.</span> À propos de moi</h2>
        <div class="about-grid">
            <div class="about-text">
                <p>Je suis Théo Dufeuille, passionné par le génie électrique, la robotique et l'automatique. Je suis étudiant en Génie Électrique et Informatique Industrielle en 3ème année, spécialisé en Automatisme et informatique industrielle à l'IUT de l'Aisne situé à Cuffies. Je suis en alternance chez Tereos Bucy-le-Long, mon poste est apprenti électricien industriel.</p>
                <p>Mon objectif est d'apprendre les nouvelles technologies et de comprendre l'industrie de demain. Un autre de mes objectifs est de rentrer dans une école d'ingénieur avec comme spécialité l'automatique et la robotique.</p>
                <br>
                <div class="tech-box">
                    <h3><i class="fa-solid fa-bullseye"></i> Objectifs</h3>
                    <p>• Apprendre et comprendre l'industrie du futur</p>
                    <p>• Intégration d'une école d'ingénieur</p>
                    <p>• Trouver une alternance en tant qu'ingénieur en automatique, robotique ou en bureau d'étude d'automatisme</p>
                </div>
            </div>
            <div class="about-visual" style="text-align: center;">
                <i class="fa-solid fa-robot" style="font-size: 15rem; color: #1e222b; text-shadow: 0 0 5px var(--primary-blue);"></i>
            </div>
        </div>
    </section>

    <section id="skills">
        <div class="container">
            <h2 class="section-title"><span>02.</span> Compétences Techniques</h2>
            <div class="skills-grid">
                <div class="skill-card">
                    <div class="skill-icon"><i class="fa-solid fa-bolt"></i></div>
                    <h3>Génie Électrique</h3>
                    <ul class="skill-list">
                        <li>Schémas: Xrelais, EPLAN</li>
                        <li>Dimensionnement câbles & protections</li>
                        <li>Variateurs de vitesse (VFD)</li>
                        <li>Habilitations Électriques B1V</li>
                        <li>Branchement électrique B1V</li>
                    </ul>
                </div>
                <div class="skill-card">
                    <div class="skill-icon"><i class="fa-solid fa-gears"></i></div>
                    <h3>Automatisme</h3>
                    <ul class="skill-list">
                        <li>Siemens (TIA Portal), Schneider (Control Expert), Wago (Codesys)</li>
                        <li>Langages: Ladder, ST, Grafcet</li>
                        <li>Réseaux: Profinet, Modbus TCP/RTU</li>
                        <li>Supervision (PCvue)</li>
                    </ul>
                </div>
                <div class="skill-card">
                    <div class="skill-icon"><i class="fa-solid fa-hand-holding-hand"></i></div>
                    <h3>Robotique</h3>
                    <ul class="skill-list">
                        <li>Robotique Industrielle (Cobot, Kuka)</li>
                        <li>Vision industrielle (Factory.io)</li>
                    </ul>
                </div>
                <div class="skill-card">
                    <div class="skill-icon"><i class="fa-solid fa-code"></i></div>
                    <h3>Informatique Indus.</h3>
                    <ul class="skill-list">
                        <li>C / Java / Arduino</li>
                        <li>IoT & MQTT</li>
                        <li>MATLAB / Simulink</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="projects" class="container">
        <h2 class="section-title"><span>03.</span> Projets Techniques</h2>
        <div class="projects-grid">
            
            <div class="project-card">
                <div class="project-header">
                    <h3>Programmation séquence Robot KUKA</h3>
                    <span class="project-status status-in-progress">En cours</span>
                </div>
                <div class="project-body">
                    <p><strong>Problématique :</strong> Réussir à faire une séquence complète en programmant le robot et réussir à faire communiquer le robot avec l'automate.</p>
                    <p><strong>Solution :</strong> Intégration d'une IHM pour le choix des séquences.</p>
                    <div class="project-techs">
                        <span class="tech-tag">Visual work (KRL)</span>
                        <span class="tech-tag">TIA Portal</span>
                        <span class="tech-tag">Grafcet et Gemma</span>
                        <span class="tech-tag">Profinet</span>
                    </div>
                   <!-- <a href="#" class="btn btn-sm">Documentation <i class="fa-solid fa-file-pdf"></i></a> -->
                </div>
            </div>

            <div class="project-card">
                <div class="project-header">
                    <h3>Automatisme Simulation château d'eau et intégration d'une IHM</h3>
                    <span class="project-status status-completed">Complété</span>
                </div>
                <div class="project-body">
                    <p><strong>Problématique :</strong> Modernisation d'un simulateur de distribution d'eau.</p>
                    <p><strong>Solution :</strong> Ajout d'un Automate Schneider, ajout d'une nouvelle platine et création d'une IHM tactile ergonomique.</p>
                    <div class="project-techs">
                        <span class="tech-tag">Eco Structure Operator Terminal</span>
                        <span class="tech-tag">IHM HMIST6400</span>
                        <span class="tech-tag">ModbusSIO (Ethernet)</span>
                    </div>
                    <a href="assets/pdf/SAE-Pompe-a-eau.pdf" class="btn btn-sm" target="_blank">Documentation <i class="fa-solid fa-file-pdf"></i></a>
                </div>
            </div>

            <div class="project-card">
                <div class="project-header">
                    <h3>Simulation d'une cuve via factory.io</h3>
                    <span class="project-status status-completed">Complété</span>
                </div>
                <div class="project-body">
                    <p>Découverte du logiciel Factory.io en simulant une cuve qui communique via un automate Wago et découverte du logiciel Codesys nous permettant la programmation de la cuve avec un mode automatique et manuel.</p>
                    <div class="project-techs">
                        <span class="tech-tag">Factory.io</span>
                        <span class="tech-tag">Codesys</span>
                        <span class="tech-tag">Simulation IHM</span>
                    </div>
                    <!-- <a href="" class="btn btn-sm">Documentation <i class="fa-solid fa-file-pdf"></i></a> -->
                </div>
            </div>

        </div>
    </section>
   
    <section id="Parcours" class="container">
        <h2 class="section-title"><span>04.</span> Parcours académique</h2>                   
            <div class="timeline-item right">
                <div class="content-box">
                    <div class="date">2023- Aujourd'hui</div>
                    <h3>BUT Génie Électrique et Informatique Industrielle</h3>
                    <h4>IUT de l'Aisne</h4>
                    <p>Spécialisation Automatisme et Informatique Industrielle.</p>
                </div>
            </div>
            <div class="timeline-item left">
                <div class="content-box">
                    <div class="date">2021-2023</div>
                    <h3>Baccalauréat Technologique STI2D</h3>
                    <h4>Lycée Jean Racine Montdidier</h4>
                    <p>Spécialisation Énergie et Environnement</p>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 50px;">
            <a href="assets/pdf/CV-theo-Dufeuille.pdf" class="btn btn-accent" target="_blank"><i class="fa-solid fa-download"></i> Télécharger CV Complet</a>
        </div>
    </section>

    <section id="contact">
        <div class="container">
            <h2 class="section-title"><span>05.</span> Me contacter</h2>
            
            <?php 
            
            // Affichage des messages de succès ou d'erreur
            if (isset($_SESSION['contact_success'])) {
                echo '<div class="alert alert-success">✅ Message envoyé avec succès! Je vous répondrai dans les plus brefs délais.</div>';
                unset($_SESSION['contact_success']);
            }
            
            if (isset($_SESSION['contact_error'])) {
                echo '<div class="alert alert-error">❌ ' . htmlspecialchars($_SESSION['contact_error']) . '</div>';
                unset($_SESSION['contact_error']);
            }
            
            // Génération d'un token CSRF sécurisé
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            
            // Génération d'un token unique pour cette soumission
            $submissionToken = bin2hex(random_bytes(16));
            $_SESSION['form_tokens'][] = $submissionToken;
            
            // Nettoyer les anciens tokens (garder seulement les 10 derniers)
            if (isset($_SESSION['form_tokens']) && count($_SESSION['form_tokens']) > 10) {
                $_SESSION['form_tokens'] = array_slice($_SESSION['form_tokens'], -10);
            }
            ?>
            
            <div class="contact-wrapper">
                <form class="control-panel" action="contact.php" method="POST" id="contactForm">
                    <!-- Token CSRF sécurisé -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <!-- Token de soumission pour éviter les doubles envois -->
                    <input type="hidden" name="submission_token" value="<?php echo $submissionToken; ?>">
                    
                    <!-- Honeypot anti-bots (champ invisible) -->
                    <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;">
                        <input type="text" name="website" tabindex="-1" autocomplete="off" placeholder="Ne pas remplir">
                        <input type="email" name="email_check" tabindex="-1" autocomplete="off">
                        <input type="tel" name="phone" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="input-group">
                        <label>Votre Nom *</label>
                        <input type="text" name="nom" placeholder="Entrez votre nom" 
                               minlength="2" maxlength="50" 
                               pattern="[a-zA-ZÀ-ÿ\s\-']+"
                               title="Nom valide uniquement (2-50 caractères, lettres et espaces)"
                               required>
                    </div>
                    <div class="input-group">
                        <label>Adresse Email *</label>
                        <input type="email" name="email" placeholder="email@exemple.com" 
                               maxlength="100"
                               title="Adresse email valide requise"
                               required>
                    </div>
                    <div class="input-group full-width">
                        <label>Objet de la transmission *</label>
                        <input type="text" name="objet" placeholder="Proposition de projet / Recrutement" 
                               minlength="5" maxlength="100"
                               title="Objet entre 5 et 100 caractères"
                               required>
                    </div>
                    <div class="input-group full-width">
                        <label>Votre message *</label>
                        <textarea name="message" rows="5" placeholder="Saisissez votre message ici..." 
                                  minlength="10" maxlength="2000"
                                  title="Message entre 10 et 2000 caractères"
                                  required></textarea>
                        <div class="char-counter">
                            <span id="charCount">0</span>/2000 caractères
                        </div>
                    </div>
                    <div class="full-width" style="text-align: right;">
                        <button type="submit" class="btn btn-accent" id="submitBtn">
                            <span class="btn-text">ENVOYER TRANSMISSION</span>
                            <span class="btn-loading" style="display: none;">
                                <i class="fa-solid fa-spinner fa-spin"></i> ENVOI EN COURS...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="socials">
                <a target="_blank" href="https://www.linkedin.com/in/th%C3%A9o-dufeuille-34ab2b2a4/"><i class="fa-brands fa-linkedin"></i></a>
                <a target="_blank" href="#"><i class="fa-brands fa-github"></i></a>
                <a href="#" onclick="openEmailPopup()"><i class="fa-solid fa-envelope"></i></a>
            </div>
            <p style="font-family: var(--font-sub); color: var(--text-dim);">
                ©2026 Designed By Théo Dufeuille.<br>
                <em>"Merci de votre visite"</em>
            </p>
        </div>
    </footer>

    <!-- Popup Email -->
    <div id="emailPopup" class="email-popup-overlay">
        <div class="email-popup">
            <div class="popup-header">
                <h3><i class="fa-solid fa-envelope"></i> Contact Email</h3>
                <button class="close-popup" onclick="closeEmailPopup()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="popup-content">
                <div class="email-display">
                    <p>Vous pouvez me contacter à :</p>
                    <div class="email-address" id="emailAddress">
                        dufeuilletheo@gmail.com
                    </div>
                    <div class="popup-actions">
                        <button onclick="copyEmail()" class="btn btn-accent">
                            <i class="fa-solid fa-copy"></i> Copier
                        </button>
                    </div>
                    <div id="copyMessage" class="copy-message">Adresse email copiée !</div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
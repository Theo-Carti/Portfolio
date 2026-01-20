# 🤖 Portfolio Théo Dufeuille

**Portfolio personnel d'un étudiant en Génie Électrique et Informatique Industrielle**

![Portfolio Preview](https://img.shields.io/badge/Status-En%20ligne-brightgreen)
![PHP](https://img.shields.io/badge/PHP-7.4+-blue)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black)

## 📋 Description

Portfolio moderne et responsive présentant les compétences, projets et parcours de Théo Dufeuille, étudiant en 3ème année de BUT GEII (Génie Électrique et Informatique Industrielle) spécialisé en Automatisme et Informatique Industrielle.

## ⚡ Fonctionnalités

### 🎨 **Design & Interface**
- **Design futuriste** avec thème sombre tech
- **Animations fluides** et effets visuels 3D
- **Responsive design** adaptatif mobile/desktop
- **Navigation fluide** avec ancres
- **Typographies Google Fonts** : Orbitron, Rajdhani, Inter

### 📧 **Formulaire de Contact Sécurisé**
- **Protection CSRF** avec tokens cryptographiques
- **Honeypot anti-bots** invisible
- **Rate limiting** (5 tentatives/heure)
- **Validation stricte** des données
- **Protection contre doublons** avec cooldown
- **Envoi d'emails HTML** avec PHPMailer

### 🛡️ **Sécurité Avancée**
- **Détection de bots** intelligente
- **Validation de User-Agent**
- **Nettoyage automatique des sessions**
- **Logging des tentatives suspectes**
- **Compatible extensions de remplissage** (Fake Filler)

## 🚀 Installation

### Prérequis
- **PHP 7.4+** avec OpenSSL
- **Serveur web** (Apache/Nginx)
- **Compte Gmail** pour l'envoi d'emails

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone [URL_DU_REPO]
cd Porfolio-theo
```

2. **Configuration email**
```bash
# Éditer le fichier de configuration
nano config/email_config.php
```

3. **Ajouter le mot de passe Gmail**
```php
'smtp_password' => 'votre-mot-de-passe-application-16-caracteres',
```

4. **Générer un mot de passe d'application Gmail**
   - Aller sur [myaccount.google.com](https://myaccount.google.com/)
   - **Sécurité** → **Mots de passe des applications**
   - Créer un mot de passe pour "Mail"
   - Copier le mot de passe de 16 caractères dans la config

5. **Démarrer le serveur**
```bash
# Avec MAMP/XAMPP ou serveur PHP intégré
php -S localhost:8000
```

## 📁 Structure du Projet

```
Porfolio-theo/
├── 📄 index.php          # Page principale
├── 📄 contact.php           # Traitement formulaire
├── 📁 assets/
│   ├── 🎨 styles.css        # Styles CSS principaux
│   ├── ⚡ script.js         # JavaScript interactif
│   └── 📁 pdf/             # Documents PDF
├── 📁 config/
│   └── 📧 email_config.php  # Configuration SMTP
├── 📁 PHPMailer/            # Librairie email
│   └── 📁 src/
├── 📄 README.md             # Documentation
```

## 🎯 Sections du Portfolio

### **01. À propos**
- Présentation personnelle
- Objectifs professionnels
- Spécialisation GEII

### **02. Compétences Techniques**
- **Génie Électrique** : Schémas, EPLAN, VFD, Habilitations B1V
- **Automatisme** : Siemens TIA Portal, Schneider, Wago Codesys
- **Robotique** : Cobot, Kuka, Vision industrielle
- **Informatique Indus.** : C/Java/Arduino, IoT, MATLAB

### **03. Projets Techniques**
- **Robot KUKA** : Programmation séquences + communication automate
- **Château d'eau** : Modernisation avec IHM tactile
- **Factory.io** : Simulation cuve avec Codesys

### **04. Parcours Académique**
- **BUT GEII** (2023-Aujourd'hui) - IUT de l'Aisne
- **Bac STI2D** (2021-2023) - Lycée Jean Racine

### **05. Contact**
- Formulaire sécurisé
- Popup email avec copie
- Links réseaux sociaux

## 🔧 Personnalisation

### Couleurs CSS
```css
:root {
    --bg-dark: #121418;           /* Fond principal */
    --primary-blue: #2980b9;      /* Bleu tech */
    --accent-orange: #e67e22;     /* Orange énergique */
    --accent-green: #2ecc71;      /* Vert tech */
}
```

### Configuration Email
```php
// Dans config/email_config.php
$emailConfig = [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_username' => 'votre-email@gmail.com',
    'to_email' => 'votre-email@gmail.com',
    // ...
];
```

## 🛡️ Sécurité

### Protections Implémentées
- ✅ **CSRF Protection** avec tokens sécurisés
- ✅ **Anti-Bot Honeypot** invisible
- ✅ **Rate Limiting** par IP
- ✅ **Validation stricte** des données
- ✅ **Échappement XSS** complet
- ✅ **Protection doublons** avec cooldown

### Tests de Sécurité
```bash
# Test détection bot (doit échouer)
curl -X POST localhost/contact.php -d "nom=Bot&..."

# Test honeypot (doit échouer)
curl -X POST localhost/contact.php -d "website=spam&nom=Test&..."
```

## 📱 Responsive Design

- **Desktop** : Design complet avec grilles
- **Tablet** (768px) : Adaptation layout
- **Mobile** (480px) : Navigation verticale
- **Touch-friendly** : Boutons adaptés tactile

## 🚨 Dépannage

### Problèmes Courants

**❌ "Configuration email non complète"**
→ Ajouter le mot de passe d'application Gmail dans `config/email_config.php`

**❌ "Accès non autorisé" avec Fake Filler**
→ Remplir manuellement ou désactiver l'extension temporairement

**❌ "Token de sécurité invalide"**
→ Recharger la page pour obtenir un nouveau token

### Logs d'Erreur
```bash
# Vérifier les logs PHP
tail -f /var/log/php_errors.log

# Logs personnalisés dans contact.php
error_log("Message de debug");
```

## 👨‍💻 Auteur

**Théo Dufeuille**
- 🎓 BUT GEII - IUT de l'Aisne (Cuffies)
- 🏭 Alternant chez Tereos Bucy-le-Long
- 🔗 [LinkedIn](https://www.linkedin.com/in/théo-dufeuille-34ab2b2a4/)
- 📧 dufeuilletheo@gmail.com

## 📄 Licence

Ce projet est personnel et à des fins éducatives.

---

⚙️ **GEII • AUTOMATISME • ROBOTIQUE** ⚙️


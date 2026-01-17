<?php
// Protection contre l'accès direct
if (!defined('EMAIL_CONFIG_LOADED')) {
    die('Accès direct non autorisé');
}

// Configuration SMTP pour Gmail
$emailConfig = [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_secure' => 'tls',
    'smtp_username' => 'dufeuilletheo@gmail.com', // Votre email Gmail
    'smtp_password' => 'suqa awyl mxog nojy', // À remplir avec votre mot de passe d'application
    'from_email' => 'dufeuilletheo@gmail.com',
    'from_name' => 'Portfolio Théo Dufeuille',
    'to_email' => 'dufeuilletheo@gmail.com', // Email où recevoir les messages
    'to_name' => 'Théo Dufeuille'
];

// Alternative pour d'autres fournisseurs d'email
/*
// Configuration pour Outlook/Hotmail
$emailConfig = [
    'smtp_host' => 'smtp-mail.outlook.com',
    'smtp_port' => 587,
    'smtp_secure' => 'tls',
    'smtp_username' => 'votre-email@outlook.com',
    'smtp_password' => 'votre-mot-de-passe',
    'from_email' => 'votre-email@outlook.com',
    'from_name' => 'Portfolio Théo Dufeuille',
    'to_email' => 'votre-email@outlook.com',
    'to_name' => 'Théo Dufeuille'
];
*/
?>
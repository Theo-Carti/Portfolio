<?php
session_start();

// Inclusion des fichiers PHPMailer
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Charger la configuration email
define('EMAIL_CONFIG_LOADED', true);
require_once 'config/email_config.php';

// Fonctions de sécurité
function isBot() {
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    
    // Vérifier les user agents clairement malveillants
    $maliciousAgents = ['curl', 'wget', 'crawler', 'spider', 'scrapy', 'python', 'bot'];
    foreach ($maliciousAgents as $agent) {
        if (strpos($userAgent, $agent) !== false) {
            return true;
        }
    }
    
    // Vérifier les champs honeypot seulement si ce n'est pas un navigateur standard
    $legitBrowsers = ['mozilla', 'chrome', 'safari', 'firefox', 'edge', 'opera'];
    $isLegitBrowser = false;
    foreach ($legitBrowsers as $browser) {
        if (strpos($userAgent, $browser) !== false) {
            $isLegitBrowser = true;
            break;
        }
    }
    
    // Si ce n'est pas un navigateur légitime, vérifier les honeypots
    if (!$isLegitBrowser) {
        if (!empty($_POST['website']) || !empty($_POST['email_check']) || !empty($_POST['phone'])) {
            return true;
        }
    }
    
    // Pour les navigateurs légitimes, vérifier seulement si TOUS les honeypots sont remplis
    // (ce qui est très suspect même pour une extension)
    if ($isLegitBrowser) {
        if (!empty($_POST['website']) && !empty($_POST['email_check']) && !empty($_POST['phone'])) {
            // Vérifier si les valeurs semblent automatiques/identiques
            $website = $_POST['website'];
            $emailCheck = $_POST['email_check'];
            $phone = $_POST['phone'];
            
            // Si les trois champs ont des valeurs suspectes identiques ou pattern évident
            if ($website === $emailCheck || $website === $phone || 
                (strlen($website) > 20 && strlen($emailCheck) > 20 && strlen($phone) > 20)) {
                return true;
            }
        }
    }
    
    return false;
}

function getClientIP() {
    $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    
    foreach ($ipKeys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            $ip = $_SERVER[$key];
            if (strpos($ip, ',') !== false) {
                $ip = explode(',', $ip)[0];
            }
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function isRateLimited($ip) {
    $maxAttempts = 5; // 5 tentatives
    $timeWindow = 3600; // par heure
    
    if (!isset($_SESSION['rate_limit'])) {
        $_SESSION['rate_limit'] = [];
    }
    
    $now = time();
    $attempts = $_SESSION['rate_limit'][$ip] ?? [];
    
    // Nettoyer les anciennes tentatives
    $attempts = array_filter($attempts, function($time) use ($now, $timeWindow) {
        return ($now - $time) < $timeWindow;
    });
    
    $_SESSION['rate_limit'][$ip] = $attempts;
    
    return count($attempts) >= $maxAttempts;
}

function recordAttempt($ip) {
    if (!isset($_SESSION['rate_limit'])) {
        $_SESSION['rate_limit'] = [];
    }
    if (!isset($_SESSION['rate_limit'][$ip])) {
        $_SESSION['rate_limit'][$ip] = [];
    }
    $_SESSION['rate_limit'][$ip][] = time();
}

// Fonction pour envoyer une réponse JSON
function sendJsonResponse($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

// Fonction pour rediriger avec message en session
function redirectWithMessage($status, $message) {
    if ($status === 'success') {
        $_SESSION['contact_success'] = $message;
    } else {
        $_SESSION['contact_error'] = $message;
    }
    
    // Rediriger vers la page principale avec ancre contact
    header('Location: Portofio.php#contact');
    exit;
}

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', 'Méthode non autorisée');
    } else {
        redirectWithMessage('error', 'Méthode non autorisée');
    }
}

// Vérification des bots
if (isBot()) {
    error_log("Bot détecté - IP: " . getClientIP() . " - UA: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'));
    $errorMsg = 'Remplissage automatique détecté. Si vous utilisez une extension de remplissage automatique, veuillez remplir le formulaire manuellement ou désactiver l\'extension pour ce site.';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Vérification du rate limiting
$clientIP = getClientIP();
if (isRateLimited($clientIP)) {
    $errorMsg = 'Trop de tentatives. Veuillez patienter une heure avant de réessayer.';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Vérification du token CSRF
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    recordAttempt($clientIP);
    $errorMsg = 'Token de sécurité invalide. Veuillez recharger la page.';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Générer un token unique pour cette soumission
$currentToken = md5(time() . $_SERVER['REMOTE_ADDR']);

// Vérifier les doubles soumissions
$submissionToken = isset($_POST['submission_token']) ? $_POST['submission_token'] : '';
$currentTime = time();

// Vérifier que le token de soumission est valide
if (empty($submissionToken) || !isset($_SESSION['form_tokens']) || 
    !in_array($submissionToken, $_SESSION['form_tokens'])) {
    recordAttempt($clientIP);
    $errorMsg = 'Token de formulaire invalide. Veuillez recharger la page.';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Protection basée sur le temps (minimum 30 secondes entre les envois)
if (isset($_SESSION['last_submission_time']) && 
    ($currentTime - $_SESSION['last_submission_time']) < 30) {
    
    $waitTime = 30 - ($currentTime - $_SESSION['last_submission_time']);
    $errorMsg = "Veuillez patienter {$waitTime} secondes avant d'envoyer un autre message.";
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Protection par token
if (isset($_SESSION['used_tokens']) && in_array($submissionToken, $_SESSION['used_tokens'])) {
    $errorMsg = 'Message déjà envoyé. Veuillez éviter de renvoyer le formulaire.';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Récupérer et nettoyer les données du formulaire
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$objet = isset($_POST['objet']) ? trim($_POST['objet']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validation basique des champs requis
if (empty($nom)) {
    recordAttempt($clientIP);
    $errorMsg = 'Le nom est requis';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Validation stricte du nom
if (strlen($nom) < 2 || strlen($nom) > 50) {
    recordAttempt($clientIP);
    $errorMsg = 'Le nom doit contenir entre 2 et 50 caractères';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/', $nom)) {
    recordAttempt($clientIP);
    $errorMsg = 'Le nom contient des caractères non autorisés';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

if (empty($email)) {
    recordAttempt($clientIP);
    $errorMsg = 'L\'email est requis';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

if (empty($objet)) {
    recordAttempt($clientIP);
    $errorMsg = 'L\'objet est requis';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Validation stricte de l'objet
if (strlen($objet) < 5 || strlen($objet) > 100) {
    recordAttempt($clientIP);
    $errorMsg = 'L\'objet doit contenir entre 5 et 100 caractères';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

if (empty($message)) {
    recordAttempt($clientIP);
    $errorMsg = 'Le message est requis';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Validation stricte du message
if (strlen($message) < 10 || strlen($message) > 2000) {
    recordAttempt($clientIP);
    $errorMsg = 'Le message doit contenir entre 10 et 2000 caractères';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Validation de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMsg = 'Format d\'email invalide';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Vérification de la configuration email
if (empty($emailConfig['smtp_password'])) {
    $errorMsg = 'Configuration email non complète. Veuillez configurer le mot de passe SMTP.';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}

// Créer une instance PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host = $emailConfig['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $emailConfig['smtp_username'];
    $mail->Password = $emailConfig['smtp_password'];
    $mail->SMTPSecure = $emailConfig['smtp_secure'];
    $mail->Port = $emailConfig['smtp_port'];
    $mail->CharSet = 'UTF-8';

    // Destinataires
    $mail->setFrom($emailConfig['from_email'], $emailConfig['from_name']);
    $mail->addAddress($emailConfig['to_email'], $emailConfig['to_name']);
    $mail->addReplyTo($email, $nom);

    // Contenu de l'email
    $mail->isHTML(true);
    $mail->Subject = '[Portfolio] ' . $objet;
    
    $htmlMessage = "
    <html>
    <head>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&family=Inter:wght@300;400;600&display=swap');
            
            body { 
                font-family: 'Inter', Arial, sans-serif; 
                line-height: 1.6; 
                color: #ecf0f1; 
                background-color: #121418;
                margin: 0;
                padding: 20px;
            }
            .container { 
                max-width: 600px; 
                margin: 0 auto; 
                background: #121418;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            }
            .header { 
                background: linear-gradient(135deg, #2980b9, #3498db);
                color: white; 
                padding: 30px 20px; 
                text-align: center;
                position: relative;
            }
            .header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-image: 
                    linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
                background-size: 20px 20px;
                opacity: 0.3;
            }
            .header h2 { 
                font-family: 'Orbitron', Arial, sans-serif;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 2px;
                margin: 0;
                font-size: 1.5em;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }
            .header .icon {
                font-size: 2em;
                margin-bottom: 10px;
                display: block;
            }
            .content { 
                background: #1e222b; 
                padding: 30px 20px;
            }
            .info { 
                background: #121418; 
                padding: 20px; 
                margin: 15px 0; 
                border-radius: 5px;
                border-left: 4px solid #e67e22;
                position: relative;
            }
            .info::after {
                content: '';
                position: absolute;
                top: -1px;
                right: -1px;
                width: 15px;
                height: 15px;
                border-top: 2px solid #2ecc71;
                border-right: 2px solid #2ecc71;
            }
            .info strong {
                color: #2980b9;
                font-family: 'Rajdhani', Arial, sans-serif;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            .info .content-text {
                color: #bdc3c7;
                margin-top: 8px;
            }
            .message-content {
                background: #0a0c10;
                padding: 15px;
                margin-top: 10px;
                border-radius: 3px;
                border: 1px solid #333;
                font-family: 'Courier New', monospace;
                color: #2ecc71;
                white-space: pre-wrap;
            }
            .footer { 
                text-align: center; 
                padding: 25px 20px; 
                color: #bdc3c7; 
                font-size: 12px;
                background: #0a0c10;
                border-top: 2px solid #2980b9;
            }
            .footer .tech-signature {
                font-family: 'Orbitron', Arial, sans-serif;
                color: #e67e22;
                font-weight: 700;
                margin-top: 10px;
            }
            .status-badge {
                display: inline-block;
                background: rgba(46, 204, 113, 0.2);
                color: #2ecc71;
                padding: 5px 12px;
                border-radius: 3px;
                border: 1px solid #2ecc71;
                font-family: 'Rajdhani', Arial, sans-serif;
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.8em;
                letter-spacing: 1px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <span class='icon'>🤖</span>
                <h2>Nouveau message portfolio</h2>
                <span class='status-badge'>Transmission reçue</span>
            </div>
            <div class='content'>
                <div class='info'>
                    <strong>⚡ Expéditeur</strong>
                    <div class='content-text'>" . htmlspecialchars($nom) . " (" . htmlspecialchars($email) . ")</div>
                </div>
                <div class='info'>
                    <strong>📡 Objet</strong>
                    <div class='content-text'>" . htmlspecialchars($objet) . "</div>
                </div>
                <div class='info'>
                    <strong>💬 Message</strong>
                    <div class='message-content'>" . htmlspecialchars($message) . "</div>
                </div>
                <div class='info'>
                    <strong>🕒 Horodatage</strong>
                    <div class='content-text'>" . date('d/m/Y à H:i:s') . "</div>
                </div>
            </div>
            <div class='footer'>
                <p>Message automatiquement transmis depuis le portfolio Théo Dufeuille</p>
                <div class='tech-signature'>⚙️ GEII • AUTOMATISME • ROBOTIQUE ⚙️</div>
            </div>
        </div>
    </body>
    </html>";
    
    $mail->Body = $htmlMessage;
    
    // Version texte alternative
    $textMessage = "Nouveau message depuis votre portfolio\n\n";
    $textMessage .= "De: " . $nom . " (" . $email . ")\n";
    $textMessage .= "Objet: " . $objet . "\n";
    $textMessage .= "Date: " . date('d/m/Y à H:i:s') . "\n\n";
    $textMessage .= "Message:\n" . $message;
    
    $mail->AltBody = $textMessage;

    // Envoyer l'email
    $mail->send();
    
    // Marquer cette soumission comme traitée
    if (!isset($_SESSION['used_tokens'])) {
        $_SESSION['used_tokens'] = [];
    }
    $_SESSION['used_tokens'][] = $submissionToken;
    
    // Nettoyer les anciens tokens utilisés (garder seulement les 20 derniers)
    if (count($_SESSION['used_tokens']) > 20) {
        $_SESSION['used_tokens'] = array_slice($_SESSION['used_tokens'], -20);
    }
    
    // Retirer le token de la liste des tokens valides
    if (isset($_SESSION['form_tokens'])) {
        $_SESSION['form_tokens'] = array_diff($_SESSION['form_tokens'], [$submissionToken]);
    }
    
    $_SESSION['last_submission_time'] = $currentTime;
    
    // Générer un nouveau token CSRF pour le prochain formulaire
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    
    // Répondre selon le type de requête
    $successMsg = 'Message envoyé avec succès! Je vous répondrai dans les plus brefs délais.';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('success', $successMsg);
    } else {
        redirectWithMessage('success', $successMsg);
    }

} catch (Exception $e) {
    error_log("Erreur PHPMailer: " . $mail->ErrorInfo);
    $errorMsg = 'Erreur lors de l\'envoi du message. Veuillez réessayer plus tard.';
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendJsonResponse('error', $errorMsg);
    } else {
        redirectWithMessage('error', $errorMsg);
    }
}
?>

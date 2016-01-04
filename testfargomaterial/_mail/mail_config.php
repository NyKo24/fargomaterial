<?
/**
 * Fichier de configuration pour l'envoie de mail
 * @author Nicolas BORDES <nicolasbordes@me.com>
 * 
 */

/**
 * Adresse du serveur mail (IP / URL
 * @var string
 */
define("MAIL_SERVER", "127.0.0.1");

/**
 * Active ou non l'authentification par nom d'utilisateur / mot de passe
 * @var Boolean
 */
define("MAIL_AUTH",false);

/**
 * Nom d'utilisateur (null si non utilisé)
 * @var string
 */
define("MAIL_USER", null);

/**
 * Mot de passe (null si non utilisé)
 * @var string
 */
define("MAIL_PASSWORD", null);

/**
 * Numéro de port du serveur
 * @var int
 */
define("MAIL_PORT", 1025);

/**
 * Type de connexion (SSL ou TLS)
 * @var string
 */
define("MAIL_CRYPTAGE", null); // SSL or TLS

/**
 * Adresse mail de l'expéditeur
 * @var string
 */
define("MAIL_ADRESSE", null);

/**
 * Active ou non l'envoie de mail 
 * @var Boolean
 */
define("ENVOI_MAIL_OK",true);
?>
<?php
[session]session_start();
/**	Ce fichier est inclus sur toutes les pages du site et il doit être accessible via le include_path **/

/**
 * Titre du site
 * @var string
 */
define("TITRE_SITE","[titreSite]");

/**
 * URL du site (avec une "/" à la fin)
 * @var string
 */
define("BASE_URL","[url]");

/**
 * Chemin du repertoir contenant le projet (avec un "/" à la fin)
 * @var string
 */
define("BASE_REP","[rep]");

/**
 * Adresse du serveur de base de donnée
 * @var string
 */
define("DB_SERVER","[serveurBDD]");

/**
 * Nom d'utilisateur de la base de donnée
 * @var string
 */
define("DB_USER","[loginBDD]");

/**
 * Mot de passe de la base de donnée
 * @var string
 */
define("DB_PWD","[passBDD]");

/**
 * Nom de la base de donnée
 * @var unknown
 */
define("DB_BDD","[baseBDD]");

/**
 * Clé de cryptage sérvant au cryptage des mots de passe
 * @var string
 */
define("CLE_CRYPTAGE", "[clecryptage]");

/** Gestion des profils */
/**
 * Identifiant du profil ADMIN
 * @var int
 */
define("PROFIL_ADMIN", [profiladmin]);

/**
 * Chargement du fichier Table.class.php
 */
require BASE_REP . "_framework/Table.class.php";

/**
 * Chargement du fichier Controleur.class.php
 */
require BASE_REP . "_framework/Controleur.class.php";

/** 
 * Chargement du fichier inc_fonction.php
 */
require BASE_REP . "_include/inc_fonction.php";

/**
 * Création d'une instance de connexion à la base de donnée
 */
Table::getCon(DB_SERVER,DB_USER,DB_PWD,DB_BDD);


?>
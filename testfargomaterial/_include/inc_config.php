<?php
session_start();
/**	Ce fichier est inclus sur toutes les pages du site et il doit être accessible via le include_path **/

/**
 * Titre du site
 * @var string
 */
define("TITRE_SITE","Fargo material TEST");

/**
 * URL du site (avec une "/" à la fin)
 * @var string
 */
define("BASE_URL","http://fargocss/");

/**
 * Chemin du repertoir contenant le projet (avec un "/" à la fin)
 * @var string
 */
define("BASE_REP","D:/workspacePHP/testFargoMaterial/");

/**
 * Adresse du serveur de base de donnée
 * @var string
 */
define("DB_SERVER","localhost");

/**
 * Nom d'utilisateur de la base de donnée
 * @var string
 */
define("DB_USER","root");

/**
 * Mot de passe de la base de donnée
 * @var string
 */
define("DB_PWD","");

/**
 * Nom de la base de donnée
 * @var unknown
 */
define("DB_BDD","fargo_test");

/**
 * Clé de cryptage sérvant au cryptage des mots de passe
 * @var string
 */
define("CLE_CRYPTAGE", "568acc119d90b");

/** Gestion des profils */
/**
 * Identifiant du profil ADMIN
 * @var int
 */
define("PROFIL_ADMIN", 3);

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
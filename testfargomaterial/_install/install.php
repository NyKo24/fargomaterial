<?php
/**
* Configuration de l'installation de FARGO 
* 
* @author Nicolas BORDES <nicolasbordes@me.com>
* 
* @version 1.6.2
*
* ATTENTION : Les chemin de fichier doivent être avec "/" (slash) et non pas des "\" (anti-slash)
*
*
*/

// 1. Apache 

/**
 * @var string $cheminHTTP chemin du fichier httpd.com d'Apache
 */
$cheminHTTP="C:/wamp/bin/apache/apache2.4.4/conf/httpd.conf";


/**
 * @var string $serveurName Nom du virtual host (serverName)
 */
$serveurName="fargo.material";

/**
* IP du VHOST : 
* 127.0.0.1 pour un usage en local
* IP Publique : accèsible depuis l'exterieur sur le reseau
* @var string $serveurIP IP du serveur
*/
$serveurIP="127.0.0.1";

/**
 * @var string $chemin Chemin du projet (DocumentRoot) ATTENTION : ajouter un "/" à la fin
 */
$chemin="D:/workspacePHP/testFargoMaterial/";

/**
 * @var string $documentRoot $chemin + "www" (correspond au point d'entrée de l'application
 */
$documentRoot=$chemin."www";

/**
 * @var string $includePath $chemin + "_include" ajout ce chemin à l'include path du projet
 */
$includePath=$chemin."_include";

// 2. MySQL

/**
 * @var string $serveurBDD adresse du serveur MySQL
 */
$serveurBDD="localhost";

/**
 * @var string $userBDD nom d'utilisateur de connexion à la BDD
 */
$userBDD="root";

/**
 * @var string $passBDD mot de passe de l'utilisateur de connexion à la BDD
 */
$passBDD="";

/**
 * @var string $nameBDD nom de la base de donnée
 */
$nameBDD="fargo_test";

// 3. Configuration du site

/**
 * @var string $siteName Defini la valeur de la constante TITRE_SITE reprensant le nom du site
 */
$siteName="Fargo material TEST";

/**
 * @var string $url : URL du site (ATTENTION ajouter un "/" a la fin) * Correspond au serverName *
 */
$url="http://fargo.material/";

/**
 * @var string $cleCryptage Clé de cryptage des mots de passe (Si laisser vide alors génération d'une clé avec unicid())
 */
$cleCryptage="";


/**
 * @var boolean $session Activer / désactiver les session
 */
$session=true;

/**
 * @var int $admin identifiant correspondant au profil ADMIN 
 */
$admin=3;

/**
* La configuration est fini
* exécuter le fichier en ligne de commande ou bien via le web
* ENJOY ! 
*/
echo "Création du VHOST: \n\n";
echo "Création d'un backup de votre HTTPD.CONF\n";
$f=fopen(str_ireplace("httpd.conf", "", $cheminHTTP)."httpd.conf_backup".strtotime("now"), "w");
fwrite($f, file_get_contents($cheminHTTP));
fclose($f);
echo "fait \n";
echo "Ecriture dans le HTTPD.CONF\n";
$f=fopen($cheminHTTP, "a+");

$chaine=file_get_contents("vhost_template.txt");
$chaine=str_ireplace("[serveurIP]", $serveurIP, $chaine);
$chaine=str_ireplace("[serveurName]", $serveurName, $chaine);
$chaine=str_ireplace("[documentRoot]", $documentRoot, $chaine);
$chaine=str_ireplace("[includePath]", $includePath, $chaine);

fwrite($f, "\n\n\n" . $chaine);
fclose($f);
echo "fait \n";

echo "Configuration du site : \n";
$chaine=file_get_contents("inc_config.php");
$chaine=str_ireplace("[titreSite]", $siteName, $chaine);
$chaine=str_ireplace("[url]", $url, $chaine);
$chaine=str_ireplace("[rep]", $chemin, $chaine);
$chaine=str_ireplace("[serveurBDD]", $serveurBDD, $chaine);
$chaine=str_ireplace("[loginBDD]", $userBDD, $chaine);
$chaine=str_ireplace("[passBDD]", $passBDD, $chaine);
$chaine=str_ireplace("[baseBDD]", $nameBDD, $chaine);
if ($session)
	$chaine=str_ireplace("[session]", "", $chaine);
else
	$chaine=str_ireplace("[session]", "//", $chaine); 

if ($cleCryptage=="") {
	$cle=uniqid();
	$chaine=str_ireplace("[clecryptage]", $cle, $chaine); 
} else{
	$chaine=str_ireplace("[clecryptage]", $cleCryptage, $chaine); 
}
$chaine=str_ireplace("[profiladmin]", $admin, $chaine);
file_put_contents("../_include/inc_config.php", $chaine);
echo "fait \n";
echo "La configuration est maintenant terminé, vous pouvez redémarer apache";
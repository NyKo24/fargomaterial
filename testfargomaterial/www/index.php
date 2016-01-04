<?php
/**
 * Point d'entrée de l'application
 * 
 * Toutes les requêtes HTTP arrivents sur cette page
 * la réécriture d'URL est la suivante : BASE_URL/controleur/action/param1/valeur1/param2/valeur2.../paramn/valeurn
 * @author Nicolas BORDES <nicolasbordes@me.com>
 */
require "inc_config.php";

spl_autoload_register("monAutoload");
//appel de l'autoloader de Composer
require BASE_REP.'vendor/autoload.php';

//Recupération de l'URL
if (isset($_GET['url']))
	$data = explode("/", $_GET['url']);
else
	$data = null;
	
$controleur = (isset($data[0]) and $data[0]!="") ? $data[0] : "_default";
$action = (isset($data[1])  and $data[1]!="") ? $data[1] : "index";

//Les parametres
for ($i = 2; $i < count($data); $i+=2) {
	if (isset($data[$i]) and isset($data[$i + 1])) {
		$_GET[$data[$i]] = $data[$i + 1];
	}
}

autoconnect();
$ctr="Ctr_" . $controleur;

//vérification que le Controller existe
if (!class_exists($ctr,true))
	header("location: ".BASE_URL."/404/index.php" );
//vérification que la méthode du Controller existe
else if (!method_exists($ctr, $action))
	header("location: ".BASE_URL."/404/index.php" );

$oCtr=new $ctr($action);


// Controleur::debug($_SESSION);
// Controleur::debug($_POST);
// Controleur::debug($_SERVER);
// Controleur::debug($_COOKIE);

?>
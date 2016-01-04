<?php
/**
 * Librairie de fonction
 * 
 * @author Nicolas BORDES <nicolasbordes@me.com>
 * 
 * @version 1.6.2
 */

/**
 * Définition de l'autoloader de FARGO
 * Si une class controleur $class_name existe, alors il y un require du fichier
 * Si une class modèle $class_name existe, alors il y a un require du fichier
 * @param string $class_name nom de la class à require
 */
function monAutoload($class_name){
	if ("Ctr_" == substr($class_name, 0, 4)){
		$file=BASE_REP . "_controleur/" . strtolower($class_name) . "/" . $class_name . ".class.php";
		if (file_exists($file))
			require_once $file;
	}
	else{
		$file = BASE_REP . "_modele/" . $class_name . ".class.php";
		if (file_exists($file))
			require_once $file;
	}
		
}

/**
 * Evite l'injection de code JavaScript / HTML lors de l'affichage (Convertit tous les caractères éligibles en entités HTML)
 * @param string $x chaine à néttoyer
 * @return string chaine nettoyée
 */
function mhe($x) {
    return htmlentities($x, ENT_QUOTES,"utf-8");
}

/**
 * Permet d'autoriser un ou plusieurs profil d'utilisateur à accéder à certaines actions des controleurs
 * @param mixed $profil ID du profil ou tableau contenant les liste des ID des profils autorisés
 */
function isAuth($profil="") {
    if (!isset($_SESSION["uti_id"])){
        header("location:" .BASE_URL . "authentification/deconnexion?message=noaccess");
	} else if(is_array($profil)){
		$verif=false;
		foreach ($profil as $id)
			if ($id == $_SESSION["uti_type"])
				$verif=true;
		if (!$verif)
			header("location:" .BASE_URL . "authentification/deconnexion?message=noaccess");
    }else if ($profil!="") {
        if ($profil!=$_SESSION["uti_profil"])
            header("location:" .BASE_URL . "authentification/deconnexion?message=noaccess");
    }
}


/**
 * Retourne la valeur de $chaine encrypté à laide de la constante CLE_CRYPTAGE
 * @paran $chaine string : chaine de caractère à crypter
 * @return string : chaine de caractère crypter 
 */
function cryptage($chaine){
	return sha1(md5($chaine).CLE_CRYPTAGE);
}


/**
 * si $_SESSION["uti_id"] n'existe pas et que l'utilisateur possède des cookis créer grâce à Authentification::connexion() alors il est automatiquement connecté
 * $_SESSION est initialisé avec les mêmes valeurs que Authentification::connexion()
 */
function autoconnect(){
	if (!isset($_SESSION["uti_id"])){
		if (isset($_COOKIE["uti_login"])){
			$query="select * from utilisateur where uti_login='".$_COOKIE["uti_login"]."' and uti_mdp='".$_COOKIE["uti_mdp"]."' ";
			$result=Table::$con->query($query);
			if ($row=$result->fetch_assoc()) {
				$_SESSION["uti_id"]=$row["uti_id"];
				$_SESSION["uti_nom"]=$row["uti_nom"];
				$_SESSION["uti_prenom"]=$row["uti_prenom"];
				$_SESSION["uti_profil"]=$row["uti_profil"];
				if ($_SESSION["uti_profil"]==PROFIL_ADMIN)
					$_SESSION["admin"]="ok";
			}
		}
	}	
}


?>
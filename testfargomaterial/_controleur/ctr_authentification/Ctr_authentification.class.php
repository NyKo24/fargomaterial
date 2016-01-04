<?php
/**
 * Class gérant l'authentification
 * 
 * Cette classe class gère la connexion / déconnexion / mot de passe oublié
 * 
 * @author Nicolas BORDES <nicolasbordes@me.com>
 * @package Controleur\
 * @version 1.6.2
 */

class Ctr_authentification extends Controleur {

	/**
	 * Constructeur, fait appel au constructeur parent
	 * @param string $a méthode (action) à exécuter 
	 */
    public function __construct($a) {
        parent::__construct("authentification", $a);
    }

    /**
     * Recherche les information d'un utilisateur de par le couple $_POST["uti_login"] et $_POST["uti_mdp"]
     * Si un utilisateur est trouvé, alors $_SESSION est initialisé avec les informations de l'utilisateur
     * Création des coockis "uti_login" et "uti_mdp" si l'utilisateur à cocher la case sur la page de connexion
     * Sinon, redirection vers la page de connexion
     */
    function connexion() {
        if (isset($_POST["uti_login"])) {
            extract($_POST);
            $uti_mdp=cryptage($uti_mdp);
            $query="select * from utilisateur where uti_login='$uti_login' and uti_mdp='$uti_mdp' ";
            $result=Table::$con->query($query);
            if (isset($_POST["souvenir"])){
            	setcookie("uti_login",$uti_login,time()+60*60*24*30,"/");
            	setcookie("uti_mdp",$uti_mdp,time()+60*60*24*30,"/");
            }
            if ($row=$result->fetch_assoc()) {
                $_SESSION["uti_id"]=$row["uti_id"];
                $_SESSION["uti_nom"]=$row["uti_nom"];
                $_SESSION["uti_prenom"]=$row["uti_prenom"];
                $_SESSION["uti_profil"]=$row["uti_profil"];
                if ($_SESSION["uti_profil"]==ADMIN_PROFIL){
                	$_SESSION["admin"]="ok";
                }
                	
				header("location:" . BASE_URL . "_default/index");
            } else 
            	header("location:" . BASE_URL . "authentification/connexion");
        } else 
            require BASE_REP . "_gabarit/$this->gabarit";
    }
    
    /**
     * Detruit la variable de SESSION associé à l'utilisateur
     * Supprime les coockis de l'utilisateur
     * Redirige vers la page de connexion avec un message
     */
    function deconnexion() {
        session_start();
        session_destroy();
        setcookie("uti_login","",time()-3600,"/");
        setcookie("uti_mdp","",time()-3600,"/");
        $_COOKIE=array();
        header("location:" . BASE_URL."authentification/connexion/message/noaccess");
    }
    
    /**
     * Affiche la page de saisie de l'adresse email pour re-générer un mot de passe
     */
    function mdpoublie(){
    	require BASE_REP . "_gabarit/$this->gabarit";
    }
    
    /**
     * Regenère un mot de passe pour $_POST["uti_email"] puis lui envoie par email
     * Succès : redirige vers la page de connexion avec un message
     * Echec : redirige vers la page "mot de passe oublié" avec un message
     */
    function resetmdp(){
    	extract($_POST);
    	$query="select * from utilisateur where uti_email='$uti_email'";
    	$result=Table::$con->query($query);
    	if ($row=$result->fetch_assoc()){
    		$newMdp=substr(uniqid(), 0,10);
    		$uti_mdp=cryptage($newMdp);
    		$query="update utilisateur set uti_mdp='$uti_mdp' where uti_id=".$row["uti_id"];
    		Table::$con->query($query);
    		
    		$chaine=file_get_contents(BASE_REP."_mail/tpl_mdpoublie.txt");
    		$chaine=str_ireplace("[prenom]", $row["uti_prenom"], $chaine);
    		$chaine=str_ireplace("[login]", $row["uti_login"], $chaine);
    		$chaine=str_ireplace("[mdp]", $newMdp, $chaine);
    		$chaine=str_ireplace("[lien]", BASE_URL."authentification/connexion", $chaine);
    		$chaine=str_ireplace("[titreSite]", TITRE_SITE, $chaine);
    		
    		envoyerUnMail($row["uti_email"], "Mot de passe oublié", $chaine);
    		header("location:".BASE_URL."authentification/connexion/message/emailsend");
    	}else {
    		header("location:".BASE_URL."authentification/mdpoublie/message/nouser");
    	}

    }
    
    
}

?>
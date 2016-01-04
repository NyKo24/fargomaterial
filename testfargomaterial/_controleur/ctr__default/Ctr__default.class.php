<?php
/**
 * Controleur par défaut
 * 
 * Il récupères toutes les requetes de BASE_URL qui n'ont pas de controleur / d'action dans leur adresse
 * 
 * @author Nicolas BORDES <nicolasbordes@me.com>
 *
 * @package Controleur\
 *
 */
class Ctr__default extends Controleur {

	/**
	 * Constructeur : fait appel au contrusteur parent
	 * @param string $a nom de l'action à appeler
	 */
    public function __construct($a) {
        parent::__construct("_default", $a);
    }

    /**
     * Charge la vue par défaut
     * 
     * @see Controleur::index()
     */
    function index() {
    	$this->titre="Acceuil - " . TITRE_SITE;
    	require BASE_REP . "_gabarit/$this->gabarit";
    }
}

?>
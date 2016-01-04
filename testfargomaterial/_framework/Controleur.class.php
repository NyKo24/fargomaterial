<?php
/**
 * Class générique contrôlleur
 * 
 * Cette class est la classe mère de toutes classe de type contrôlleur, elle dispose des méthodes générique de gestion du CRUB pour n'importe quelles tables
 * 
 * @author Nicolas BORDES <nicolasbordes@me.com>
 * @author Gilles LEVY
 * 
 * @version 1.6.2
 * 
 * @package Framework\Controleur
 *
 */

class Controleur {

    //pour un CRUD, $table représente la table gérer et $className la class associée
    /**
     * Nom de la table
     * @var string
     */
	public $table;
    
	/**
	 * Nom de la classe modèle
	 * @var string
	 */
    public $className;
    
    /**
     * Titre de la page (balise HTML title)
     * @var string
     */
    public $titre;
    
    /**
     * Nom de la méthode à appeler
     * @var string
     */
    public $action;
    
    /**
     * Nom de la vue à charger
     * @var string
     */
    public $vue;
    
    /**
     * Nom du gabarit à utiliser
     * @var string
     */
    public $gabarit;

    /**
     * Set les valeurs des attributs $table, $action, $gabarit puis appel l'action
     * @param string $t nom de la table
     * @param string $a nom la méthode (action) à appeler
     * @param [string] $gabarit nom du gabarit à utiliser
     */
    public function __construct($t, $a, $gabarit = "standard.php") {
        $this->table = $t;
        $this->className = ucfirst($t);
        $this->action = $a;
        $this->titre = "$this->action $this->table";
        $this->gabarit = $gabarit;
        $this->vue = BASE_REP . "_controleur/ctr_" . $this->table . "/" . "vue_" . $this->table . "_" . $this->action . ".php";
        $this->$a();
    }

    /**
     * Lister tous les enregistrement de $this->table dans une vue HTML
     */
    function lister() {
    	//isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $result=$obj->lister();
        require BASE_REP . "_gabarit/$this->gabarit";
    }

    /**
     * Affiche un formulaire d'édition en pré-remplissant les valeur de l'enregistrement $_GET["id"], affiche un formulaire sinon 
     */
    function editer() {
    	//isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        //Parametre : identifiant
        $id = (isset($_GET["id"])) ? $_GET["id"] : 0;
        $this->titre .= " : id=$id";

        $obj->chargerDepuisBdd($id);
        extract($obj->data);
        require BASE_REP . "_gabarit/$this->gabarit";
    }

    /**
     * Sauvegarder le contenu de du formulaire dans la table $this->table
     */
    function sauver() {
    	//isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $obj->chargerDepuisTableau($_POST);
        if ($obj->sauver())
            header("location:" . BASE_URL . "$this->table/lister");
    }

    /**
     * Supprimer l'enregistrement $_GET["id"] de la table $this->table puis redirige sur la vue lister de la table $this->table
     */
    function supprimer() {
    	//isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $id = (isset($_GET["id"])) ? $_GET["id"] : 0;
        $obj->supprimer($id);
        header("location:" . BASE_URL . "$this->table/lister");
    }

    /**
     * Renvoi sur la page index
     */
    function index() {
        require BASE_REP . "_gabarit/$this->gabarit";
    }
    
    /**
     * Affiche entre balise HTML <pre> un print_r de $x
     * @param mixed $x variable à afficher
     */
    static function debug($x){
    	echo "<pre>";
    	print_r($x);
    	echo "</pre>";
    }

    /**
     * Affiche la version crypée de la chaine de caractère contenu dans $_GET["chaine"]
     */
    function crypte(){
    	echo cryptage($_GET["chaine"]);
    }
    
    /**
     * Retourne la requeute LIMIT X,Y pour la pagination en fonction de $resultTotal et $currentPage
     * @param int $resultTotal nombre total d'enregistrement dans la table
     * @param int $currentPage âge courrante
     */
    public function calculepagination($resultTotal,$currentPage){
    	//nombre de page total
    	$nbPageTotal = ceil($resultTotal / NB_RESULT_PAGE);
    	 
    	if($currentPage){
    		//si le numérode la page est plus grand que le nombre de page total on renvoie a la 1er page
    		$currentPage = ($currentPage > $nbPageTotal) ? header("location:".BASE_URL.$this->table."/lister/page/1") : $currentPage;
    		 
    		if($currentPage > 1)
    			$query = " LIMIT " . ($currentPage - 1) * NB_RESULT_PAGE ."," . NB_RESULT_PAGE;
    			else
    				$query = "LIMIT " . "0"."," . NB_RESULT_PAGE;
    	}else{
    		$query = "LIMIT " . "0"."," . NB_RESULT_PAGE;
    	}
    	 
    	return $query;
    }
    
    /**
     * Genère automatiquement le code HTML de la pagination
     * @param int $resultTotal
     * @param int $curentPage
     */
    public function generationPagination($resultTotal, $curentPage){
    	//nombre de page total
    	$nbPageTotal = ceil($resultTotal / NB_RESULT_PAGE);
    	 
    	?>
        	
        	<nav>
    		  <ul class="pagination">
    		    <li class="<?= (1 == $curentPage) ? " disabled " : "" ?>">
    		      <a href="<?=BASE_URL.$this->table."/".$this->action?>/page/<?=$curentPage-1?>" aria-label="Précédent">
    		        <span  aria-hidden="true">&laquo;</span>
    		      </a>
    		    </li>
    		    <?php for ($i=1; $i<= $nbPageTotal;$i++){?>
    		    	<li class="<?= ($i == $curentPage) ? " active " : "" ?>"><a  href="<?=BASE_URL.$this->table."/".$this->action?>/page/<?=$i?>"><?=$i?></a></li>
    		    <?php }?>
    		    <li class="<?= ($nbPageTotal == $curentPage) ? " disabled " : "" ?>>
    		      <a href="href="<?=BASE_URL.$this->table/$this->action?>."/".page/<?=$curentPage+1?>" aria-label="Suivant">
    		        <span aria-hidden="true">&raquo;</span>
    		      </a>
    		    </li>
    		  </ul>
    		</nav>
    		
    		<?php
    		
        }
}

?>
<?php
/**
 * Classe du framework gérant les accès à la base de donnée
 * 
 * Cette classe est la classe parente de toutes les classe modèles, elle offre des méthodes générique de CRUD
 * 
 * @author Nicolas BORDES <nicolasbordes@me.com> 
 * @author Gilles LEVY
 * 
 * @package Frameworkd\Table
 * @version 1.6.2
 */

class Table {

	/**
	 * @var string $cle Nom du champ PRIMARY KEY dans la table
	 */
    public $cle;
    
    /**
     * @var string $table Nom de la table
     */
    public $table;
    
    /**
     * Tableau où les clés associatives sont les noms des champs de la table
     * @var array $data
     */
    public $data = array();
    
    /**
     * Instance de la connexion à la base de donnée
     * @var ressource $con
     */
    public static $con = null;

    /**
     * Constructeur, set les valeurs de $table et de $cle
     * @param string $table Nom de la table
     * @param string $cle Nom du champ clé primmaire
     */
    public function __construct($table, $cle) {
        $this->table = $table;
        $this->cle = $cle;
        $this->initData();
    }
    
    /**
     * Retourne la valeur de l'élement à l'indice $indice du tableau $this->data
     * @example $objUtilisateur->id (retourne l'id de l'utilisateur, cela evite d'écrire $objUtilisateur->data["id"])
     * @param string $indice Nom de l'élement à chercher
     * @throws Exception retourne le message "$indice . " n'existe pas dans \$this->data" en cas d'erreur
     * @return mixed
     */
    public function __get($indice){
    		if(isset($this->data[str_replace("_id","", $this->cle)."_".$indice]))
    			return $this->data[str_replace("_id","", $this->cle)."_".$indice];
    		else if(isset($this->data[$indice]))
    			return $this->data[$indice];
    		else 
    			throw new Exception($indice . " n'existe pas dans \$this->data");
    }
    
    /**
     * Set la valeur $valeur à l'indice $indice
     * @example $objArticle->titre = "Je suis un titre", cela évite d'écrire $objArticle->data["titre"]="Je suis un titre"
     * @param string $indice L'indice doit être défini dans $this->data
     * @param mixed $valeur
     * @throws Exception Renvoie une exception si $indice n'existe pas dans $this->data
     * @return void
     */
    public function __set($indice,$valeur){
    	if(isset($this->data[$indice]))
    		$this->data[$indice]=$valeur;
    	else if(isset($this->data[str_replace("_id","", $this->cle)."_".$indice])){
    		$this->data[str_replace("_id","", $this->cle)."_".$indice] = $valeur;
    	}
    	else
    		throw new Exception("La clé " . $indice . " n'est pas un champ de la table $this->table il est donc impossible de le setter");
    }

    /**
     * Quand création d'un nouvel objet vide : 
     * initialise le nom des index dans le tableau $this->data,
     * les valeurs associées étant vides
     * @return void
     */
    public function initData() {
        $result = self::$con->query("SHOW COLUMNS FROM " . $this->table);
        while ($row = $result->fetch_row())
            $this->data[$row[0]] = "";
        $this->data[$this->cle] = 0;
    }
    

	/**
	 * Renvoie le nombre d'enregistrement de $this->table;
	 * @return int
	 */
    public function count(){
    	$result = self::$con->query("select count($this->cle) as nb from " . $this->table);
    	$row = $result->fetch_assoc();
    	return $row["nb"];
    }
    
    /**
    * Retourn la valeur de $champ de l'enregistrment $id de la table $this->table 
    * @param int $id identifiant de l'enregistrement
    * @param string $champ nom du champ
    * @return mixed 
    */
    public function valeur($id,$champ){
        $query="select $champ from $this->table where $this->cle=$id";
        $result=self::$con->query($query);
        $row=$result->fetch_assoc();
        return $row[$champ];
    }

	/**
	 * Initialise $this->data avec les données de l'enregistrement $id de la table $this->table
	 * @param int $id identifiant de l'enregistrement à charger
	 * @return void
	 */
    public function chargerDepuisBdd($id) {
        $sql = "select * from $this->table where $this->cle=$id";
        $result = self::$con->query($sql);
        if ($result and $result->num_rows == 1)
            $this->data = $result->fetch_assoc();
        else
            $this->initData();
    }

    /**
     * Remplie les valeurs de $tableau dans $this->data pour les clés présentent dans les deux tableaux
     * @param array $tableau tableau à injecter dans $this->data
     * @return void
     */
    public function chargerDepuisTableau($tableau) {
        foreach ($this->data as $index => $valeur)
            if (isset($tableau[$index]))
                $this->data[$index] = $tableau[$index];
            elseif (@is_null($tableau[$index])) 
                $this->data[$index]=NULL;
    }

    /**
     * Retourne l'ensemble ou une partie des enregistrement de $this->table
     * @example $objArticle->lister() retourne un resultset de MySQL contenant l'ensemble des enregistrements
     * @example $objArticle->lister("limit 0,30) retourne un resultset de MySQL contenant les 30 premiers resultats 
     * @param [string] $page permet de filtrer les résultats en ajoutant une clause à la requête SQL
     * @return resource
     */
    public function lister($page=null) {
    	if($page)
    		$sql = "select * from $this->table $page";
    	else 	
       		$sql = "select * from $this->table";
        return self::$con->query($sql);
    }

    /**
     * Retourne un tableau indicé multidimensionnel ou chaque indice contient un tableau associaitif pour chaque enregistrement de $this->table
     * @return array[int][array]
     */
    public function getListe() {
        $sql = "select * from $this->table";
        $result = self::$con->query($sql);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     *  Sauve le contenu de $this->data dans la table $this->table 
     *  @return void
     */
    public function sauver() {
        //copie du tableau
        $tab = $this->data;
        //Préparation de la liste des champs
        $listField = "(" . implode(",", array_keys($tab)) . ")";
        //extraction de la clé
        $id = $tab[$this->cle];
        //suppression du champ clé du tableau
        unset($tab[$this->cle]);
        //valeurs enveloppées de quote
        $tab = array_map("Table::myQuote", $tab);

        //Création
        if ($id == 0)
            $requete = "insert into $this->table " . $listField . "  values (''," . implode(",", $tab) . ")";
        else
            $requete = "update $this->table set " . implode(",", self::myPrepareUpdate($tab)) . " where $this->cle=$id";

        self::$con->query($requete);

        if ($id == 0)
            $this->data[$this->cle] = self::$con->insert_id;

        if (self::$con->errno) {
            echo "ERREUR : $requete : " . self::$con->error;
            return false;
        } else
            return true;
    }

    /**
     * Supprime un enregistrement
     * @param int $id identifiant de l'enregistrement
     * @return void
     */
    public function supprimer($id) {
        if ($id != 0) {
            $requete = "delete from $this->table where $this->cle = $id";
            self::$con->query($requete);
        }
    }

    /**
     * Echappe les quotes et enveloppe de quotes, sauf si $valeur est de type null
     * @param mixed $valeur valeur à échhaper
     * @return string chaine échappée
     */
    public static function myQuote($valeur) {
        if (is_null($valeur))
            return "null";
        else
            return "'" . self::$con->real_escape_string($valeur) . "'";
    }

    /**
     * Crée un tableau Nom_champ=Valeur
     * @param array $tab Tableau contenant les valeurs à préparer
     * @return array
     */
    public static function myPrepareUpdate($tab) {
        foreach ($tab as $index => $valeur)
            $tab2[] = "$index=$valeur";
        return $tab2;
    }

   
    /**
     * Singleton renvoyant une seule instance de conenxion à la base de donnée
     * Sauvegarder cette instance dans Table::$con
     * @param string $db_server adresse du serveur
     * @param string $db_user nom d'utilisateur 
     * @param string $db_pwd mot de passe
     * @param string $db_bdd nom de la base de donnée
     * @return ressource instance de la connexion à la base de donnée
     */
    public static function getCon($db_server, $db_user, $db_pwd, $db_bdd) {
        if (self::$con == null) {
            self::$con = new mysqli($db_server, $db_user, $db_pwd, $db_bdd);
            //self::$con->set_charset("utf8");

            if (self::$con->connect_errno)
                echo "Echec lors de la connexion à MySQL : " . self::$con->connect_error;
        }
        return self::$con;
    }

    /**
     * Retourn un tableau indicé contenant le nom de chaque champs de $table
     * @param string $table nom de la table
     * @return array
     */
    static function getChamps($table) {
        $sql = "select * from $table";
        $result = self::$con->query($sql);
        $ar = array();
        while ($champ = $result->fetch_field())
            $ar[] = $champ->name;
        return $ar;
    }

   /**
    * Affiche un tableau HTML pour visualisé tous les enregistrements issu de la requète $sql
    * @param string $sql requete SQL
    */
    public function affiche_liste($sql) {
        $result = self::$con->query($sql);
        $table = $this->table;
        $pk = $this->cle;
        ?>
		<H1>table <?= $table ?></H1>
		<table border='1'>
			<caption>
				<a href="<?= BASE_URL ?><?= $table ?>/editer/<?= $pk ?>/0">Ajouter</a>
				<caption>
					<tr>
						<th>Edit</th>
		                        <?php
					// Affichage des noms de champs
					// if ($result)
				foreach ( $result->fetch_fields () as $champ )
					echo "<th>" . $champ->name . "</th>";
				?>
		                        <th>Suppr</th>
					</tr>
		                    <?php
		                    //affichage de chaque ligne de la table
		                    //if ($result)
		                    while ($row = $result->fetch_assoc()) {
		                        ?>
		                        <tr>
						<td><a
							href="<?= BASE_URL ?><?= $table ?>/editer/<?= $pk ?>/<?= $row[$pk] ?>">Editer</a></td>
		                            <?php
		                            foreach ($row as $cle => $item)
		                                echo "<td>" . mhe($item) . "</td>";
		                            ?>
		                            <td><a
							href="<?= BASE_URL ?><?= $table ?>/supprimer/<?= $pk ?>/<?= $row[$pk] ?>">Supprimer</a></td>
					</tr>
		                    <?php } //FIN WHILE  ?>
		                    
		
		</table>
		<?php
	}

	/**
	 * Génère des balises <option> </option> en fonction des résultats de la requête $sql
	 * @param string $sql requete SQL
	 * @param string $pk nom du champ clé primaire primaire
	 * @param string $libelle nom du champ contenant le text à afficher entre les balise option
	 * @param int $idsel identifiant de l'enregistrement à pre-selectionner
	 */
	public static function OPTION_from_table($sql, $pk, $libelle, $idsel) {
		$result = self::$con->query($sql);
		while ($row = $result->fetch_assoc()) {
			$sel = ($idsel == $row[$pk]) ? " selected " : "";
            ?>
			<option value="<?= $row[$pk] ?>" <?= $sel ?>><?= mhe($row[$libelle]) ?></option>
			<?php
		} //FIN WHILE
	}

	/**
	 * Genère des inputs type checkbox à partir des enregistrements de la requete $sql
	 * @param string $sql requête SQL
	 * @param string $pk nom du champ clé primaire
	 * @param string $libelle nom du champ contenant la valeur à afficher à côté du checkbox
	 * @param string $name contenu de l'attribut name de la balise <input>
	 * @param int $idsel identifiant de l'enregistrement à pré-cocher
	 */
	public static function CHECKBOX_from_table($sql, $pk, $libelle, $name, $idsel="") {
                    $result = self::$con->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $sel='';
                        if(is_array($idsel)){
                            foreach($idsel as $cle => $valeur){
                                if($row[$pk]==$valeur)
                                    $sel=" checked ";
                            }
                        }else
                            $sel = ($idsel == $row[$pk]) ? " checked " : "";
                        ?>
						<input value="<?= $row[$pk] ?>" type="checkbox" name="<?=$name?>[]"
							id="<?=$row[$libelle]?>" <?=$sel?>>
						<label for="<?=$row[$libelle]?>"><?=$row[$libelle]?></label>
						<br>
						
						<?php
                                    } //FIN WHILE
                                }
               
                // FUN FUNC option
            }

            //FIN CLASS Table
            ?>
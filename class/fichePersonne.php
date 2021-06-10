<?php
require_once "database.php";
/**
 * class FichePersonne
 * Gestion des informations personnelles d'un memebre
 * 
 */

class FichePersonne
{

    /**
     * @var string prenom du membre
     */
    private $prenom;

    /**
     * @var string nom du membre
     */
    private $nom;

    /**
     * @var string email du membre
     */
    private $email;

    /**
     * @var string date naissance du membre
     */
    private $datenaiss;

    /**
     * @var string code postal du membre
     */
    private $cpostal;

    /**
     * @var string ville du membre
     */
    private $ville;

    /**
     * @var string pays du membre
     */
    private $pays;

    /**
     * @var string adresse postale du membre
     */
    private $adresse;


    /**
     * @var int id fiche personne 
     */
    private $id_perso;


    /**
     * @var int nombre de résultats trouvés après une recherche
     */
    private $nbrResult;

    /**
     * @var int nombre total de pages 
     */
    public $nbrPage;


    /**
     * @var int nombre total de membres par page 
     */
    public $perPage = 15;


    /**
     * @var int Page courante 
     */
    public $currentPage = 1;





    private $db;






    /**
     * Constructeur fesant appel à la classe database pour la connection base de donnée
     */

    function __construct($id_fichepersonne = 1)
    {
        $this->db = new Database();
        $this->infoPersonne($id_fichepersonne);
    }

    private function infoPersonne($id_fichepersonne)
    {
        $db = $this->db->connect_db();
        $info = $db->prepare("select * from fiche_personne where id_perso=:id_fiche");
        $info->bindParam(":id_fiche", $id_fichepersonne, PDO::PARAM_INT);
        if ($info->execute()) {
            $results = $info->fetch(PDO::FETCH_OBJ);
            $this->prenom = $results->prenom;
            $this->nom = $results->nom;
            $this->datenaiss = $results->date_naissance;
            $this->email = $results->email;
            $this->adresse = $results->adresse;
            $this->cpostal = $results->cpostal;
            $this->ville = $results->ville;
            $this->pays = $results->pays;
            $this->id_perso = $results->id_perso;
        }
    }

    /**
     * permet de recuperer tout les membres
     *
     * @return void
     */
    public function AllMembres()
    {
        $database = $this->db->connect_db();

        $count =  $database->query("SELECT count(id_perso) as 'total' from fiche_personne ")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrPage = ceil($total / $this->perPage);
        $this->nbrResult = $total;
        $offset = $this->perPage * ($this->currentPage - 1);
       
        $membres = $database->prepare("SELECT * from fiche_personne ORDER BY nom ASC LIMIT :perpage OFFSET :offset ");
        $membres->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $membres->bindParam(':offset', $offset, PDO::PARAM_INT);
        

        if ($membres->execute()) {
            $result = $membres->fetchAll(PDO::FETCH_OBJ);
           
        }
    }
    
    /**
     * permetre de recuperer les membre ayant pour nom $nom
     * @param string  $nom 
     * @return object  
     */
    public function filter_nom($nom){
        $database = $this->db->connect_db();
        $count =  $database->query("SELECT count(id_perso) as 'total' from fiche_personne where nom like" . "'$nom%'")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResult = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage );

        $membres = $database->prepare("SELECT * from fiche_personne where nom like" . "'$nom%'"."ORDER BY nom ASC LIMIT :perpage OFFSET :offset ");
        $membres->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $membres->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($membres->execute()) {
            $result = $membres->fetchAll(PDO::FETCH_OBJ);
              return $result;
        }else {
            echo "erreur";
        }


    }
    
    /**
     * permetre de recuperer les les membres ayant pour prenom $prenom
     *@param string $prenom
     * @return object
     */
    public function filter_prenom($prenom){
        $database = $this->db->connect_db();
        $count =  $database->query("SELECT count(id_perso) as 'total' from fiche_personne where prenom like" . "'$prenom%'")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResult = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage );

        $membres = $database->prepare("SELECT * from fiche_personne where prenom like" . "'$prenom%'"."ORDER BY prenom ASC LIMIT :perpage OFFSET :offset ");
        $membres->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $membres->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($membres->execute()) {
            $result = $membres->fetchAll(PDO::FETCH_OBJ);
              return $result;
        } else {
            echo "erreur";
        }
    
    }

    /**
     * permetre de recuperer les membres ayant pour prenom $prenom et pour nom $nom
     * @param string $nom
     * @param string $prenom 
     * 
     */
    public function filter_nom_prenom(string $nom , string $prenom){
        $database = $this->db->connect_db();
        $count =  $database->prepare("SELECT count(id_perso) as 'total' from fiche_personne where prenom=:prenom AND nom=:nom");
        $count->bindParam(":prenom",$prenom,PDO::PARAM_STR_CHAR);
        $count->bindParam(":nom",$nom,PDO::PARAM_STR_CHAR);
       
        $count->execute();
        $count = $count->fetch(PDO::FETCH_OBJ);
        
        $total = (int) $count->total ;
        $this->nbrResult = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage - 1);
        if ($offset < 0){
            $offset = 0;

        }

        $membres = $database->prepare("SELECT * from fiche_personne where  prenom=:prenom AND nom=:nom ORDER BY nom ASC LIMIT :perpage OFFSET :offset ");
        $membres->bindParam(":prenom",$prenom,PDO::PARAM_STR_CHAR);
        $membres->bindParam(":nom",$nom,PDO::PARAM_STR_CHAR);
        $membres->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $membres->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($membres->execute()) {
            $result = $membres->fetchAll(PDO::FETCH_OBJ);
             return $result;
        } else {
            echo "erreur";
        }
    }


    /**
     * permetre de mettre a jour les cordonne de l'utilisateur ;
     * @return bool return true si tout c'est bien passer dans le cas contraitre il return false
     */
    public function updateinfo(){
        $database = $this->db->connect_db();

        $update = $database->prepare("UPDATE fiche_personne SET 
        email='$this->email',  ville='$this->ville ', adresse='$this->adresse'
        WHERE id_perso=$this->id_perso");

        if ($update->execute()){
            return true ;
        }
        else{
            return false ;
        }
    }

    /**
     * Permet de recuperer le prénom du membre
     * @return string  
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @return string nom du membre
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return string date de naissance du membre
     */
    public function getNaiss()
    {
        return $this->datenaiss;
    }

    /**
     *@return string email du membre
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email permet de modifier une adresse email 
     * @return void 
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string adresse du membre
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse permet de modifier l'adresse 
     * @return void
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string ville du membre
     */
    public function getVille()
    {
        return $this->ville;
    }
     /**
     * @return string ville du membre
     */
    public function getCpostal()
    {
        return $this->cpostal;
    }
    /**
     * @param string $ville permet de modifier la ville 
     * @return void 
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    public function getPays()
    {
        return $this->pays;
    }

    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    public function getIdperso()
    {
        return $this->id_perso;
    }
    public function getNbrResult()
    {
        return $this->nbrResult;
    }
}

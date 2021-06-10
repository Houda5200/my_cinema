<?php
require_once 'database.php';
class Membre
{

    /**
     * id du membre
     * @property int $id_membre
     */
    private $id_membre;

    /**
     * date de souscription abonement du membre
     * @property string $date_abo
     */
    private $date_abo;

    /**
     * id fiche personne du membre
     * @property int $id_perso
     */
    private $id_perso;

    /**
     * id de l'abonnement que possede le membre 
     * @property int $id_abo
     */
    private $id_abo;

    /**
     * id dernier film vus par l'utilisateur
     * @property int $id_dernier_film
     */
    private $id_dernier_film;

    /**
     * date du dernier film vus par le membre
     * @property int $date_dernier_film
     */
    private $date_dernier_film;

    /**
     * date d(inscription
     * @property int $date_inscription
     */
    private $date_inscription;

  // paginations
    /**
     *
     * @var int $nbrPage nombre de page que contient la recherche
     */
    public $nbrPage;
    /**
     *
     * @var int $perPage nombre de resulat qu'on souhaite afficher par page pardefaut 15
     */
    public $perPage = 10;
    /**
     *
     * @var int $currentPage indique la page courante 
     */
    public $currentPage;

    /**
     *
     * @var int $nbrResultat nombre de film trouver lors de la recherche 
     */
    private $nbrResultat;

    private $db;



    /**
     * Constructeur fesant appel à la classe database pour la connection base de donnée
     */

    function __construct($id_perso = null)
    {
        $this->db = new Database();
        
        $this->infoMembre($id_perso);
    }


    private function infoMembre($id_perso)
    {
        $db = $this->db->connect_db();
        $membre = $db->prepare("SELECT * FROM membre where id_fiche_perso=:id_perso");
        $membre->bindParam(":id_perso", $id_perso, PDO::PARAM_INT);

        if ($membre->execute()) {
            $result = $membre->fetch(PDO::FETCH_OBJ);
            $this->id_membre = $result->id_membre;
            $this->date_abo = $result->date_abo;
            $this->id_abo = $result->id_abo;
            $this->id_perso = $id_perso;
            $this->id_dernier_film = $result->id_dernier_film;
            $this->date_dernier_film = $result->date_dernier_film;
            $this->date_inscription = $result->date_inscription;
        } else {
            return false;
        }
    }
    /**
     * permet de souscrire ou modifier un abonnement
     *@param int $id_abo
     *@param int $id_perso
     * @return bool
     */
    public function updateAbonement($id_abo)    /*fonction publique, qd id abo = 0 = pad d'abonnement donc abo supr*/
    {
        $db = $this->db->connect_db();
        $abonnement = $db->prepare("UPDATE membre SET id_abo=:id_abo , date_abo=NOW() Where id_fiche_perso=:id_perso");
        $abonnement->bindParam(":id_abo", $id_abo, PDO::PARAM_INT);
        $abonnement->bindParam(":id_perso", $this->id_perso, PDO::PARAM_INT);

        if ($abonnement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * permet de recuperer tout l'historique d'un membre
     *
     * @return object 
     */
    public function historiqueMembre()
    {
        $db = $this->db->connect_db();

        $count =  $db->query("SELECT count(id_membre) as 'total' FROM historique_membre WHERE id_membre=$this->id_membre")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResultat = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage - 1);
        if($offset < 0){
            $offset = 0;
        }


        $membre = $db->prepare("SELECT * FROM historique_membre WHERE id_membre=:id_membre ORDER BY date DESC LIMIT :perpage OFFSET :offset");
        $membre->bindParam(":id_membre", $this->id_membre, PDO::PARAM_INT);
       $membre->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
       $membre->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        if ($membre->execute())
        {
             $result = $membre->fetchAll(PDO::FETCH_OBJ);
            return $result;
        }
        else
        {
            return false;
        }
    }

    /**
     * permet de recuperer l'id du membre 
     *
     * @return int
     */
    public function getId_membre()
    {
        return $this->id_membre;
    }
    /**
     * permet de recuperer l'id de la fiche personne du membre
     *
     * @return int
     */
    public function getId_perso()
    {
        return $this->id_perso;
    }
    /**
     * permet de recuperer l'id du dernier film vus par le membre
     *
     * @return int
     */
    public function getId_dernier_film()
    {
        return $this->id_dernier_film;
    }
    /**
     * permet de recuperer la date du dernier vue par le membre
     *
     * @return int
     */
    public function getDate_dernier_film()
    {
        return $this->date_dernier_film;
    }
    public function getDate_inscription()
    {
        return $this->date_inscription;
    }
    public function getId_abo()
    {
        return $this->id_abo;
    }
    public function getDate_abo()
    {
        return $this->date_abo;
    }
    public function getNbrResult()
    {
        return $this->nbrResultat;
    }
}





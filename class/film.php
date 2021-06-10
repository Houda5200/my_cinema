<?php
require_once 'database.php';
/**
 * class Film
 * 
 * permet de recuperer un ensemble d'information sur un ou des film rapidement et simplement
 */
class Film
{
    /**
     * @var int id d'un film
     */
    private $id_film;

    /**
     * @var string  titre d'un film
     */
    private $titre;
    /**
     * @var string genre d'un film
     */
    private $genre;
    /**
     * @var int  duree d'un film en minute
     */
    private $duree;
    /**
     * @var $resum  resumé d'un film
     */
    private $resum;
    /**
     * @var string  nom du distrib  d'un film
     */
    private $distrib;
    /** 
     * @var string  date de debut d'affichage d'un film
    */
    private $date_affiche;

    /**
     * @var string de fin d'affichage d'un film
     */
    private $date_fin;
    /**
     * @var int date d'année de profuction d'un film
     */
    private $annee_prod;
    /**
     *
     * @var int $nbrPage nombre de page que contient la recherche
     */
    public $nbrPage;
    /**
     *
     * @var int $perPage nombre de resulat qu'on souhaite afficher par page pardefaut 15
     */
    public $perPage = 20;
    /**
     *
     * @var int $currentPage indique la page courante 
     */
    public $currentPage  ;
    
    /**
     *
     * @var int $nbrResultat nombre de film trouver lors de la recherche 
     */
    private $nbrResultat;
    /**
     *
     * @var string $imagefilm recupere url de l'image d'un film
     */
    private $imagefilm;

    private $db;

    /**
     * Constructeur fesant appel a la class database pour la connection base de donnée
     */
    function __construct($id_film = null)
    {
        $this->db = new Database();
        $this->info_film($id_film);
    }

    /**
     * @param int $id_film id du film pour le qu'elle on veut recupérer des information
     * @return void
     */
    private function info_film($id_film)
    {

        $database = $this->db->connect_db();
        $film = $database->prepare("SELECT * from film where id_film=:id");
        $film->bindParam(':id', $id_film, PDO::PARAM_INT);

        if ($film->execute()) {
            $results = $film->fetchAll(PDO::FETCH_OBJ);

            foreach ($results as $result) :
                $this->id_film = $result->id_film;
                $this->titre = $result->titre;
                $id_genre = $result->id_genre;
                $this->duree = $result->duree_min;
                $this->resum = $result->resum;
                $id_distrib = $result->id_distrib;
                $this->date_affiche = $result->date_debut_affiche;
                $this->date_fin = $result->date_fin_affiche;
                $this->annee_prod = $result->annee_prod;
            endforeach;

            $genre = $this->db->connect_db();
            $genre = $database->prepare("SELECT nom from genre where id_genre=:id_genre");
            $genre->bindParam(':id_genre', $id_genre, PDO::PARAM_INT);
           
            if($id_genre != NULL){
                if ($genre->execute()) {
                    $result_genre = $genre->fetch(PDO::FETCH_OBJ);
    
                    $this->genre =  $result_genre->nom;
                }
            }
            

            $distrib = $this->db->connect_db();
            $distrib = $database->prepare("SELECT nom from distrib where id_distrib=:id_distrib");
            $distrib->bindParam(':id_distrib', $id_distrib, PDO::PARAM_INT);

             if ($id_distrib != NULL)
            {
                if ($distrib->execute()) {

                    $result_distrib = $distrib->fetch(PDO::FETCH_OBJ);
                       
                    $this->distrib =  $result_distrib->nom;
                }
            }
             // api call    omdbapi

             // recuperer le nom du film
            $titre_film=$this->titre;
            // trim retirer les espace devant la chaine de caractere
            $titre_film=trim($titre_film);
            // rtrim retirer les espace a la fin la chaine de caractere
            $titre_film= rtrim($titre_film);
            // str_replace remplacer les espave des "_"
            $titre_film= str_replace(" ","_",$titre_film);
              
            // executer l'api avec curl
            $curl = curl_init();
            $url = "http://www.omdbapi.com/?t=".$titre_film."&apikey=26c135a9";
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
 
                $data = curl_exec($curl);

            if($data === false){
     
            $this->imagefilm = Null;

            }else{
            $data = json_decode($data);
            if (isset($data->Poster)) {
            $this->imagefilm = $data->Poster; 
            }
            else{
                $this->imagefilm = Null;
            }
     
            }
            curl_close($curl);
            }
        }

    

    /**
     * Permet de recupérer tout les film de la base de donnees
     *
     * @return void
     */
    public function allFilm()
    {
           
        $database = $this->db->connect_db();

        $count =  $database->query("SELECT count(id_film) as 'total' from film ")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrPage = ceil($total / $this->perPage);
        $this->nbrResultat = $total;
        $offset = $this->perPage * ($this->currentPage - 1);
        if($offset < 0){
            $offset =0;
        }
        
   
        
        $film = $database->prepare("SELECT * from film ORDER BY titre ASC LIMIT :perpage OFFSET :offset ");
        $film->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $film->bindParam(':offset', $offset, PDO::PARAM_INT);
        

        if ($film->execute()) {
            $result = $film->fetchAll(PDO::FETCH_OBJ);
            return $result;
        }
    }




    /**
     * function permettant de recuper les info d'un film en ayant l'id genre et le titre du film
     *
     * @param int $id_genre
     * @param  string $titre
     * @return object
     */
    public function filter_genre_nom($id_genre , $titre)
    {
        $database = $this->db->connect_db();

        $count =  $database->query("SELECT count(id_film) as 'total' from film where id_genre=$id_genre and titre like" . "'$titre%'")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResultat = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage - 1);
        if($offset < 0){
            $offset = 0;
        }

        $film = $database->prepare("SELECT * from film where id_genre=:id and titre like" . "'$titre%'"."ORDER BY titre ASC LIMIT :perpage OFFSET :offset ");
        $film->bindParam(':id', $id_genre, PDO::PARAM_INT);
        $film->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $film->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($film->execute()) {
            $result = $film->fetchAll(PDO::FETCH_OBJ);
             return $result;
        } else {
            echo "erreur";
        }
    }

    /**
     * function permettant de recuper les info d'un film en ayant l'id genre et le titre du film
     *
     * @param int $id_genre
     * @param  string $titre
     * @return object
     */
    public function filter_distrib_nom($id_distrib , $titre)
    {
        $database = $this->db->connect_db();

        $count =  $database->query("SELECT count(id_film) as 'total' from film where id_distrib=$id_distrib and titre like" . "'$titre%'")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResultat = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage - 1);
        if($offset < 0){
            $offset = 0;
        }

        $film = $database->prepare("SELECT * from film where id_distrib=:id and titre like" . "'$titre%'"."ORDER BY titre ASC LIMIT :perpage OFFSET :offset ");
        $film->bindParam(':id', $id_distrib, PDO::PARAM_INT);
        $film->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $film->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($film->execute()) {
            $result = $film->fetchAll(PDO::FETCH_OBJ);
             return $result;
        } else {
            echo "erreur";
        }
    }
    
    /**
     * permet de recuperer l'ensemble des film ayants pour id  $ig genre
     *
     * @param int $id_genre id du genre qu'on recherche 
     * @return object  retourne un object contenant l'ensemble des film ayants pour id  $ig genre
     */
    public function filter_genre($id_genre)
    {
      
        $database = $this->db->connect_db();

        $count =  $database->query("SELECT count(id_film) as 'total' from film where id_genre=$id_genre ")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResultat = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage - 1);
        if($offset < 0){
            $offset =0;
        }

        $film = $database->prepare("SELECT * from film where id_genre=$id_genre ORDER BY titre ASC LIMIT :perpage OFFSET :offset ");
        $film->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $film->bindParam(':offset', $offset, PDO::PARAM_INT);

        

        if ($film->execute()) {
            $result = $film->fetchAll(PDO::FETCH_OBJ);
             return $result;
        } else {
            echo "erreur genre";
        }
    }
    
     /**
     * permet de recuperer l'ensemble des film ayants pour id_distrib  $id_distrib
     *
     * @param int $id_distrib
     * @return object  retourne un object contenant l'ensemble des film ayants pour id_distrib  $ig distrib
     */
    public function filter_distrib($id_distrib)
    {

        $database = $this->db->connect_db();

        $count =  $database->query("SELECT count(id_film) as 'total' from film where id_distrib=$id_distrib ")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResultat = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage - 1);
        if($offset < 0){
            $offset =0;
        }

        $film = $database->prepare("SELECT * from film where id_distrib=:id ORDER BY titre ASC LIMIT :perpage OFFSET :offset");
        $film->bindParam(':id', $id_distrib, PDO::PARAM_INT);
        $film->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $film->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($film->execute()) {
            $result = $film->fetchAll(PDO::FETCH_OBJ);
             return $result;
        }
    }


      /**
     * permet de recuperer tout l'historique du film 
     *
     * @return object 
     */
    public function historiqueFilm()
    {
        $db = $this->db->connect_db();

        $count =  $db->query("SELECT count(id_film) as 'total' FROM historique_membre WHERE id_film=$this->id_film")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResultat = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage - 1);
        if($offset < 0){
            $offset = 0;
        }
       

        $membre = $db->prepare("SELECT * FROM historique_membre WHERE id_film=:id_film ORDER BY date DESC LIMIT :perpage OFFSET :offset");
        $membre->bindParam(":id_film", $this->id_film, PDO::PARAM_INT);
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
     * permet de recuperer tout information des film commençant par la chaine de caractere passer en paramettre 
     *
     * @param string $titre
     * @return object retourne un object contenant l'ensemble  des information des film ayant des titre commençant par $titre
     */
    public function filter_titre($titre)
    {

        $database = $this->db->connect_db();
        $count =  $database->query("SELECT count(id_film) as 'total' from film WHERE titre like" . "'$titre%'"."ORDER BY titre ASC ")->fetch(PDO::FETCH_OBJ);
        $total = (int) $count->total ;
        $this->nbrResultat = $total;
        $this->nbrPage = ceil($total / $this->perPage);
        $offset = $this->perPage * ($this->currentPage - 1);
        if($offset < 0){
            $offset =0;
        }

        $film = $database->prepare("SELECT * from film WHERE titre like" . "'$titre%'"." ORDER BY titre ASC LIMIT :perpage OFFSET :offset");
        $film->bindParam(':perpage', $this->perPage, PDO::PARAM_INT);
        $film->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($film->execute()) {
            $result = $film->fetchAll(PDO::FETCH_OBJ);
             return $result;
        }else{
            echo "error";
        }
        
    }
    /**
     * permet de recuperer  titre d'un film
     *
     * @return string  
     */
    public function getTitre()
    {
        return $this->titre;
    }
    
    /**
     * permet de recuperer le resumer d'un film
     *
     * @return string
     */
    public function getResum()
    {
        return $this->resum;
    }
    
    /**
     * permet de recuperer le nom du genre d'un film
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }
    /**
     * permet de recuperer le nom du distrib d'un film
     *
     * @return string
     */
    public function getDistrib()
    {
        return $this->distrib;
    }
    /**
     * permet de recuperer l'id d'un film 
     *
     * @return int
     */
    public function getId_film()
    {
        return $this->id_film;
    }
     /**
     * permet de recuperer la duree d'un film 
     *
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }
     /**
     * permet de recuperer la date de debut d'affiche d'un film 
     *
     * @return string
     */
    public function getDate_debut_affiche()
    {
        return $this->date_affiche;
    }
      /**
     * permet de recuperer la date de fin d'affiche d'un film 
     *
     * @return string
     */
    public function getDate_fin_affiche()
    {
        return $this->date_fin;
    }
      /**
     * permet de recuperer l'anée de prod d'un film 
     *
     * @return int
     */
    public function getAnnee_prod()
    {
        return $this->annee_prod;
    }
       /**
     * permet de recuperer le nombre de resultat trouver 
     *
     * @return int
     */
    public function getNbr_resultat()
    {
        return $this->nbrResultat;
    }

    public function getImagefilm()
    {
        return $this->imagefilm;
    }
}


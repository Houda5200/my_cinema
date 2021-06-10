<?php 
require_once 'database.php';

class Abonnement{
    private $id_abo;
    
    private $name ;

    private $resum;

    private $duree_abo;

    private $prix;

    function __construct($id_abo = null)
    {
        $this->db = new Database();
        $this->infoGenre($id_abo);
    }

    private  function infoGenre($id_abo){

        $db = $this->db->connect_db();
        $abo = $db->prepare("SELECT * FROM abonnement where id_abo=:id_abo");
        $abo->bindParam(":id_abo", $id_abo, PDO::PARAM_INT);

        
        if($abo->execute()){
            $result = $abo->fetch(PDO::FETCH_OBJ);

               $this->id_abo = $result->id_abo;
               $this->name= $result->nom;
               $this->resum = $result->resum;
               $this->duree_abo = $result->duree_abo;
               $this->prix = $result->prix;

        }
        else{
               return false;
            }
    }

    public static function allAbo(){
        $db = new Database();
        $db = $db->connect_db();
        $abo = $db->prepare("SELECT * FROM abonnement ");
        
        if($abo->execute()){
            $result = $abo->fetchAll(PDO::FETCH_OBJ);

            return $result;
        }
        else{
               return false;
            }
    }

    public function getId_abo()
    {
        return $this->id_abo;
    }

    public function getName_abo(){
        return $this->name;
    }
    public function getPrix(){
        return $this->prix;
    }
    public function getResum(){
        return $this->resum;
    }
    public function getDuree(){
        return $this->duree_abo;
    }
}
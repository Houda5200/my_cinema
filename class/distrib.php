<?php

require_once 'database.php';
class Distrib {

    private $id_distrib;
    
    private $name ;

    function __construct($id_distrib = null)
    {
        $this->db = new Database();   /*apl a la bdd*/
        $this->infoDistrib($id_distrib);
    }

    private  function infoDistrib($id_distrib){   

        $db = $this->db->connect_db();
        $distrib = $db->prepare("SELECT * FROM distrib where id_distrib=:id_distrib");
        $distrib->bindParam(":id_distrib", $id_distrib, PDO::PARAM_INT);

        
        if($distrib->execute()){
            $result = $distrib->fetch(PDO::FETCH_OBJ);

               $this->id_distrib = $result->id_distrib;
               $this->name= $result->nom;

        }
        else{
               return false;
            }
    }

    public static function allDistrib(){
        $db = new Database();
        $db = $db->connect_db();
        $distrib = $db->prepare("SELECT id_distrib , nom FROM distrib ORDER BY nom asc ");
        
        if($distrib->execute()){
            $result = $distrib->fetchAll(PDO::FETCH_OBJ);

            return $result;
        }
        else{
               return false;
            }
    }

    public function getId_distrib()
    {
        return $this->id_distrib;
    }

    public function getName_distrib(){
        return $this->name;
    }
}


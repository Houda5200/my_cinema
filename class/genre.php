<?php

require_once 'database.php';
class Genre {

    private $id_genre;
    
    private $name ;

    function __construct($id_genre = null)
    {
        $this->db = new Database();
        $this->infoGenre($id_genre);
    }

    private  function infoGenre($id_genre){

        $db = $this->db->connect_db();
        $genre = $db->prepare("SELECT * FROM genre where id_genre=:id_genre");
        $genre->bindParam(":id_genre", $id_genre, PDO::PARAM_INT);

        
        if($genre->execute()){
            $result = $genre->fetch(PDO::FETCH_OBJ);

               $this->id_genre = $result->id_genre;
               $this->name= $result->nom;

        }
        else{
               return false;
            }
    }

    public static function allGenre(){
        $db = new Database();
        $db = $db->connect_db();
        $genre = $db->prepare("SELECT * FROM genre ORDER BY nom ASC");
        
        if($genre->execute()){
            $result = $genre->fetchAll(PDO::FETCH_OBJ);

            return $result;
        }
        else{
               return false;
            }
    }

    public function getId_genre()
    {
        return $this->id_genre;
    }

    public function getName_genre(){
        return $this->name;
    }
}


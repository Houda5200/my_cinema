<?php

class Database
{
    private $username = "houda";
    private $password = "houda";
    private $dbname = "cinema";



    /*connexion PDO*/
    function connect_db()
    {
        try {
            $bdd = new PDO("mysql:host=localhost;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            return $bdd;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}

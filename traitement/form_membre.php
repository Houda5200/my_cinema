<?php
require_once'class/fichePersonne.php';

if(isset($_GET['nom'])&&isset($_GET['prenom'])){
     
   

    $fiche_personne = new FichePersonne();
    if(isset($_GET["page"])){
        $fiche_personne->currentPage = $_GET["page"];
       }else{
        $fiche_personne->currentPage = 0;
       }

    if($_GET['nom']!="" && $_GET['prenom'] == "")
    {
        
        
       
       $result = $fiche_personne->filter_nom($_GET['nom']);

      

    }
    elseif($_GET['prenom'] !="" && $_GET['nom'] == "")
    {
        $result = $fiche_personne->filter_prenom($_GET['prenom']);
    }
    else
    {
        $result = $fiche_personne->filter_nom_prenom($_GET['nom'],$_GET['prenom']);
    }
    
}
<?php


if(isset($_GET['titre'])&&isset($_GET['genre'])&&isset($_GET['distrib'])){
     
   

    $fiche_film = new Film();
    
    if(isset($_GET["page"])){
        $fiche_film->currentPage = $_GET["page"];
       }else{
        $fiche_film->currentPage = 0;
       }


    if($_GET['titre']!="" && $_GET['genre'] == "" && $_GET['distrib'] == "")
    {
        
        $result = $fiche_film->filter_titre($_GET['titre']);
         
    }
    elseif($_GET['titre'] == "" && $_GET['genre'] != "" && $_GET['distrib'] == "")
    {
        $result = $fiche_film->filter_genre($_GET['genre']);
    }
    elseif($_GET['titre'] == "" && $_GET['genre'] == "" && $_GET['distrib'] != "")
    {
        $result = $fiche_film->filter_distrib($_GET['distrib']);
    }
    
    elseif($_GET['titre'] =! "" && $_GET['genre'] == "" && $_GET['distrib'] != "")
    {
        $result = $fiche_film->filter_distrib_nom($_GET['titre'] , $_GET['distrib']);
    }
    elseif($_GET['titre']=!"" && $_GET['genre'] != "" && $_GET['distrib'] == "")
    {
        $result = $fiche_film->filter_genre_nom($_GET['titre'] , $_GET['genre']);
    }
    
}
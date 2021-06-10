<?php

require_once '../class/membre.php';

if (isset($_GET["id_perso"] )&& isset($_GET["id_abo"])) {
    $modif_id_abo = new Membre($_GET["id_perso"]);

    $modif_id_abo->updateAbonement($_GET["id_abo"]);

    header("Location: ../view/fiche_membre.php?id=".$_GET["id_perso"]."&etat=success");
    exit();
    
}
<?php require_once '../class/fichePersonne.php';
require_once '../class/membre.php';
require_once '../class/film.php';
require_once '../class/abonnement.php';
$info = new FichePersonne($_GET["id"]);
$infoMembre = new Membre($_GET["id"]);
if(isset($_GET["page"])){
  $infoMembre->currentPage = (int) $_GET["page"];
 }else{
  $infoMembre->currentPage = 0;
 }
$titreFilm = new Film($infoMembre->getId_dernier_film());
if ($infoMembre->getId_abo() > 0) {
  $nomAbo = new Abonnement($infoMembre->getId_abo());
}


?>
<!DOCTYPE html>
<html lang="fr">
<?php include 'head.php';?>
<body>
<div class="container-fluid">

<div class="row">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">My_cinema</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item ">
        <a class="nav-link" href="../index.php">Client <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="recherche_film.php">Film</a>
      </li>
    </ul>
  </div>
</nav>
</div>

<?php if (isset($_GET["etat"])):?>
<div class="row  " id="success" >
<div class="alert alert-success" role="alert">
  Les modifications on étés effectuées avec succès 
</div>
</div>
<?php endif;?>

<div class="row info-membre">
<div class="col-3"><img src="../assets/default.jpeg" alt="photo par default"></div>
<div class="col-3">
 <p>Nom : <?= $info->getNom()?></p>
 <p>Prenom :  <?= $info->getPrenom()?> </p>
 <p>Email :  <?= $info->getEmail()?> </p>
 <p>Adresse : <?= $info->getAdresse()?> </p>
 <p>Ville : <?= $info->getVille().", ".$info->getCpostal()?> </p>
</div>

<div class="col">
 <h5>info membre : </h5>
 <?php if($infoMembre->getId_abo() == 0):?>
<p>le client ne possède pas d'abonnement</p>
<a href="abonnement.php?id_perso=<?= $_GET["id"]?>">Souscrire un abonnement</a>

<?php elseif($infoMembre->getId_abo() > 0):?>
<p>Abonnement  : <?=$nomAbo->getName_abo()?></p>
<p>date de souscription : <?=$infoMembre->getDate_abo()?></p>
<a href="abonnement.php?id_perso=<?= $_GET["id"]?>">Modifier l'abonnement</a>
 <?php endif ?>
 <p>Date insciption  : <?=$infoMembre->getDate_inscription()?></p>
 <p>Dernier film vus  : <?=$titreFilm->getTitre()?></p>
</div>

</div>

  <div class="row historique-titre ">
     <h3 class="titre-histo">Historique des films vus par le membre : (<?php $infoMembre->historiqueMembre(); echo $infoMembre->getNbrResult();?>)</h3>
  </div>

  <div class="row">
  <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">titre</th>
      <th scope="col">date</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($infoMembre->historiqueMembre() as $histo): $titre= new Film($histo->id_film)?>
    <tr>
      <th scope="row"><a href="fiche_film.php?id=<?=$histo->id_film?>"><?= $titre->getTitre()?></a></th>
      <td><?=$histo->date?></td>
      <td><a href="avis.php?id_histo=">Ajouter un avis</a></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
  </div>

  <?php if($infoMembre->getNbrResult() > 15): 

    ?>
<div class="row">
<nav aria-label="Page navigation example">
  <ul class="pagination">
         
    <li class="page-item"><a class="page-link" href="fiche_membre.php?id=<?=$_GET['id']?>&page=<?= $infoMembre->currentPage - 1;?>">Precédent</a></li>
    <?php for ($i=0; $i < $infoMembre->nbrPage ; $i++):?>
      <li class="page-item"><a class="page-link" href="fiche_membre.php?id=<?=$_GET['id']?>&page=<?= $i;?>"><?= $i;?></a></li>
      <?php endfor;?>
    <li class="page-item"><a class="page-link" href="fiche_membre.php?id=<?=$_GET['id']?>&page=<?= $infoMembre->currentPage + 1;?>">Suivant</a></li>
  </ul>
</nav>
</div>

<?php endif; ?>

</div>

<script src="../assets/main.js"></script>

</body>
</html>
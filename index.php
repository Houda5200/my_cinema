<?php 
require_once'traitement/form_membre.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="assets/style.css">
    <title>My_cinema</title>
</head>
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
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Client <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="view/recherche_film.php">Film</a>
      </li>
    </ul>
  </div>
</nav>
</div>

<form action="" method="get">
<div class="row">
    <div class="col-4">
      <input type="text" name="nom" class="form-control" placeholder="Nom">
    </div>
    <div class="col-4">
      <input type="text" name="prenom" class="form-control" placeholder="Prenom">
    </div>
    <div class="col-4">
    <button type="submit" class="btn btn-primary">rechercher</button>
    </div>
   
  </div>
 </form>
<?php if(isset($result)):?>
<div class="row">
<h5>Resultat trouver : (<td><?= $fiche_personne->getNbrResult();?></td>)</h5> 
<table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Nom</th>
      <th scope="col">Prenom</th>
      <th scope="col">E-mail</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>

  <?php foreach($result as $membre):?>
    <tr>
      <th scope="row"><?= $membre->id_perso?></th>
      <td><?= $membre->nom?></td>
      <td><?= $membre->prenom?></td>
      <td><?= $membre->email?></td>
      <td><a href="view/fiche_membre.php?id=<?= $membre->id_perso?>">accéder a la fiche membre</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>

 <?php if($fiche_personne->getNbrResult() > 15): 
    ?>
<div class="row">
<nav aria-label="Page navigation example">
  <ul class="pagination">
         
    <li class="page-item"><a class="page-link" href="index.php?nom=<?=$_GET['nom']?>&prenom=<?=$_GET['prenom']?>&page=<?= $fiche_personne->currentPage - 1;?>">Precédent</a></li>
    <?php for ($i=0; $i < $fiche_personne->nbrPage ; $i++):?>
    <li class="page-item"><a class="page-link" href="index.php?nom=<?=$_GET['nom']?>&prenom=<?=$_GET['prenom']?>&page=<?= $i;?>"><?= $i;?></a></li>
    <?php endfor;?>
    <li class="page-item"><a class="page-link" href="index.php?nom=<?=$_GET['nom']?>&prenom=<?=$_GET['prenom']?>&page=<?= $fiche_personne->currentPage + 1;?>">Suivant</a></li>
  </ul>
</nav>
</div>
<?php endif; ?>
<?php endif; ?>
</div>

</body>
</html>
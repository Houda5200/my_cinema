<?php 
require_once '../class/genre.php';
require_once '../class/distrib.php';
require_once '../class/film.php';
require_once '../traitement/form_film.php';

?>
<!DOCTYPE html>
<html lang="en">
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
              <a class="nav-link active" href="#">Film</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>

    <form action="" method="get">
      <div class="row">
        <div class="col-3">
          <input type="text" name="titre" class="form-control" placeholder="TItre">
        </div>
        
        <div class="col-3">
        <select class="form-select" aria-label="Default select " name="genre">
          <option value="" selected>Genre</option>
          <?php foreach(Genre::allGenre() as $genre):?>
          <option value="<?= $genre->id_genre;?>"><?= $genre->nom;?></option>
          <?php endforeach;?>
        </select>
        </div>

        <div class="col-3">
        <select class="form-select" aria-label="Default select " name="distrib">
          <option value="" selected>Distrib</option>
          <?php foreach(Distrib::allDistrib() as $distrib):?>
          <option value="<?= $distrib->id_distrib;?>"><?= $distrib->nom;?></option>
          <?php endforeach;?>
        </select>
        </div>

        <div class="col-3">
          <button type="submit" class="btn btn-primary">rechercher</button>
        </div>

      </div>
    </form>
    
    <?php if(isset($result)):?>
<div class="row">
<h5>Resultat trouver : (<td><?= $fiche_film->getNbr_resultat();?></td>)</h5> 
<table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col">Id_film</th>
      <th scope="col">titre</th>
      <th scope="col">id_genre</th>
      <th scope="col">id distrib</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>

  <?php foreach($result as $film):?>
    <tr>
      <th scope="row"><?= $film->id_film?></th>
      <td><?= $film->titre?></td>
      <td><?= $film->id_genre?></td>
      <td><?= $film->id_distrib?></td>
      <td><a href="fiche_film.php?id=<?= $film->id_film?>">accéder a la fiche film</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>

 <?php if($fiche_film->getNbr_resultat() > 15): 
    ?>
<div class="row">
<nav aria-label="Page navigation example">
  <ul class="pagination">
         
    <li class="page-item"><a class="page-link" href="recherche_film.php?titre=<?=$_GET['titre']?>&genre=<?=$_GET['genre']?>&distrib=<?=$_GET['distrib']?>&page=<?= $fiche_personne->currentPage - 1;?>">Precédent</a></li>
    <?php for ($i=0; $i < $fiche_film->nbrPage ; $i++):?>
    <li class="page-item"><a class="page-link" href="recherche_film.php?titre=<?=$_GET['titre']?>&genre=<?=$_GET['genre']?>&distrib=<?=$_GET['distrib']?>&page=<?= $i;?>"><?= $i;?></a></li>
    <?php endfor;?>
    <li class="page-item"><a class="page-link" href="recherche_film.php?titre=<?=$_GET['titre']?>&genre=<?=$_GET['genre']?>&distrib=<?=$_GET['distrib']?>&page=<?= $fiche_personne->currentPage + 1;?>">Suivant</a></li>
  </ul>
</nav>
</div>
<?php endif; ?>
<?php endif; ?>


  </div>



</body>

</html>
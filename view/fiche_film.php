<?php
require_once '../class/film.php';
$infoFilm = new Film($_GET["id"]);

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
              <a class="nav-link active" href="#">Film</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
    <div class="row  titre-film">
           <h4 class="titre">Titre : <?= $infoFilm->getTitre() ?></h4>
           </div>
    <div class="row">
       <div class="col-3 image">
         <?php if($infoFilm->getImagefilm() != null) :?>

       <img class="image_titre" src="<?= $infoFilm->getImagefilm(); ?>" alt="image du film">

          <?php else:?>
            <img class="image-default" src="../assets/image_film_default.jpg" alt="image de defaut">
            <p>404 image non disponible</p>

          <?php endif;?>
       </div>

      <div class="col info">
          
         <div class="row info-film">
            <div class="col-4 info-g">
              <p>Genre : <?= $infoFilm->getGenre();?></p>
              <p>Distribution : <?= $infoFilm->getDistrib();?></p>
              <p class="resum"> Resumé : <?= $infoFilm->getResum();  ?></p>
            </div>
            <div class="col-3 infog2">
              <p>durée : <?=$infoFilm->getDuree();?> min</p>
              <p>date debut affiche : <?=$infoFilm->getDate_debut_affiche()?></p>
              <p>date fin affiche : <?= $infoFilm->getDate_fin_affiche()?></p>
              <p>annee de prod : <?= $infoFilm->getAnnee_prod()?></p>
            </div>
         </div>
      </div>
      

    </div>

  </div>


  </div>
</body>

</html>
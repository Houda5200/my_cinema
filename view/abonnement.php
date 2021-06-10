<?php
require_once '../class/abonnement.php';
require_once '../class/membre.php';

?>
<!DOCTYPE html>
<html lang="fr">
<?php include 'head.php'; ?>

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

        <h4>Modiffier/Supprimer/Souscrire</h4>

        <div class="row">
            <div class="col-4">

                <form action="../traitement/form_abo.php" method="get">
                    <label for="id_perso">id_perso</label>
                    <input type="text" id="id_perso" name="id_perso" value="<?= $_GET["id_perso"] ?>" readonly="readonly">
                    <select name="id_abo" class="form-select form-select-lg mb-4" aria-label=".form-select-lg example">
                        <option value="" selected>Sélectionner une Action </option>
                        <option value="0">Supprimer</option>
                        <?php foreach (Abonnement::allAbo() as $abo) : ?>
                            <option value="<?= $abo->id_abo ?>"><?= $abo->nom ?></option>
                        <?php endforeach; ?>
                    </select>

            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary">executer</button>
            </div>
            </form>
        </div>

        <div class="row titre-abo">
            <h4>Liste des abonnement disponible</h4>
        </div>

        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Resum</th>
                    <th scope="col">Duree</th>
                    <th scope="col">Prix</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach (Abonnement::allAbo() as $abo) : ?>                   
                <tr>
                    <th scope="row"><?= $abo->nom?></th>
                    <td><?= $abo->resum?></td>
                    <td><?= $abo->duree_abo?> jour</td>
                    <td><?= $abo->prix?>£</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>
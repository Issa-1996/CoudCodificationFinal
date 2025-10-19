<?php
include('../../traitement/fonction.php');
$pavillon = $_GET['pavillon'];

$data = getTitulaireByPavillon($pavillon, $connexion);


?>
<?php
    include_once("head.php");
?>

<head>
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
</head>

<div class="container-fluid">
    <center>
        <br><br>
        <h1> GESTION DES LOGEMENTS</h1>
        <br><br>
        <h2> PAVILLON : <?= htmlspecialchars($pavillon) ?></h2>
    </center>
    <br><br>
    <center>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Chambre</th>
                    <th scope="col">Lit</th>
                    <th scope="col">Num Etudiant</th>
                    <th scope="col">Nom Titulaire</th>
                    <th scope="col">Voisins</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)) : ?>
                <?php foreach ($data as $index => $row) : ?>

                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['chambre']) ?></td>
                    <td><?= htmlspecialchars($row['lit']) ?></td>
                    <td><?= htmlspecialchars($row['num_etu']) ?></td>
                    <td><?= htmlspecialchars($row['titulaire_nom']) ?></td>
                    <td>
                        <a
                            href="lit.php?id_paie=<?= htmlspecialchars($row['id_paie'], ENT_QUOTES) ?>&lit=<?= htmlspecialchars($row['lit'], ENT_QUOTES) ?>">
                            Voir détails
                        </a>
                    </td>

                </tr>
                <?php endforeach; ?>
                <?php else : ?>
                <tr>
                    <td colspan="6">Aucun étudiant trouvé pour ce pavillon.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br><br>
        <button class="btn btn-success" onclick="goBack()">Retour</button>

        <script>
        function goBack() {
            window.history.back();
        }
        </script>
    </center>
</div>
<?php include('footer.php'); ?>

<script src="../../assets/js/script.js"></script>
<script src="../../assets/js/jquery-3.2.1.min.js"></script>
<script src="../../assets/js/plugins.js"></script>
<script src="../../assets/js/main.js"></script>
</body>

</html>
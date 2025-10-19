<?php
include('../../traitement/fonction.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_paie'])  && isset($_GET['lit'])) {
    $paie = $_GET['id_paie'] ?? null;
    $lit = $_GET['lit'] ?? null;
}

$occupants = getEtudiantByLit($lit,$paie, $conn); ;
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
        <h2> Occupant du Lit <?= htmlspecialchars($lit) ?></h2>
        <br><br>
    </center>
    <center>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Num Étudiant</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($occupants)) : ?>
                <?php foreach ($occupants as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['num_etu']) ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= htmlspecialchars($row['prenoms']) ?></td>
                    <td><?= htmlspecialchars($row['statut_etudiant']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php else : ?>
                <tr>
                    <td colspan="5">Aucun étudiant trouvé pour ce pavillon.</td>
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
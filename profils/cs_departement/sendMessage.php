<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /coud_app_codification_final/');
    exit();
}
if (empty($_SESSION['classe'])) {
    header('location: /coud_app_codification_final/profils/personnels/niveau3.php');
    exit();
}

include('../../traitement/fonction.php');
connexionBD();
include('../../traitement/requete3.php');
$bool_F = isDemarre($_SESSION['classe'], 'F');
$bool_G = isDemarre($_SESSION['classe'], 'G');
$isQuota_F = getQuotaClasse($_SESSION['classe'], 'F')['COUNT(*)'];
$isQuota_G = getQuotaClasse($_SESSION['classe'], 'G')['COUNT(*)'];
if ($bool_F) {
    $data_all_quota_F = getQuotaClasse_2($_SESSION['classe'], 'F');
    // Regrouper les données par pavillon -> chambre -> lits (avec id_lit_q)
    $structure_F = [];
    while ($row = mysqli_fetch_assoc($data_all_quota_F)) {
        $pavillon = $row['pavillon'];
        $chambre = $row['chambre'];
        $structure_F[$pavillon][$chambre][] = [
            'lit' => $row['lit'],
            'id_lit_q' => $row['id_lit_q']
        ];
    }
} else {
    $data_all_quota_F = array();
}
if ($bool_G) {
    $data_all_quota_G = getQuotaClasse_2($_SESSION['classe'], 'G');
    // Regrouper les données par pavillon -> chambre -> lits (avec id_lit_q)
    $structure_G = [];
    while ($row = mysqli_fetch_assoc($data_all_quota_G)) {
        $pavillon = $row['pavillon'];
        $chambre = $row['chambre'];
        $structure_G[$pavillon][$chambre][] = [
            'lit' => $row['lit'],
            'id_lit_q' => $row['id_lit_q']
        ];
    }
} else {
    $data_all_quota_G = array();
}


if (isset($_GET['message'])) {
    $_SESSION['message'] = $_GET['message'];
} else {
    $_SESSION['message'] = '';
}
if (isset($_GET['message2'])) {
    $_SESSION['message2'] = $_GET['message2'];
} else {
    $_SESSION['message2'] = '';
}
if (isset($_GET['message3'])) {
    $_SESSION['message3'] = $_GET['message3'];
} else {
    $_SESSION['message3'] = '';
}
$isSend = getMessageEnvoyer($_SESSION['classe'])['COUNT(*)'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include('../../head.php'); ?>
    <div class="container mt-4">
        <div class="row" style="justify-content: center;">
            <?php if ($_SESSION['message']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-success" role="alert">
                        <?= $_SESSION['message']; ?>
                    </div>
                </div>
            <?php } else if ($_SESSION['message2']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-success" role="alert">
                        <?= $_SESSION['message2']; ?>
                    </div>
                </div>
            <?php } else if ($_SESSION['message3']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['message3']; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h2><?= $_SESSION['classe'] ?> (Filles)</h2>
                <table class="table table-bordered" style="font-size: 16px;">
                    <thead class="table" style="background-color:#3777b0; color: white;">
                        <tr>
                            <th>Pavillon</th>
                            <th>Chambre</th>
                            <th>Lit</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($structure_F)) {
                            foreach ($structure_F as $pavillon => $chambres): ?>
                                <?php $rowspanPavillon = array_sum(array_map('count', $chambres)); ?>
                                <?php $firstPavillon = true; ?>
                                <?php foreach ($chambres as $chambre => $lits): ?>
                                    <?php $rowspanChambre = count($lits); ?>
                                    <?php $firstChambre = true; ?>
                                    <?php foreach ($lits as $info_lit): ?>
                                        <tr>
                                            <?php if ($firstPavillon): ?>
                                                <td rowspan="<?= $rowspanPavillon ?>" style="vertical-align: middle; text-align: center;"><?= $pavillon ?></td>
                                                <?php $firstPavillon = false; ?>
                                            <?php endif; ?>
                                            <?php if ($firstChambre): ?>
                                                <td rowspan="<?= $rowspanChambre ?>" style="vertical-align: middle; text-align: center;"><?= $chambre ?></td>
                                                <?php $firstChambre = false; ?>
                                            <?php endif; ?>
                                            <td style="text-align: center;"><?= $info_lit['lit'] ?></td>
                                            <!-- <td style="text-align: center;">
                                            <?php if (!$bool_F) { ?>
                                                <a class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#exampleModal1">
                                                    <i class="fa fa-trash"></i> Supprimer
                                                </a>
                                            <?php } else { ?>
                                                <a class="btn btn-dark btn-sm" data-toggle="modal" data-target="#exampleModal1" style="pointer-events: none; color: gray; cursor: not-allowed; text-decoration: none;">
                                                    <i class="fa fa-trash"></i> Supprimer
                                                </a>
                                            <?php } ?>
                                        </td> -->
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRMATION DE SUPPRESSION</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ETES-VOUS SUR DE VOULOIR CONTINUER ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a type="button" class="btn btn-secondary" data-dismiss="modal">ANNULER</a>
                                                        <a href="requestSendMessage.php?id_lit_q=<?= $info_lit['id_lit_q'] ?>" class="btn btn-danger">SUPPRIMER</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach;
                        } else { ?>
                            <td colspan="3">LE QUOTA DE LA CLASSE N'EST ENCORE VALIDER PAR LE CHEF DE SERVICE</td>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h2><?= $_SESSION['classe'] ?> (Garçons)</h2>
                <table class="table table-bordered" style="font-size: 16px;">
                    <thead class="table" style="background-color:#3777b0; color: white;">
                        <tr>
                            <th>Pavillon</th>
                            <th>Chambre</th>
                            <th>Lit</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($structure_G)) {
                            foreach ($structure_G as $pavillon => $chambres): ?>
                                <?php $rowspanPavillon = array_sum(array_map('count', $chambres)); ?>
                                <?php $firstPavillon = true; ?>
                                <?php foreach ($chambres as $chambre => $lits): ?>
                                    <?php $rowspanChambre = count($lits); ?>
                                    <?php $firstChambre = true; ?>
                                    <?php foreach ($lits as $info_lit): ?>
                                        <tr>
                                            <?php if ($firstPavillon): ?>
                                                <td rowspan="<?= $rowspanPavillon ?>" style="vertical-align: middle; text-align: center;"><?= $pavillon ?></td>
                                                <?php $firstPavillon = false; ?>
                                            <?php endif; ?>
                                            <?php if ($firstChambre): ?>
                                                <td rowspan="<?= $rowspanChambre ?>" style="vertical-align: middle; text-align: center;"><?= $chambre ?></td>
                                                <?php $firstChambre = false; ?>
                                            <?php endif; ?>
                                            <td style="text-align: center;"><?= $info_lit['lit'] ?></td>
                                            <!-- <td style="text-align: center;">
                                            <?php if (!$bool_F) { ?>
                                                <a class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#exampleModal1">
                                                    <i class="fa fa-trash"></i> Supprimer
                                                </a>
                                            <?php } else { ?>
                                                <a class="btn btn-dark btn-sm" data-toggle="modal" data-target="#exampleModal1" style="pointer-events: none; color: gray; cursor: not-allowed; text-decoration: none;">
                                                    <i class="fa fa-trash"></i> Supprimer
                                                </a>
                                            <?php } ?>
                                        </td> -->
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRMATION DE SUPPRESSION</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ETES-VOUS SUR DE VOULOIR CONTINUER ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a type="button" class="btn btn-secondary" data-dismiss="modal">ANNULER</a>
                                                        <a href="requestSendMessage.php?id_lit_q=<?= $info_lit['id_lit_q'] ?>" class="btn btn-danger">SUPPRIMER</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach;
                        } else { ?>
                            <td colspan="3">LE QUOTA DE LA CLASSE N'EST ENCORE VALIDER PAR LE CHEF DE SERVICE</td>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="requestSendMessage.php" method="post">
                    <input type="text" name="niveauFormation" value="<?= $_SESSION['classe'] ?>" style="visibility: hidden;">
                    <!-- Button trigger modal -->
                    <?php if ((($bool_F) && ($bool_G)) && (($isQuota_F > 0) && ($isQuota_G > 0)) && ($isSend<1)) { ?>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                            ENVOYER SMS
                        </button>
                    <?php } else { ?>
                        <a href="niveau3.php" class="btn btn-secondary">
                            RETOUR
                        </a>
                    <?php } ?>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRMATION</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    VOUS ETES SUR DE VOULOIR CONTINUER ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ANNULER</button>
                                    <button type="submit" class="btn btn-success">VALIDER</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../../assets/js/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../../assets/js/script3.js"></script>

</html>
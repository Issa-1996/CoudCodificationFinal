<?php session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /campuscoud.com/');
    exit();
}
//connexion à la base de données
include('../../traitement/fonction.php');
connexionBD();
// Sélectionnez les options à partir de la base de données avec une pagination
include('../../traitement/requete.php');

verif_type_mdp_2($_SESSION['username']);

// Comptez le nombre total d'options dans la base de données details lits affecter (quotas)

$countIn = 0;
if (isset($_GET['erreurValider'])) {
    $_SESSION['erreurValider'] = $_GET['erreurValider'];
} else {
    $_SESSION['erreurValider'] = '';
}
if (isset($_GET['successValider'])) {
    $_SESSION['successValider'] = $_GET['successValider'];
} else {
    $_SESSION['successValider'] = '';
}
if (isset($_GET['erreurNonTrouver'])) {
    $_SESSION['erreurNonTrouver'] = $_GET['erreurNonTrouver'];
} else {
    $_SESSION['erreurNonTrouver'] = '';
}
if (isset($_GET['erreurForclo'])) {
    $_SESSION['erreurForclo'] = $_GET['erreurForclo'];
} else {
    $_SESSION['erreurForclo'] = '';
}
if (isset($_GET['erreurImpayer'])) {
    $_SESSION['erreurImpayer'] = $_GET['erreurImpayer'];
} else {
    $_SESSION['erreurImpayer'] = '';
}




?>
<!--script langage='javascript'>
alert('Veuillez reessayer plus tard.')

</script-->
<?php
//echo '<meta http-equiv="refresh" content="0;URL=../../">';
//	exit();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION</title>
    <!-- CSS================================================== -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <!-- script================================================== -->
    <script src="../../assets/js/modernizr.js"></script>
    <script src="../../assets/js/pace.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include('../../head.php'); ?>
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1>VALIDATION PAR PRESENCE PHYSIQUE</h1><br>
            </div>
        </div>
        <!-- <span style="color: red;"> <?= $_SESSION['erreurValider']; ?> </span> -->
        <div class="row" style="justify-content: center;">
            <?php if ($_SESSION['erreurValider']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-warning" role="alert">
                        <?= $_SESSION['erreurValider']; ?>
                    </div>
                </div>
            <?php } elseif ($_SESSION['successValider']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-success" role="alert">
                        <?= $_SESSION['successValider']; ?>
                    </div>
                </div>
            <?php } elseif ($_SESSION['erreurNonTrouver']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['erreurNonTrouver']; ?>
                    </div>
                </div>
            <?php } elseif ($_SESSION['erreurImpayer']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['erreurImpayer']; ?>
                    </div>
                </div>
            <?php } elseif ($_SESSION['erreurForclo']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-dark" role="alert">
                        <?= $_SESSION['erreurForclo']; ?>
                    </div>
                </div>
            <?php } ?>
            <form action="requestValidation" method="POST" style="display: flex;justify-content: center">
                <div class="row">
                    <div class="col-md-10">
                        <input id="numEtudiant" name="numEtudiant" type="text" class="form-control" placeholder="NUMERO CARTE ETUDIANT" oninput="checkInput()" onblur="validateInput()">
                        <!-- <p id="affichage"></p> -->
                        <script>
                            // Sélectionner l'élément input
                            var inputElement = document.getElementById('numEtudiant');

                            // Ajouter un écouteur d'événement sur l'input pour détecter les changements
                            inputElement.addEventListener('input', function() {
                                // Récupérer la valeur du champ input
                                var texte = inputElement.value;

                                // Convertir le texte en majuscule
                                var texteMajuscule = texte.toUpperCase();

                                // Mettre à jour la valeur du champ input
                                inputElement.value = texteMajuscule;

                                // Récupérer l'élément où afficher le texte
                                var affichageElement = document.getElementById('affichage');

                                // Mettre à jour le texte de l'élément
                                affichageElement.textContent = texteMajuscule;
                            });
                        </script>
                        <!-- <span id="inputMessage" style="color: green; font-size: 12px;"></span> -->
                    </div>
                    <div class="col-md-2">
                        <button id="submitBtn" type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </div>
            </form>
        </div><br><br>
        <div class="row">
            <div class="col-md-12">
                <ul class="options">
                    <?php
                    if (isset($_GET['data'])) {
                        $data = $_GET['data'];
                        if (isset($_GET['statut'])) { ?>
                            <form action="requestValidation" method="POST">
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input type="text" class="form-control" placeholder="Prénom : <?= $data['prenoms'] ?>" disabled>
                                        <input class="form-control" name="id_etu" value="<?= $data['id_etu'] ?>" style="visibility: hidden;">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="Nom : <?= $data['nom'] ?>" disabled>
                                        <?php if (isset($_GET['idLit'])) { ?>
                                            <input class="form-control" name="idLit" value="<?= $_GET['idLit'] ?>" style="visibility: hidden;">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="Faculté : <?= $data['etablissement'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="Niveau : <?= $data['niveauFormation'] ?>" disabled>
                                    </div>
                                </div><br>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="Moyenne : <?= $data['moyenne'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="Statut : <?= $_GET['statut'] ?>" disabled>
                                    </div>
                                </div><br>



                                <?php if ($_GET['statut'] == 'Suppleant(e)') {

                                    $sexe_etudiant =  studentConnect($data['num_etu'])['sexe'];
                                    $quota = getQuotaClasse($data['niveauFormation'], $sexe_etudiant)['COUNT(*)'];
                                    $dataStatutStudentSearch = getOnestudentStatus($quota, $data['niveauFormation'], $sexe_etudiant, $data['num_etu']);
                                    $rang = $dataStatutStudentSearch['rang'];

                                    $monTitulaire = getOneTitulaireBySuppleant($quota, $data['niveauFormation'], $sexe_etudiant, $rang);
                                    $resultatReqLitEtu = getOneLitByStudent($monTitulaire['num_etu']);
                                    $rows = $resultatReqLitEtu->fetch_assoc();
                                    $pavillon = $rows['pavillon'];
                                    $lit = $rows['lit'];
                                ?>

                                    <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <div class="col-md-4     mb-3">
                                            <input class="form-control" placeholder="Pavillon: <?php echo $pavillon; ?>" disabled>
                                        </div>
                                        <div class="col-md-4    ">
                                            <input class="form-control" placeholder="Lit: <?php echo $lit; ?>" disabled>
                                        </div>
                                    </div>

                                <?php
                                }
                                ?>

                                <?php
                                if (isset($_GET['statut']) && $_GET['statut'] != 'Forclos(e)') {
                                ?>
                                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#confirmationModal">VALIDER</button>
                                <?php
                                } else { ?>
                                    <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <?php $type = $data['type'];
                                        if ($data['type'] == 'auto') {
                                            $type = 'Automatique';
                                        }
                                        $motif = $data['motif_manuel'];
                                        if ($data['type'] == 'auto') {
                                            $motif = 'Retard';
                                        }
                                        ?>
                                        <div class="col-md-4 mb-3">
                                            <input class="form-control" placeholder="Type de Forclusion : <?= $type ?>" disabled>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input class="form-control" placeholder="Motif : <?= $motif ?>" disabled>
                                        </div>
                                    </div><br>
                                    <a class="btn btn-secondary" href="/campuscoud.com/profils/validation/validation" type="button">RETOUR</a>
                                <?php } ?>
                                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir effectuer cette action ?
                                            </div>
                                            <div class="modal-footer">
                                                <!-- Boutons pour confirmer ou annuler -->
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Confirmer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php } else {
                        ?>
                            <form action="requestValidation" method="POST">
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4     mb-3">
                                        <input type="text" class="form-control" placeholder="Prenom: <?= $data['prenoms'] ?>" disabled>
                                        <input class="form-control" name="valide" value="<?= $data['0'] ?>" style="visibility: hidden;">
                                    </div>
                                    <div class="col-md-4    ">
                                        <input class="form-control" placeholder="Nom: <?= $data['nom'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4     mb-3">
                                        <input class="form-control" placeholder="FAC: <?= $data['etablissement'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4    ">
                                        <input class="form-control" placeholder="Niveau: <?= $data['niveauFormation'] ?>" disabled>
                                    </div>
                                </div><br>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4     mb-3">
                                        <input class="form-control" placeholder="Numero carte: <?= $data['num_etu'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4    ">
                                        <input class="form-control" placeholder="Campus: <?= $data['campus'] ?>" disabled>
                                    </div>
                                </div><br>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4     mb-3">
                                        <input class="form-control" placeholder="Pavillon: <?= $data['pavillon'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4    ">
                                        <input class="form-control" placeholder="Lit: <?= $data['lit'] ?>" disabled>
                                    </div>
                                </div>
                                <?php if ($data['migration_status'] == 'Migré') { ?>
                                    <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <div class="col-md-4     mb-3">
                                            <input class="form-control" placeholder="Validé le <?= dateFromat($data['dateTime_val']) ?>" disabled>
                                        </div>
                                    </div>
                                    <a class="btn btn-secondary" href="/campuscoud.com/profils/validation/validation" type="button">RETOUR</a>
                                <?php } else { ?>
                                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#confirmationModal">VALIDER</button>
                                <?php } ?>
                                <!-- Modal -->
                                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir effectuer cette action ?
                                            </div>
                                            <div class="modal-footer">
                                                <!-- Boutons pour confirmer ou annuler -->
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Confirmer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    <?php }
                    } else {
                        if (isset($_GET['data_1'])) {
                            $data = $_GET['data_1'];

                            $nom_base = $data['base'];
                            $date_debut = $data['date_debut'];
                            $date_fermeture = $data['date_fermeture'];
                            $nb_mois_total = $data['nb_mois_total'];
                            $nom = $data['nom'];
                            $prenom = $data['prenoms'];
                            $niveauFormation = $data['niveauFormation'];
                            $nb_mois_payes = $data['nb_mois_payes'];
                            $nb_mois_impayes = $data['nb_mois_impayes'];
                            $montant_restant = $data['montant_restant'];
                            if (isset($data['libelle_paye']) && !empty($data['libelle_paye'])) {
                                $libelle_paye = $data['libelle_paye'];
                            } else {
                                $libelle_paye = [];
                            }

                            // Calcul du pourcentage de paiement
                            $pourcentage_paye = ($nb_mois_payes / $nb_mois_total) * 100;

                            echo "
    <div style='
        font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border: none;
        border-radius: 16px;
        padding: 25px 30px;
        max-width: 900px;
        margin: 25px auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        line-height: 1.6;
        color: #333;
        position: relative;
        overflow: hidden;
    '>
    
    <!-- Effet décoratif -->
    <div style='
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        border-radius: 0 0 0 100px;
        opacity: 0.1;
        z-index: 0;
    '></div>
    
    <div style='position: relative; z-index: 1;'>
    
        <!-- En-tête -->
        <div style='text-align: center; margin-bottom: 25px;'>
            <h2 style='
                color: #2c3e50; 
                margin: 0 0 5px 0;
                font-size: 24px;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            '>
                <span style='
                    background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 20px;
                '>📘</span>
                Fiche Étudiant
            </h2>
            <p style='color: #7f8c8d; margin: 0; font-size: 14px;'>Informations académiques et financières</p>
        </div>

        <!-- Tableau d'informations -->
        <div style='
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        '>
            <!-- Ligne 1 -->
            <div style='
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 1px solid #f1f1f1;
            '>
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #4facfe;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>📚</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Base</div>
                        <div style='font-weight: 600;'>$nom_base</div>
                    </div>
                </div>
                
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #00cec9;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>🗓️</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Début</div>
                        <div style='font-weight: 600;'>$date_debut</div>
                    </div>
                </div>
                
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #fd79a8;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>📅</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Fermeture</div>
                        <div style='font-weight: 600;'>$date_fermeture</div>
                    </div>
                </div>
            </div>

            <!-- Ligne 2 -->
            <div style='
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 1px solid #f1f1f1;
            '>
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #6c5ce7;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>👤</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Étudiant</div>
                        <div style='font-weight: 600;'>$nom $prenom</div>
                    </div>
                </div>
                
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #00b894;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>🎓</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Niveau</div>
                        <div style='font-weight: 600;'>$niveauFormation</div>
                    </div>
                </div>
                
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #fdcb6e;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>💰</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Mois payés</div>
                        <div style='font-weight: 600;'>$nb_mois_payes / $nb_mois_total</div>
                    </div>
                </div>
            </div>

            <!-- Ligne 3 -->
            <div style='
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            '>
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #e17055;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>⚠️</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Mois impayés</div>
                        <div style='font-weight: 600;'>$nb_mois_impayes</div>
                    </div>
                </div>
                
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #d63031;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>💸</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Montant restant</div>
                        <div style='font-weight: 600; color: #d63031;'>$montant_restant FCFA</div>
                    </div>
                </div>
                
                <div style='
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                '>
                    <span style='
                        background: #00b894;
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 14px;
                    '>📊</span>
                    <div>
                        <div style='font-size: 12px; color: #7f8c8d;'>Progression</div>
                        <div style='font-weight: 600;'>" . number_format($pourcentage_paye, 1) . "%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de progression -->
        <div style='
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        '>
            <div style='
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
            '>
                <span style='font-weight: 600; color: #2c3e50;'>Progression des paiements</span>
                <span style='font-weight: 600; color: #4facfe;'>" . number_format($pourcentage_paye, 1) . "%</span>
            </div>
            <div style='
                height: 12px;
                background: #e9ecef;
                border-radius: 6px;
                overflow: hidden;
            '>
                <div style='
                    height: 100%;
                    width: $pourcentage_paye%;
                    background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
                    border-radius: 6px;
                    transition: width 0.5s ease;
                '></div>
            </div>
        </div>

        <!-- Liste des mois payés -->
        <div style='
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        '>
            <div style='
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 15px;
            '>
                <span style='
                    background: #4facfe;
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 14px;
                '>✅</span>
                <span style='font-weight: 600; color: #2c3e50;'>Mois payés</span>
            </div>
            
            <div style='
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            '>
    ";

                            for ($i = 0; $i < count($libelle_paye); $i++) {
                                echo "
                <span style='
                    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-size: 13px;
                    font-weight: 500;
                    color: #2c3e50;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                '>{$libelle_paye[$i]}</span>
        ";
                            }

                            echo "
            </div>
        </div>
        
        <!-- Pied de page -->
        <div style='
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 12px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        '>
            Généré le " . date('d/m/Y') . "
        </div>
        
        </div>
    </div>
    ";
                        }
                    } ?>
                </ul>
            </div>
        </div>
        <script src="../../assets/js/jquery-3.2.1.min.js"></script>
        <script src="../../assets/js/plugins.js"></script>
        <script src="../../assets/js/main.js"></script>

        <!-- JavaScript de Bootstrap (assurez-vous d'ajuster le chemin si nécessaire) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script src="../../assets/js/script.js"></script>
</body>

</html>
<?php
// $periodes = liste_periode();
// $test= verifierUtilisateursToutesBases();
// print_r($test);
// $periodes = liste_periode();
// $nombre = count($periodes);

// for ($i = 0; $i < $nombre; $i++) {
//     echo "ID : " . $periodes[$i]['id_peri'] . " | ";
//     echo "Année : " . $periodes[$i]['annee'] . " | ";
//     // echo "Nom base : " . $periodes[$i]['nom_base'] . "<br>";
//     $connexion = connexionBD($periodes[$i]['nom_base']);
//     $test = getUsers();
//     print_r(count($test));
// }

?>
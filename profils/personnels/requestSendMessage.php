<?php
// Démarrer la session pour stocker les messages
session_start();
include('../../traitement/fonction.php');

$data_all_quota_F = getQuotaClasse($_SESSION['classe'], 'F');
if (isset($_GET['id_lit_q'])) {
    $delete = removeQuotas($_GET['id_lit_q']);
    if ($delete) {
        header("Location: sendMessage.php?message=LIT RETIRER DU QUOTA AVEC SUCCESS");
        exit;
    }
}

if (isset($_POST['niveauFormation_F']) && isset($_POST['sexe_F']) && $_POST['sexe_F'] == "F") {
    try {
        // TRAITEMENT ENVOIE MESSAGE AUX ETUDIANTS FEMININ
        $quotaNiveau_F = getQuotaClasse($_POST['niveauFormation_F'], $_POST['sexe_F'])['COUNT(*)'];
        $allStudent_F = getAllDatastudentStatus($quotaNiveau_F, $_POST['niveauFormation_F'], 'F');
        $dateTime_sys = date("Y-m-d H:i:s");
        addSendMessage($_POST['niveauFormation_F'], $dateTime_sys, $_SESSION['username'], $_POST['sexe_F']);
        for ($i = 0; $i < count($allStudent_F); $i++) {
            if ($allStudent_F[$i]['statut'] == 'Attributaire') {
                //Mettre ici l'appel de la fonction sendMessage() pour les attributaires
                // print_r($allStudent_F[$i]['statut']);
                // print_r($allStudent_F[$i]['telephone']);
            }
            if ($allStudent_F[$i]['statut'] == 'Suppleant(e)') {
                //Mettre ici l'appel de la fonction sendMessage() pour les suppleants
                // print_r($allStudent_F[$i]['statut']);
                // print_r($allStudent_F[$i]['telephone']);
            }
        }
        header("Location: sendMessage.php?message2=SMS ENVOYER AU FILLES AVEC SUCCESS");
        exit;
    } catch (Exception $e) {
        header("Location: sendMessage.php?message3=Oups =>Erreur : " . $e->getMessage());
        exit;
    }
}
if (isset($_POST['niveauFormation_G']) && isset($_POST['sexe_G']) && $_POST['sexe_G'] == "G") {
    try {
        // TRAITEMENT ENVOIE MESSAGE AUX ETUDIANTS MASCULIN
        $quotaNiveau_G = getQuotaClasse($_POST['niveauFormation_G'], $_POST['sexe_G'])['COUNT(*)'];
        $allStudent_G = getAllDatastudentStatus($quotaNiveau_G, $_POST['niveauFormation_G'], 'G');
        $dateTime_sys = date("Y-m-d H:i:s");
        addSendMessage($_POST['niveauFormation_G'], $dateTime_sys, $_SESSION['username'], $_POST['sexe_G']);
        for ($i = 0; $i < count($allStudent_G); $i++) {
            if ($allStudent_G[$i]['statut'] == 'Attributaire') {
                //Mettre ici l'appel de la fonction sendMessage() pour les attributaires
                // print_r($allStudent_G[$i]['statut']);
                // print_r($allStudent_G[$i]['telephone']);
            }
            if ($allStudent_G[$i]['statut'] == 'Suppleant(e)') {
                //Mettre ici l'appel de la fonction sendMessage() pour les suppleants
                // print_r($allStudent_G[$i]['statut']);
                // print_r($allStudent_G[$i]['telephone']);
            }
        }
        header("Location: sendMessage.php?message2=SMS ENVOYER AU GARCONS AVEC SUCCESS");
        exit;
    } catch (Exception $e) {
        header("Location: sendMessage.php?message3=Oups =>Erreur : " . $e->getMessage());
        exit;
    }
}

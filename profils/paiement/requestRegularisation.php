<?php session_start();

if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /campuscoud.com/');
    exit();
}

include('../../traitement/fonction.php');

$datesys0 = date("Y-m-d");
$datesys = strtotime($datesys0);
$an0 = date('Y', $datesys);
$an = substr($an0, 2, 2);

if (isset($_POST['numEtudiant'])) {
    $num_etu = $_POST['numEtudiant'];
    $_SESSION['num_etu'] = $_POST['numEtudiant'];
    $bd_connect =  verifierUtilisateursToutesBases($num_etu);
    if (isset($bd_connect) && !empty($bd_connect) && $bd_connect != 'none') {
        $bd_connect =  $bd_connect['connexion'];
        $base = verifierUtilisateursToutesBases($num_etu)['base'];
        if (getIsForclu_1($num_etu, $bd_connect)) {
            $queryString = http_build_query(['data' => getIsForclu_1($num_etu, $bd_connect)]);
            header('Location: regularisation.php?erreurForclo=ETUDIANT FORCLOS(E) !!!&statut=forclos(e)&' . $queryString);
        } else {
            if ($dataStudentConnect = studentConnect_1($num_etu, $bd_connect)) {
                $dataStudentConnect_classe = $dataStudentConnect['niveauFormation'];
                $dataStudentConnect_sexe = $dataStudentConnect['sexe'];
                $dataStudentConnect_quota = getQuotaClasse_1($dataStudentConnect_classe, $dataStudentConnect_sexe, $bd_connect)['COUNT(*)'];
                $dataStudentConnect_statut = getOnestudentStatus_1($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $num_etu, $bd_connect);
                if ($dataStudentConnect_statut['statut'] == 'Attributaire') {
                    $data = getOneByValidate_1($num_etu, $bd_connect);
                    if (mysqli_num_rows($data) > 0) {
                        $temp = 0;
                        while ($row = mysqli_fetch_array($data)) {
                            $array[$temp] = $row;
                            $temp++;
                        }
                        $tmpDir = __DIR__ . '/tmp';
                        if (!file_exists($tmpDir)) {
                            mkdir($tmpDir, 0777, true);
                        }
                        $fileName = 'data_' . uniqid() . '.json';
                        $filePath = $tmpDir . '/' . $fileName;
                        file_put_contents($filePath, json_encode($array));
                        header("Location: regularisation.php?file=$fileName");
                        exit();
                    } else {
                        header("location: regularisation.php?erreurNonTrouver=VOUS N'AVEZ PAS ENCORE VALIDER VOTRE LIT !!!");
                    }
                    mysqli_free_result($data);
                } else if ($dataStudentConnect_statut['statut'] == 'Suppleant(e)') {
                    header("location: regularisation.php?erreurNonTrouver=VOUS ETES SUPPLEANT, C'EST VOTRE TITULAIRE QUI DOIT PAYER LA CAUTION !!!");
                } else {
                    header("location: regularisation.php?erreurNonTrouver=VOUS N'ETES PAS ATTRIBUTAIRE DE LIT !!!");
                }
            } else {
                header("location: regularisation.php?erreurNonTrouver=ETUDIANT INTROUVABLE: Veuillez vous approcher du Departement informatique du COUD");
            }
        }
    } else {
        header("location: regularisation.php?erreurNonTrouver=ETUDIANT INTROUVABLE DANS TOUTES LES BASES DE REGULATION DES ARRIERER: Veuillez vous approcher du Departement informatique du COUD");
    }
}

if (isset($_POST['valide'])) {
    $i = 0;
    $libelle = "";
    $bd_connect =  verifierUtilisateursToutesBases($_POST['num_etu'])['connexion'];
    $base = verifierUtilisateursToutesBases($_POST['num_etu'])['base'];
    try {
        $id_val = $_POST['valide'];
        $user = $_SESSION['username'];
        $montant_recu = $_POST['montant_recu'];
        $libelle = [];
        foreach ($_POST['libelle'] as $mois_caution => $value) {
            try {
                $libelle[$i] = $value;
                $i++;
            } catch (Exception $e) {
                header('Location: paiement.php?erreurValider=VEUILLER SELECTIONNER LES MOIS OU LA CAUTION !!!');
                exit();
            }
        }
        $chaine_libelle = json_encode($libelle);
        $chaine_libelle = str_replace(['[', ']', '"'], ' ', $chaine_libelle);
        $tableau_situation_paye = getAllSituation_2($_SESSION['num_etu'], $bd_connect);
        $compt = 0;
        while ($situation = mysqli_fetch_array($tableau_situation_paye)) {
            $motsA = explode(' ', $chaine_libelle);
            $motsA = str_replace(' ', '', $motsA);
            foreach ($motsA as $mot) {
                if (strlen($mot) > 2) {
                    if (strpos($situation['libelle'], $mot) !== false) {
                        $compt++;
                        $queryString = http_build_query(['data' => $situation]);
                        header('Location: paiement.php?erreurMois=' . $mot . '&' . $queryString);
                        exit();
                    }
                }
            }
        }
        if ($compt == 0) {
            $user = $_SESSION['username'];
            $accronyme = accronyme($user);
            $link = connexionBD($base);
            $ins00 = "select max(num_ordre_user) as numauto from codif_paiement where an='$an0' and username_user='$user'"; //echo $ins00;
            $exx00 = mysqli_query($link, $ins00);
            $n_rows0 = mysqli_fetch_assoc($exx00);
            $ordre = $n_rows0['numauto'] + 1;
            $quittance = $an . "-" . $accronyme . "-" . $ordre;
            $requete = setPaiement_1($id_val, $user, $montant_recu, $chaine_libelle, $quittance, $an0, $ordre, $bd_connect);
            if ($requete == 1) {
                $telephone = getTelephoneEtudiant($_SESSION['num_etu']);
                sms_paiement_etudiant($montant_recu, $_SESSION['num_etu'], $quittance);
                enreg_sms($_SESSION['num_etu'], $telephone, 'paiement_chambre');
                header('Location: paiement.php?successValider=PAIEMENT REUSSI: SMS ENVOYE au ' . $telephone . ' !');
            }
        }
    } catch (Exception $e) {
        header('Location: paiement.php?erreurValider=ERREUR !');
        echo $e;
    }
}

<?php
include("vues/v_sommaire3.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
switch($action){
	case 'selectionnerMois':{//recup toutes les fiches validees
		$LesGens = $pdo->getLesFichesValidees();
		include("vues/v_listeMoisVisit2.php");
		break;
	}
	case 'voirEtatFrais':{//on recup les infos de la fiche validée choisie
		$leMois = $_REQUEST['lstVisit'];
                $mois=substr( $leMois,3,4).substr( $leMois,0,2);
                $numAnnee =substr( $mois,0,4);
		$numMois =substr( $mois,4,2);
                $nom=substr( $leMois,9,7);
                $nom= str_replace(' ', '', $nom);//supprime les espaces
                $idVisiteur=$pdo->getIdVisiteur2($nom);
                $idVisiteur=$idVisiteur[0]['id'];
                $idVisiteur=str_replace(' ', '', $idVisiteur);
                $LesGens = $pdo->getLesFichesValidees();//recup les fiches validees
		include("vues/v_listeMoisVisit2.php");
                $mois=substr( $leMois,3,4).substr( $leMois,0,2);
                $mois= str_replace(' ', '', $mois);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait2($idVisiteur,$mois);
		$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$mois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais2($idVisiteur,$mois);
                $libEtat = $lesInfosFicheFrais[0]['libetat'];//recup les valeurs de la fiche frais validee choisie
                $idEtat = $lesInfosFicheFrais[0]['idetat'];
		$montantValide = $lesInfosFicheFrais[0]['montantvalide'];
		$nbJustificatifs = $lesInfosFicheFrais[0]['nbjustificatifs'];
		$dateModif =  $lesInfosFicheFrais[0]['datemodif'];
		$dateModif =  dateAnglaisVersFrancais($dateModif);
		include("vues/v_etatFrais3.php");
                break;
	}
        
        case 'validerRemboursement':{
            $idVisiteur = $_REQUEST['idVisiteur'];
            $mois = $_REQUEST['mois'];
            $etat='RB';
            $pdo->majEtatFicheFrais($idVisiteur,$mois,$etat);//change l'etat de la fiche frais de valide à remboursé
        
        }
}
?>
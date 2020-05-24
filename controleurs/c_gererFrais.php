<?php
include("vues/v_sommaire.php");
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date("d/m/Y"));
$numAnnee =substr( $mois,0,4);
$numMois =substr( $mois,4,2);
$action = $_REQUEST['action'];
switch($action){
	case 'saisirFrais':{//si cest le premier frais crée pour le mois, on crée une fiche de frais pour le mois
		if($pdo->estPremierFraisMois($idVisiteur,$mois)){
			$pdo->creeNouvellesLignesFrais($idVisiteur,$mois);
		}
		break;
	}
	case 'validerMajFraisForfait':{//on met à jour les frais compris dans le forfait
		$lesFrais = $_REQUEST['lesFrais'];
                $m=array();
                $m[0]=110;
                $m[1]=0.62;
                $m[2]=80;
                $m[3]=25;// les 4 montants 
		if(lesQteFraisValides($lesFrais)){
	  	 	$pdo->majFraisForfait($idVisiteur,$mois,$lesFrais,$m);//on les met a jour
		}
		else{
			ajouterErreur("Les valeurs des frais doivent être numériques");//erreur si texte saisi
			include("vues/v_erreurs.php");
		}
	  break;
	}
	case 'validerCreationFrais':{//on crée des frais hors forfait, on recupere les valeurs
		$dateFrais = $_REQUEST['dateFrais'];
		$libelle = $_REQUEST['libelle'];
		$montant = $_REQUEST['montant'];
		valideInfosFrais($dateFrais,$libelle,$montant);
		if (nbErreurs() != 0 ){
			include("vues/v_erreurs.php");
		}
		else{// on insere les valeus recup dans la bdd (la fiche frais du mois)
			$pdo->creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$dateFrais,$montant);
		}
		break;
	}
	case 'supprimerFrais':{//on supprime le frais hors forfait 
		$idFrais = $_REQUEST['idFrais'];
                $info=$pdo->getInfoFraisHorsForfait($idFrais);
                $montant=$info[0]['montant'];
                $idVisiteur=$info[0]['idvisiteur'];
                $mois=$info[0]['mois'];//on recup les infos du frais a partir de son id
                $pdo->supprimerFraisHorsForfait($idFrais);
                $pdo->supprimerFraisHorsForfait2($montant,$mois,$idVisiteur);//on le supprime de la liste, 
                ///et on le retire du montant valide
		break;
	}
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$mois);
$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$mois);
include("vues/v_listeFraisForfait.php");
include("vues/v_listeFraisHorsForfait.php");

?>
<?php

include("vues/v_sommaire3.php");
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date("d/m/Y"));
$numAnnee =substr( $mois,0,4);
$numMois =substr( $mois,4,2);
$action = $_REQUEST['action'];
switch($action){
    case 'selectionnerMois':{
                $LesGens= $pdo->getLesGens();
                $current=date("Y/m");
                $current=substr($current,0,4).substr($current,5,2);
		$lesMois=$pdo->getLesMoisDisponibles2($current);
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
                $visit = 'none';
		include("vues/v_listeMoisVisit.php");
		break;
	}
        case 'voirEtatFrais':{
                $visit='oui';
                $current=date("Y/m");
                $current=substr($current,0,4).substr($current,5,2);
		$leMois = $_REQUEST['lstMois']; 
                $leV = $_REQUEST['lstVisit'];
                $leV1= $pdo->getPrenom($leV);
                $leV2=$leV1['prenom'];
                $leVisiteur=$leV." ".$leV2 ;
		$lesMois=$pdo->getLesMoisDisponibles2($current);
                $LesGens= $pdo->getLesGens();//recup la liste des mois et des visiteurs
		$moisASelectionner = $leMois;
		include("vues/v_listeMoisVisit.php");
                $idV=$pdo->getIdVisiteur($leV);//recupere les infos de la fichefrais saisi
                $idVisiteur=$idV['id'];
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
		$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
                //change aussi l'etat de ceux qui ne sont pas de la date courrante
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
                $idEtat = $lesInfosFicheFrais['idetat'];
		$libEtat = $lesInfosFicheFrais['libetat'];
		$montantValide = $lesInfosFicheFrais['montantvalide'];
		$nbJustificatifs = $lesInfosFicheFrais['nbjustificatifs'];
		$dateModif =  $lesInfosFicheFrais['datemodif'];
		$dateModif =  dateAnglaisVersFrancais($dateModif);//recupere  les valeurs de la requete dans des variables
		include("vues/v_etatFrais2.php");
                break;
	}
        
        case 'validerMajFraisForfait':{
		$frais0 = $_REQUEST['0'];
                $frais1 = $_REQUEST['1'];
                $frais2 = $_REQUEST['2'];
                $frais3 = $_REQUEST['3'];//recupere les quatre frais du forfait
                $lesFrais=array();
                $lesFrais[0]=$frais0 ;//les met dans un tableau
                $lesFrais[1]=$frais1 ;
                $lesFrais[2]=$frais2 ;
                $lesFrais[3]=$frais3 ;
                $m=array();
                $m[0]=110;
                $m[1]=0.62;
                $m[2]=80;
                $m[3]=25;
		if(lesQteFraisValides($lesFrais)){
	  	 	$pdo->majFraisForfait($idVisiteur,$mois,$lesFrais,$m);//les met a jour
		}
		else{
			ajouterErreur("Les valeurs des frais doivent être numériques");
			include("vues/v_erreurs.php");//ereur si texte saisi
		}
	  break;
	}
         case 'reporterFrais':{
		$idFrais = $_REQUEST['idFrais'];
                $num=$pdo->getMois($idFrais); 
                $mois=$num['mois'];
                $numMois =substr( $mois,4,2);
                $numMois=intval($numMois);
                $numMois=$numMois+1;//reporter au mois suivant en recuperant le mois, et en le mettant au mois suivant
                $mois=substr($mois,0,4).$numMois;
                $pdo->reporterFraisHorsForfait($idFrais,$mois);
                //include("vues/v_listeMoisVisit.php");
		break;
	}
        
        case 'supprimerFrais':{
		$idFrais = $_REQUEST['idFrais'];
                $pdo->supprimerFraisHorsForfait3($idFrais);//le supprimer de la fiche mais aussi du montantvalide
                //include("vues/v_listeMoisVisit.php");
                //include("vues/v_etatFrais2.php");
                break;
	}
        
        
        case 'validerFiche':{
            $idFrais = $_REQUEST['idFrais'];
            $nbJustificatifs = $_REQUEST['nb'];
            $idVisiteur=$pdo->getId($idFrais);
            $idVisiteur=$idVisiteur[0]['id'];
            $mois=$pdo->getMois($idFrais); 
            $mois=$mois['mois'];
            $etat='VA';//valide la fiche en changeant son etat de CL a Validé
            $pdo->majEtatFicheFrais($idVisiteur,$mois,$etat);
            $pdo->majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs);
            break;
        }
        
    
        
} 
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$mois);
$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$mois);
//include("vues/v_listeMoisVisit.php");
//include("vues/v_etatFrais2.php");
?>


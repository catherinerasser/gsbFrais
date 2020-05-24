<?php
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;//page d'accueil
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
		$visiteur = $pdo->getInfosVisiteur($login,$mdp);
		if(!is_array( $visiteur)){//affiche erreur si login ou mdp incorrect
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else{//si mdp et log correct on crée une sessin et on se connecte
			$id = $visiteur['id'];
			$nom =  $visiteur['nom'];
			$prenom = $visiteur['prenom'];
			connecter($id,$nom,$prenom);
                        if($visiteur['type']=="visiteur"){//vu visiteur si cest un visiteur
                        include("vues/v_sommaire.php");}
                        else {
                            include ("vues/v_sommaire2.php");//vue comptable si c'est un comptable
                        }
		}
		break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>

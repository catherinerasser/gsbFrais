﻿<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='pgsql:host=';
      	private static $bdd='dbname=gsb';   		
      	private static $user='paul' ;    		
      	private static $mdp='f14' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
        PdoGsb::$serveur.= $_SERVER['SERVER_ADDR'];//pas toucher
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select visiteur.id as id, visiteur.type as type , visiteur.nom as nom, visiteur.prenom as prenom from visiteur 
		where visiteur.login='$login' and visiteur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; //recup tous les frais hors forfait d'un visiteur
	}
        
        public function getLesFraisHorsForfait2($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
  and lignefraishorsforfait.mois = '$mois' and not libelle like 'REFUSE : %' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; //recup tous les frais hors forfait d'un visiteur sauf ceux qui commencent par REFUSE
	}
/**
 * 
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais,$m){
		$lesCles = array_keys($lesFrais);
                $i=0;
		foreach($lesCles as $unIdFrais){
                        
                        $montant=$m[$i];
			$qte = $lesFrais[$unIdFrais];
                        $req = "update lignefraisforfait set quantite = $qte where idvisiteur = '$idVisiteur' and mois = '$mois' and idfraisforfait = $unIdFrais";
			$req2 = "update fichefrais set montantValide=montantValide+($qte*$montant) where idvisiteur='$idVisiteur' and mois='$mois'";
                        PdoGsb::$monPdo->exec($req);
                        PdoGsb::$monPdo->exec($req2);
                        $i=$i+1;
		}
		
	}
        
        
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait (idVisiteur, mois, libelle, date,montant)
		values( '$idVisiteur','$mois','$libelle','$dateFr','$montant')";
                $req2="update fichefrais set montantValide=montantValide+$montant where idvisiteur='$idVisiteur' and mois='$mois'";
		PdoGsb::$monPdo->exec($req);
                PdoGsb::$monPdo->exec($req2);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);//supprimer frais hf
	}
        
        public function supprimerFraisHorsForfait2($montant,$mois,$idVisiteur){
            $req = "update fichefrais set montantValide=montantValide-$montant where idvisiteur='$idVisiteur' and mois='$mois'";
            PdoGsb::$monPdo->exec($req);//supprime frais hf avec parametre different
        }
        
        public function supprimerFraisHorsForfait3($idFrais){
            $req = "update lignefraishorsforfait set libelle=concat('REFUSE : ',libelle) where id=$idFrais";
            PdoGsb::$monPdo->query($req);
            $req2 = "update lignefraishorsforfait set etat=1 where id=$idFrais";
            PdoGsb::$monPdo->query($req2);//supprimer frais hf en rajoutant REFUSE devant
        }
        
        public function reporterFraisHorsForfait($idFrais,$mois){
            $req = "update lignefraishorsforfait set mois='$mois' where id=$idFrais";
            PdoGsb::$monPdo->query($req);
        }//report frais hf
        
        public function getMois($idFrais){ 
            $req="select mois from lignefraishorsforfait where id=$idFrais;";
            $res=PdoGsb::$monPdo->query($req);
            return $res->fetch();
        }//recup moi dun frais
        
        
        public function getInfoFraisHorsForfait($idFrais){
            $req = "select idVisiteur, mois, montant from lignefraishorsforfait where id=$idFrais";
            $res=PdoGsb::$monPdo->query($req);
            return $res->fetchAll();
            //recup info dun frais
        }
        
        
        public function validerFiche($idFrais,$mois){
            $req="update fichefrais ff set idetat = 'VA' where ff.mois='$mois' and ff.idvisiteur = (select idvisiteur from lignefraishorsforfait lf where lf.id = $idFrais)";
            PdoGsb::$monPdo->query($req);
        }
        //change etat dune fiche en la validant
        
        public function getLesFichesValidees(){
            $req = "select distinct(visiteur.nom) as nom, visiteur.prenom as prenom, mois as mois from visiteur, fichefrais where fichefrais.idvisiteur=visiteur.id and idetat='VA' and type='visiteur' order by nom ASC";
            $res = PdoGsb::$monPdo->query($req);
            $laLigne = $res->fetchAll();
            return $laLigne;
        }
/**
 *
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
        /**
 *
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
        public function getLesMoisDisponibles2($current){
		$req = "select distinct(fichefrais.mois) as mois,datemodif from  fichefrais where mois!='$current'
		order by fichefrais.datemodif desc LIMIT 6";//Que les 6 derniers mois
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
        
        public function getLesGens(){
            $req = "select distinct(visiteur.nom) as nom, visiteur.prenom as prenom from visiteur where type='visiteur' order by nom ASC";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetchAll();
		return $laLigne;//recup tous les gens
        }
        
        public function getIdVisiteur($leV){
            $req = "select id as id from visiteur where nom='$leV'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
        }
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$req2="update fichefrais set idetat='CL' where mois!='$mois'";
                $res = PdoGsb::$monPdo->query($req);
                $res2 = PdoGsb::$monPdo->query($req2);
		$laLigne = $res->fetch();
		return $laLigne;
	}
        
        public function getLesInfosFicheFrais2($idVisiteur,$mois){
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		
                $res = PdoGsb::$monPdo->query($req);
                $laLigne = $res->fetchAll();
		return $laLigne;//pareil qu'au dessus  mais sans le update
	}
        
        public function getPrenom($leV){
            $req = "select prenom as prenom from visiteur where nom='$leV'";
            $res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;//get prenom du visiteur
            
        }
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}//maj l'etat dune fiche
        
        public function getId($idFrais){
            $req = "select visiteur.id from visiteur,lignefraishorsforfait where visiteur.id=lignefraishorsforfait.idvisiteur and lignefraishorsforfait.id=$idFrais";
            $res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetchAll();
		return $laLigne;
        }//recup id du visiteur avec l'id dun frais
        
       
        
        public function getIdVisiteur2($nom){
            $req="select distinct(visiteur.id) from visiteur,lignefraishorsforfait where visiteur.id=lignefraishorsforfait.idvisiteur and nom='$nom'";
            $res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetchAll();
		return $laLigne;
        }//same mais avec son nom
}
?>
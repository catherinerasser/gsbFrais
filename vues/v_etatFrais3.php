
<h3>Fiche de frais du mois <?php echo $numMois."- ".$numAnnee?> : 
    </h3>
    <div class="encadre">
    <p>
        Etat : <?php echo $libEtat?> depuis le <?php echo $dateModif?> <br> Montant validé : <?php echo $montantValide?>
              
                     
    </p>
  	<table class="listeLegere">
  	   <caption>Eléments forfaitisés </caption>
        <tr>
         <?php
         foreach ( $lesFraisForfait as $unFraisForfait ) 
		 {
			$libelle = $unFraisForfait['libelle'];
		?>	
			<th> <?php echo $libelle?></th>
		 <?php
        }
		?>
		</tr>
                <tr>
                <form method="POST"  action="index.php?uc=validFrais&action=validerMajFraisForfait">
        <?php  $i=0;
          foreach (  $lesFraisForfait as $unFraisForfait  ) 
		  {
				$quantite = $unFraisForfait['quantite'];
                                
                                
		?>
                <td class="qteForfait"><?php echo $quantite ;?> </td>
		 <?php $i++;
                    }
		?>
		</tr>
    </table>
    <div class="piedForm">
    
   
    
        </div><br></br>
        </form>
                
        <form>
        <table class="listeLegere">
  	   <caption>Descriptif des éléments hors forfait -<?php echo $nbJustificatifs ?> justificatifs reçus -
       </caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>
                
             </tr>
        <?php     
          foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
		  {
			$date = $unFraisHorsForfait['date'];
			$libelle = $unFraisHorsForfait['libelle'];
			$montant = $unFraisHorsForfait['montant'];
                        $etat=$unFraisHorsForfait['etat'];
                        $idFrais = $unFraisHorsForfait['id'];
		?>
                  
                     <tr><tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <input type="hidden" id="idFrais" name="idFrais" value="<?php echo $idFrais;?>"/>
             
             
               <?php} ?>
          
                         </tr>
             
        <?php 
          }
		?>
        </table></form>
         <form method="POST"  action="index.php?uc=suiviValid&action=validerRemboursement&mois=<?php echo $mois;?>">
        <?php $mois=$numAnnee.$numMois ; $mois= str_replace(' ', '', $mois);  $idVisiteur= str_replace(' ', '', $idVisiteur); ?>
             <input type="hidden" id="mois" name="mois" value="<?php echo $mois;?>"/>
             <input type="hidden" id="idVisiteur" name="idVisiteur" value="<?php echo $idVisiteur;?>"/>
             <input type="hidden" id="nb" name="nb" value="<?php echo $nbJustificatifs;?>"/>
             
             <div class="piedForm">
    <?php if($idEtat=='VA'){echo '<input  id="ok" type="submit" value="Valider le remboursement" size="20"/>';} 
    else{ echo '<input disabled="disabled" id="ok" type="submit" value="Valider le remboursement" size="20" />';} ?>
    
    
        </div>
        </form>
  </div>
  </div>



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
                <td class="qteForfait"><?php echo "<input name='".$i."' value='".$quantite."' type='text' size='3'"?> </td>
		 <?php $i++;
                    }
		?>
		</tr>
    </table>
    <div class="piedForm">
    
        
        <?php if($idEtat=='CL'){echo '<input id="ok" type="submit" value="Valider" size="20" /> <input id="annuler" type="reset" value="Annuler" size="20" />';} 
    else{ echo '<input  disabled="disabled" id="ok" type="submit" value="Valider" size="20" />
    <input disabled="disabled" id="annuler" type="reset" value="Annuler" size="20" />';} ?>
    
   
    
        </div><br></br>
        </form>
                
        <div class="piedForm"><form method="POST" action="index.php?uc=validFrais&action=validerFiche&idFrais=<?php echo $idFrais ?>&nb=<?php echo $nbJustificatifs ?>">
            <table class="listeLegere">
             
  	   <caption>Descriptif des éléments hors forfait -<?php echo '<input id="nb" size="1" type="text" value="'.$nbJustificatifs.'"/>'; ?> justificatifs reçus -
       </caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>
                <th colspan="2" class='action'>Action</th>
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
             <input type="hidden" id="idFrais" name="idFrais" value="<?php echo $idFrais;?>"/>
             
                 <?php if ($etat==1){?>
                     <tr style="background-color: #cc0000;">
                        
                         <?php } else{?>
                         <tr><?php }?>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <?php if ($etat==1 ){echo "<td>Supprimer</td><td>Reporter</td>";}?>
                <?php if($etat==0 ){?><td><a href="index.php?uc=validFrais&action=supprimerFrais&idFrais=<?php echo $idFrais ?>" 
				onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer</a></td>
                <td><a href="index.php?uc=validFrais&action=reporterFrais&idFrais=<?php echo $idFrais ?>" 
				onclick="return confirm('Voulez-vous vraiment reporter ce frais?');">Reporter</a></td>
             
               <?php} ?>
          
                         </tr>
             
        <?php 
          }}
		?>
        </table>
        <div class="piedForm">
    <?php if($idEtat=='CL'){echo '<input  id="ok" type="submit" value="Valider la fiche" size="20"/>';} 
    else{ echo '<input disabled="disabled" id="ok" type="submit" value="Valider la fiche" size="20" />';} ?>
    
    
        </div>
        </form>
  </div>
  </div>

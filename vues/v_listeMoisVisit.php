<div id="contenu">
      <h2>Validation des fiches de frais</h2>
      <h3>Visiteur et Mois à sélectionner : </h3>
      <form action="index.php?uc=validFrais&action=voirEtatFrais" method="post">
      <div class="corpsForm">
         
      <p>
        <label for="lstVisit" accesskey="n">Visiteur : </label>
	<select id="lstVisit" name="lstVisit"> <?php 
			foreach ($LesGens as $unGens)
			{
                                $nom = $unGens['nom'];
                                $prenom = $unGens['prenom'];
                                $visiteur2 = $nom." ".$prenom;
                            if($visit == 'oui'){
				?>
				<option selected value="<?php echo $leVisiteur ?>"><?php echo $leVisiteur; ?> </option>
				<?php 
				$visit='none';}
				else{ 
                                    if($visiteur2 != $leVisiteur){?>
                                    <option value="<?php echo $nom; ?>"><?php echo $nom." ".$prenom; ?> </option><?php } ?>
				
                                    <?php	}  }
                        ?>
			   </select>
        
        <br></br>
        
        <label for="lstMois" accesskey="n">Mois : </label>
        <select id="lstMois" name="lstMois">
            <?php
			foreach ($lesMois as $unMois)
			{
			    $mois = $unMois['mois'];
				$numAnnee =  $unMois['numAnnee'];
				$numMois =  $unMois['numMois'];
                                if($numMois==1){$nomMois='Janvier ';}
                                if($numMois==2){$nomMois='Février ';}
                                if($numMois==3){$nomMois='Mars ';}
                                if($numMois==4){$nomMois='Avril ';}
                                if($numMois==5){$nomMois='Mai ';}
                                if($numMois==6){$nomMois='Juin ';}
                                if($numMois==7){$nomMois='Juillet ';}
                                if($numMois==8){$nomMois='Aout ';}
                                if($numMois==9){$nomMois='Septembre ';}
                                if($numMois==10){$nomMois='Octobre ';}
                                if($numMois==11){$nomMois='Novembre ';}
                                if($numMois==12){$nomMois='Decembre ';}
				if($mois == $moisASelectionner){
				?>
				<option selected value="<?php echo $mois ?>"><?php echo  $nomMois.$numAnnee ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $mois ?>"><?php echo  $nomMois.$numAnnee ?> </option>
				<?php 
				}
			
			}
                       
           
		   ?>    
            
        </select>
        
        
      </p>
      </div>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
        
      </form><input type="hidden" id="idFrais" name="idFrais" value="<?php echo $idFrais;?>"/>
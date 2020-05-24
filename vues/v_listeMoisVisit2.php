 <div id="contenu">
      <h2>Suivi des fiches de frais</h2>
      <h3>Fiches de Visiteurs et Mois à sélectionner : </h3>
      <form action="index.php?uc=suiviValid&action=voirEtatFrais" method="post">
      <div class="corpsForm">
         
      <p>
	 
        <p>
        <label for="lstVisit" accesskey="n">Visiteur : </label>
	<select id="lstVisit" name="lstVisit"> <?php 
			foreach ($LesGens as $unGens)
			{
                                $nom = $unGens['nom'];
                                $Mois=$unGens['mois'];
                                $mois=substr($Mois,4,6)."/".substr($Mois,0,4);
                                $prenom = $unGens['prenom'];
                                $visiteur2 = $nom." ".$prenom;
                            if($visit == 'oui'){
				?>
				<option selected value="<?php echo $mois." - ".$leVisiteur ?>"><?php echo $mois." - ".$leVisiteur; ?> </option>
				<?php 
				$visit='none';}
				else{ 
                                    if($visiteur2 != $leVisiteur){?>
                                    <option value="<?php echo $mois." - ".$nom." ".$prenom; ?>"><?php echo $mois." - ".$nom." ".$prenom; ?> </option><?php } ?>
				
                                    <?php	}  }
                        ?>
			   </select>
        
        <br></br>
      </p><input type='hidden'  name='<?php echo $Mois;?>' value='<?php echo $Mois;?>'/>
      </div>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
        
      </form>
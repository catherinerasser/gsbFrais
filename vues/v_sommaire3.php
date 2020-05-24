   <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">
			<li >
                            Comptable :<br><strong>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?></strong>
			</li>
           <li class="smenu">
              <a href="index.php?uc=validFrais&action=selectionnerMois" title="Saisie fiche de frais ">Valider les fiches de frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=suiviValid&action=selectionnerMois" title="Consultation de mes fiches de frais">Suivi des fiches de frais</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
        
    </div>
    
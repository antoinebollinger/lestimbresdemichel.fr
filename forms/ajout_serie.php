<div id="div_ajout_serie_1" class="dialogue_1">
	<div id="div_ajout_serie_2" class="dialogue_2">
		<div id="div_ajout_serie_3" class="dialogue_3">
			<div id="div_ajout_serie_in">
				<div id="ajout_serie_photo"></div>
				<h3>Ajouter cette série à la liste d'intérêt</h3>
				<form id="ajout_serie_form">
					<p>Veuillez indiquer si vous êtes intéressé par la série complète ou par un ou plusieurs éléments de la série :</p>
					<div class="p">
						<input type="radio" id="choix_serie_1" name="serie_timbre" value="serie" checked />
						<label for="choix_serie_1">La série complète</label>
					</div>
					<div class="p">
						<input type="radio" id="choix_serie_2" name="serie_timbre" value="timbre" />
						<label for="choix_serie_2">Un ou plusieurs éléments</label>
					</div>
					<div class="p">
						<label for="choix_serie_3" class="disabled">Précisez :</label>
						<input type="text" id="choix_serie_3" name="timbres" disabled />
					</div>			
					<button type="button" id="valider_ajout" class="valider"><i class="fas fa-check"></i><span>Valider</span></button>
					<button type="button" id="annuler_ajout" class="fermer_ajout"><i class="fas fa-times"></i><span>Annuler</span></button>
					<div style="clear:both"></div>
				</form>
			</div>
			<div id="div_ajout_serie_out" style="display:none;opacity:0;">
				<h3>La série de timbre a été ajoutée avec succès à votre liste d'intérêt.</h3>
				<a href="collection.php?ma_liste=ma_liste"><button class="valider"><i class="fas fa-shopping-basket"></i><span>Voir ma liste</span></button></a>
				<button class="fermer_ajout"><i class="fas fa-times"></i><span>Fermer</span></button>
			</div>
		</div>
	</div>
</div>
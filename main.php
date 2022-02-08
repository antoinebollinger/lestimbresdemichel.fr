<?php $id_sub_page = 'main'; ?>
<div class="main_main main_main_0 righttop" id="main_main_0">
	<div class="sub_main">
		<div class="sub_main_intro">
			<h1 class="entrance first">Découvrez</h1>
			<h2 class="entrance second"><span class="intro">ma collection</h2>
			<h1 class="entrance third"><span>de</span> timbres.</h1>
		</div>
	</div>
</div>
<div class="main_main main_main_1 bottomleft" id="main_main_1">
	<div class="main_arrow_right" id="main_1_right"><i class="fas fa-caret-right" alt="2" title="Aller vers Contact"></i></div>
	<div class="sub_main">
		<div class="sub_main_collec">
			<h1>- Mode d'emploi -</h1>
			<br/>
			<p>Philatéliste depuis toujours, ma collection compte aujourd'hui près de <b>5000 séries de timbres</b> de tous les horizons. Je m'intéresse particulièrement à la thématique de la faune.</p>
			<p>Ce site à pour but de vous présenter cette collection avec, et surtout, mes <b>dispoliste</b> et <b>mancoliste</b>.</p>
			<p>Sur la page de mon <b><a href="collection.php"><i class="fas fa-book-open"></i> Album</a></b>, vous avez la possibilité de faire des <b>recherches évoluées</b> sur la base de différents critères (filtrer par pays, période, thème, et naviguer à partir de la <b><a href="collection.php?carte=on"><i class="fas fa-globe-americas"></i> carte</a></b>, etc.), afin d'identifier les séries qui vous intéressent.</p><p>À partir de là, vous pouvez également générer une <b><a href="collection.php?ma_liste=ma_liste"><i class="fas fa-shopping-basket"></i> liste d'intérêt</a></b>, dans laquelle vous mettrez les séries et/ou timbres de ma dispoliste qu'il vous intéresse d'acquérir (échange, vente). Cette liste se gère comme un "panier" d'un site marchand et vous pouvez me l'envoyer par email directement depuis ce site.</p>
			<p>Je vous souhaite une très bonne visite !</p>
			<br/>
			<div class="table">
				<div class="table-cell">
					<a href="collection.php?carte=on"><button class="valider"><i class="fas fa-globe-americas"></i><span>Voir la carte</span></button></a>
				</div>
				<div class="table-cell">
					<a href="collection.php"><button class="valider"><i class="fas fa-book-open"></i><span>Voir l'album</span></button></a>
				</div>
				<div class="table-cell">
					<a href="collection.php?ma_liste=ma_liste"><button class="valider"><i class="fas fa-shopping-basket"></i></i><span>Votre liste d'intérêt</span></button></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="main_main main_main_2 lefttop" id="main_main_2">
	<div class="main_arrow_left" id="main_2_left"><i class="fas fa-caret-left" alt="1" title="Revenir à la collection"></i></div>
	<div class="main_arrow_right" id="main_2_right"><i class="fas fa-caret-right" alt="3" title="Aller vers les liens utiles"></i></div>
	<div class="sub_main">
		<div class="sub_main_contact">
			<fieldset><legend>Pour me contacter</legend>
				<form id="form_contact" method="post" action="index.php">
				<div class="sub_main_contact_gauche">
					<label for="input_nom">Nom* :</label>
					<input type="text" name="input_nom" id="input_nom" placeholder="Ex. : Michel Dumont" required />
					<label for="input_email">Email* :</label>
					<input type="email" name="input_email" id="input_email" placeholder="Ex. : md62730@outlook.com" required />
					<label for="input_sujet">Sujet* :</label>
					<input type="text" name="input_sujet" id="input_sujet" placeholder="Ex. : Renseignement, échange de timbre..." required />
					<p class="small_text">* Tous les champs sont obligatoires<br/>Une copie du message sera envoyée à l'adresse email renseignée. Si vous ne la recevez pas, vérifiez dans les spams.</p>
				</div>
				<div class="sub_main_contact_droite">
					<label for="textarea_message">Message* :</label>
					<textarea name="textarea_message" id="textarea_message" required></textarea>
				</div>
				<div style="clear:both;">
					<br/>
					<p><i class="fas fa-phone-square"></i>&nbsp;<span class="lienjaune">+33 (0)6 09 12 39 30</span></p>
					<p><i class="fas fa-envelope-square"></i>&nbsp;<a href="mailto:md62730@outlook.com" class="lienjaune">md62730@outlook.com</a></p>
				</div>
				</form>
				<button id="envoyer_email" class="valider"><i class="fas fa-paper-plane"></i><span>Envoyer</span></button>
				<div style="clear:both;"></div>
			</fieldset>
		</div>	
	</div>
</div>
<div class="main_main main_main_3 bottomright" id="main_main_3">
	<div class="main_arrow_left" id="main_3_left"><i class="fas fa-caret-left" alt="2" title="Revenir à Contact"></i></div>
	<div class="sub_main">
		<div class="sub_main_liens">
			<h1>- Liens utiles -</h1>
			<br/>
			<p>Quelques adresses de liens utiles pour tous les philatélistes :</p>
			<ul>
				<li><a href="https://www.yvert.com/" target="_blank">Yvert & Tellier</a></li>
				<li><a href="https://www.stanleygibbons.com/" target="_blank">Stanley Gibbons</a></li>
				<li><a href="http://www.briefmarken.de/" target="_blank">Michel</a></li>
				<li><a href="https://www.scottonline.com/" target="_blank">Schott catalogue</a></li>
			</ul>
		</div>
	</div>
</div>
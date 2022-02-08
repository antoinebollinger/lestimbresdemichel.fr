<?php $id_sub_page = 'espace_pro_session'; ?>
<h4 class="title">Paramètres</h4>
<div class="main_pro_right_table">
	<div class="main_pro_right_sub">
		<h4>Profil</h4>
		<form id="form_id">
			<label for="login">Login : <input type="text" name="login" id="input_login" value="<?php echo $_SESSION['login']; ?>" disabled /></label>
			<label for="prenom">Prénom : <input type="text" name="prenom" data-value="<?php echo $_SESSION['prenom']; ?>" value="<?php echo $_SESSION['prenom']; ?>" class="modifiable" disabled /></label>
			<label for="nom">Nom : <input type="text" name="nom" data-value="<?php echo $_SESSION['nom']; ?>" value="<?php echo $_SESSION['nom']; ?>" class="modifiable" disabled /></label>
			<label for="telephone">Téléphone : <input type="text" name="telephone" data-value="<?php echo $_SESSION['telephone']; ?>" value="<?php echo $_SESSION['telephone']; ?>" class="modifiable" disabled /></label>
			<label for="email">Email : <input type="text" name="email" data-value="<?php echo $_SESSION['email']; ?>" value="<?php echo $_SESSION['email']; ?>" class="modifiable" disabled /></label>
		</form>
		<br/>
		<button type="button" id="modifier_id">Modifier</button>
		<button type="button" class="valider" id="valider_id" disabled >Valider</button>
	</div>
	<div class="main_pro_right_sub">
		<h4>Mot de passe</h4>
		<div id="div_form_mdp" class="unactive">
			<form id="form_mdp">
			<div>
				<label for="old_mdp">Ancien mot de passe : <input type="password" name="old_mdp" id="old_mdp" value="" class="modifiable" disabled /></label>
			</div>
			<div>
				<label for="new_mdp_1">Nouveau mot de passe : <input type="password" name="new_mdp_1" id="new_mdp_1" value="" class="modifiable" disabled /></label>
				<p><b>Niveau de sécurité : <span id="strength" style="color:red">faible</span></b></p> 
			</div>
			<div>
				<label for="new_mdp_2">Confirmer le nouveau mot de passe : <input type="password" name="new_mdp_2" id="new_mdp_2" value="" class="modifiable" disabled /></label>
			</div>
			</form>
			<br/>
		</div>
		<button type="button" id="modifier_mdp">Modifier</button>
		<button type="button" class="valider" id="valider_mdp" disabled >Valider</button>	
	</div>
	<div class="main_pro_right_sub">
		<h4>Paramètres de session</h4>
			<p>$_SESSION => <em>array</em></p>
			<?php echo print_tab($_SESSION); ?>
	</div>
</div>
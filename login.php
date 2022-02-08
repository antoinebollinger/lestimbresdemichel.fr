<?php
session_start();
include('session/stop_message.php');
if (isset($_SESSION['connect']) && $_SESSION['connect'] === true) {header('Location: espace_pro.php');exit();}
include('bdd/bdd.php');
$connect = 0;$login_af = '';$false = 0;$stayconnect_af = '';$id_page = "login";
if (isset($_COOKIE['verif']) && $_COOKIE['verif'] != null) {
	$req_cookie = $bdd->prepare('SELECT * FROM senha WHERE login = :login');
	$req_cookie->execute(array('login' => $_COOKIE['id']));
	$resultat_cookie = $req_cookie->fetch();
	$req_cookie->closeCursor();
	if ($resultat_cookie) {
		$cryptPrenom = sha1($resultat_cookie['prenom']);
		$cryptNom = sha1($resultat_cookie['nom']);
		$cryptLogin = sha1($resultat_cookie['login']);
		$cookieCrypt = sha1($cryptPrenom.'qjjfhddkfi[{)@$'.$cryptNom.'é!è!tyuh#^{{'.$cryptLogin);	
		if($_COOKIE['verif'] == $cookieCrypt) {
			$_SESSION['prenom']=$resultat_cookie['prenom'];
			$_SESSION['nom']=$resultat_cookie['nom'];
			$_SESSION['login']=$resultat_cookie['login'];
			$_SESSION['fonction']=$resultat_cookie['fonction'];
			$_SESSION['fonction_bis']=$resultat_cookie['fonction_bis'];
			$_SESSION['telephone']=$resultat_cookie['telephone'];
			$_SESSION['email']=$resultat_cookie['email'];
			$_SESSION['connect']=true;
			$connect = 1;
		}
	} else {
		$false = 1;
	}
}
if (isset($_POST['login']) && isset($_POST['mdp'])) {
	$login_af = htmlspecialchars($_POST['login']);
	$mdp_hache = sha1($_POST['mdp']);
	$stayconnect_af = (isset($_POST['stayconnect']) && $_POST['stayconnect'] == 'on') ? 'checked' : '';
	$req_post = $bdd->prepare('SELECT * FROM senha WHERE (login = :login AND mdp = :mdp) OR (email = :login AND mdp = :mdp)');
	$req_post->execute(array('login' => htmlspecialchars($_POST['login']),'mdp' => $mdp_hache));
	$resultat_post = $req_post->fetch();
	$req_post->closeCursor();
	$prenom = '-'; $connecte = 'non';
	if ($resultat_post) {
		$prenom = $resultat_post['prenom']; $connecte = 'oui';
		$_SESSION['prenom']=$resultat_post['prenom'];
		$_SESSION['nom']=$resultat_post['nom'];
		$_SESSION['login']=$resultat_post['login'];
		$_SESSION['fonction']=$resultat_post['fonction'];
		$_SESSION['fonction_bis']=$resultat_post['fonction_bis'];
		$_SESSION['telephone']=$resultat_post['telephone'];
		$_SESSION['email']=$resultat_post['email'];
		$_SESSION['connect']=true;
		if (isset($_POST['stayconnect']) && $_POST['stayconnect'] == 'on') 
		{
			$cryptPrenom = sha1($resultat_post['prenom']);
			$cryptNom = sha1($resultat_post['nom']);
			$cryptLogin = sha1($resultat_post['login']);
			$cookieCrypt = sha1($cryptPrenom.'qjjfhddkfi[{)@$'.$cryptNom.'é!è!tyuh#^{{'.$cryptLogin);
			setcookie('id', $resultat_post['login'], time() + 365*24*3600, null, null, false, true);
			setcookie('verif', $cookieCrypt, time() + 365*24*3600, null, null, false, true);
		}
	} else {
		$false = 1;
	}
	$ok = array( 'prenom' => $prenom, 'connecte' => $connecte);
	if (isset($_POST['funny']) && $_POST['funny'] == "funny") {$connect = 2;}
}
switch ($connect) {
	case 2: echo json_encode($ok); break;
	case 1: header('Location: espace_pro.php');exit();break;
	case 0:
session_destroy();
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php include('header/head.php'); ?></head>
<body>
<div id="login">
	<div id="sublogin">
		<div id="sublogin_border">
			<a href="index.php">
				<div id="div_logo">
					<p class="caveat">Michel Dumont</p>
					<p>Philatélie</p>
				</div>
			</a>
			<fieldset id="login_fieldset">
				<h2>Connexion</h2>
				<form method="post" action="login.php" id="form_login_form">
				<div class="input_div" id="login_div">
				<input type="text" name="login" id="form_login" value="<?php echo $login_af; ?>" required />
				<label for="form_login">Adresse e-mail ou identifiant</label>
				</div>
				<div class="input_div" id="mdp_div"><div id="visible" alt="Afficher/masquer le mot de passe" title="Afficher le mot de passe"><i class="fas fa-eye-slash"></i></div>
				<input type="password" name="mdp" id="form_mdp" required />
				<label for="form_mdp">Saisissez votre mot de passe</label>
				</div>
				<?php if ($false == 1) {echo '<p style="color:#C00000;font-weight:bold;">Il semble que vos informations sont erronnées !</p>';} else {echo '<p><br/></p>';} ?>				
				<div class="sublogin_table">
				<div class="sublogin_cell cell_gauche"><input type="checkbox" name="stayconnect" id="stayconnect" <?php echo $stayconnect_af; ?>/><label for="stayconnect">Rester connecté</label></div>
				<div class="sublogin_cell cell_droite"><input id="valider" type="submit" value="C'est parti !" class="valider" /></div>
				</div>
				<p id="mdp_oublie" class="login_lien">Mot de passe oublié ?</p>
				</form>
			</fieldset>
			<fieldset id="oubli_fieldset" class="unactive">
				<h2>Demande de mot de passe</h2>
				<div class="input_div" id="email_div">
				<input type="text" name="email" id="form_email" value="" required />
				<label for="form_email">Adresse e-mail</label>
				</div>
				<div style="height:60px;">
				<p><em>Veuillez indiquer votre adresse e-mail et nous vous renverrons un nouveau mot de passe par email.<br/>Pensez à regarder dans votre dossier Spam si vous ne voyez pas arriver notre email.</em></p>
				</div>
				<p><br/></p>		
				<div class="sublogin_table">
				<div class="sublogin_cell cell_gauche" style="visibility:hidden"></div>
				<div class="sublogin_cell cell_droite"><input id="valider_oubli" type="submit" value="Soumettre" class="valider" /></div>
				</div>
				<p id="mdp_back" class="login_lien">Annuler</p>
			</fieldset>				
		</div>
	</div>
</div>
<script>
<?php if ($false == 1) { ?>
animate_css('#sublogin','shake');
<?php } ?>
$("#valider").click(function(event) {
	event.preventDefault(); 
	$("body").prepend('<div id="div_patientez" class="active"></div>');
	var stayconnect_var = ($("#stayconnect").is(":checked")) ? "on" : "off"; 
	$.post('login.php',{login: $("#form_login").val(), mdp: $("#form_mdp").val(), stayconnect: stayconnect_var, funny: "funny"}, function(data) {
		switch (data.connecte) {
			case 'oui':
				$("#div_patientez").removeClass("active");
				animate_css('#sublogin','zoomOut', function() {
					$("#sublogin").css("display","none");
					location.reload();
				});
				break;
			case 'non':
				$("#form_login_form").submit();
				break;
			default:
				location.reload();
		}
	},'json');
}); 
$("#valider_oubli").click(function(event) {
	event.preventDefault();
	var posting_01 = $.post("post/test_email.php", {mdp_action: 'test_email', email : $("#form_email").val()}, function(data) {
		if (data.email == '1') {
			var posting_02 = $.post("post/test_email.php", {mdp_action: 'envoi_email', login: data.login, nom: data.nom, prenom: data.prenom, email: $("#form_email").val()}, function(data) {
				if (data.envoye == 'non') {
					alert('Une erreur est survenue. Merci de contacter votre administrateur.');
				} else {
					alert("Un email contenant vos informations de connexion vient de vous être envoyé.");location.reload();
				}
			}, "json");
		} else {
			alert('Il ne semble pas exister de compte associé à cet email. Merci de contacter votre administrateur.');
		}
	}, "json");
}); 
$("#mdp_oublie").click(function() {
	$("#login_fieldset").addClass("active_out");
	$("#oubli_fieldset").removeClass("unactive");
});
$("#mdp_back").click(function() {
	location.reload();
}); 
$("#visible").click(function() {
	console.log($("#form_mdp").attr('type'));
	if ($(this).hasClass('unvisible')) {
		$(this).removeClass('unvisible');
		$(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
		$(this).attr("title","Afficher le mot de passe");
		$("#form_mdp").each(function() {this.type="password";});
	} else {
		$(this).addClass('unvisible');
		$(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
		$(this).attr("title","Masquer le mot de passe");
		$("#form_mdp").each(function() {this.type="text";});
	}
}); 
function animate_css(element, animationName, callback) {
	var node = document.querySelector(element);
	node.classList.add('animated')
	node.classList.add(animationName);
    node.addEventListener('webkitAnimationEnd', handleAnimationEnd);
    node.addEventListener('animationend', handleAnimationEnd);
	function handleAnimationEnd() {
		node.classList.remove('animated');
		node.classList.remove(animationName);
		node.removeEventListener('webkitAnimationEnd', handleAnimationEnd); 
		node.removeEventListener('animationend', handleAnimationEnd);
        if (typeof callback == 'function') {
			callback();
		}
    }	
}
</script>
</body>
</html>
<?php } ?>
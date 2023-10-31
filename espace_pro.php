<?php 
$id_page = 'espace_pro';
session_start();
if (!isset($_SESSION['connect']) OR $_SESSION['connect']==false) {header('Location: login.php');exit();} else {
include('session/stop_message.php');
include('bdd/bdd.php');

//Variables
$table = (isset($_GET['table']) && in_array($_GET['table'],array('catalogue','pays','etiquettes','session'))) ? $_GET['table'] : '';
$code = (isset($_GET['code']) && $_GET['code'] != '') ? htmlspecialchars($_GET['code']) : '';
$pays = (isset($_GET['pays']) && $_GET['pays'] != '') ? htmlspecialchars($_GET['pays']) : '';

?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header/head.php'); ?>
	<body>
<?php include('header/logo.php'); include('patientez.php'); ?>
		<nav>
			<div id="menu_icon">
				<div class="menu-icon menu-icon-cross">
					<span></span>
					<svg x="0" y="0" width="40px" height="40px" viewBox="0 0 40 40">
					<circle cx="20" cy="20" r="18"></circle>
					</svg>
				</div>
			</div>
		</nav>
		<div id="main_pro">
			<div id="main_pro_left">
				<table>
					<tr>
						<td><a href="espace_pro.php" alt="Accueil" title="Accueil"<?php if ($table == '') echo ' class="active"'; ?>><i class="fas fa-home"></i></a></td>
						<td><a href="espace_pro.php?table=session" alt="Paramètres" title="Paramètres"<?php if ($table == 'session') echo ' class="active"'; ?>><i class="fas fa-cog"></i></a></td>
						<td><a href="" id="deconnexion" alt="Se déconnecter" title="Se déconnecter"><i class="fas fa-sign-out-alt"></i></a></td>
					</tr>
				</table>		
				<h3>Etiquettes</h3>
					<ul>
						<li><a href="espace_pro.php?table=etiquettes" id="table_etiquettes"<?php if ($table == 'etiquettes') echo ' class="active"'; ?>>Imprimer</a></li>
					</ul>
				<h3>Tables</h3>
					<ul>
						<li><a href="espace_pro.php?table=catalogue" id="table_catalogue"<?php if ($table == 'catalogue') echo ' class="active"'; ?>>Catalogue</a></li>
						<li><a href="espace_pro.php?table=pays" id="table_pays"<?php if ($table == 'pays') echo ' class="active"'; ?>>Pays</a></li>
					</ul>
			</div>
			<div id="main_pro_right">
<?php 
if ($table != '') {
	include('espace_pro_'.$table.'.php');
} else {
	echo '<h4>Bienvenue sur votre espace personnel</h4>'; 
} 
?>
			</div>
			<div style="clear:both;"></div>
		</div>
<?php include('liens_directs.php'); ?>		
		<script defer>var cur_table = "<?php echo $table; ?>";</script>
		<script defer src="js/espace_pro.js"></script>		
	</body>
</html>
<?php } ?>
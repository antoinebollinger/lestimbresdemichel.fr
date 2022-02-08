<?php 
$id_page = 'index';
session_start();
include('session/stop_message.php');
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header/head.php'); ?>
	<body class="initiate index">
	<div id="background"></div>
<?php include('header/logo.php'); include('patientez.php'); include('forms/cgu.php'); ?>
	<nav>
		<div id="menu_icon">
			<div class="menu-icon menu-icon-cross">
				<span></span>
				<svg x="0" y="0" width="40px" height="40px" viewBox="0 0 40 40">
				<circle cx="20" cy="20" r="18"></circle>
				</svg>
			</div>
		</div>
<?php include('nav/nav.php'); ?>
	</nav>
	<div id="main_main">
<?php include('main.php'); ?>
	</div>
<?php include('liens_directs.php'); ?>
<?php include('footer/footer.php'); ?>
	</body>
</html>
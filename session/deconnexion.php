<?php
session_start();
$_SESSION = array();
session_destroy();
foreach($_COOKIE as $key => $value) {
	unset($_COOKIE[$key]);
	setcookie($_COOKIE[$key], '', time() - 4200, '/');
}
// setcookie('id', "", time() - 3600, "/");
// setcookie('verif', "", time() - 3600, "/");
// setcookie(session_name(), "", time() - 3600, "/");
// unset($_COOKIE['id']);
// unset($_COOKIE['verif']);
// unset($_COOKIE[session_name()]);
if (isset($_POST['deconnect']) && $_POST['deconnect'] == 'deconnect') {
	return true;
} else {
	header('Location: ../login.php');
	exit();	
}
?>
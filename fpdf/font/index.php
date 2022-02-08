<?php
session_start();
$idpage = "produits"; $idpage_ss = "plv";
if (!isset($_SESSION['connect']) OR $_SESSION['connect']==false)
{
	header('Location: login.php');
	exit();
}
else
{
	header('Location: login.php');
	exit();
}
?>
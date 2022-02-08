<?php
session_start();
$id = (isset($_POST['id'])? $_POST['id']:  (isset($_GET['id'])? $_GET['id']:'' )) ;	
$present="non";
if (isset($_SESSION['list']) && count($_SESSION['list']['id']) > 0) {
	if (in_array($id,$_SESSION['list']['id'])) {$present = "oui";}
}
echo json_encode(array('present_liste' => $present));
?>
<?php 
if (!isset($id_page)) {session_start();}
// FONCTIONS
function creer_liste() {
	if (isset($_SESSION['list'])) {unset($_SESSION['list']);}
	$_SESSION['list'] = array();
	$_SESSION['list']['id'] = array();
	$_SESSION['list']['libelle'] = array();
	$_SESSION['list']['photo'] = array();
	$_SESSION['list']['pays'] = array();
	$_SESSION['list']['annee'] = array();
	$_SESSION['list']['yvert'] = array();
	$_SESSION['list']['cote'] = array();
	$_SESSION['list']['qte'] = array();
	$_SESSION['list']['commentaire'] = array();
	return true;
}
function ajouter_id($id,$lib,$phot,$pay,$ann,$yve,$cot,$qte,$com) {
	if (isset($_SESSION['list'])) {
		if (in_array($id,$_SESSION['list']['id'])) {
			for($i = 0; $i < count($_SESSION['list']['id']); $i++) {	
				if ($_SESSION['list']['id'][$i] == $id) {
					$_SESSION['list']['qte'][$i]++;
				}
			}				
		} else {
			array_push($_SESSION['list']['id'],$id);
			array_push($_SESSION['list']['libelle'],$lib);
			array_push($_SESSION['list']['photo'],$phot);
			array_push($_SESSION['list']['pays'],$pay);		
			array_push($_SESSION['list']['annee'],$ann);
			array_push($_SESSION['list']['yvert'],$yve);
			array_push($_SESSION['list']['cote'],$cot);
			array_push($_SESSION['list']['qte'],$qte);
			array_push($_SESSION['list']['commentaire'],$com);
		}
		return true;
	} else {
		return false;
	}
}
function retirer_id($id) {
	if (isset($_SESSION['list'])) {
		$tmp = array();
		$tmp['id'] = array();
		$tmp['libelle'] = array();
		$tmp['photo'] = array();
		$tmp['pays'] = array();
		$tmp['annee'] = array();
		$tmp['yvert'] = array();
		$tmp['cote'] = array();
		$tmp['qte'] = array();
		$tmp['commentaire'] = array();	
		$j = 1;
		for($i = 0; $i < count($_SESSION['list']['id']); $i++) {
			if ($_SESSION['list']['id'][$i] != $id) {
				array_push($tmp['id'],$_SESSION['list']['id'][$i]);
				array_push($tmp['libelle'],$_SESSION['list']['libelle'][$i]);
				array_push($tmp['photo'],$_SESSION['list']['photo'][$i]);
				array_push($tmp['pays'],$_SESSION['list']['pays'][$i]);		
				array_push($tmp['annee'],$_SESSION['list']['annee'][$i]);
				array_push($tmp['yvert'],$_SESSION['list']['yvert'][$i]);
				array_push($tmp['cote'],$_SESSION['list']['cote'][$i]);
				array_push($tmp['qte'],$_SESSION['list']['qte'][$i]);
				array_push($tmp['commentaire'],$_SESSION['list']['commentaire'][$i]);
				$j++;
			}
		}			
		$_SESSION['list'] =  $tmp;
		unset($tmp);
		return true;
	} else {
		return false;
	}
}
// INITIALISATION
if (!isset($_SESSION['list'])) {
	creer_liste();
} 
// ACTIONS
$erreur = false;
$action = (isset($_POST['action'])? $_POST['action']:  (isset($_GET['action'])? $_GET['action']:null )) ;
if($action !== null) {
	if (!in_array($action,array('initialiser','ajouter','retirer'))) {
		$erreur = true;
	}
	//Variables pour ajout
	$id_ajout = (isset($_POST['id_ajout'])? $_POST['id_ajout']:  (isset($_GET['id_ajout'])? $_GET['id_ajout']:'' )) ;
	$libelle_ajout = (isset($_POST['libelle_ajout'])? $_POST['libelle_ajout']:  (isset($_GET['libelle_ajout'])? $_GET['libelle_ajout']:'' )) ;
	$photo_ajout = (isset($_POST['photo_ajout'])? $_POST['photo_ajout']:  (isset($_GET['photo_ajout'])? $_GET['photo_ajout']:'' )) ;
	$pays_ajout = (isset($_POST['pays_ajout'])? $_POST['pays_ajout']:  (isset($_GET['pays_ajout'])? $_GET['pays_ajout']:'' )) ;
	$annee_ajout = (isset($_POST['annee_ajout'])? $_POST['annee_ajout']:  (isset($_GET['annee_ajout'])? $_GET['annee_ajout']:'' )) ;	
	$yvert_ajout = (isset($_POST['yvert_ajout'])? $_POST['yvert_ajout']:  (isset($_GET['yvert_ajout'])? $_GET['yvert_ajout']:'' )) ;
	$cote_ajout = (isset($_POST['cote_ajout'])? $_POST['cote_ajout']:  (isset($_GET['cote_ajout'])? $_GET['cote_ajout']:'' )) ;
	$qte_ajout = (isset($_POST['qte_ajout'])? $_POST['qte_ajout']:  (isset($_GET['qte_ajout'])? $_GET['qte_ajout']:'' )) ;
	$commentaire_ajout = (isset($_POST['commentaire_ajout'])? $_POST['commentaire_ajout']:  (isset($_GET['commentaire_ajout'])? $_GET['commentaire_ajout']:'' )) ;
	//variable pour suppression
	$id_delete = (isset($_POST['id_delete'])? $_POST['id_delete']:  (isset($_GET['id_delete'])? $_GET['id_delete']:'' )) ;	
	//Switch
	if (!$erreur){
		switch($action){
			Case 'initialiser':
				creer_liste();
				break;
				
			Case 'ajouter':
				ajouter_id($id_ajout,$libelle_ajout,$photo_ajout,$pays_ajout,$annee_ajout,$yvert_ajout,$cote_ajout,$qte_ajout,$commentaire_ajout);
				break;
			
			Case 'retirer':
				retirer_id($id_delete);
				break;

			Default:
				break;
		}
	}
}
?>
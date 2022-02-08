<?php
session_start();
if (!isset($_SESSION['connect']) OR $_SESSION['connect']==false) {header('Location: login.php');exit();} else {
include('session/stop_message.php');
include('../bdd/bdd.php');

//Variable action
$action = htmlspecialchars(isset($_POST['action'])? $_POST['action']:  (isset($_GET['action'])? $_GET['action']:'' )) ;
//Variables concernant l'ID (table senha)
$login = htmlspecialchars(isset($_POST['login'])? $_POST['login']:  (isset($_GET['login'])? $_GET['login']:'' )) ;
$query = htmlspecialchars(isset($_POST['query'])? $_POST['query']:  (isset($_GET['query'])? $_GET['query']:'' )) ;
$elements = (isset($_POST['elements'])? $_POST['elements']:  (isset($_GET['elements'])? $_GET['elements']:null )) ;
if ($elements !== null) {$elements = json_decode($elements,true);}
//Variables concernant les tables Catalogue et Pays
$post_table = htmlspecialchars(isset($_POST['post_table'])? $_POST['post_table']:  (isset($_GET['post_table'])? $_GET['post_table']:'' )) ;
$post_id = htmlspecialchars(isset($_POST['post_id'])? $_POST['post_id']:  (isset($_GET['post_id'])? $_GET['post_id']:'' )) ;
$post_title = htmlspecialchars(isset($_POST['post_title'])? $_POST['post_title']:  (isset($_GET['post_title'])? $_GET['post_title']:'' )) ;
$post_old_val = htmlspecialchars(isset($_POST['post_old_val'])? $_POST['post_old_val']:  (isset($_GET['post_old_val'])? $_GET['post_old_val']:'' )) ;
$post_new_val = htmlspecialchars(isset($_POST['post_new_val'])? $_POST['post_new_val']:  (isset($_GET['post_new_val'])? $_GET['post_new_val']:'' )) ;

$result = array("done" => "nop"); 

if (in_array($action,array('modifier_id','modifier_mdp','modifier_table'))) {
	Switch ($action) {
		case 'modifier_id' :
			if ($login != '' && $query != '') {
				$req_modif = $bdd->prepare("UPDATE senha SET ".$query." WHERE login = ".$login);
				$req_modif->execute();
				$req_modif->closeCursor();
				foreach($elements as $key => $value) {
					if (isset($_SESSION[$key])) {$_SESSION[$key] = $value;}
				}
				$result["done"] = "yep";
			}
			break;
		case 'modifier_mdp' :
			$req_check = $bdd->prepare("SELECT mdp FROM senha WHERE login = ".$login);
			$req_check->execute();
			$donnees_check = $req_check->fetch();
			$req_check->closeCursor();
			if ($donnees_check['mdp'] = sha1($elements['old_mdp'])) {
				$req_modif = $bdd->prepare("UPDATE senha SET mdp = :mdp WHERE login = :login");
				$req_modif->execute(array('mdp' => sha1($elements['new_mdp_1']), 'login' => $login));
				$req_modif->closeCursor();
				$result["done"] = "yep";
			}
			break;
		case 'modifier_table' :
			if ($post_table != '' && $post_id != '' && $post_title != '') {
				$req_modif = $bdd->prepare("UPDATE ".$post_table." SET ".$post_title." = :new_val WHERE id = :id AND ".$post_title." = :old_val");
				$req_modif->execute(array('id' => $post_id, 'old_val' => $post_old_val, 'new_val' => $post_new_val));
				$req_modif->closeCursor();
				$result["done"] = "yep";
			}
			break;
		default :
			break;		
	}
}
echo json_encode($result);
}
?>
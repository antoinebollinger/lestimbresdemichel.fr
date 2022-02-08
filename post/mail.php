<?php
//EMAIL
	/*
	 * cette fonction sert à nettoyer et enregistrer un texte
	 */
	function Rec($text)
	{
		$text = htmlspecialchars(trim($text), ENT_QUOTES);
		if (1 === get_magic_quotes_gpc())
		{
			$text = stripslashes($text);
		}
 
		$text = nl2br($text);
		return $text;
	};
 
	/*
	 * Cette fonction sert à vérifier la syntaxe d'un email
	 */
	function IsEmail($email)
	{
		$value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
		return (($value === 0) || ($value === false)) ? false : true;
	}
$email_expediteur_nom = (isset($_POST['nom'])? $_POST['nom']:  (isset($_GET['nom'])? $_GET['nom']:null ));
$email_expediteur_email = (isset($_POST['email'])? $_POST['email']:  (isset($_GET['email'])? $_GET['email']:null ));
$email_expediteur_email = (IsEmail($email_expediteur_email)) ? $email_expediteur_email : '';
$email_message_sujet = (isset($_POST['sujet'])? $_POST['sujet']:  (isset($_GET['sujet'])? $_GET['sujet']:null ));
$email_message_text = Rec((isset($_POST['message'])? $_POST['message']:  (isset($_GET['message'])? $_GET['message']:null )));	
$email_message_html = (isset($_POST['message'])? $_POST['message']:  (isset($_GET['message'])? $_GET['message']:null ));	


$mail = 'antoine.bollinger@gmail.com'; // Déclaration de l'adresse de destination.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui présentent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = $email_message_text;
$message_html = $email_message_html;
//==========
 

//=====Création de la boundary.
$boundary = "-----=".md5(rand());
$boundary_alt = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = $email_message_sujet;
//=========
 
//=====Crétion du header de l'e-mail.
$header = "From: ".$email_expediteur_nom." <".$email_expediteur_email.">".$passage_ligne;
$header.= "Cc: ".$email_expediteur_nom." <".$email_expediteur_email.">".$passage_ligne;
$header.= "Reply-to: \"".$email_expediteur_nom."\" <".$email_expediteur_email.">".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "X-Priority: 2".$passage_ligne;
$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========
 
//=====Création du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;

//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"utf-8\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
 
$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
 
//=====Ajout du message au format HTML.
$message.= "Content-Type: text/html; charset=\"utf-8\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
 
//=====On ferme la boundary alternative.
$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
//==========
 
$message.= $passage_ligne."--".$boundary."--".$passage_ligne; 

//=====Envoi de l'e-mail.
$envoye = array();
if (mail($mail,$sujet,$message,$header)) {$envoye['envoye'] = 'oui';}
else {$envoye['envoye'] = 'non';}

echo json_encode($envoye);

//==========

?>

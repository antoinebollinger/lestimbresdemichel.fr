<?php 
$mdp_action = (isset($_POST['mdp_action'])? $_POST['mdp_action']:  (isset($_GET['mdp_action'])? $_GET['mdp_action']:'' )) ;
if ($mdp_action == 'test_email') {
	$email = (isset($_POST['email'])? $_POST['email']:  (isset($_GET['email'])? $_GET['email']:'' )) ;
	include('../bdd/bdd.php');
	$resultat = array();
	$resultat['email'] = '0';
	$resultat['nom'] = '';
	$resultat['prenom'] = '';
	$resultat['login'] = '';	
	$req_email = $bdd->prepare("SELECT nom, prenom, login FROM senha WHERE email = :email");
	$req_email->execute(array("email" => htmlspecialchars($email)));
	While ($donnees = $req_email->fetch()) {
		$resultat['email'] = '1';
		$resultat['nom'] = $donnees['nom'];
		$resultat['prenom'] = $donnees['prenom'];		
		$resultat['login'] = $donnees['login'];	
	}
	$req_email->closeCursor();
	echo json_encode($resultat);
}
if ($mdp_action == 'envoi_email') {
	function Genere_Password($size) {
		$password = '';
		$characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
		for($i=0;$i<$size;$i++) {
			$password .= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
		}
		return $password;
	}
	function Rec($text) {
		$text = htmlspecialchars(trim($text), ENT_QUOTES);
		if (1 === get_magic_quotes_gpc()) {
			$text = stripslashes($text);
		}
		$text = nl2br($text);
		return $text;
	};
	function IsEmail($email) {
		$value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
		return (($value === 0) || ($value === false)) ? false : true;
	}
	$email = (isset($_POST['email'])? $_POST['email']:  (isset($_GET['email'])? $_GET['email']:'' )) ;
	$login = (isset($_POST['login'])? $_POST['login']:  (isset($_GET['login'])? $_GET['login']:'' )) ;
	$nom = (isset($_POST['nom'])? $_POST['nom']:  (isset($_GET['nom'])? $_GET['nom']:null ));
	$prenom = (isset($_POST['prenom'])? $_POST['prenom']:  (isset($_GET['prenom'])? $_GET['prenom']:null ));

	
	include('../bdd/bdd.php');
	$resultat = array();
	$resultat['mdp'] = Genere_Password(8);
	$resultat['envoye'] = 'non';
	$req_mdp = $bdd->prepare("UPDATE senha SET mdp = :mdp WHERE email = :email AND login = :login");
	$req_mdp->execute(array("mdp" => sha1($resultat['mdp']), "email" => htmlspecialchars($email), "login" => htmlspecialchars($login)));		
	$req_mdp->closeCursor();
	
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email)) // On filtre les serveurs qui présentent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
	$message_html = '
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv=Content-Type content="text/html; charset=iso-8859-1"><meta name=Generator content="Microsoft Word 14 (filtered medium)"><style><!--
/* Font Definitions */
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:Ebrima;
	panose-1:2 0 0 0 0 0 0 0 0 0;}
/* Style Definitions */
p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-language:EN-US;}
a:link, span.MsoHyperlink
	{mso-style-priority:99;
	color:blue;
	text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
	{mso-style-priority:99;
	color:purple;
	text-decoration:underline;}
span.EmailStyle17
	{mso-style-type:personal-compose;
	font-family:Ebrima;
	color:windowtext;
	font-weight:normal;
	font-style:normal;}
.MsoChpDefault
	{mso-style-type:export-only;
	font-family:"Calibri","sans-serif";
	mso-fareast-language:EN-US;}
@page WordSection1
	{size:612.0pt 792.0pt;
	margin:70.85pt 70.85pt 70.85pt 70.85pt;}
div.WordSection1
	{page:WordSection1;}
--></style><!--[if gte mso 9]><xml>
<o:shapedefaults v:ext="edit" spidmax="1026" />
</xml><![endif]--><!--[if gte mso 9]><xml>
<o:shapelayout v:ext="edit">
<o:idmap v:ext="edit" data="1" />
</o:shapelayout></xml><![endif]--></head><body lang=FR link=blue vlink=purple><div class=WordSection1><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima">Bonjour '.$prenom.',<o:p></o:p></span></p><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima"><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima">Suite à votre demande, nous avons réinitialisé votre mot de passe. Voici le nouveau :<o:p></o:p></span></p><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima"><o:p>&nbsp;</o:p></span></p><p class=MsoNormal align=center style="text-align:center"><b><span style="font-size:10.0pt;font-family:Ebrima">'.$resultat['mdp'].'<o:p></o:p></span></b></p><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima"><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima">Vous pouvez dès à présent l\'utiliser pour vous connecter. Nous vous conseillons de le personnaliser rapidement.<o:p></o:p></span></p><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima"><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima">Bien cordialement,<o:p></o:p></span></p><p class=MsoNormal><span style="font-size:10.0pt;font-family:Ebrima"><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><b><span style="font-size:9.0pt;font-family:"Arial","sans-serif";color:black;mso-fareast-language:FR">Antoine Bollinger</span></b><span style="font-size:9.0pt;font-family:"Arial","sans-serif";color:black;mso-fareast-language:FR"><br>Webmaster du site Michel Dumont Philatélie<br>Email: <a href="mailto:abollinger@osborn-unipol.fr">antoine.bollinger@gmail.com</a></span><u><span style="font-size:9.0pt;font-family:"Arial","sans-serif";mso-fareast-language:FR"><o:p></o:p></span></u></p><p class=MsoNormal><o:p>&nbsp;</o:p></p></div></body></html>';
$message_txt = $message_html;
//==========
 

//=====Création de la boundary.
$boundary = "-----=".md5(rand());
$boundary_alt = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = 'Les timbres de Michel | Votre demande de mot de passe';
//=========
 
//=====Crétion du header de l'e-mail.
$header = "From: Antoine Bollinger <antoine.bollinger@gmail.com>".$passage_ligne;
$header.= "Reply-to: \"Antoine Bollinger\" <antoine.bollinger@gmail.com>".$passage_ligne;
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
if (mail($email,$sujet,$message,$header)) {$resultat['envoye'] = 'oui';}

echo json_encode($resultat);

}
?>
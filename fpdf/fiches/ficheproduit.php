<?php
session_start();
if (!isset($_SESSION['connect']) OR $_SESSION['connect']==false)
{
	header('Location: login.php');
	exit();
}
else
{
	//Chargement de la base de données
		if( in_array($_SERVER['SERVER_NAME'], array('localhost')) )
	{
		$mysql_host = "localhost";
		$mysql_user = "root";
		$mysql_password = "";
		$mysql_base = "osborndronco";
	}
	else
	{
		$mysql_host = "osborndrmtdb.mysql.db";
		$mysql_user = "osborndrmtdb";
		$mysql_password = "JsABpdVeC92";
		$mysql_base = "osborndrmtdb";
	}
	try
	{
		$bdd = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_base.';charset=utf8', $mysql_user, $mysql_password);
	}
	catch (Exception $e)
	{
			die('Erreur : ' . $e->getMessage());
	}	
//DEFINITION ET SECURISATION VARIABLE
$reference = (isset($_POST['ref_cache_fiche_produit'])? $_POST['ref_cache_fiche_produit']:  (isset($_GET['reference'])? $_GET['reference']:null )) ;
//Vérifier si référence existe dans catalogue
$req = $bdd->prepare('SELECT * FROM catalogue WHERE refinf LIKE :refinf OR refcom LIKE :refinf');
$req->execute(array('refinf' => $reference));
$test = 0;
While ($donnees = $req->fetch())
{
$test = 1;
}
//SI REFERENCE EXISTE, AFFICHER UN APERCU DE LA FICHE PRODUIT
if ($test == 1) {
	//RECUPERATION DONNEES PRODUITS
	$req1 = $bdd->prepare('SELECT * FROM catalogue WHERE refinf LIKE :refinf OR refcom LIKE :refinf');
	$req1->execute(array('refinf' => $reference));
	While ($donnees = $req1->fetch())
	{
		$refinf = $donnees['refinf'];
		$refint = $donnees['refint'];
		$refcom = $donnees['refcom'];
		$ean = $donnees['ean'];
		$categorie = $donnees['categorie'];
		$ss_categorie = $donnees['ss_categorie'];
		$libelle_long = $donnees['libelle_long'];
		$libelle_court = $donnees['libelle_court'];
		$photo_produit = $donnees['photo'];
		$dia = $donnees['dia'];
		$W = $donnees['W']; //1
		$S = $donnees['S']; //2
		$F = $donnees['F']; //3
		$A = $donnees['A']; //4
		$B = $donnees['B']; //5
		$R = $donnees['R']; //6
		$G = $donnees['G']; //7
		$T = $donnees['T']; //8
		$L = $donnees['L']; //9
		$rangs = $donnees['rangs']; //10
		$K = $donnees['K']; //11
		$segment_nb = $donnees['segment_nb']; //12
		$segment_ht = $donnees['segment_ht']; //13
		$type = $donnees['type']; //14
		$tr_min = $donnees['tr/min']; //15
		$garnissage = $donnees['garnissage']; //16
		$dia_fil = $donnees['dia_fil']; //17
		$grain = $donnees['grain']; //18
		$qualite_inox = $donnees['qualite_inox']; //18
		$emb_ind = $donnees['emb_ind'];
		$poids_ind = $donnees['poids_ind'];
		$emb_ind_long = $donnees['emb_ind_long'];
		$emb_ind_larg = $donnees['emb_ind_larg'];
		$emb_ind_haut = $donnees['emb_ind_haut'];
		$cdt = $donnees['cdt'];
		$emb = $donnees['emb'];
		$poids_emb = $donnees['poids_emb'];
		$emb_long = $donnees['emb_long'];
		$emb_larg = $donnees['emb_larg'];
		$emb_haut = $donnees['emb_haut'];
		$photo_emb = $donnees['photo_emb'];
		$tu = $donnees['tbu'];
		$tb = $donnees['tbg'];
		}
	$dimensions_tmp = array("ø" => $dia,
						"W" => $W, 
						"S" => $S,
						"F" => $F,
						"A" => $A,
						"B" => $B,
						"R" => $R,
						"G" => $G,
						"T" => $T,
						"L" => $L,
						"Rangs" => $rangs,
						"K" => $K,
						"Nb. segments" => $segment_nb,
						"Ht. segments" => $segment_ht,
						"Type" => $type,
						"tr/min" => $tr_min,
						"Garnissage" => $garnissage,
						"ø fil" => $dia_fil,
						"Grain" => $grain,
						"Qualité inox" => $qualite_inox);
	$dimensions = array();$i = 0;
	foreach($dimensions_tmp as $key => $value) {if ($value != "") {$dimensions[$key] = $value; $i++;}}	
	$conditionnement = array("Emballage individuel" => $emb_ind,
							"Poids pièces (g)" => $poids_ind,
							"(Lxlxh)" => $emb_ind_long.' x '.$emb_ind_larg.' x '.$emb_ind_haut,
							"Cdt" => $cdt,
							"Type d'emballage" => $emb,
							"Poids emballage (kg)" => $poids_emb,
							"Emballage (Lxlxh)" => $emb_long.' x '.$emb_larg.' x '.$emb_haut);
	$req1->closeCursor();	
	
	$req2 = $bdd->prepare('SELECT * FROM categorie WHERE refint LIKE :refint');
	$req2->execute(array('refint' => $refint));
	While ($donnees = $req2->fetch())
	{
		$refint = $donnees['refint'];
		$ligne = $donnees['ligne'];
		$gamme = $donnees['gamme'];
		$caracteristique = $donnees['caracteristique'];
		$utilisation = $donnees['utilisation'];
		$certification = $donnees['certification'];
		$photo_schema = $donnees['photo_schema'];
		if (strlen($donnees['mach1']) == 1) {$mach1 = "0".$donnees['mach1'];}
		else {$mach1 = $donnees['mach1'];}
		$dim1 = $donnees['dim1'];
		if (strlen($donnees['mach2']) == 1) {$mach2 = "0".$donnees['mach2'];}
		else {$mach2 = $donnees['mach2'];}
		$dim2 = $donnees['dim2'];
		if (strlen($donnees['mach3']) == 1) {$mach3 = "0".$donnees['mach3'];}
		else {$mach3 = $donnees['mach3'];}
		$dim3 = $donnees['dim3'];
	}
	if ($mach1 == "") {$libelle_machine1 = "";}
	elseif ($mach1 == "01") {$libelle_machine1 = "Meuleuse d'angle";}
	elseif ($mach1 == "02") {$libelle_machine1 = "Perceuse";}
	elseif ($mach1 == "03") {$libelle_machine1 = "Machine Haute Vitesse";}
	elseif ($mach1 == "04") {$libelle_machine1 = "Machine Fixe";}
	elseif ($mach1 == "05") {$libelle_machine1 = "Manuelle";}
	elseif ($mach1 == "06") {$libelle_machine1 = "Polisseuse d'angle";}
	elseif ($mach1 == "07") {$libelle_machine1 = "Satineuse";}
	elseif ($mach1 == "08") {$libelle_machine1 = "Rectifieuse interne";}
	elseif ($mach1 == "09") {$libelle_machine1 = "Meuleuse d'angle";}
	elseif ($mach1 == "10") {$libelle_machine1 = "Scie de chantier";}
	elseif ($mach1 == "11") {$libelle_machine1 = "Machine thermique";}
	elseif ($mach1 == "12") {$libelle_machine1 = "Meuleuse droite";}
	elseif ($mach1 == "13") {$libelle_machine1 = "Ponceuse triangulaire";}
	elseif ($mach1 == "14") {$libelle_machine1 = "Ponceuse excentrique";}
	elseif ($mach1 == "15") {$libelle_machine1 = "Ponceuse vibrante";}
	elseif ($mach1 == "16") {$libelle_machine1 = "Machine portative";}
	else {$libelle_machine1 = "";}	
	if ($mach2 == "") {$libelle_machine2 = "";}
	elseif ($mach2 == "01") {$libelle_machine2 = "Meuleuse d'angle";}
	elseif ($mach2 == "02") {$libelle_machine2 = "Perceuse";}
	elseif ($mach2 == "03") {$libelle_machine2 = "Machine Haute Vitesse";}
	elseif ($mach2 == "04") {$libelle_machine2 = "Machine Fixe";}
	elseif ($mach2 == "05") {$libelle_machine2 = "Manuelle";}
	elseif ($mach2 == "06") {$libelle_machine2 = "Polisseuse d'angle";}
	elseif ($mach2 == "07") {$libelle_machine2 = "Satineuse";}
	elseif ($mach2 == "08") {$libelle_machine2 = "Rectifieuse interne";}
	elseif ($mach2 == "09") {$libelle_machine2 = "Meuleuse d'angle";}
	elseif ($mach2 == "10") {$libelle_machine2 = "Scie de chantier";}
	elseif ($mach2 == "11") {$libelle_machine2 = "Machine thermique";}
	elseif ($mach2 == "12") {$libelle_machine2 = "Meuleuse droite";}
	elseif ($mach2 == "13") {$libelle_machine2 = "Ponceuse triangulaire";}
	elseif ($mach2 == "14") {$libelle_machine2 = "Ponceuse excentrique";}
	elseif ($mach2 == "15") {$libelle_machine2 = "Ponceuse vibrante";}
	elseif ($mach2 == "16") {$libelle_machine2 = "Machine portative";}
	else {$libelle_machine2 = "";}	
	if ($mach3 == "") {$libelle_machine3 = "";}
	elseif ($mach3 == "01") {$libelle_machine3 = "Meuleuse d'angle";}
	elseif ($mach3 == "02") {$libelle_machine3 = "Perceuse";}
	elseif ($mach3 == "03") {$libelle_machine3 = "Machine Haute Vitesse";}
	elseif ($mach3 == "04") {$libelle_machine3 = "Machine Fixe";}
	elseif ($mach3 == "05") {$libelle_machine3 = "Manuelle";}
	elseif ($mach3 == "06") {$libelle_machine3 = "Polisseuse d'angle";}
	elseif ($mach3 == "07") {$libelle_machine3 = "Satineuse";}
	elseif ($mach3 == "08") {$libelle_machine3 = "Rectifieuse interne";}
	elseif ($mach3 == "09") {$libelle_machine3 = "Meuleuse d'angle";}
	elseif ($mach3 == "10") {$libelle_machine3 = "Scie de chantier";}
	elseif ($mach3 == "11") {$libelle_machine3 = "Machine thermique";}
	elseif ($mach3 == "12") {$libelle_machine3 = "Meuleuse droite";}
	elseif ($mach3 == "13") {$libelle_machine3 = "Ponceuse triangulaire";}
	elseif ($mach3 == "14") {$libelle_machine3 = "Ponceuse excentrique";}
	elseif ($mach3 == "15") {$libelle_machine3 = "Ponceuse vibrante";}
	elseif ($mach3 == "16") {$libelle_machine3 = "Machine portative";}
	else {$libelle_machine3 = "";}	
	$req2->closeCursor();	

	if ($categorie == "Disques à tronçonner") {$code = $ss_categorie;}
	else {$code = $categorie;}
	$req3 = $bdd->prepare('SELECT * FROM couleurs WHERE code LIKE :code');
	$req3->execute(array('code' => $code));
	While ($donnees = $req3->fetch())
	{
		$R1 = $donnees['R1'];
		$G1 = $donnees['G1'];
		$B1 = $donnees['B1'];
		$R2 = $donnees['R2'];
		$G2 = $donnees['G2'];
		$B2 = $donnees['B2'];
		$RP = $donnees['RP'];
		$GP = $donnees['GP'];
		$BP = $donnees['BP'];
		$logo = $donnees['logo'];
	}
	$req3->closeCursor();	
	
	$code_couleur = "Gris";
	$req4 = $bdd->prepare('SELECT * FROM couleur_correspondance WHERE entree LIKE :garnissage');
	$req4->execute(array('garnissage' => $garnissage));
	While ($donnees = $req4->fetch())
	{
		$code_couleur = $donnees['sortie'];
	}
	$req4->closeCursor();	
	
	$req5 = $bdd->prepare('SELECT * FROM couleur_garnissage WHERE code LIKE :sortie');
	$req5->execute(array('sortie' => $code_couleur));
	While ($donnees = $req5->fetch())
	{
		$R1gar = $donnees['R1'];
		$G1gar = $donnees['G1'];
		$B1gar = $donnees['B1'];
		$RPgar = $donnees['RP'];
		$GPgar = $donnees['GP'];
		$BPgar = $donnees['BP'];
	}
	$req5->closeCursor();		
	
	$req6 = $bdd->prepare('SELECT * FROM certification WHERE entree LIKE :entree');
	$req6->execute(array('entree' => $certification));
	$img_certification = "blank.png";
	While ($donnees = $req6->fetch())
	{
		$img_certification = $donnees['sortie'];
	}
	$req6->closeCursor();
	
// GENERATION PDF

require('../fpdf.php');

class PDF extends FPDF
{
// En-tête
function Header()
{
	$this->Image('https://www.osborn-dronco.fr/css/images/logo_dronco.png',10,10,50);
	$this->Image('https://www.osborn-dronco.fr/css/images/logo_osborn.png',150,10,50);
	$this->Ln(20);
	$this->SetFont('Arial','I',8);
	$this->Cell(60,10,$refint,0,0,'L');
}

// Pied de page
function Footer()
{
	// Positionnement à 1,5 cm du bas
	$this->SetY(-15);
	// Police Arial italique 8
	$this->SetFont('Arial','I',8);
	// Numéro de page
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation de la classe dérivée
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
	$pdf->Cell(0,10,'Impression de la ligne numéro '.$i,0,1);
$pdf->Output();

}
//SINON AFFICHER MESSAGE ERREUR
else {echo 
'<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>Fiche produit non créée</title>
<link rel="icon" type="images/png" href="css/images/favicon.png" />
</head><body>
<h1>Fiche produit non créée</h1>
<p>La fiche produit pour cette référence n\'a pas encore été créée. Merci de contacter votre administrateur.</p>
<hr>
<address>Apache/2.4.27 (Win64) PHP/5.6.31 Server at localhost Port 80</address>
</body></html>'
;}

}
?>
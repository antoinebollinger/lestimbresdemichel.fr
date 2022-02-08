<?php 
session_start();
if (!isset($_SESSION['connect']) OR $_SESSION['connect']==false) {header('Location: login.php');exit();} else {
include('session/stop_message.php');
include('bdd/bdd.php');
	
if (!isset($_SESSION['ids'])) {$_SESSION['ids'] = array();}

$libelle = utf8_decode(isset($_POST['libelle'])? $_POST['libelle']:  (isset($_GET['libelle'])? $_GET['libelle']:'' )) ;
$action = (isset($_POST['action'])? $_POST['action']:  (isset($_GET['action'])? $_GET['action']:'' )) ;
$ids = (isset($_POST['ids'])? $_POST['ids']:  (isset($_GET['ids'])? $_GET['ids']:null )) ;
if ($ids !== null) {$_SESSION['ids'] = json_decode($ids,true);}

if ($action == 'print') {
		
$font = "post/font/FreeSansBold.ttf";

require('fpdf/mem_image.php');

class PDF extends PDF_MemImage {
	
	function Footer() {
		global $bdp_ele; global $libelle;
		$this->SetFillColor(150,150,150);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','I',8);
		$this->SetXY(15,10);
		$this->Cell(55,4,$bdp_ele,0,0,'L',false);		
		$this->MultiCell(125,4,$libelle,0,'R',false);		
		$this->SetY(-14);
		$this->Cell(0,4,'Page '.$this->PageNo().'/{nb}',0,1,'C');		
	}

	function SetDash($black=null, $white=null) 	{
		if($black!==null)
			$s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
		else
			$s='[] 0 d';
		$this->_out($s);
	}
	protected $_Avery_Labels = array('etiquette' => array('paper-size'=>'A4','metric'=>'mm','marginLeft'=>12,'marginTop'=>25,'NX'=>9,'NY'=>6,'SpaceX'=>0,'SpaceY'=>0,'width'=>20,'height'=>40,'font-size'=>7));
// Constructor
	function __construct($format, $unit='mm', $posX=1, $posY=1) {
		if (is_array($format)) {
			// Custom format
			$Tformat = $format;
		} else {
			// Built-in format
			if (!isset($this->_Avery_Labels[$format]))
				$this->Error('Unknown label format: '.$format);
			$Tformat = $this->_Avery_Labels[$format];
		}
		parent::__construct('P', $unit, $Tformat['paper-size']);
		$this->_Metric_Doc = $unit;
		$this->_Set_Format($Tformat);
		$this->SetFont('Arial');
		$this->SetMargins(0,0); 
		$this->SetAutoPageBreak(false); 
		$this->_COUNTX = $posX-2;
		$this->_COUNTY = $posY-1;
	}
	function _Set_Format($format) {
		$this->_Margin_Left    = $this->_Convert_Metric($format['marginLeft'], $format['metric']);
		$this->_Margin_Top    = $this->_Convert_Metric($format['marginTop'], $format['metric']);
		$this->_X_Space     = $this->_Convert_Metric($format['SpaceX'], $format['metric']);
		$this->_Y_Space     = $this->_Convert_Metric($format['SpaceY'], $format['metric']);
		$this->_X_Number     = $format['NX'];
		$this->_Y_Number     = $format['NY'];
		$this->_Width         = $this->_Convert_Metric($format['width'], $format['metric']);
		$this->_Height         = $this->_Convert_Metric($format['height'], $format['metric']);
		$this->Set_Font_Size($format['font-size']);
		$this->_Padding        = $this->_Convert_Metric(3, 'mm');
	}
	function _Convert_Metric($value, $src) {
		$dest = $this->_Metric_Doc;
		if ($src != $dest) {
			$a['in'] = 39.37008;
			$a['mm'] = 1000;
			return $value * $a[$dest] / $a[$src];
		} else {
			return $value;
		}
	}
	// Give the line height for a given font size
	function _Get_Height_Chars($pt) {
		$a = array(6=>2, 7=>2.5, 8=>3, 9=>4, 10=>5, 11=>6, 12=>7, 13=>8, 14=>9, 15=>10);
		if (!isset($a[$pt]))
			$this->Error('Invalid font size: '.$pt);
		return $this->_Convert_Metric($a[$pt], 'mm');
	}
	// Set the character size
	// This changes the line height too
	function Set_Font_Size($pt) {
		$this->_Line_Height = $this->_Get_Height_Chars($pt);
		$this->SetFontSize($pt);
	}
	// Print a label
	function Add_Label($drap,$pays,$nb_v,$annee,$type,$yvert,$y_cote,$y_cata) {
		$this->_COUNTX++;
		if ($this->_COUNTX == $this->_X_Number) {
			// Row full, we start a new one
			$this->_COUNTX=0;
			$this->_COUNTY++;
			if ($this->_COUNTY == $this->_Y_Number) {
				// End of page reached, we start a new one
				$this->_COUNTY=0;
				$this->AddPage();
			}
		}
		$_PosX = $this->_Margin_Left + $this->_COUNTX*($this->_Width+$this->_X_Space) + $this->_Padding;
		$_PosY = $this->_Margin_Top + $this->_COUNTY*($this->_Height+$this->_Y_Space) + $this->_Padding;
		$this->SetDrawColor(200,200,200);
		$this->SetFillColor(150,150,150);
		$this->SetDash(0.5,1);
		//Contour
		$this->Rect($_PosX,$_PosY,$this->_Width,$this->_Height,'D');
		//Drapeau (H 9,6)
		$this->Image('css/images/drapeaux/'.$drap,$_PosX+5,$_PosY,10,0);
		//Pays (H 7,60)
		$this->SetXY($_PosX,$_PosY+9.6);
		$this->SetTextColor(192,0,0);
		$this->SetFont('Arial','B',7);
		$pays_width = $this->GetStringWidth(utf8_decode($pays))+1;
		Switch (true) {
			case $pays_width <= 20 :
				$this->Cell(20,7.6,utf8_decode($pays),0,1,'C',false);
				break;
			case $pays_width <= 40 :
				$this->MultiCell(20,3.8,utf8_decode($pays),0,'C',false);
				break;
			case $pays_width <= 60 :
				$this->MultiCell(20,2.4,utf8_decode($pays),0,'C',false);
				break;
			default :
				$this->MultiCell(20,2,utf8_decode($pays),0,'C',false);
				break;
		}
		//N° Yvert (H 6,90)
		$this->SetXY($_PosX,$_PosY+9.6+7.6);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',8);
		$this->Cell(6,6.9,'N°:',0,0,'L',false);
		$this->SetTextColor(51,51,255);
		$this->SetFont('Arial','B',8);
		if ($this->GetStringWidth(utf8_decode($yvert))<=14) {$this->Cell(14,6.9,utf8_decode($yvert),0,1,'C',false);}
		else {$this->MultiCell(14,3.45,utf8_decode($yvert),0,'C',false);}
		//Type (H 3)
		$this->SetXY($_PosX,$_PosY+9.6+7.6+6.9);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',7);
		$this->Cell(7,3,'Type:',0,0,'L',false);
		$this->SetTextColor(51,51,255);
		$this->SetFont('Arial','B',7);
		$this->Cell(13,3,utf8_decode($type),0,1,'C',false);
		//Cote (H 3)
		$this->SetXY($_PosX,$_PosY+9.6+7.6+6.9+3);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',7);
		$this->Cell(6.5,3,'Cote:',0,0,'L',false);
		$this->SetTextColor(51,51,255);
		$this->SetFont('Arial','B',7);
		$y_cote_tmp = ($y_cote != '') ? number_format(floatval(str_replace(',','.',utf8_decode($y_cote))),2,',',' ').' €' : '';
		$this->Cell(13.5,3,$y_cote_tmp,0,1,'C',false);		
		//Année (H 3)
		$this->SetXY($_PosX,$_PosY+9.6+7.6+6.9+3+3);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',7);
		$this->Cell(9,3,'Année:',0,0,'L',false);
		$this->SetTextColor(51,51,255);
		$this->SetFont('Arial','B',7);
		$this->Cell(11,3,utf8_decode($annee),0,1,'C',false);				
		//Nb valeur (H 3)
		$this->SetXY($_PosX,$_PosY+9.6+7.6+6.9+3+3+3);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',7);
		$this->Cell(5,3,'Nb.:',0,0,'L',false);
		$this->SetTextColor(51,51,255);
		$this->SetFont('Arial','B',7);
		$this->Cell(15,3,utf8_decode(str_replace('val','v',str_replace(' ','',$nb_v))),0,1,'C',false);				
		//Catalogue (H 3,90)
		$this->SetXY($_PosX,$_PosY+9.6+7.6+6.9+3+3+3+3);
		$this->SetTextColor(51,51,255);
		$this->SetFont('Arial','',6);
		if ($this->GetStringWidth(utf8_decode($y_cata))<=20) {$this->Cell(20,3.9,utf8_decode($y_cata),0,1,'C',false);}
		else {$this->MultiCell(20,1.9,utf8_decode($y_cata),0,'C',false);}		
	}
	function _putcatalog() {
		parent::_putcatalog();
		// Disable the page scaling option in the printing dialog
		$this->_put('/ViewerPreferences <</PrintScaling /None>>');
	}	
}

$pdf = new PDF('etiquette');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle($libelle);

$resultat = 0;
foreach($_SESSION['ids'] as $key => $value) {
	$resultat += $value['qte'];
}
foreach($_SESSION['ids'] as $key => $value) {
	$req_tmp = $bdd->prepare("SELECT pa.drapeau, ca.pays, ca.nb_valeur, ca.annee, ca.yvert, ca.type, ca.yvert_cote, ca.yvert_catalogue FROM catalogue ca LEFT JOIN pays pa ON pa.pays = ca.pays WHERE ca.id = ".$value['id']);
	$req_tmp->execute();
	While ($donnees = $req_tmp->fetch()) {
		for ($i=0;$i < $value['qte'];$i++) {
			$pdf->Add_Label($donnees['drapeau'],$donnees['pays'],$donnees['nb_valeur'],$donnees['annee'],$donnees['type'],$donnees['yvert'],$donnees['yvert_cote'],$donnees['yvert_catalogue']);
			$first_ele = (1 + ((($pdf->PageNo()) - 1) * ($pdf->_X_Number * $pdf->_Y_Number)));
			$last_ele = ($key + 1);
			$nb_ele = $last_ele - $first_ele + 1;
			$bdp_ele = 'Etiquette(s) '.$first_ele.' à '.$last_ele.' ('.$nb_ele.' sur un total de '.$resultat.')';
		}
	}
	$req_tmp->closeCursor();
}

//SORTIE
$pdf->Output('I',$libelle.'.pdf',false);		
unset($_SESSION['ids']);
	}
} ?>
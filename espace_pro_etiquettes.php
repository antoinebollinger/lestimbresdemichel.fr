<?php $id_sub_page = 'espace_pro_etiquettes'; ?>
<h4>Imprimer des étiquettes</h4>
<form action="espace_pro.php" method="get" id="form_pays">
<label>Sélectionner un pays (code ISO 3166-2) :</label>
<select class="select_pays" name="code" id="select_code">
	<option value="">-</option>
<?php 
$req_code = $bdd->prepare("SELECT pa.code, pa_t.pays, COUNT(ca.pays) AS 'nbr' FROM catalogue ca LEFT JOIN pays pa ON pa.pays = ca.pays LEFT JOIN pays_tmp pa_t ON pa.code = pa_t.code WHERE ca.statut != 'C' AND pa.code != '' AND ca.pays LIKE :pays GROUP BY pa.code ORDER BY pa_t.pays");
$req_code->execute(array('pays' => (($pays != '') ? $pays : "%")));
While ($donnees_code = $req_code->fetch()) {
	echo '<option'.(($code == $donnees_code['code']) ? " selected" : "").' value="'.$donnees_code['code'].'">'.$donnees_code['pays'].' ('.$donnees_code['nbr'].')</option>';
}
$req_code->closeCursor();
?>
</select>
<label>Sélectionner un pays, une ancienne colonie, une région :</label>
<select class="select_pays" name="pays" id="select_pays">
	<option value="">-</option>
<?php 
$req_pays = $bdd->prepare("SELECT ca.pays, pa.code, COUNT(ca.pays) AS 'nbr' FROM catalogue ca LEFT JOIN pays pa ON pa.pays = ca.pays WHERE ca.statut != 'C' AND ca.pays != '' AND pa.code LIKE :code GROUP BY ca.pays ORDER BY ca.pays");
$req_pays->execute(array('code' => (($code != '') ? $code : "%")));
While ($donnees_pays = $req_pays->fetch()) {
	echo '<option'.(($pays == $donnees_pays['pays']) ? " selected" : "").' value="'.$donnees_pays['pays'].'">'.$donnees_pays['pays'].' ('.$donnees_pays['nbr'].')</option>';
}
$req_pays->closeCursor();
?>
</select>
<input type="hidden" name="table" value="etiquettes" />
</form>
<?php if ($code != '' || $pays != '') { ?>
<h4>Veuillez indiquer la quantité souhaitée (mettre 0 pour ne pas imprimer l'étiquette) puis cliquez sur 'Valider'.</h4>
<table id="table_etiquettes">
	<thead>
		<tr>
			<th>Etiquette(s)</th>
			<th>Pays</th>
			<th>Nb valeurs</th>
			
			<th>Thématique</th>
			<th>Année</th>
			
			<th>Type</th>
			<th>N° Yvert</th>
			<th>Cote **</th>
			<th>Réf. catalogue</th>
		</tr>
	<thead>
	<tbody>
<?php 
$libelle_array = array();
$req_table = $bdd->prepare("
SELECT pa2.pays AS 'libelle', ca.id, ca.pays, ca.nb_valeur, ca.thematique, ca.annee, ca.type, ca.yvert, ca.yvert_cote, ca.yvert_catalogue 
FROM catalogue ca 
LEFT JOIN pays pa ON pa.pays = ca.pays 
LEFT JOIN pays_tmp pa2 ON pa.code = pa2.code 
WHERE ca.statut != 'C' AND ca.pays LIKE :pays AND pa.code LIKE :code 
ORDER BY ca.pays, CAST(ca.yvert AS UNSIGNED), ca.yvert
");
$req_table->execute(array('pays' => (($pays != '') ? $pays : "%"), 'code' => (($code != '') ? $code : "%")));
While ($donnees = $req_table->fetch()) {
	if (!in_array($donnees['pays'],$libelle_array)) {$libelle_array[] = $donnees['pays'];}
	$libelle = $donnees['libelle'];
	echo '
		<tr>
			<td><input type="number" name="'.$donnees['id'].'" min="0" step="1" value="1" style="text-align:center;" /></td>
			<td>'.$donnees['pays'].'</td>
			<td>'.$donnees['nb_valeur'].'</td>

			<td>'.$donnees['thematique'].'</td>
			<td>'.$donnees['annee'].'</td>

			<td>'.$donnees['type'].'</td>
			<td>'.$donnees['yvert'].'</td>
			<td>'.$donnees['yvert_cote'].'</td>
			<td>'.$donnees['yvert_catalogue'].'</td>
		</tr>';
}
$req_table->closeCursor();
Switch (true) {
	case count($libelle_array) > 1 :
		$libelle .= ' ('.implode(', ',$libelle_array).')';
		break;
	case count($libelle_array) == 1 :
		if ($libelle_array[0] != $libelle) {$libelle .= ' ('.$libelle_array[0].')';}
		break;
	default : 
		break;
}
?>
		</tbody>
	</table>
	<br/>
	<button type="button" id="valider_etiquette">Valider</button>
<form action="etiquettes.php" method="post" target="_blank" id="form_etiquettes">
	<input type="hidden" name="libelle" value="<?php echo $libelle; ?>" />
	<input type="hidden" name="action" value="print" />
</form>	
<?php } ?>

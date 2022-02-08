<?php
$id_sub_page = 'espace_pro_tables';
$req_title = $bdd->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$mysql_base."' AND TABLE_NAME = '".$table."'");
$req_title->execute();
$title= array();
While ($donnees_title = $req_title->fetch()) {
	$title[] = $donnees_title['COLUMN_NAME'];
}
$req_title->closeCursor();
echo '<h4>Table : '.$table.'</h4>';
echo '<form id="form_update"><table id="table_'.$table.'" class="table_sql">';
echo '<thead><tr>';
foreach($title as $key1 => $value1) {
	echo '<th>'.$value1.'</th>';
}
echo '</tr></thead><tbody>';	
$req_table = $bdd->prepare("SELECT * FROM pays");
$req_table->execute();
While ($donnees = $req_table->fetch()) {
	echo '<tr>';
	foreach ($title as $key2 => $value2) {
		echo '<td data-id="'.$donnees['id'].'" data-title="'.$value2.'">'.(($value2 != 'id') ? '<span>'.$donnees[$value2].'</span><input type="text" value="'.$donnees[$value2].'" class="input_table" disabled data-id="'.$donnees['id'].'" data-title="'.$value2.'" id="'.$donnees['id'].$value2.'"/>' : $donnees[$value2]).'</td>';	}
	echo '</tr>';
}
echo '</tbody></table><input type="submit" style="display:none;" /></form>';
$req_table->closeCursor();
?>
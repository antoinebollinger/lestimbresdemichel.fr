<?php 
$id_sub_page = 'espace_pro_tables';
//TITRES
$req_title = $bdd->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$mysql_base."' AND TABLE_NAME = '".$table."'");
$req_title->execute();
$title= array();
While ($donnees_title = $req_title->fetch()) {
	$title[] = $donnees_title['COLUMN_NAME'];
}
$req_title->closeCursor();
$req_libelle = $bdd->prepare("SELECT entree, sortie, couleur, police FROM titre");
$req_libelle->execute();
$libelle = array();
While ($donnees_libelle = $req_libelle->fetch()) {
	$libelle[$donnees_libelle['entree']] = array('libelle' => $donnees_libelle['sortie'], 'couleur' => $donnees_libelle['couleur'], 'police' => $donnees_libelle['police']);
}
$req_libelle->closeCursor();
//SELECT TITRE
$title_select = array(); $query_where = ''; $form_gen_inputs = ''; $result = '';
foreach($title as $key => $value) {
	if (isset($_GET['select_'.$value]) && $_GET['select_'.$value] != '') {
		$query_where .= (($query_where == '') ? "WHERE " : "AND ").$value." = '".htmlspecialchars($_GET['select_'.$value])."' ";
		$form_gen_inputs .= '<input type="hidden" name="select_'.$value.'" value="'.htmlspecialchars($_GET['select_'.$value]).'" />';
		$result .= '<b>'.htmlspecialchars($_GET['select_'.$value]).'</b>, ';
	}
}
foreach($title as $key => $value) {
	$get_select = ''; $label_active = '';
	if (isset($_GET['select_'.$value]) && $_GET['select_'.$value] != '') {
		$get_select = htmlspecialchars($_GET['select_'.$value]);
		$label_active = ' class="active"';
	}
	$title_select_tmp = '<th'.$label_active.((array_key_exists($value,$libelle)) ? ' style="color:'.$libelle[$value]['police'].';background-color:'.$libelle[$value]['couleur'].'"' : '').'><label'.$label_active.'>'.((array_key_exists($value,$libelle)) ? $libelle[$value]['libelle'] : $value).'</label><select id="select_'.$value.'" name="select_'.$value.'"><option value="">-</option>';
	$req_select = $bdd->prepare("SELECT ".$value." FROM ".$table." ".(($query_where == '') ? "WHERE ".$value." != ''" : $query_where." AND ".$value." != ''")." GROUP BY ".$value);
	$req_select->execute();
	While ($donnees_select = $req_select->fetch()) {
		$title_select_tmp .= '<option value="'.$donnees_select[$value].'"'.(($get_select == $donnees_select[$value]) ? ' selected' : '').'>'.$donnees_select[$value].'</option>';
	}
	$req_select->closeCursor();
	$title_select_tmp .= '</select></th>';
	$title_select[$value] = $title_select_tmp;
}
//GESTION NB ELEMENTS AFFICHES
$req_nb = $bdd->prepare('SELECT id FROM catalogue '.$query_where);
$req_nb->execute();
$nb_total = 0;
While ($donnees_nb = $req_nb->fetch()) {$nb_total++;}
$req_nb->closeCursor();
//gestion limite nb par page
if (!isset($_SESSION['nb_par_page_pro'])) {$_SESSION['nb_par_page_pro'] = 50;}
if (isset($_GET['nb_par_page_pro']) AND $_GET['nb_par_page_pro'] != "") {
	$nb_par_page_tmp = intval(htmlspecialchars($_GET['nb_par_page_pro']));
	$_SESSION['nb_par_page_pro'] = (in_array($nb_par_page_tmp,array(50,100,150,200))) ? $nb_par_page_tmp : 50;
}
$nombreDeMessagesParPage = intval($_SESSION['nb_par_page_pro']);
$nombreDePages  = ceil($nb_total / $nombreDeMessagesParPage);
if (!isset($_SESSION['page_pro'])) {$_SESSION['page_pro'] = 1;}
if (isset($_GET['page_pro']) AND $_GET['page_pro'] != "") {
	$page_tmp = intval(htmlspecialchars($_GET['page_pro']));
	$_SESSION['page_pro'] = ($page_tmp > $nombreDePages || $page_tmp < 1) ? 1 : $page_tmp;
}
$page = $_SESSION['page_pro'];
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;
//TABLE
echo '<h4>Table : '.$table.'</h4>';
echo '<form id="form_update"><table id="table_'.$table.'" class="table_sql">';
//tbody
echo '<tbody>';
$nb_count = $premierMessageAafficher;
$req_table = $bdd->prepare("SELECT * FROM ".$table." ".$query_where."LIMIT ".$premierMessageAafficher.",".$nombreDeMessagesParPage);
$req_table->execute();
While ($donnees = $req_table->fetch()) {
	echo '<tr>';
	foreach ($title as $key2 => $value2) {
		echo '<td data-id="'.$donnees['id'].'" data-title="'.$value2.'">'.(($value2 != 'id') ? '<span>'.$donnees[$value2].'</span><input type="text" value="'.$donnees[$value2].'" class="input_table" disabled data-id="'.$donnees['id'].'" data-title="'.$value2.'" id="'.$donnees['id'].$value2.'"/>' : $donnees[$value2]).'</td>';
	}
	echo '</tr>';
	$nb_count++;
}
echo '</tbody>';
//thead
echo '<thead><tr>';
foreach($title_select as $key1 => $value1) {
	echo ($key1 != 'id') ? $value1 : '<th>Id</th>' ;
}
echo '</tr></thead>';
echo '</table><input type="submit" style="display:none;" /></form>';
$req_table->closeCursor();
?>
<div id="main_pro_results">
	<div class="results-table">
		<div class="results-table-cell">
			<h4>
				<i class="nav fas fa-angle-double-left<?php echo ($page != 1 && $nombreDePages != 0) ? ' active' : ''; ?>" alt="1"></i>
				<i class="nav fas fa-angle-left min_w<?php echo ($page != 1 && $nombreDePages != 0) ? ' active' : ''; ?>" alt="<?php echo ($page > 1) ? $page - 1 : 1; ?>"></i>
				<span><input id="change_page" type="text" min="1" max="<?php echo $nombreDePages; ?>" value="<?php echo $page; ?>" /><?php echo ' / '.(($nombreDePages > 0) ? $nombreDePages : 1); ?></span>
				<i class="nav fas fa-angle-right min_w<?php echo ($page != $nombreDePages && $nombreDePages != 0) ? ' active' : ''; ?>" alt="<?php echo ($page < $nombreDePages) ? $page + 1 : $nombreDePages; ?>"></i>
				<i class="nav fas fa-angle-double-right<?php echo ($page != $nombreDePages && $nombreDePages != 0) ? ' active' : ''; ?>" alt="<?php echo $nombreDePages; ?>"></i>
			</h4>	
		</div>	
		<div class="results-table-cell">
			<p>
				<label for="nb_par_page_pro">Afficher</label>
				<select name="nb_par_page_pro" id="nb_par_page_select" onchange="">
					<option<?php echo ($nombreDeMessagesParPage == 50) ? ' selected' : ''; ?> value="50">50</option>
					<option<?php echo ($nombreDeMessagesParPage == 100) ? ' selected' : ''; ?> value="100">100</option>
					<option<?php echo ($nombreDeMessagesParPage == 150) ? ' selected' : ''; ?> value="150">150</option>
					<option<?php echo ($nombreDeMessagesParPage == 200) ? ' selected' : ''; ?> value="200">200</option>		
				</select>
				<label>résultats par page.</label>
			</p>			
		</div>
		<div class="results-table-cell">
			<p>Résultat(s) : <b><?php echo $nb_total; ?></b> <em>(affichage des résultats <?php echo ($nb_total == 0) ? '0' : $premierMessageAafficher + 1; ?> à <?php echo $nb_count; ?>)</em> | Filtres actifs : <?php echo ($result != '') ? substr($result,0,strlen($result)-2) : '<b>aucun</b>'; ?></p>
		</div>
	</div>
</div>
<form method="get" action="espace_pro.php" id="form_gen">
<?php echo $form_gen_inputs; ?>
<input type="hidden" name="table" value="catalogue" />
<input type="hidden" id="page_input" name="page_pro" value="<?php echo $page; ?>" />
<input type="hidden" id="nb_par_page_input" name="nb_par_page_pro" value="<?php echo $nombreDeMessagesParPage; ?>" />
</form>
<script defer>var cur_page = <?php echo $page; ?>, nb_par_page = <?php echo $nombreDeMessagesParPage; ?>, nombreDePages = <?php echo $nombreDePages; ?>;</script>		
<?php $id_sub_page = 'main_collec'; ?>
<div id="main_collec_left" class="main_collec_left">
<h3><i class="fas fa-search"></i>Votre recherche</h3>
<form method="get" action="collection.php" id="form_gen">
	<div id="div_search_sub">
		<input type="text" name="rech_gen" id="text_search_sub" value="<?php echo $rech_gen; ?>" placeholder=" " autocomplete="off" />
		<i id="delete_search" class="far fa-times-circle"></i>
	</div>
<h3 id="show_map"><i class="fas fa-globe-americas"></i>Carte</h3>
<h3><i class="fas fa-filter"></i>Filtres</h3>
<div id="main_filters">
<label for="code">Pays <em>(code ISO 3166-2)</em></label>
<select name="code" id="code_select" class="auto">
	<optgroup label="Code pays">
		<option value="">-</option>	
<?php 
	$req = $bdd->prepare("SELECT pa.code, pa_t.pays FROM catalogue ca LEFT JOIN pays pa ON pa.pays = ca.pays LEFT JOIN pays_tmp pa_t ON pa.code = pa_t.code WHERE pa.code != '' AND ca.pays LIKE :pays AND ca.annee LIKE :annee AND ca.theme_1 LIKE :theme ".$periode_query." ".$type_query." GROUP BY pa.code ORDER BY pa_t.pays");
	$req->execute(array('annee' => (($annee != '') ? $annee : "%"), 'theme' => (($theme != '') ? $theme : "%"), 'pays' => (($pays != '') ? $pays : "%")));
	While ($donnees_code = $req->fetch()) {
		echo '<option'.(($code == $donnees_code['code']) ? " selected" : "").' data-code="'.$donnees_code['code'].'" value="'.$donnees_code['code'].'">'.$donnees_code['pays'].' ('.$donnees_code['code'].')</option>';
	}
	$req->closeCursor();
?>		
	</optgroup>
</select>
<label for="pays">Pays, anc. colonies, etc.</label>
<select name="pays" id="pays_select" class="auto">
	<optgroup label="Pays, anc. colonie...">
		<option value="">-</option>	
<?php 
	$req = $bdd->prepare("SELECT ca.pays, pa.code FROM catalogue ca LEFT JOIN pays pa ON pa.pays = ca.pays WHERE ca.pays != '' AND ca.annee LIKE :annee AND ca.theme_1 LIKE :theme AND pa.code LIKE :code ".$periode_query." ".$type_query." GROUP BY ca.pays ORDER BY ca.pays");
	$req->execute(array('annee' => (($annee != '') ? $annee : "%"), 'theme' => (($theme != '') ? $theme : "%"), 'code' => (($code != '') ? $code : "%")));
	While ($donnees_pays = $req->fetch()) {
		echo '<option'.(($pays == $donnees_pays['pays']) ? " selected" : "").'>'.$donnees_pays['pays'].'</option>';
	}
	$req->closeCursor();
?>		
	</optgroup>
</select>
<div style="display:none;">
<label for="annee">Année</label>
<select name="annee" id="annee_select" class="auto">
	<optgroup label="Années">
		<option value="">-</option>	
<?php 
	$req = $bdd->prepare("SELECT ca.annee FROM catalogue ca LEFT JOIN pays pa ON pa.pays = ca.pays WHERE ca.pays LIKE :pays AND ca.annee != '' AND ca.theme_1 LIKE :theme AND pa.code LIKE :code ".$periode_query." ".$type_query." GROUP BY ca.annee ORDER BY ca.annee");
	$req->execute(array('pays' => (($pays != '') ? $pays : "%"), 'theme' => (($theme != '') ? $theme : "%"), 'code' => (($code != '') ? $code : "%")));
	While ($donnees_annee = $req->fetch()) {
		echo '<option'.(($annee == $donnees_annee['annee']) ? " selected" : "").'>'.$donnees_annee['annee'].'</option>';
	}
	$req->closeCursor();
?>		
	</optgroup>
</select>
</div>
<label>Période</label>
<div class="table">
	<div class="table-cell" id="periode_select">
		<select name="type_annee">
			<option value="entre"<?php echo ($type_annee == 'entre') ? ' selected': ''; ?>>Entre</option>
			<option value="avant"<?php echo ($type_annee == 'avant') ? ' selected': ''; ?>>Avant</option>
			<option value="apres"<?php echo ($type_annee == 'apres') ? ' selected': ''; ?>>Après</option>
			<option value="annee"<?php echo ($type_annee == 'annee') ? ' selected': ''; ?>>Exacte</option>
		</select>
	</div>
	<div class="table-cell" id="periode_annee_1">
		<input type="number" name="annee_1" min="0" max="<?php echo date('Y'); ?>" step="1" value="<?php echo $annee_1; ?>" placeholder="Année" required />
	</div>
	<div class="table-cell active" id="periode_annee_2">
		<input type="number" name="annee_2" min="0" max="<?php echo date('Y'); ?>" step="1" value="<?php echo $annee_2; ?>" placeholder="Année" required />
	</div>
</div>
<label for="theme">Thème</label>
<select name="theme" id="theme_select" class="auto">
	<optgroup label="Thèmes">
		<option value="">-</option>	
<?php 
	$req = $bdd->prepare("SELECT ca.theme_1 FROM catalogue ca LEFT JOIN pays pa ON pa.pays = ca.pays WHERE ca.pays LIKE :pays AND ca.annee LIKE :annee AND ca.theme_1 != '' AND pa.code LIKE :code ".$periode_query." ".$type_query." GROUP BY ca.theme_1 ORDER BY ca.theme_1");
	$req->execute(array('pays' => (($pays != '') ? $pays : "%"), 'annee' => (($annee != '') ? $annee : "%"), 'code' => (($code != '') ? $code : "%")));
	While ($donnees_theme = $req->fetch()) {
		echo '<option'.(($theme == $donnees_theme['theme_1']) ? " selected" : "").'>'.$donnees_theme['theme_1'].'</option>';
	}
	$req->closeCursor();
?>		
	</optgroup>
</select>
<div id="type_select">
	<p class="p_legende double<?php echo (($type == "Dispoliste") ? " active" : ""); ?>">
		<label class="pointer"><input type="radio" name="type" class="auto" value="Dispoliste"<?php echo (($type == "Dispoliste") ? " checked" : ""); ?>>Dispoliste</label>
	</p>
	<p class="p_legende mancoliste<?php echo (($type == "Mancoliste") ? " active" : ""); ?>">
		<label class="pointer"><input type="radio" name="type" class="auto" value="Mancoliste"<?php echo (($type == "Mancoliste") ? " checked" : ""); ?>>Mancoliste</label>
	</p>
	<p class="p_legende<?php echo (($type == "") ? " active" : ""); ?>">
		<label class="pointer"><input type="radio" name="type" class="auto" value=""<?php echo (($type == "") ? " checked" : ""); ?>>Tous</label>
	</p>
</div>
<!--
<label for="type">Dispoliste / mancoliste</label>
<select name="type" id="type_select" class="auto">
	<optgroup label="Type">
		<option value=""<?php echo (($type == "") ? " selected" : ""); ?>>-</option>	
		<option value="Dispoliste"<?php echo (($type == "Dispoliste") ? " selected" : ""); ?>>Dispoliste</option>	
		<option value="Mancoliste"<?php echo (($type == "Mancoliste") ? " selected" : ""); ?>>Mancoliste</option>
	</optgroup>
</select>
-->
<input type="text" id="page_input" name="page" style="display:none;" value="<?php echo $page; ?>" />
<input type="text" id="nb_par_page_input" name="nb_par_page" style="display:none;" value="<?php echo $nombreDeMessagesParPage; ?>" />
</form>
<h3>Filtres actifs</h3>
<div id="filtres_actifs">
<?php 
$result = '';
foreach($filtre as $key => $value) {
	$bg = "";$label = $filtre[$key][1];
	if (in_array($filtre[$key][0],array('pays','code'))) {
		$req = $bdd->prepare('SELECT pa.drapeau, pa_t.pays FROM pays pa LEFT JOIN pays_tmp pa_t ON pa.code = pa_t.code WHERE pa.pays = :pays OR pa.code = :code');
		$req->execute(array('pays' => $filtre[$key][1], 'code' => $filtre[$key][1]));
		$donnees = $req->fetch();
		$req->closeCursor();
		$bg = 'style="background-image:url(\'css/images/drapeaux/'.$donnees['drapeau'].'\');" class="filtre_pays"';
		$label = ($filtre[$key][0] == 'code') ? $donnees['pays'].' ('.$filtre[$key][1].')' : $filtre[$key][1];
	}
	echo '<h5 data-type="'.$filtre[$key][0].'" title="Cliquez sur la croix pour supprimer ce filtre"'.$bg.'>'.$label.'<i class="far fa-times-circle delete_filter"></i></h5>';
	$result .= '<b>'.$label.'</b>'.(($key + 1 < count($filtre)) ? ', ' : '');
}
echo ($result == '') ? '<p>Aucun filtre actif</p>' : '<div style="clear:both"><br/><a href="collection.php" class="rouge"><i class="far fa-times-circle delete_filter"></i>Supprimer tous les filtres</a></div>';
?>
</div>
<form method="get" action="collection.php" id="form_gen_reset">
<input type="hidden" name="page" value="1" />
<input type="hidden" name="rech_gen" id="rech_gen" value="<?php echo $rech_gen; ?>" />
<input type="hidden" name="pays" id="pays" value="<?php echo $pays; ?>" />
<input type="hidden" name="code" id="code" value="<?php echo $code; ?>" />
<input type="hidden" name="annee" id="annee" value="<?php echo $annee; ?>" />
<input type="hidden" name="type_annee" id="type_annee" value="<?php echo $type_annee; ?>" />
<input type="hidden" name="annee_1" id="annee_1" value="<?php echo $annee_1; ?>" />
<input type="hidden" name="annee_2" id="annee_2" value="<?php echo $annee_2; ?>" />
<input type="hidden" name="theme" id="theme" value="<?php echo $theme; ?>" />
<input type="hidden" name="type" id="type" value="<?php echo $type; ?>" />
<input type="hidden" name="nb_par_page" value="<?php echo $nombreDeMessagesParPage; ?>" />
</form>
</div>
<h4>Légende</h4>
<!--
	<p class="p_legende double">Dispoliste</p>
	<p class="p_legende mancoliste">Mancoliste</p>
-->
	<p class="p_legende present">Présent dans ma liste d'intérêt</p>
<h4>Lexique</h4>
	<table>
		<tr>
			<td><i class="fas fa-star-of-life small"></i><i class="fas fa-star-of-life small"></i></td>
			<td>:</td>
			<td>neuf sans charnière</td>
		</tr>
		<tr>
			<td><i class="fas fa-star-of-life small"></i></td>
			<td>:</td>
			<td>neuf avec charnière</td>
		</tr>
		<tr>
			<td>(<i class="fas fa-star-of-life small"></i>)</td>
			<td>:</td>
			<td>neuf sans gomme</td>
		</tr>
		<tr>
			<td><i class="far fa-dot-circle"></i></td>
			<td>:</td>
			<td>oblitéré</td>
		</tr>
		<tr>
			<td><i class="far fa-envelope"></i></td>
			<td>:</td>
			<td>sur document</td>
		</tr>
	</table>
</div>
<div id="main_collec_right" class="main_collec_right<?php echo ((isset($_SESSION['list_grid']) && $_SESSION['list_grid'] == 'grid') ? ' grid' : ''); ?>">
<?php 
$swiper_html = ''; $nb_count = 0;
if ($nb_total > 0) {
	$req_gen = $bdd->prepare($main_query.' LIMIT '.$premierMessageAafficher.','.$nombreDeMessagesParPage);
	$req_gen->execute($main_query_array);
	$id_slide = 0; $nb_count = $premierMessageAafficher; $pays_actuel ="";
	echo '<fieldset>';
	While ($donnees = $req_gen->fetch()) {
		if ($donnees['pays'] != $pays_actuel) {
			echo '<div style="clear:both"></div></fieldset>			
			<fieldset class="palette"><legend><h2 style="background-image:url(\'css/images/drapeaux/'.$donnees['drapeau'].'\')">'.$donnees['pays'].'</h2></legend>';
			$pays_actuel = $donnees['pays'];
		}
		$nb_count++; $add_class = " active"; $delete_class = " unactive"; $present_class = "";
		if (isset($_SESSION['list']) && count($_SESSION['list']['id']) > 0 && in_array($donnees['id'],$_SESSION['list']['id'])) {
			$add_class = " unactive"; $delete_class = " active"; $present_class = " present";
		}
		$double_class = ($donnees['nbr'] - $donnees['nbr_my_collec'] >= 1) ? " double" : "";
		$mancoliste_class = ($donnees['nbr'] - $donnees['nbr_my_collec'] < 0) ? " mancoliste" : "";
		$disabled_class = ($donnees['nbr'] - $donnees['nbr_my_collec'] < 1) ? " disabled" : "";		
		$photo_a = (file_exists('css/images/timbres/miniatures/'.$donnees['photo_a'])) ? $donnees['photo_a'] : 'zero.png' ;
		
		echo '
<div class="id_timbre'.$mancoliste_class.$double_class.$present_class.'" id="vigne_'.$donnees['id'].'">
	<div class="id_timbre_img launch_swiper" style="background-image:url(\'css/images/timbres/miniatures/'.$photo_a.'\');" data-id="'.$id_slide.'"></div>
	<div class="id_timbre_text">
		<h4>'.$donnees['pays'].'</h4>
		<p><b>'.$donnees['annee'].'</b></p>
		<p><b>'.($donnees['theme_1'] != '' ? $donnees['theme_1'] : '&nbsp;').'</b></p>
		<p><b>'.($donnees['nb_valeur'] != '' ? $donnees['nb_valeur'] : '&nbsp;').'</b></p>
		<p>N° Yvert : <b>'.$donnees['yvert'].'</b></p>
		<p>Cote : <b>'.$donnees['yvert_cote'].'</b> €</p>
	</div>
	<div class="id_timbre_button">
		<button	data-id="'.$donnees['id'].'" class="valider ajouter'.$add_class.'"'.$disabled_class.'><i class="fas fa-shopping-basket"></i><span>Ajouter à la liste</span></button>
		<button data-id="'.$donnees['id'].'" class="retirer'.$delete_class.'"'.$disabled_class.'><i class="fas fa-trash-alt"></i><span>Retirer de la liste</span></button>
	</div>
	<div style="clear:both;"></div>	
</div>';
$swiper_html .= '
<div class="swiper-slide" id="slide_'.$donnees['id'].'">
	<div class="card'.$mancoliste_class.$double_class.$present_class.'">
		<div>
			<div class="sliderText">
				<img src="css/images/timbres/'.$photo_a.'" data-magnify-src="css/images/timbres/'.$photo_a.'" />
			</div>
			<div class="content">
				<h4>'.$donnees['pays'].'</h4>
				<p><b>'.$donnees['annee'].'</b></p>
				<p><b>'.($donnees['theme_1'] != '' ? $donnees['theme_1'] : '&nbsp;').'</b></p>
				<p><b>'.($donnees['nb_valeur'] != '' ? $donnees['nb_valeur'] : '&nbsp;').'</b></p>
				<p>N° Yvert : <b>'.$donnees['yvert'].'</b></p>
				<p>Cote : <b>'.$donnees['yvert_cote'].'</b> €</p>
			</div>
			<div class="data"  data-id_ajout="'.$donnees['id'].'" data-libelle_ajout="'.$donnees['libelle'].'" data-photo_ajout="'.$photo_a.'" data-pays_ajout="'.$donnees['pays'].'" data-annee_ajout="'.$donnees['annee'].'" data-yvert_ajout="'.$donnees['yvert'].'" data-cote_ajout="'.$donnees['yvert_cote'].'"></div>
		</div>
		<button	data-id="'.$donnees['id'].'" class="valider ajouter'.$add_class.'"'.$disabled_class.'><i class="fas fa-shopping-basket"></i><span>Ajouter à la liste</span></button>
		<button data-id="'.$donnees['id'].'" class="retirer'.$delete_class.'"'.$disabled_class.'><i class="fas fa-trash-alt"></i><span>Retirer de la liste</span></button>
		<div style="clear:both;"></div>
	</div>
</div>
';
		$id_slide++;
	}
	$req_gen->closeCursor();
	echo '<div style="clear:both;"></div></fieldset>';
} else {
	echo '<p>Pas de résultat</p>';
}
?>

</div>
<div style="clear:both;"></div>
<div id="main_collec_results">
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
				<span id="list_grid">Affichage : <i class="fas fa-list-ul<?php echo ((isset($_SESSION['list_grid']) && $_SESSION['list_grid'] == 'list') ? ' active' : ''); ?>" id="aff_list" alt="list" title="Afficher sous forme de liste."></i> <i class="fas fa-th<?php echo ((isset($_SESSION['list_grid']) && $_SESSION['list_grid'] == 'grid') ? ' active' : ''); ?>" id="aff_grid" alt="grid" title="Afficher sous forme de grille."></i> | </span><span class="a_swiper launch_swiper" data-id="0"><i class="far fa-images"></i> Mode Diapo</span> | <label for="nb_par_page">Afficher</label>
				<select name="nb_par_page" id="nb_par_page_select" onchange="">
					<option<?php echo ($nombreDeMessagesParPage == 50) ? ' selected' : ''; ?> value="50">50</option>
					<option<?php echo ($nombreDeMessagesParPage == 100) ? ' selected' : ''; ?> value="100">100</option>
					<option<?php echo ($nombreDeMessagesParPage == 150) ? ' selected' : ''; ?> value="150">150</option>
					<option<?php echo ($nombreDeMessagesParPage == 200) ? ' selected' : ''; ?> value="200">200</option>		
				</select>
			<label>résultats par page.</label>
			</p>			
		</div>
		<div class="results-table-cell">
			<p>Résultat(s) : <b><?php echo $nb_total; ?></b> <em>(affichage des résultats <?php echo ($nb_total == 0) ? '0' : $premierMessageAafficher + 1; ?> à <?php echo $nb_count; ?>)</em> | Filtres actifs : <?php echo ($result != '') ? $result : '<b>aucun</b>'; ?></p>
		</div>
	</div>
</div>
<div id="details">
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<?php echo $swiper_html; ?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
</div>
<?php 
$current_pays = array(); $current_content = array();
$req = $bdd->prepare("
SELECT 
pa_t.pays, 
pa_t.code, SUM(ca.nbr) AS 'nbr_total', 
CASE WHEN COUNT(DISTINCT pa.pays) > 1 THEN GROUP_CONCAT(DISTINCT pa.pays ORDER BY pa.pays ASC SEPARATOR '</li><li>') ELSE '' END AS 'content' 
FROM pays pa 
LEFT JOIN pays_tmp pa_t ON pa_t.code = pa.code 
LEFT JOIN catalogue ca ON ca.pays = pa.pays 
WHERE pa.code != '' GROUP BY pa_t.code
");
$req->execute();
While ($donnees = $req->fetch()) {
	$current_pays[$donnees['code']] = $donnees['nbr_total'];
	$current_content[$donnees['code']] = $donnees['content'];
}
$req->closeCursor();
?>
<script>var cur_page = <?php echo $page; ?>, nb_par_page = <?php echo $nombreDeMessagesParPage; ?>, nombreDePages = <?php echo $nombreDePages; ?>,current_pays = <?php if (isset($current_pays) && count($current_pays) != 0) {echo json_encode($current_pays);} else {echo '[""]';} ?>,current_content = <?php if (isset($current_content) && count($current_content) != 0) {echo json_encode($current_content);} else {echo '[""]';} ?>,current_code = <?php echo '"'.$code.'"'; ?>,carte = <?php echo '"'.((isset($_GET['carte']) && $_GET['carte'] == 'on') ? 'on' : 'off').'"'; ?>;</script>
<script src="./js/main_collec.js"></script>
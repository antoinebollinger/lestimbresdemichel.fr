<div id="acces_menu" class="acces_direct">
	<div class="acces_menu_bar" id="acces_menu_bar_1"></div>
	<div class="acces_menu_bar" id="acces_menu_bar_2"></div>
	<div class="acces_menu_bar" id="acces_menu_bar_3"></div>	
</div>
<div id="acces_suite" class="acces_direct">
	<p id="acces_suite_a">Cliquez ici ou faites défiler pour continuer<br/><i class="fas fa-caret-down"></i></p>
</div>
<div id="acces_collect" class="acces_direct">
	<?php if ($id_sub_page == 'main_liste') { ?><a id="p_direct_mail" class="p_direct boutons" href="#" alt="Outils" title="Outils"><i class="fas fa-tools"></i></a><?php } ?>
	<?php if ($id_sub_page == 'main_collec') { ?><a id="p_direct_filtres" class="p_direct boutons" href="#" alt="Filtrez les résultats" title="Filtrez les résultats"><i class="fas fa-filter"></i></a><?php } ?>
	<?php if ($id_sub_page == 'main' || $id_page == 'espace_pro') { ?>
	<a id="p_direct_carte" class="p_direct liens" href="collection.php?carte=on" alt="Faire une recherche sur la carte interactive" title="Faire une recherche sur la carte interactive"><i class="fas fa-globe-americas"></i></a>
	<?php } ?>
	<a id="p_direct_collec" class="p_direct liens<?php echo (($id_sub_page == 'main_collec') ? ' active' : '');?>" href="collection.php" alt="Accédez à l'album" title="Accédez à l'album"><i class="fas fa-book-open"></i></a>
	<a id="p_direct_liste" class="p_direct liens<?php echo (($id_sub_page == 'main_liste') ? ' active' : '');?>" href="collection.php?ma_liste=ma_liste" alt="Accédez à votre liste d'intérêt" title="Accédez à votre liste d'intérêt">
		<i class="fas fa-shopping-basket"></i>
		<span><?php echo (isset($_SESSION['list']) ? count($_SESSION['list']['id']) : '0'); ?></span>
	</a>
	<a id="p_direct_pro" class="p_direct liens<?php echo (($id_page == 'espace_pro') ? ' active' : '');?>" href="espace_pro.php" alt="Accédez à l'espace pro" title="Accédez à l'espace pro"><i class="fas fa-user"></i></a>
	<div style="clear:both"></div>
</div>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="./css/jquery-jvectormap-2.0.3.css">
<link rel="stylesheet" type="text/css" href="./css/swiper.min.css">
<link rel="stylesheet" type="text/css" href="./css/magnify.css">
<link rel="stylesheet" type="text/css" href="./css/boutons.css" />
<link rel="stylesheet" type="text/css" href="./css/couleurs.css" />
<link rel="stylesheet" type="text/css" href="./css/animate.css" />
<link rel="stylesheet" type="text/css" href="./css/top_bottom.css" media="screen and (min-width: 651px)" />
<link rel="stylesheet" type="text/css" href="./css/dialogue.css" media="screen and (min-width: 651px)" />
<link rel="stylesheet" type="text/css" href="./css/dialogue_mobile.css" media="screen and (max-width: 650px)" />
<link rel="stylesheet" type="text/css" href="./css/style.css" media="screen and (min-width: 651px)" />
<link rel="stylesheet" type="text/css" href="./css/style_mobile.css" media="screen and (max-width: 650px)" />	
<?php if ($id_page == 'collection') { ?>
<link rel="stylesheet" type="text/css" href="./css/collection.css" media="screen and (min-width: 651px)" />
<link rel="stylesheet" type="text/css" href="./css/collection_mobile.css" media="screen and (max-width: 650px)" />
<?php } ?>
<?php if (isset($id_page) && in_array($id_page,array('espace_pro','login'))) { ?>
<link rel="stylesheet" type="text/css" href="./css/style_<?php echo $id_page; ?>.css" media="screen and (min-width: 651px)" />
<link rel="stylesheet" type="text/css" href="./css/style_<?php echo $id_page; ?>_mobile.css" media="screen and (max-width: 650px)" />	
<?php } ?>
<link rel="stylesheet" type="text/css" href="./css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" media="screen" href="./js/slimbox/css/slimbox2.css" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Les timbres de Michel</title>
<meta name="description" content="Site de Michel Dumont, philatéliste. Découvrez ma collection de timbres et contactez-moi pour des renseignements et/ou échanges." />
<meta name="keywords" content="michel, dumont, philatélie, philatéliste, timbres, yvert" />
<meta property="og:title" content="Site de Michel Dumont, philatéliste" />
<meta property="og:type" content="website" />
<meta property="og:image" content="preview.jpg" />
<!--<meta property="og:url" content="https://www.antoine-traductions.com" />
<meta property="og:image" content="https://www.antoine-traductions.com/css/images/apercu.jpg" />
<meta property="og:image:secure_url" content="https://www.antoine-traductions.com/css/images/apercu.jpg" />-->
<link rel="icon" type="images/png" href="./css/images/favicon.png" />
<script src='./js/jquery-1.7.1.js'></script>
<script src="./js/jquery-ui.js"></script>
<script src="./js/slimbox/js/slimbox2.js"></script>
<script src="./js/ckeditor/ckeditor.js"></script>
<script src="./js/jquery.sticky.js"></script>
<script src="./js/jquery.magnify.js"></script>
<script src="./js/swiper.min.js"></script>
<script src="./js/jquery-jvectormap-2.0.3.min.js"></script>
<script src="./js/jquery-jvectormap-world-mill.js"></script>
<script>var id_page="<?php echo $id_page; ?>"; var id_item = "<?php echo ((isset($_GET['id']) && in_array($_GET['id'],array(1,2,3))) ? htmlspecialchars($_GET['id']) : '') ; ?>";</script>
<script src="https://kit.fontawesome.com/63124842e5.js" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css?family=Caveat|Indie+Flower|Just+Another+Hand|Nothing+You+Could+Do|Open+Sans|Roboto" rel="stylesheet">
</head>
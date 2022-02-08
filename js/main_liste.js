$(window).load(function() {
	
//GESTION LISTE
	
	var liste = $(".id_liste");
	$(document).click(function(event) {
		var actual_ele = $(event.target).closest(liste);
		liste.removeClass('active');
		actual_ele.addClass('active');
	});
	$("button.retirer").click(function() {
		if (confirm("Êtes-vous certain de vouloir supprimer cette série de votre liste ?")) {
			var actual_ele = $(this).closest(liste);
			$.post('post/liste_fonctions.php',{action: 'retirer', id_delete: actual_ele.data('id')}, function() {
				location.reload();
			});
		}
	});
	$("#tout_supprimer").click(function(event) {
		event.preventDefault();
		if (confirm("Êtes-vous certain de vouloir tout retirer ?")) {
			$.post('post/liste_fonctions.php',{action: 'initialiser'},function() {
				location.reload();
			});
		}
	});
	liste.mousedown(function() {
		$(this).addClass("grabbing");
	}).mouseup(function() {
		$(this).removeClass("grabbing");
	});

//ENVOYER MAIL
	CKEDITOR.replace('my_message', {language: 'fr', uiColor: '#EFEFEF'});
	
	$("#annuler_mail").click(function(event) {
		event.preventDefault();
		$("#div_mail_1").removeClass('active');
	});
	$("#envoyer_mail").click(function(event) {
		event.preventDefault();
		$("#div_mail_1").addClass('active');
	});
	
});
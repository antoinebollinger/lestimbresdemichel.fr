$(window).load(function() {

//NAVIGATION GENERALE

	var is_iPad = navigator.userAgent.match(/iPad/i) != null;
	var is_iPhone = navigator.userAgent.match(/iPhone/i) != null;	
	var mainItems = $("#main_main").children().length,
	menuItems = $("nav").find("li"),
	id_header,curId,
	headerItems = $("#main_main_0 h1").length;
	if (id_item != '') {item_click(id_item);}
	setTimeout(function() {
		$('body').removeClass('initiate ending');
	}, 200);
	if (id_page != 'collection') {
		if ($("#menu_icon").css("display") == 'none') {	
			activeNav(getId());
			bgPercent(scrollPercent());
		} else {
			activeNav_mobile();
		}
	}
	$(window).scroll(function(){
		if (id_page != 'collection') {
			if ($("#menu_icon").css("display") == 'none') {
				activeNav(getId());
				bgPercent(scrollPercent());
			} else {
				$('body').css('height','100%');
				activeNav_mobile();
			}
		}
	});
	if (!is_iPhone) {
		$("body").prepend('<div id="top_bottom" class="left"><div id="left_div" alt="Précédent" title="Précédent"></div><div id="top_div" alt="Haut de page" title="Haut de page"></div><div id="right_div" alt="Suivant" title="Suivant"></div><div id="bottom_div" alt="Bas de page" title="Bas de page"></div><div id="center_div"></div><div style="clear:both;"></div></div>'); 
		$("#top_div").click(function(){
			$('html, body').animate({scrollTop:0},400);
			return false;
		}); 
		$("#bottom_div").click(function(){
			$('html, body').animate({scrollTop:$(document).height()},400);
			return false;
		}); 
		$("#left_div").click(function(){
			var id;
			$("#main_main").children().each(function(index) {
				if ($(this).hasClass('active')) {id = index;}
			});
			item_click(id-1);
		}); 
		$("#right_div").click(function(){
			if (!$(this).hasClass('inactive')) {
				var id;
				$("#main_main").children().each(function(index) {
					if ($(this).hasClass('active')) {id = index;}
				});
				item_click(id+1);
			}
		});	
	}
	menuItems.click(function(e){
		if (id_page != 'collection') {
			if ($(this).attr('id') != "main_nav_li_seach") {
				e.preventDefault();
				item_click(parseInt($(this).attr("id").substring(12)));
			}
		}
	});
	$("#acces_suite_a").click(function() {
		item_click(1);
	});
	$(".main_arrow_right i, .main_arrow_left i, a.a_arrow").click(function(e){
		e.preventDefault();
		item_click($(this).attr('alt'));
	});
	$("body a").not(".boutons").click(function(event) {
		get_to($(this).attr('href'),event,$(this).hasClass('active'),$(this).hasClass('a_nav'));
	});

//LIENS NAVIGATION GENERALE

	$("#link_cgu").click(function(event) {
		event.preventDefault();
		$("body").addClass("bloc");
		$("#div_cgu_1").addClass('active');
	});
	$("#fermer_cgu").click(function(event) {
		event.preventDefault();
		$("body").removeClass("bloc");
		$("#div_cgu_1").removeClass('active');
	});
	$("#div_cgu_1").click(function(event) {
		if (event.target.id == 'div_cgu_1') {
			$("body").removeClass("bloc");
			$(this).removeClass('active');
		}
	});
	$("#acces_menu").click(function() {
		if (!$('body').hasClass('active') || ($('body').hasClass('active') && $('body').hasClass('collection'))) {
			if ($(this).hasClass('active')) {
				$(this).removeClass('active');
				if ($('body').hasClass('collection')) {
					//$('nav').css({'top':'-55px'});
				} else {
					$("nav").attr('style','');
				}
			} else {
				$(this).addClass('active');
				$('nav').css({'top':0,'opacity':1});
			}
		}
	});
	
//NAVIGATION MOBILE

	$("#menu_icon").click(function() {
		if ($("#nav_nav").hasClass("active")) {
			$(this).removeClass('active');
			$("#nav_nav").removeClass("active"); 
		} else {
			$(this).addClass('active');
			$("#nav_nav").addClass("active"); 
		} 
	}); 
	$("#nav_nav").click(function(evt) {
		if (evt.target.nodeName != "UL") {
			if ($(this).hasClass("active")) {
				$(this).removeClass("active"); 
				$("#menu_icon").removeClass("active");
			} else {
				$(this).addClass("active");
				$("#menu_icon").addClass("active");
			} 
		}
	});
	$("#acces_collect a.boutons").click(function(event) {
		event.preventDefault();
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
			$('.main_collec_left').removeClass('active');			
		} else {
			$(this).addClass('active');
			$('.main_collec_left').addClass('active');
		}
	});	

//FONCTIONS

	function get_to(url,event,active,a_nav) {
		if (!a_nav || (a_nav && id_page == 'collection')) {
			event.preventDefault();
			if (!active) {
				$('body').animate({'opacity':'0'},100,function() {
					document.location = url;
				});		
			}
		}
	}
	function item_click(target_id) {
		if ($("#menu_icon").css("display") == 'none') {
			var offsetTop = target_id*$("html").height()+((target_id == 0) ? 0 : 50);
		} else {
			var offsetTop = $("#main_main_"+target_id).offset().top-((target_id == 2) ? 50 : 0);
		}
		$('html, body').stop().animate({scrollTop: offsetTop}, 300);
	}	
	function getId() {
		var id = parseInt(mainItems - (((mainItems*$("html").height())-($(window).scrollTop()))/ $("html").height()));
		return id;
	}
	function activeNav(id) {
		$("#right_div").removeClass('inactive');
		if (id > 0) {
			$("footer").addClass('active');
			$("nav").attr('style','').addClass('active');
			$("#acces_menu").removeClass('active');
			$("body").addClass('active');
			if ($("#top_bottom").css('opacity') != 1) {
				$("#top_bottom").removeClass('top_bottom_hide').addClass('top_bottom_show');
			}		
			if (id == mainItems-1) {$("#right_div").addClass('inactive');}			
		} else {
			$("footer").removeClass('active');
			$("nav").removeClass('active');
			$("body").removeClass('active');
			if ($("#top_bottom").css('opacity') != 0) {
				$("#top_bottom").removeClass('top_bottom_show').addClass('top_bottom_hide');
			}
		}
		$("nav li").removeClass('active');
		$("#main_nav_li_"+id).addClass('active');
		for (var i=0; i<mainItems; i++) {
			if (i < id) {$("#main_main_"+i).removeClass('active').addClass('active_out');}
			if (i == id) {$("#main_main_"+i).removeClass('active_out').addClass('active');}
			if (i > id) {$("#main_main_"+i).removeClass('active active_out');}
		}
	}
	function activeNav_mobile() {
		$(".main_main").each(function() {
			var scrollId = $(this).offset().top-$(window).scrollTop()-50;
			if (scrollId <= 0) {
				curId = $(this).attr("id").substring(10);
			}
		});
		if ($(window).scrollTop() > 0) {
			$("footer").addClass('active');
			$("body").addClass('active');
		} else {
			$("footer").removeClass('active');
			$("body").removeClass('active');
		}
		$("nav li").removeClass('active');
		$("#main_nav_li_"+curId).addClass('active');		
	}
	function scrollPercent() {
		if ($('body').css('height') != ((mainItems*100)+20)+'%') {$('body').css('height',((mainItems*100)+20)+'%');}
		percent = ((100/($("body").height()-$("html").height()))*($(window).scrollTop()));
		return percent;
	}
	function bgPercent(percent) {
		if (is_iPad || is_iPhone) {
			$("body").addClass("unactive");
		} else {
			$('body').css('background-position-y',percent+'%');
			//setTimeout(function() {$('body').animate({'background-position-y':percent+'%'},50,'swing')},50);			
		}
	}
	
//MAIL

	if (id_page == 'index') {
		CKEDITOR.replace('textarea_message', {language: 'fr', uiColor: '#EFEFEF'});
	}
	$("#envoyer_email").click(function() {
		var mon_message = CKEDITOR.instances.textarea_message.getData();
		if (mon_message != '') {
			envoi_mail(mon_message);
		} else {
			alert("Votre message est vide, il n'est pas envoyé.");
		}
	});
	function envoi_mail(mon_message) {
		$("#div_patientez").addClass("active");
		var posting = $.post('post/mail.php', {
		nom: $("#input_nom").val(),
		email: $("#input_email").val(),
		sujet: $("#input_sujet").val(),
		message: mon_message}, 
		function(data) {
			if (data.envoye == 'oui') {
				alert('Votre message a bien été envoyé, nous nous efforcerons de vous répondre dans les plus brefs délais.');
				location.reload();
			}
			$("#div_patientez").removeClass("active");
		}, "json" );		
	};	
});
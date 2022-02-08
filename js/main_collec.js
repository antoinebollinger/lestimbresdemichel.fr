$(window).load(function () {

	//FILTRES RECHERCHE

	$("#main_collec_left").on("keypress", "#text_search_sub", function (event) {
		var key = event.which || event.keyCode;
		if (key == 13) {
			$('#form_gen').submit();
		}
	});
	$("#main_collec_left").on("keyup change", "#text_search_sub", function () {
		//update_results();
	});
	$("#delete_search").click(function () {
		if ($("#text_search_sub").val() != '') {
			//update_results();
		}
		$("#text_search_sub").val('').focus();
	});
	$("#main_filters").on("change", "select.auto, input[type=radio]", function () {
		$('#form_gen').submit();
	});
	$("#main_filters").on("change", "#periode_select select", function () {
		periode_change($(this).find("option:selected").val())
	});
	periode_change($("#periode_select select").find("option:selected").val())
	$("#main_filters").on("keypress", "#periode_annee_1 input, #periode_annee_2 input", function (event) {
		var key = event.which || event.keyCode;
		if (key == 13) {
			if ($("#periode_select select").find("option:selected").val() == 'entre' && ($("#periode_annee_1 input").val() == '' || $("#periode_annee_2 input").val() == '')) {
				alert("Vous devez remplir les deux champs !");
			} else {
				$('#form_gen').submit();
			}
		}
	});
	$("#main_filters").on("click", "i.delete_filter", function () {
		var type_filter = $(this).parents("h5").data("type");
		switch (type_filter) {
			case "rech_gen_array":
				var rech = '', intru = $(this).parents("h5").text();
				$("#filtres_actifs h5[data-type='rech_gen_array']").each(function () {
					console.log($(this).text());
					if ($(this).text() != intru) {
						rech += $(this).text() + " ";
					}
				});
				rech = $.trim(rech);
				$("#form_gen_reset input#rech_gen").val(rech);
				break;
			case "rech_gen":
				$("#form_gen_reset input#rech_gen").val('');
				break;
			case "periode":
				$("#form_gen_reset input#type_annee").val('');
				$("#form_gen_reset input#annee_1").val('');
				$("#form_gen_reset input#annee_2").val('');
				break;
			default:
				$("#form_gen_reset input#" + type_filter).val('');
				break;
		}
		//update_results();
		$("#form_gen_reset").submit();
	});

	//GESTIONS AFFICHAGE & RESULTATS 

	$("#main_collec_results").on("click", "#list_grid i", function () {
		var ele = $(this);
		if (!ele.hasClass('active')) {
			$.post('collection.php', { list_grid: ele.attr('alt') }, function () {
				$("#list_grid i").removeClass('active');
				ele.addClass('active');
				$(".main_collec_right").removeClass('grid list').addClass(ele.attr('alt'));
				$("#results_list_grid").text((ele.attr('alt') == 'list') ? 'liste' : 'grille');
				rezise_id_timbre();
			});
		}
	});
	$("#main_collec_results").on("change", "#nb_par_page_select", function (event) {
		if ($(this).find("option:selected").val() != nb_par_page) {
			$("#nb_par_page_input").val($(this).find("option:selected").val());
			$('#form_gen').submit();
		}
	});
	$("#main_collec_results").on("keypress", "#change_page", function (event) {
		if (event.which == 13 && $(this).val() != cur_page && parseFloat($(this).val()) == parseInt($(this).val()) && !isNaN($(this).val()) && $(this).val() <= nombreDePages && $(this).val() >= 1) {
			$("#page_input").val($(this).val());
			$('#form_gen').submit();
		}
	});
	$("#main_collec_results").on("click", "i.nav", function () {
		if ($(this).attr('alt') != cur_page) {
			$("#page_input").val($(this).attr('alt'));
			$('#form_gen').submit();
		}
	});
	function periode_change(type_annee) {
		switch (type_annee) {
			case "entre":
				$("#periode_annee_2").addClass("active");
				break;
			default:
				$("#periode_annee_2").removeClass("active");
				$("#periode_annee_2 input").val('');
				break;
		}
	}
	function update_results() {
		$("#div_patientez").addClass("active");
		var post_data = {
			rech_gen: $("#text_search_sub").val(),
			page: '1',
			pays: $("#pays_select option:selected").val(),
			annee: $("#annee_select option:selected").val(),
			theme: $("#theme_select option:selected").val(),
			type: $("#type_select option:selected").val(),
			nb_par_page: $("#nb_par_page").val()
		}
		$.get('collection.php', post_data, function (data) {
			var new_page = $(data);
			$("#main_collec_right").empty().html(new_page.find("#main_collec_right").html());
			$("#main_collec_results").empty().html(new_page.find("#main_collec_results").html());
			$("#details").empty().html(new_page.find("#details").html());
			$("#main_filters").empty().html(new_page.find("#main_filters").html());
			$("#div_patientez").removeClass("active");
		}, 'html');
	}

	//AJOUTER/RETIRER A LA LISTE

	// $("#main_collec_right .id_timbre").draggable({
	// opacity: 0.7,
	// revert : 'invalid',
	// revertDuration: 100,
	// helper: "clone",
	// start: function(event,ui) {
	// enablebloc();
	// },
	// drag: function(event,ui) {

	// },
	// stop: function() {
	// disablebloc();
	// }
	// });

	$("body").on("click", "button.ajouter:enabled", function () {
		var id_a_ajouter = $(this).data('id');
		$.post('post/present_liste.php', { id: id_a_ajouter }, function (data) {
			if (data.present_liste == 'oui') {
				if (confirm("Cette série est déjà dans votre liste. Souhaitez-vous l'ajouter à nouveau ? Nous vous conseillons de vous rendre sur votre liste d'intérêt et de modifier la série")) {
					launch_ajout(id_a_ajouter);
				}
			} else {
				launch_ajout(id_a_ajouter);
			}
		}, 'json');
	});
	$("body").on("click", "button.retirer:enabled", function () {
		if (confirm("Êtes-vous certain de vouloir supprimer cette série de votre liste ?")) {
			var id_a_suppr = $(this).data('id');
			$.post('post/liste_fonctions.php', { action: 'retirer', id_delete: id_a_suppr }, function () {
				$.post('post/nb_liste.php', {}, function (data) {
					$("#p_direct_liste span").text(data.nb_liste);
					$("#vigne_" + id_a_suppr + " button.retirer, #slide_" + id_a_suppr + " button.retirer").removeClass("active").addClass("unactive");
					$("#vigne_" + id_a_suppr + " button.ajouter, #slide_" + id_a_suppr + " button.ajouter").removeClass("unactive").addClass("active");
					$("#vigne_" + id_a_suppr + ", #slide_" + id_a_suppr + " .card").removeClass('present');
				}, 'json');
			});
		}
	});
	$("#ajout_serie_form :radio").change(function () {
		var radio_checked = $("input[name='serie_timbre']:checked").val();
		if (radio_checked == 'timbre') {
			$("#choix_serie_3").attr('disabled', false);
			$("label[for='choix_serie_3']").removeClass('disabled');
		} else {
			$("#choix_serie_3").attr('disabled', true).val('');
			$("label[for='choix_serie_3']").addClass('disabled');
		}
	});
	$("#valider_ajout").click(function () {
		var nb = $("#ajout_serie_photo .sliderText").data('nb');
		var post_data = $("#ajout_serie_photo div.data").data();
		var id_a_ajouter = $("#ajout_serie_photo div.data").data('id_ajout');
		var radio_checked = $("input[name='serie_timbre']:checked").val();
		post_data['qte_ajout'] = 1;
		post_data['commentaire_ajout'] = (radio_checked == 'timbre') ? $("#choix_serie_3").val() : 'La série complète';
		post_data['action'] = 'ajouter';
		$.post('post/liste_fonctions.php', post_data, function () {
			$.post('post/nb_liste.php', {}, function (data) {
				$("#p_direct_liste span").text(data.nb_liste);
				$("#vigne_" + id_a_ajouter + " button.retirer, #slide_" + id_a_ajouter + " button.retirer").removeClass("unactive").addClass("active");
				$("#vigne_" + id_a_ajouter + " button.ajouter, #slide_" + id_a_ajouter + " button.ajouter").removeClass("active").addClass("unactive");
				$("#vigne_" + id_a_ajouter + ", #slide_" + id_a_ajouter + " .card").addClass('present');
				$("#div_ajout_serie_in").animate({ opacity: 0 }, 200, function () {
					$("#div_ajout_serie_in").css({ display: 'none' });
					$("#div_ajout_serie_out").css({ display: 'block' });
					$("#div_ajout_serie_out").animate({ opacity: 1 }, 200, function () {
					});
				});
			}, 'json');
		});
	});
	$("button.fermer_ajout").click(function () {
		setTimeout(close_ajout, 100);
	});
	function launch_ajout(id) {
		$("#ajout_serie_photo").empty().append($("#slide_" + id + " .card div").html());
		setTimeout(light_div, 100);
		function light_div() {
			enablebloc();
			$("#div_ajout_serie_1").addClass("active");
		}
	}
	function close_ajout() {
		$("#div_ajout_serie_in").css({ opacity: 1, display: 'block' });
		$("#div_ajout_serie_out").css({ opacity: 0, display: 'none' });
		$("#choix_serie_1").attr('checked', true);
		$("#choix_serie_3").attr('disabled', true).val('');
		$("label[for='choix_serie_3']").addClass('disabled');
		$("#div_ajout_serie_1").removeClass("active");
		if (!$("#details").hasClass("active")) { disablebloc(); }
	}

	//MODE DIAPO	

	var swiper, magnify, swiper_id, liste = $(".id_timbre");
	$("body").on("click", ".launch_swiper", function (event) {
		event.preventDefault();
		swiper_id = $(this).data('id');
		setTimeout(light_swiper, 100);
	});
	$("#details .swiper-slide").mousedown(function () { $(this).addClass("grabbing"); }).mouseup(function () { $(this).removeClass("grabbing"); });
	$("#details").click(function (event) {
		if (event.target.id == this.id || event.target.classList.contains('swiper-container')) { details_close(); }
	});
	$(document).click(function (event) {
		if (!$("#div_ajout_serie_1").hasClass('active') && !$("#details").hasClass('active')) {
			$(".id_timbre").removeClass('active');
			$(event.target).closest(liste).addClass('active');
		}
	});
	liste.mousedown(function () {
		$(this).addClass("grabbing");
	}).mouseup(function () {
		$(this).removeClass("grabbing");
	});
	function light_swiper() {
		swiper = new Swiper('.swiper-container', {
			initialSlide: swiper_id,
			effect: 'coverflow',
			grabCursor: true,
			centeredSlides: true,
			slidesPerView: 'auto',
			coverflowEffect: {
				rotate: 30,
				stretch: 0,
				depth: 200,
				modifier: 1,
				slideShadows: true,
			},
			pagination: {
				el: '.swiper-pagination',
				type: 'fraction'
			},
			keyboard: {
				enabled: true,
				onyInViewport: false,
			},
			on: {
				init: function () {
					magnify = $(".swiper-slide-active .sliderText img").magnify();
				},
				slideChange: function () {
					magnify.destroy();
					magnify = $($(".swiper-slide").get(swiper.activeIndex)).find(".sliderText img").magnify();
				},
			},
		});
		enablebloc();
		$("#details").addClass('active').animate({ opacity: 1 }, function () {
			$("#details").addClass('active');
		});
	}
	function details_close() {
		$("#details").animate({ opacity: 0 }, 200, 'linear', function () {
			magnify.destroy();
			swiper.destroy();
			disablebloc();
			$("#details").removeClass('active');
		});
	}

	//MAP

	let map;
	if (carte == 'on') {
		launch_map(500);
	}
	$("#div_map_1").click(function (event) {
		if (event.target.id == 'div_map_1') {
			//close_map();
		}
	});
	$("#fermer_map").click(function (event) {
		event.preventDefault();
		close_map();
	});
	$("#show_map").click(function (event) {
		event.preventDefault();
		launch_map(400);
	});
	function launch_map(de) {
		enablebloc();
		$("#div_map_1").addClass('active');
		setTimeout(set_map, de);
		//setTimeout(map.updateSize(),200);
	}
	function set_map() {
		$("#div_map_3").append('<div id="div_map"></div>');
		map = new jvm.Map({
			container: $("#div_map"),
			map: 'world_mill',
			series: {
				regions: [{
					values: current_pays,
					scale: ['#D7A354', '#9F4A34'],
					normalizeFunction: 'polynomial'
				}]
			},
			backgroundColor: 'transparent',
			regionsSelectable: true,
			regionsSelectableOne: true,
			regionStyle: {
				initial: {
					fill: '#D6DCE0',
					"fill-opacity": 1,
					stroke: 'none',
					"stroke-width": 0,
					"stroke-opacity": 1
				},
				hover: {
					"fill-opacity": 0.8,
					cursor: 'pointer'
				},
				selected: {
					fill: '#338380'
				},
				selectedHover: {
				}
			},
			onRegionTipShow: function (e, el, code) {
				var libelle = '<b>' + map.getRegionName(code) + '</b> (' + code + ')' + ((current_pays[code] !== undefined) ? '<br/><em>Nb de séries :</em> ' + current_pays[code] : '') + ((current_content[code] != '' && current_content[code] !== undefined) ? '<br/><em>Concerne :</em><ul><li>' + current_content[code] + '</li></ul>' : '');
				el.html(libelle);
				if (UrlExists('css/images/drapeaux/' + code + '.png')) {
					el.css({ 'background-image': "url('css/images/drapeaux/" + code + ".png')" });
				}
			},
			onRegionClick: function (e, code) {
				map.clearSelectedRegions();
				var go = false;
				$.each(current_pays, function (key, value) {
					if (key == code) {
						map.clearSelectedRegions();
						map.setSelectedRegions([code]);
						go = true;
					} else {
						e.preventDefault();
					}
				});
				if (go) {
					$("#selected_map").html(map.getRegionName(code) + ' (' + code + ')');
					$("#valider_map button").prop("disabled", false);
					$("#valider_map").removeClass('active').attr("href", 'collection.php?page=1&rech_gen=&pays=&code=' + code + '&annee=&type_annee=&annee_1=&annee_2=&theme=&type=&nb_par_page=' + nb_par_page);
				}
			}
		});
		if (current_code != "") {
			$("#selected_map").html(map.getRegionName(current_code) + ' (' + current_code + ')');
			$("#valider_map button").prop("disabled", false);
			$("#valider_map").removeClass('active').attr("href", 'collection.php?page=1&rech_gen=&pays=&code=' + current_code + '&annee=&type_annee=&annee_1=&annee_2=&theme=&type=&nb_par_page=' + nb_par_page);
			map.setSelectedRegions([current_code]);
		}
	}
	function close_map() {
		disablebloc();
		$("#div_map_1").removeClass('active');
		$("#div_map").remove();
		$("#selected_map").html("<em>aucune</em>");
		$("#valider_map button").prop("disabled", true);
		$("#valider_map").addClass('active').attr("href", "#");
	}
	function UrlExists(url) {
		var http = new XMLHttpRequest();
		http.open('HEAD', url, false);
		http.send();
		return http.status != 404;
	}

	//RESIZING & BLOC BODY

	$(window).resize(function () {
		rezise_id_timbre();
	});
	rezise_id_timbre();
	function rezise_id_timbre() {
		var actual_width = $(window).width();
		if (actual_width > 650) {
			var new_width;
			if ($(".main_collec_right").hasClass('grid')) {
				switch (true) {
					case actual_width < 1200:
						new_width = '24%';
						break;
					case actual_width < 1500:
						new_width = '19%';
						break;
					case actual_width < 1700:
						new_width = '15.66%';
						break;
					default:
						new_width = '13.28%';
						break;
				}
			} else {
				new_width = '99%';
			}
			document.documentElement.style.setProperty('--grid-width', new_width);
		}
	}
	function enablebloc() {
		$('body').addClass('bloc');
	}
	function disablebloc() {
		$('body').removeClass('bloc');
	}
});
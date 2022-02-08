$(window).load(function() {

//HOME

	$("#menu_icon").click(function() {
		if ($("#main_pro_left").hasClass("active")) {
			$(this).removeClass('active');
			$("#main_pro_left").removeClass("active"); 
		} else {
			$(this).addClass('active');
			$("#main_pro_left").addClass("active"); 
		} 
	}); 
	$("#main_pro_left").click(function(evt) {
		if (evt.target.nodeName != "UL") {
			if ($(this).hasClass("active")) {
				$(this).removeClass("active"); 
				$("#main_pro_left").removeClass("active");
			} else {
				$(this).addClass("active");
				$("#main_pro_left").addClass("active");
			} 
		}
	});
	$("#deconnexion").click(function(event) {
		event.preventDefault();
		if(confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
			$("#div_patientez_loading h4").empty().text('Déconnexion en cours...');
			$("#div_patientez").addClass("active");
			setTimeout(deconnexion,500);
		}
	});
	function deconnexion() {
		$.post('session/deconnexion.php',{deconnect:'deconnect'})
		.done(function() {
			$("#div_patientez_loading h4").empty().text("A bientôt !");
			setTimeout(function() {location.reload();},500);
		})
		.fail(function() {
			alert("Une erreur est survenue, merci de retenter ultérieurement.\nSi le problème persiste, merci de contacter votre administrateur.\n\nLa page va être recharchée.");
			location.reload();
		});
	}

//SESSION

	//ID
	$("#modifier_id").on("click",function() {
		if ($("#valider_id").prop("disabled")) {
			$("#form_id input.modifiable").prop("disabled",false);
			$("#valider_id").prop("disabled",false);
			$(this).text("Annuler");
		} else {
			$("#form_id input.modifiable").prop("disabled",true);
			$("#valider_id").prop("disabled",true);
			$(this).text("Modifier");
			$("#form_id input.modifiable").each(function() {
				$(this).val($(this).data("value"));
			});
		}
	});
	$("#valider_id").on("click",function(event) {
		var ok_modif_id = false;
		$("#form_id input.modifiable").each(function() {
			if ($(this).val() != $(this).data("value")) {ok_modif_id = true;}
		});
		if (ok_modif_id) {
			var post_data = {};
			post_data['action'] = 'modifier_id';
			post_data['login'] = $("#input_login").val();
			var query = '', post_elements = {};
			$("#form_id input.modifiable").each(function() {
				query += $(this).attr("name")+"='"+$(this).val()+"', ";
				post_elements[$(this).attr("name")] = $(this).val();
			});
			post_data['query'] = query.substring(0,query.length - 2);
			if (!jQuery.isEmptyObject(post_elements)) {post_data['elements'] = JSON.stringify(post_elements);}
			$.post('post/modifier_tables',post_data,function(data) {
				if (data.done = "yep") {
					alert("Modifications réalisées avec succès");
				} else {
					alert("Un problème est survenu. Veuillez contacter votre administrateur.");
				}
				location.reload();
			},'json');
		} else {
			alert("Vous n'avez apporté aucun changement.");
		}
	});
	//MDP
	$("#modifier_mdp").on("click",function() {
		if ($("#valider_mdp").prop("disabled")) {
			$("#form_mdp input.modifiable").prop("disabled",false);
			$("#valider_mdp").prop("disabled",false);
			$("#div_form_mdp").removeClass("unactive");
			$(this).text("Annuler");
		} else {
			$("#form_mdp input.modifiable").prop("disabled",true);
			$("#valider_mdp").prop("disabled",true);
			$("#div_form_mdp").addClass("unactive");
			$(this).text("Modifier");
			$("#form_mdp input.modifiable").each(function() {
				$(this).val('');
			});
		}
	});	
	$("#valider_mdp").on("click",function(event) {
		var ok_modif_mdp = ($("#old_mdp").val() != '' && $("#new_mdp_1").val() != '' && $("#new_mdp_2").val() != '' && $("#new_mdp_1").val() == $("#new_mdp_2").val()) ? true : false ;
		if (ok_modif_mdp) {
			var post_data = {};
			post_data['action'] = 'modifier_mdp';
			post_data['login'] = $("#input_login").val();
			post_elements = {};
			$("#form_mdp input.modifiable").each(function() {
				post_elements[$(this).attr("name")] = $(this).val();
			});
			if (!jQuery.isEmptyObject(post_elements)) {post_data['elements'] = JSON.stringify(post_elements);}
			$.post('post/modifier_tables',post_data,function(data) {
				if (data.done = "yep") {
					alert("Modifications réalisées avec succès");
				} else {
					alert("Un problème est survenu. Veuillez verifier les données rentrées. Si le problème persiste, contactez votre administrateur.");
				}
				location.reload();
			},'json');
		} else {
			alert("Merci de remplir tous les champs.");
		}
	});	
	$("#old_mdp").on("keypress keyup keydown", function() {
		if ($(this).val() != "") {
			$(this).css("border-color","green");
		} else {
			$(this).css("border-color","red");				
		}
	});		
	$("#new_mdp_1").on("keypress keyup keydown", function() {
		var pass = $(this).val();
		$("#strength").text(checkPassStrength(pass));
		$("#strength").css("color",checkPassStrength_color(pass));
		$("#new_mdp_1").css("border-color",checkPassStrength_color(pass));		
	});
	$("#new_mdp_2").on("keypress keyup keydown", function() {
		if ($("#new_mdp_1").val() != "" && $(this).val() != "") {
			if ($(this).val() == $("#new_mdp_1").val()) {
				$(this).css("border-color","green");
			} else {
				$(this).css("border-color","red");				
			}
		}
	});		
	function scorePassword(pass) {
		var score = 0;
		if (!pass)
			return score;
		// award every unique letter until 5 repetitions
		var letters = new Object();
		for (var i=0; i<pass.length; i++) {
			letters[pass[i]] = (letters[pass[i]] || 0) + 1;
			score += 5.0 / letters[pass[i]];
		}
		// bonus points for mixing it up
		var variations = {
			digits: /\d/.test(pass),
			lower: /[a-z]/.test(pass),
			upper: /[A-Z]/.test(pass),
			nonWords: /\W/.test(pass),
		}
		variationCount = 0;
		for (var check in variations) {
			variationCount += (variations[check] == true) ? 1 : 0;
		}
		score += (variationCount - 1) * 10;
		return parseInt(score);
	}
	function checkPassStrength(pass) {
		var score = scorePassword(pass);
		if (score > 80)
			return "élevé";
		if (score > 60)
			return "moyen";
		if (score >= 30)
			return "faible";
		return "faible";
	}	
	function checkPassStrength_color(pass) {
		var score = scorePassword(pass);
		if (score > 80)
			return "green";
		if (score > 60)
			return "orange";
		if (score >= 30)
			return "red";
		return "red";
	}
	
//ETIQUETTES 

	$("#select_pays").on('change',function() {
		$("#form_pays").submit();
	});
	$("#select_code").on('change',function() {
		$("#form_pays").submit();
	});
	$("table#table_etiquettes tbody input").on('focus', function() {
		$("table#table_etiquettes tbody tr").removeClass("active");
		$(this).parents("tr").addClass("active");
	});

	$("#valider_etiquette").click(function(event) {
		event.preventDefault();
		var nbr = 0;
		var post_data = {};
		var post_elements = {};
		$("table input").each(function(index,ele) {
			if ($(ele).val() > 0) {
				post_elements[index] = {'id' : $(ele).attr('name'), 'qte' : $(ele).val()};
				nbr++;
			}
		});
		if (!jQuery.isEmptyObject(post_elements)) {post_data['ids'] = JSON.stringify(post_elements);}
		$.post('etiquettes.php',post_data,function(data) {
			$("#form_etiquettes").submit();
		});
	});
	
//TABLES GESTIONS AFFICHAGE 
	
	$("#main_pro_results").on("change", "#nb_par_page_select", function(event) {
		if ($(this).find("option:selected").val() != nb_par_page) {
			$("#nb_par_page_input").val($(this).find("option:selected").val());
			$('#form_gen').submit();
		}		
	});
	$("#main_pro_results").on("keypress", "#change_page", function(event) {
		if(event.which == 13 && $(this).val() != cur_page && parseFloat($(this).val()) == parseInt($(this).val()) && !isNaN($(this).val()) && $(this).val() <= nombreDePages && $(this).val() >= 1) {
			$("#page_input").val($(this).val());
			$('#form_gen').submit();
		}
	});
	$("#main_pro_results").on("click", "i.nav", function() {
		if ($(this).attr('alt') != cur_page) {
			$("#page_input").val($(this).attr('alt'));
			$('#form_gen').submit();
		}
	});

//AUTRE

	$("table.table_sql thead select").on("change", function() {
		if ($("#form_gen input[name='"+$(this).attr('name')+"']").length > 0) {
			$("#form_gen input[name='"+$(this).attr('name')+"']").val($(this).find("option:selected").val());
			
		} else {
			var ele = document.createElement("INPUT");
			ele.setAttribute("type","hidden");
			ele.setAttribute("name",$(this).attr('name'));
			ele.setAttribute("value",$(this).find("option:selected").val());
			$("#form_gen").prepend(ele);
		}
		$("#form_gen").submit();
	});
	var touchtime = 0, id_update = '', title_update = '', old_value = '';
	$("table.table_sql tbody td").on("click", function() {
		if ($(this).data('id')+$(this).data('title') != id_update+title_update) {
			initialisation();
			if (((new Date().getTime()) - touchtime) < 200) {
				var inp = $(this).find('input.input_table');
				if (inp.prop('disabled')) {
					$(this).parents('tr').addClass('active');
					inp.prop('disabled',false);
					clearSelection();
					old_value = inp.val();
					id_update = $(this).data('id');
					title_update = $(this).data('title');
				}
			}
			touchtime = new Date().getTime();
		}
	});
	$(document).on("click",function(event) {
		if (event.target.nodeName != 'INPUT') {
			initialisation();
		}
	});
	$("#form_update").submit(function(event) {
		event.preventDefault();
		initialisation();

	});
	function initialisation() {
		$("table.table_sql tbody tr").removeClass('active');
		$("table.table_sql input").prop("disabled",true);
		if (id_update != '' && title_update != '') {
			var new_value = $("table.table_sql tbody input#"+id_update+title_update).val();
			if (new_value != old_value) {
				update_table(id_update,title_update,old_value,new_value);
			}
		}
		id_update = '';
		title_update = '';
		old_value = '';
	}
	function update_table(id,title,old_val,new_val) {
		$("#div_patientez").addClass('active');
		var post_data = {action: 'modifier_table', post_table: cur_table, post_id: id, post_title: title, post_old_val: old_val, post_new_val: new_val};
		console.log(post_data);
		$.post('post/modifier_tables.php',post_data,function(data) {
			if (data.done != "yep") {
				alert("Un problème est survenu. Veuillez contacter votre administrateur.");
			}
			$("#div_patientez").removeClass('active');
			//location.reload();
		},'json');		
	}
	function clearSelection() {
		var sel;
		if ( (sel = document.selection) && sel.empty ) {
			sel.empty();
		} else {
			if (window.getSelection) {
				window.getSelection().removeAllRanges();
			}
			var activeEl = document.activeElement;
			if (activeEl) {
				var tagName = activeEl.nodeName.toLowerCase();
				if ( tagName == "textarea" ||
						(tagName == "input" && activeEl.type == "text") ) {
					// Collapse the selection to the end
					activeEl.selectionStart = activeEl.selectionEnd;
				}
			}
		}
	}
});
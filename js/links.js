function resetItemForm(){
	$("#msgSpan").remove();
	$("#item_uuid").val("");
	$("#item_label").val('');
	$("#item_link").val('');
	$("#item_level").val(0);
	$("#item_sort_order").val(0);
	$("#item_target").val('');
	$("#item_active").val('');
	$("#item_related_doc").val('');
	$("#item_type").val('');
	$("#item_query_string").val("");
	$( "#item_related_doc" ).html("");
	$( "#item_related_doc" ).combobox("destroy");
	$( "#item_related_doc" ).combobox();
	fetchLinkItems();
}
function savelinkitem(){
	$("#msgSpan").remove();
	var item_uuid=$("#item_uuid").val();
	var link_uuid=$("#GUID").val();
	var item_label=$("#item_label").val();
	var item_type=$("#item_type").val();
	var item_related_doc=$("#item_related_doc").val();
	var item_link=$("#item_link").val();
	var item_level=$("#item_level").val();
	var item_sort_order=$("#item_sort_order").val();
	var item_target=$("#item_target").val();
	var item_active=$("#item_active").val();
	var item_query_string=$("#item_query_string").val();
	
	if(link_uuid!="" && item_label!="" && (item_link!="" || item_related_doc!="")){
		$.ajax({
			type: "POST",
			url: "savelinkitem.php",
			dataType: "json",
			data: {'link_uuid' : link_uuid, 'item_uuid' : item_uuid, 'item_label' : item_label , 'item_type' : item_type , 'item_link' : item_link, 'item_level' : item_level, 'item_sort_order' : item_sort_order, 'item_target' : item_target, 'item_active' : item_active, 'item_related_doc' : item_related_doc, 'item_query_string' : item_query_string},
			success: function(response){
				if(response.success){
					$("#messageId").before('<span id="msgSpan" style="color:green;">'+response.success+'</span>');
					$('#myModal').modal('hide');
					resetItemForm();
				}else if(response.error){
					$("#messageId").before('<span id="msgSpan" style="color:#CC0000;">'+response.error+'</span>');
				}
			}
		});
	}else{
		$("#messageId").before('<span id="msgSpan" style="color:#CC0000;">Please add all the required fields!</span>');
	}	
}		
function change_url(ev){
		if($(ev).val()=="html"){
			$("#linkTargetDivID").hide();
			$("#urlSectionDoc").hide();
			$("#linkurlSection").show();
			$("#urlSectionLabel").html("Content");
			var input    = document.getElementById('link_url'),
        	textarea = document.createElement('textarea');
   			textarea.id    = input.id;
   			textarea.name    = input.id;
    		textarea.rows  = 5;
    		textarea.value = input.value;
    		input.parentNode.replaceChild(textarea, input);
		}else if($(ev).val()=="url"){
			//$("#itemsTable").hide();
			$("#urlSectionLabel").html("URL");
			var textarea    = document.getElementById('link_url'),
        	input = document.createElement('input');
   			input.id    = textarea.id;
   			input.name    = textarea.id;
   			input.value=textarea.value;
    		textarea.parentNode.replaceChild(input, textarea);
    		$("#urlSectionDoc").show();
    		$("#linkurlSection").show();
    		$("#linkTargetDivID").show();
    	}else if($(ev).val()=="items"){
    		$("#linkTargetDivID").hide();
			$("#itemsTable").show();
			$("#urlSectionDoc").hide();
			$("#linkurlSection").hide();
		}
		$("#link_url").addClass('span12');
	}
 	
 	function change_item_div(ev){
 		if($(ev).val()=="html"){
			$(".itemTargetDivClass").hide();
			$("#relatedLinkDocID").hide();
			$("#itemlinklabel").html("Content");
			var input    = document.getElementById('item_link'),
        	textarea = document.createElement('textarea');
   			textarea.id    = input.id;
   			textarea.name    = input.id;
    		textarea.rows  = 5;
    		textarea.value = input.value;
    		input.parentNode.replaceChild(textarea, input);
		}else if($(ev).val()=="url"){
			$(".itemTargetDivClass").show();
			$("#itemlinklabel").html("URL");
			var textarea    = document.getElementById('item_link'),
        	input = document.createElement('input');
   			input.id    = textarea.id;
   			input.name    = textarea.id;
   			input.value=textarea.value;
    		textarea.parentNode.replaceChild(input, textarea);
    		$("#relatedLinkDocID").show();
    	}
		$("#item_link").addClass('span5');
 	}
function remove_item(e){
	var link_uuid=$("#GUID").val();
	if(e!="" && link_uuid!=""){
		$.ajax({
			type: "POST",
			url: "deletelinkitem.php",
			dataType: "json",
			data: {'link_uuid' : link_uuid, 'uuid' : e},
			success: function(response){
				if(response.success){
					resetItemForm();
					$.prompt(response.success);
				}else if(response.error){
					$.prompt(response.error);
				}
			}
		});
	}else{
		$("#messageId").before('<span id="msgSpan" style="color:#CC0000;">Please add all the required fields!</span>');
	}	
}

function edit_item(e){
	var item=$("#GUID").val();
	if(item!="" && e!=""){
	$.getJSON("loadlinkitems.php?e="+item+"&uuid="+e,function(result){
		var htmlStr="";
		if(result.error){
			//console.log(result.error);
		}else{
			$.each(result.aaData, function(i,item){
				var itemTypeSelected="";
				if(item.GUID){
					$("#item_uuid").val(item.GUID);
				}
				if(item.item_type){
					$("#item_type").val(item.item_type);
					itemTypeSelected=item.item_type;
				}
				if(item.level){
					$("#item_level").val(item.level);
				}
				if(item.sort_order){
					$("#item_sort_order").val(item.sort_order);
				}
				if(item.source){
					$("#item_target").val(item.source);
				}
				if(item.item_label){
					$("#item_label").val(item.item_label);
				}
				if(item.item_link){
					$("#item_link").val(item.item_link);
				}
				if(item.item_active){
					$("#item_link").val(item.Status);
				}
				if(item.query){
					$("#item_query_string").val(item.query);
				}
				if(item.docID){
					if(item.doc){
						var optionStr="<option value='"+item.docID+"' selected>"+item.doc+"</option>";
						$("#item_related_doc").html(optionStr);
						$("#item_related_doc").combobox("destroy");
						$("#item_related_doc").combobox();
					}
					
				}
				$("select#item_type").val(itemTypeSelected).trigger('change');
				$('#myModal').modal('show'); 
			});
		}
	});
	}
}

function duplicate_items(){
	var selected='';
	$('.check').each(function(){
		if($(this).is(":checked")){
			if(selected==''){
				selected=$(this).val();
			}else{
				selected+=","+$(this).val();
			}
		}
	});
	
	if(selected!=""){
		$.ajax({
			type: "POST",
			url: "copylinkitem.php",
			dataType: "json",
			data: {'uuid' : selected},
			success: function(response){
				if(response.success){
					resetItemForm();
					$.prompt(response.success);
				}else if(response.error){
					$.prompt(response.error);
				}
			}
		});
	}else{
		$.prompt("Please select one or more items to create copies!");
	}
}

function fetchLinkItems() {
	var item=$("#GUID").val();
	if(item!=""){
	$('#link_items').html('');
	$.getJSON("loadlinkitems.php?e="+item,function(result){
		var htmlStr="";
		if(result.error){
			//console.log(result.error);
		}else{
			$("#duplicateItemsID").show();
			$.each(result.aaData, function(i,item){
				if(item.GUID){
					var sourceStr="Same page";
					if(item.source==0){
						sourceStr="New page";
					}
					var typeSTr=item.item_type;
					typeSTr=typeSTr.toUpperCase();
					htmlStr+='<tr><td><input type="checkbox" value="'+item.GUID+'" id="Select" class="check" name="Select"></td><td>'+item.item_label+'</td><td>'+typeSTr+'</td><td>'+item.item_link+'</td><td>'+item.doc+'</td><td>'+item.query+'</td><td>'+item.level+'</td><td>'+item.sort_order+'</td>';
					htmlStr+='<td>'+sourceStr+'</td><td>'+item.Status+'</td><td><a href="javascript:void(0)" title="Edit" onClick="edit_item(\''+item.GUID+'\')"><i class="splashy-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onClick="remove_item(\''+item.GUID+'\')" title="Remove"><i class="splashy-remove"></i></a></td></tr>';
				}
			});
		}
		$('#link_items').html(htmlStr);
	});
	}
}
$(function() {
	// toggle all checkboxes from a table when header checkbox is clicked
  	$("#rel_images th input:checkbox").click(function () {
  		$checks = $(this).closest("#rel_images").find("tbody input:checkbox");
  		if ($(this).is(":checked")) {
			$('#chk_all').prop("checked", true);
  			$checks.prop("checked", true);
  		} else {
			$('#chk_all').prop("checked", false);
  			$checks.prop("checked", false);
  		}  		
  	});
  	
	fetchLinkItems();
	$( "#docu" ).combobox();
	$( "#item_related_doc" ).combobox();
					get_check_short_url("");			
					//* regular validation
					gebo_validation.reg();
					
					
					$('#link_url').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=46) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							$.prompt("Allowed characters are A-Z, a-z, 0-9, _, -, .");
        					return false;
    					}
					
					});
					
					$('#short_url').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<65 || k>90) && (k<97 || k>122) && (k!=46) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							$.prompt("Allowed characters are a-z, 0-9, .");
        					return false;
    					}
					
					});
					
					$('#auto_generate').click(function(){
						var dataString = '';
						get_check_short_url(dataString);					
					});	
					
					
					$('#document').blur(function() {
						if($('#document').val()!=''){
						$.__contactSearch();
						}
					});
					$.__contactSearch = function() {
					var ID = '';
						var keyword = $("#document").val() ;
						var ids=$("#docu").val();
						if(ids){
							ID = ids;
							
						}
						
						$.ajax({
						  url: "search-documents.php",
						  data: 'keyword='+keyword + '&id=' +ID,
						  cache: false
						}).done(function( html ) {
						
							$("#docu").empty();
							$("#docu").append(html);
						});
						$('#docu').show();
					}
					
					$("#site_id_sel").change(function(){
						var site_id=$(this).val();
						$.ajax({
						type: "POST",
						url: "categories_list.php",
						data: {'site_id' : site_id, 'type' : 'page'},
						success: function(response){
						
							$("#cats").html(response);
						}
					 });
					 $( "#docu" ).empty();
					$( "#docu" ).append('<option value=""></option>');
					$('.custom-combobox-input').val('');
					});
					
				});
				
				function get_check_short_url(dataString){
					$.ajax({
						type: "POST",
						url: "get_or_check_short_url.php",
						data: dataString,
						dataType: "json",
						success: function(response){
							//console.log(response);
							$('#short_url').val(response.value);
							$('#unique_err').html(response.msg);
						}
					 });
				}
				
				//* validation
				gebo_validation = {
					
					reg: function() {
						reg_validator = $('.form_validation_reg').validate({
							onkeyup: false,
							errorClass: 'error',
							validClass: 'valid',
							highlight: function(element) {
								$(element).closest('div').addClass("f_error");
							},
							unhighlight: function(element) {
								$(element).closest('div').removeClass("f_error");
							},
							errorPlacement: function(error, element) {
								$(element).closest('div').append(error);
							},
							rules: {
								site_id: { required: true },
								link_label: { required: true },
								link_type: { required: true },
								short_url: { required: true },
								cats:  { required: true }
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
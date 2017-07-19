<script src="js/jquery.min.js"></script>
			<!-- smart resize event -->
			<script src="js/jquery.debouncedresize.min.js"></script>
			<!-- js cookie plugin -->
			<script src="js/jquery.cookie.min.js"></script>
			<!-- main bootstrap js -->
			<script src="bootstrap/js/bootstrap.min.js"></script>
			<!-- code prettifier -->
			<script src="lib/google-code-prettify/prettify.min.js"></script>
			<!-- tooltips -->
			<script src="lib/qtip2/jquery.qtip.min.js"></script>
			<!-- jBreadcrumbs -->
			<script src="lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
			<!-- sticky messages -->
            <script src="lib/sticky/sticky.min.js"></script>
            <!-- common functions -->
			<script src="js/gebo_common.js"></script>
			
			<!-- validation -->
            <script src="lib/validation/jquery.validate.min.js"></script>
            <script type="text/javascript" src="js/jquery-impromptu.js"></script>
            
			<script>
				function generate_code(chkd,name,code)
				{
					var status=document.getElementById(chkd).checked;
					if(status!=true){
					var val=document.getElementById(name).value;
					var patt=/[^A-Za-z0-9_-]/g;
					var result=val.replace(patt,' ');
					result=result.replace(/\s+/g, ' ');
					result = result.replace(/^\s+|\s+$/g,'');
					result=result.replace(/\s/g, '-');
					result=result.toLowerCase();
					//alert(result);	
					document.getElementById(code).value=result;
					}
				}
				function ask_for_keeping_login_session(){
					var statesdemo = {
						state0: {
							title: 'Do you want to logout of application?',
							html:'',
							buttons: { "I am still here": 1 , "Signout" : 0},																		
							submit:function(e,v,m,f){ 
								if(v==1) {
									set_timer();
									$.prompt.close();
								}else if(v==0){
									location.href = 'logout.php';
								}
							}
						},
						
					};
					$.prompt(statesdemo);
				}
				
				function set_timer(){
					setTimeout(function(){
						ask_for_keeping_login_session();
					}, 3600000);
				}
				
				$(document).ready(function() {
					set_timer();
					//* calculate sidebar height
					
					$.validator.setDefaults({
						ignore: ""
					});
					
					gebo_sidebar.make();
					//* show all elements & remove preloader
					setTimeout('$("html").removeClass("js")',1000);
					
					
					var url = window.location.pathname;
					var filename = url.substring(url.lastIndexOf('/')+1);
					if(filename=='home.php'){
						var maindiv = $('.accordion').children('.accordion-group').eq(0);						
						var div_head = maindiv.children('.accordion-heading');
						var div_body = maindiv.children('.accordion-body'); 
						div_body.addClass('in');
						div_head.addClass('sdb_h_active');
					}
					else{
					var filelink = $("a[href='"+filename+"']");
					//alert(filelink.html());
					if(!filelink.html()){
					
						var nav_li=$('.breadCrumb ul').children().eq(2);
						filename=nav_li.children('a').attr('href');
						//alert(filename);
						filelink = $("a[href='"+filename+"']");
					}
					var liparent = filelink.parents('li');
					var div_body = liparent.parents('.accordion-body');
					var div_parent = div_body.parent('.accordion-group');
					var div_head = div_parent.children('.accordion-heading');
					liparent.addClass('active');
					div_body.addClass('in');
					div_head.addClass('sdb_h_active');
					}
					
					$('.chng_db').click(function(){
						$("#connect_to").val($(this).html());
						
						$("#frm_db").submit();
					});
					
					//setup before functions
					var typingTimer;                //timer identifier
					var doneTypingInterval = 1000;  //time in ms, 5 second for example
					
					//on keyup, start the countdown
					$('#site_code').keyup(function(){
						clearTimeout(typingTimer);
						if ($('#site_code').val) {
							typingTimer = setTimeout(function(){
								var site_srch_keyword=$('#site_code').val();
								var dataString = 'site_srch_keyword='+site_srch_keyword;
								$.ajax({
									type: "POST",
									url: "load_sites.php",
									data: dataString,
									success: function(response)
									{
										$('#li_site_switch').html(response);
									}
								});							
							}, doneTypingInterval);
						}
					});	
					
					$('#frm_site_code').submit(function(event){
						event.preventDefault();
						$('#site_code').trigger('keyup');
					});	
					
					$('#srch_frm').validate({
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
								query: { required: true },
							},
							invalidHandler: function(form, validator) {
								//$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						});
 
					
				});
				
				function chng_site(switch_site_uuid){
					$("#switch_site").val(switch_site_uuid);
					$("#frm_site").submit();
				}
			</script>
			
			<!-- autocomplete dropdown-->
			<script src="js/jquery-ui-1.9.1.custom.js"></script>
			<script>
			var xhr;
			(function( $ ) {
			$.widget( "custom.combobox", {
				_create: function() {
				this.wrapper = $( "<span>" )
				.addClass( "custom-combobox" )
				.insertAfter( this.element );

				this.element.hide();
				this._createAutocomplete();
				this._createShowAllButton();
				},

				_createAutocomplete: function() {
				var ele_select= this.element;
				var selected = this.element.children( ":selected" ),
				value = selected.val() ? selected.text() : "";

				this.input = $( "<input>" )
				.appendTo( this.wrapper )
				.val( value )
				.attr( "title", "" )
				.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
				.autocomplete({
				delay: 0,
				minLength: 0,
				source: $.proxy( this, "_source" )
				})
				.tooltip({
				tooltipClass: "ui-state-highlight"
				});

				this._on( this.input, {
				autocompleteselect: function( event, ui ) {
				//alert("show all");
				ui.item.option.selected = true;

				this._trigger( "select", event, {
				  item: ui.item.option
				});
				
				ele_select.trigger('change');
					if(ele_select.attr('id')=='uk_towns_cities'){
						fetchuktownsdetails();
					}
				},

				autocompletechange: "_removeIfInvalid"
				});
				},

				_createShowAllButton: function() {
				var input = this.input,
				wasOpen = false;

				$( "<a>" )
				.attr( "tabIndex", -1 )
				.attr( "title", "Show All Items" )
				.tooltip()
				.appendTo( this.wrapper )
				.button({
				icons: {
				  primary: "ui-icon-triangle-1-s"
				},
				text: false
				})
				.removeClass( "ui-corner-all" )
				.addClass( "custom-combobox-toggle ui-corner-right" )
				.mousedown(function() {
				wasOpen = input.autocomplete( "widget" ).is( ":visible" );
				})
				.click(function() {
				input.focus();

				// Close if already visible
				if ( wasOpen ) {
				  return;
				}

				// Pass last search string as value to search for, displaying last results
				input.autocomplete( "search", 'SHOWALL' );
				});
				},

				_source: function( request, response ) {
				//var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				var ele_select= this.element;
				if(request.term=='SHOWALL'){
				response(ele_select.children( "option" ).map(function() {
				var text = $( this ).text();
				var value= $( this ).val();
				//if ( this.value && ( !request.term || matcher.test(text) ) )
				return {
				  label: text,
				  value: text,
				  option: this
				};
				}) );

				}
				else
				{
				var jsonRow = 'sites_list.php?srch='+request.term;
				
				if(ele_select.attr('id')=='docu' || ele_select.attr('id')=='cat_docu'  || ele_select.attr('id')=='item_related_doc'){
				var jsonRow = 'documents_list.php?srch='+request.term+'&site_id='+document.getElementsByName("site_id")[0].value;
				}
				else if(ele_select.attr('id')=='site_id_sel'){
				var jsonRow = 'sites_list.php?srch='+request.term;
				}
				else if(ele_select.attr('id')=='uk_towns_cities'){
				var jsonRow = 'uktownsautocomplete.php?term='+request.term;
				}

				//alert(jsonRowURLStr);
				if(xhr) xhr.abort();
				xhr=$.getJSON(jsonRow,function(result){

				if(result.error){
					var html='<option value=""></option>';
					ele_select.html(html);
					response(ele_select.children( "option" ).map(function() {
					var text = $( this ).text();
					var value= $( this ).val();
					//if ( this.value && ( !request.term || matcher.test(text) ) )
					return {
				  		label: text,
				  		value: text,
				  		option: this
					};
					}) );
				}else{
					var html='<option value=""></option>';

					$.each(result, function(i,item)
					{
						if(ele_select.attr('id')=='cat_docu') {
							html += '<option value="'+item.guid+'">'+item.value+'</option>';
						}else if(ele_select.attr('id')=='uk_towns_cities'){
							html += '<option value="'+item.value+'">'+item.name+'</option>';
						}
						else{
							html += '<option value="'+item.id+'">'+item.value+'</option>';
						}
					});
					ele_select.html(html);
					
					
					response(ele_select.children( "option" ).map(function() {
				var text = $( this ).text();
				var value= $( this ).val();
				//if ( this.value && ( !request.term || matcher.test(text) ) )
				return {
				  label: text,
				  value: text,
				  option: this
				};
				}) );
					
					
				}
				});

				} 

				},

				_removeIfInvalid: function( event, ui ) {

					// Selected an item, nothing to do
					if ( ui.item ) {

					return;
					}

					// Search for a match (case-insensitive)
					var value = this.input.val(),
					valueLowerCase = value.toLowerCase(),
					valid = false;
					var ele_select= this.element;
					this.element.children( "option" ).each(function() {
					if ( $( this ).text().toLowerCase() === valueLowerCase ) {
						this.selected = valid = true;	
						if(ele_select.attr('id')=='uk_towns_cities'){
							fetchuktownsdetails();
						}
						ele_select.trigger('change');

						return false;
					}
					});

					// Found a match, nothing to do
					if ( valid ) {
					return;
					}

					// Remove invalid value
					this.input
					.val( "" )
					.attr( "title", value + " didn't match any item" )
					.tooltip( "open" );
					this.element.val( "" );
					this._delay(function() {
					this.input.tooltip( "close" ).attr( "title", "" );
					}, 2500 );
					this.input.data( "ui-autocomplete" ).term = "";
				},

				_destroy: function() {
					this.wrapper.remove();
					this.element.show();
				}
			});
			})( jQuery );

			$(function() {
				$( "#site_id_sel" ).combobox();
			});

			</script>
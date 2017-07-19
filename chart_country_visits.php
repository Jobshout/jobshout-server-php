<?php 
session_start();
require_once("connect.php");
$curr_date=date('m/d/Y');
$start_date=date('m/d/Y', strtotime($curr_date . ' -30 days'));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Tenthmatrix Admin Panel</title>
    
        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
        <!-- gebo blue theme-->
            <link rel="stylesheet" href="css/blue.css" id="link_theme" />
        <!-- breadcrumbs-->
            <link rel="stylesheet" href="lib/jBreadcrumbs/css/BreadCrumb.css" />
        <!-- tooltips-->
            <link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <!-- colorbox -->
            <link rel="stylesheet" href="lib/colorbox/colorbox.css" />    
        <!-- code prettify -->
            <link rel="stylesheet" href="lib/google-code-prettify/prettify.css" />    
        <!-- notifications -->
            <link rel="stylesheet" href="lib/sticky/sticky.css" />    
        <!-- splashy icons -->
            <link rel="stylesheet" href="img/splashy/splashy.css" />
        <!-- main styles -->
            <link rel="stylesheet" href="css/style.css" />
			
            <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans" />
			
			 <link rel="stylesheet" href="lib/datepicker/datepicker.css" />
	
        <!-- Favicon -->
            <link rel="shortcut icon" href="favicon.ico" />
		
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/ie.css" />
        <![endif]-->
        	
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script src="lib/flot/excanvas.min.js"></script>
        <![endif]-->
		<script>
			//* hide all elements & show preloader
			document.getElementsByTagName('html')[0].className = 'js';
		</script>
    </head>
    <body>
				
		<div id="maincontainer" class="clearfix">
			<!-- header -->
            <header>
                <?php require_once('include/top-header.php');?>
            </header>
            
            <!-- main content -->
            <div id="contentwrapper">
                <div class="main_content">
				
				<div class="row-fluid">
						<!--<div class="span3 ">
				
					<div class="formSep">
					<div class="row-fluid">
						
							<label>Start Date <span class="f_req">*</span></label>

							<div class="input-append date" data-date-format="mm/dd/yyyy">
									<input class="input-small" placeholder="Start Date" type="text" readonly="readonly"  name="s_date" id="s_date" value="<?php echo $start_date; ?>"  /><span class="add-on"><i class="splashy-calendar_day"></i></span><span class="help-block">&nbsp;</span>
								</div>
	
						</div>
					</div>
						<div class="formSep">
						<div class="row-fluid">	
						
							<label>End Date <span class="f_req">*</span></label>
								
								<div class="input-append date" data-date-format="mm/dd/yyyy">
									<input class="input-small" placeholder="End Date" type="text" readonly="readonly"  name="e_date" id="e_date" value="<?php echo $curr_date; ?>"  /><span class="add-on"><i class="splashy-calendar_day"></i></span><span class="help-block">&nbsp;</span>
								</div>
		
					</div>
					</div>	
					
					<div class="formSep">
									<div class="row-fluid">
										
											<label>Site Search </label>
											<input type="text" class="span12" name="s_sites" id="s_sites" value="" />
										
											<label>Matched Sites </label>
											
											<select onChange="" name="arr_sites[]" id="arr_sites" multiple="multiple" size="5" >
												
											</select>
										
									</div>
								</div>
					
					<div class="form-actions">
									<button class="btn btn-gebo" type="button" name="submit" onClick="reload_chart()">Redraw Chart</button>
									
								</div>
					
					</div>-->
					
					
						<div class="span12">
							<h3 class="heading">Visitors by Country <small>last month</small></h3>
							<div id="fl_2" style="height:200px; float:left; width:60%;margin:50px auto 0"></div>
						</div>
						
					</div>
                  
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
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
			<!-- lightbox -->
            <script src="lib/colorbox/jquery.colorbox.min.js"></script>
            <!-- common functions -->
			<script src="js/gebo_common.js"></script>
			
			<script src="lib/jquery-ui/jquery-ui-1.8.20.custom.min.js"></script>
            <!-- touch events for jquery ui-->
            <script src="js/forms/jquery.ui.touch-punch.min.js"></script>
            <!-- multi-column layout -->
            <script src="js/jquery.imagesloaded.min.js"></script>
            <script src="js/jquery.wookmark.js"></script>
            <!-- responsive table -->
            <script src="js/jquery.mediaTable.min.js"></script>
            <!-- small charts -->
            <script src="js/jquery.peity.min.js"></script>
            <!-- charts -->
            <script src="lib/flot/jquery.flot.min.js"></script>
            <script src="lib/flot/jquery.flot.resize.min.js"></script>
            <script src="lib/flot/jquery.flot.pie.min.js"></script>
          
            <!-- sortable/filterable list -->
            <script src="lib/list_js/list.min.js"></script>
            <script src="lib/list_js/plugins/paging/list.paging.min.js"></script>
			
			<script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			
			<script>
			var data_arr;
			function get_data(s_date,e_date)
			{
				var dataString = 's_date='+s_date+'&e_date='+e_date;
				
				var ids=$("#arr_sites").val();
				if(ids){
					ID = ids.join();
					dataString += '&site_id='+ID;							
				}
								
				jsonRow = 'visitors_by_country.php?'+dataString;
		
				$.getJSON(jsonRow,function(result){
					if(result){				
						/*data_arr='[ ';
						$.each(result, function(i,item)
						{
							data_arr+='{ label: "'+ item.label +'", data:'+ item.data + '},'			
						});
						data_arr+=' ]';*/
						//alert(data_arr);
						data_arr=new Array();
						$.each(result, function(i,item)
						{
							var new_arr=new Array();
							new_arr['label']=item.label;
							new_arr['data']=Number(item.data);	
							data_arr.push(new_arr);	
						});
						
						
						load_chart();
					}
					else{
						$("#fl_2").html("NO DATA FOUND");
					}
				});
			}
			
			function reload_chart()
			{
				var s_date=$('#s_date').val();
				var e_date=$('#e_date').val();
				get_data(s_date,e_date);
			}
			
			
			function load_chart()
			{
				elem = $('#fl_2');
						
						
						var data = data_arr;
						
						// Setup the flot chart using our data
						$.plot(elem, data,         
							{
								label: "Visitors by Country",
								series: {
									pie: {
										show: true,
										innerRadius: 0.5,
										highlight: {
											opacity: 0.2
										}
									}
								},
								grid: {
									hoverable: true,
									clickable: true
								},
								
								legend: {
									show: true,
									noColumns:2,
									labelFormatter: function(label, series){
                       					 return '<div>'+label+' ('+parseFloat(series.percent).toFixed(2)+'%)</div>';
                   						 },
							
								}
								//colors: [ "#b3d3e8", "#8cbddd", "#65a6d1", "#3e8fc5", "#3073a0", "#245779", "#183b52" ]
								//colors: [ "#b4dbeb", "#8cc7e0", "#64b4d5", "#3ca0ca", "#2d83a6", "#22637e", "#174356", "#0c242e" ]
							}
						);
						// Create a tooltip on our chart
						elem.qtip({
							prerender: true,
							content: 'Loading...', // Use a loading message primarily
							position: {
								viewport: $(window), // Keep it visible within the window if possible
								target: 'mouse', // Position it in relation to the mouse
								adjust: { x: 7 } // ...but adjust it a bit so it doesn't overlap it.
							},
							show: false, // We'll show it programatically, so no show event is needed
							style: {
								classes: 'ui-tooltip-shadow ui-tooltip-tipsy',
								tip: false // Remove the default tip.
							}
						});
					 
						// Bind the plot hover
						elem.on('plothover', function(event, pos, obj) {
							
							// Grab the API reference
							var self = $(this),
								api = $(this).qtip(),
								previousPoint, content,
					 
							// Setup a visually pleasing rounding function
							round = function(x) { return Math.round(x * 1000) / 1000; };
					 
							// If we weren't passed the item object, hide the tooltip and remove cached point data
							if(!obj) {
								api.cache.point = false;
								return api.hide(event);
							}
					 
							// Proceed only if the data point has changed
							previousPoint = api.cache.point;
							if(previousPoint !== obj.seriesIndex)
							{
								percent = parseFloat(obj.series.percent).toFixed(2);
								
								// Update the cached point data
								api.cache.point = obj.seriesIndex;
								// Setup new content
								content = obj.series.label + ' ( ' + percent + '% )';
								// Update the tooltip content
								api.set('content.text', content);
								// Make sure we don't get problems with animations
								//api.elements.tooltip.stop(1, 1);
								// Show the tooltip, passing the coordinates
								api.show(pos);
							}
						});
					
			}
			
				$(document).ready(function() {
					//* calculate sidebar height
					gebo_sidebar.make();
					gebo_datepicker.init();
					//* show all elements & remove preloader
					setTimeout('$("html").removeClass("js")',1000);
					get_data('<?php echo $start_date; ?>', '<?php echo $curr_date; ?>');
					
					$('#s_sites').blur(function() {
						if($('#s_sites').val()!=''){
						$.__siteSearch();
						}
					});
					$.__siteSearch = function() {
					var ID = '';
						var keyword = $("#s_sites").val() ;
						var ids=$("#arr_sites").val();
						if(ids){
							ID = ids.join();							
						}
						
						$.ajax({
						  url: "search-sites.php",
						  data: 'keyword='+keyword + '&id=' +ID,
						  cache: false
						}).done(function( html ) {
						
							$("#arr_sites").empty();
							$("#arr_sites").append(html);
						});
						$('#arr_sites').show();
					}
					
					
				});
				//* charts
				
				gebo_datepicker = {
				init: function() {
					$('.date').datepicker();
				}
			};
				
			</script>
		
		</div>
	</body>
</html>
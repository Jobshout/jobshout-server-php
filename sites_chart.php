<?php 
session_start();
require_once("connect.php");
if($_SESSION['UserEmail'] =='') {
	header("location:index.php");
}
require_once('include/main-header.php'); 
?>


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
                    
                    <nav>
                         <div id="jCrumbs" class="breadCrumb module">
							<ul >
								<li>
									<a href="#"><i class="icon-home"></i></a>
								</li>
								
								<?php include_once("include/curr_selection.php"); ?>
								
							</ul>
							
						</div>
                    </nav>
                    
                   <?php
				   $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
				   ?>
                    <div class="row-fluid">
                        <div class="span12">
                          <h3 class="heading">Welcome to 
						  <?php if(isset($curr_site_name) && $curr_site_name!=''){
						  	echo "<strong><em>".$curr_site_name."</em></strong> ";
						  }
						  elseif($user->access_rights_code>=11) {
						  	echo "<strong><em>Jobshout</em></strong> ";
						  }
						  ?>Admin area.<br />
						   Please click on the links given in left column for desired action </h3>
                           
                        </div>
                    </div>
					<div class="row-fluid">
						<div class="span5 ">
							<h3 class="heading">Sites</small></h3>
							<div id="fl_2" style="height:200px;width:100%;margin:50px auto 0"></div>
						</div>
					</div>
                        
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
            <?php require_once('include/footer.php');?>
			<script src="lib/jquery-ui/jquery-ui-1.8.20.custom.min.js"></script>
            <!-- small charts -->
            <script src="js/jquery.peity.min.js"></script>
            <!-- charts -->
            <script src="lib/flot/jquery.flot.min.js"></script>
            <script src="lib/flot/jquery.flot.resize.min.js"></script>
            <script src="lib/flot/jquery.flot.pie.min.js"></script>
			
			<script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			
			<script>
			var data_arr;
			function get_sites_data()
			{
				jsonRow = 'returnSites.php';
		
				$.getJSON(jsonRow,function(result){
					if(result){				
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
			
			
			function load_chart()
			{
				elem = $('#fl_2');
						
						
						var data = data_arr;
						
						// Setup the flot chart using our data
						$.plot(elem, data,         
							{
								label: "Sites",
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
									noColumns:1,
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
					//* show all elements & remove preloader
					setTimeout('$("html").removeClass("js")',1000);
					get_sites_data();
				});
				
			</script>
            
		</div>
	</body>
</html>
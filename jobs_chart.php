<?php 
session_start();
require_once("connect.php");
$end_date=date('m/d/Y');
$last_year=date('m/d/Y', strtotime($end_date . ' -1 year'));

require_once('include/main-header.php'); ?>

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
				
				<div class="row-fluid">
						<div class="span3 ">
				<h3 class="heading">Select Site </h3>
					
					
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
					
					</div>
					
					
						<div class="span9">
							<h3 class="heading">Jobs <small>( <span id="time_period" ></span> )</small></h3>
							<span style="float:left">
							<button class="btn" onClick="draw_prev_chart()"  id="prev">&lt; Previous</button>
							<button class="btn" onClick="draw_next_chart()" disabled="disabled" id="next">Next &gt;</button>
							</span>
							
							<span style="float:right">
							<button class="btn" onClick="set_defaults(); draw_year_chart()" id="yearly">Yearly</button>
							<button class="btn" onClick="set_defaults(); draw_month_chart()" id="monthly">Monthly</button>
							<button class="btn" onClick="set_defaults(); draw_week_chart()" id="weekly">Weekly</button>
							</span>
							<br><br/>
							<div id="fl_2" style="height:200px;  width:80%;margin:50px auto 0"></div>
						</div>
						
					</div>
                  
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
             <?php require_once('include/footer.php');?>
            <!-- charts -->
            <script src="lib/flot/jquery.flot.min.js"></script>
            <script src="lib/flot/jquery.flot.resize.min.js"></script>
            <script src="lib/flot/jquery.flot.curvedLines.min.js"></script>
    		<script src="lib/flot/jquery.flot.orderBars.min.js"></script>
          
           
			
			
			
			<script>
			var mode='year', nav='prev';
			var tickSize=[];
			var end_date=new Date();
			var start_date=new Date();
			var curr_date=new Date(String(end_date.getMonth()+1)+'/'+String(end_date.getDate())+'/'+String(end_date.getFullYear()));
			var one_day=1000*60*60*24;
			
			function getMonthName(m) {
		if(m==1){ monthName= "January";}
		else if(m==2){ monthName= "February";}
		else if(m==3){ monthName= "March"; }
		else if(m==4){ monthName= "April"; }
		else if(m==5){ monthName= "May"; }
		else if(m==6){ monthName= "June"; }
		else if(m==7){ monthName= "July"; }
		else if(m==8){ monthName= "August"; }
		else if(m==9){ monthName= "September"; }
		else if(m==10){ monthName= "October"; }
		else if(m==11){ monthName= "November"; }
		else if(m==12){ monthName= "December"; }
		else{ monthName= "Null"; }
		return monthName;
     }
			
			function set_defaults() {
				nav='prev';
				end_date=new Date();
				start_date=new Date();
				$('#next').attr('disabled', true);				
			}
			
			function draw_year_chart(){

				if(nav=='prev') {
					var day=end_date.getDate();
					var month=end_date.getMonth()+1;
					var year=end_date.getFullYear();
					var s_date=String(month)+'/'+String(day)+'/'+String(year-1);
					var e_date=String(month)+'/'+String(day)+'/'+String(year);
				}
				else if(nav=='next') {
					var day=start_date.getDate();
					var month=start_date.getMonth()+1;
					var year=start_date.getFullYear();
					var s_date=String(month)+'/'+String(day)+'/'+String(year);
					var e_date=String(month)+'/'+String(day)+'/'+String(year+1);
				}
				mode='year';
				tickSize[0]=1;
				tickSize[1]="month";
				end_date=new Date(e_date);
				start_date=new Date(s_date);
				get_data(s_date,e_date);
				
				$('#yearly').attr('disabled', true);
				$('#monthly').attr('disabled', false);
				$('#weekly').attr('disabled', false);
				var title= String(start_date.getDate())+' '+getMonthName(start_date.getMonth()+1)+' '+String(start_date.getFullYear())+' - '+String(end_date.getDate())+' '+getMonthName(end_date.getMonth()+1)+' '+String(end_date.getFullYear());
				$('#time_period').html(title);
			}
			
			function draw_month_chart(){
				if(nav=='prev') {
					var day=end_date.getDate();
					var month=end_date.getMonth()+1;
					var year=end_date.getFullYear();
					var last_month=month-1;
					var last_year=year;
					if(last_month==0){
						last_month=12;
						last_year=year-1;
					}
					var s_date=String(last_month)+'/'+String(day)+'/'+String(last_year);
					var e_date=String(month)+'/'+String(day)+'/'+String(year);
				}
				else if(nav=='next') {
					var day=start_date.getDate();
					var month=start_date.getMonth()+1;
					var year=start_date.getFullYear();
					var next_month=month+1;
					var next_year=year;
					if(next_month==13){
						next_month=1;
						next_year=year+1;
					}
					var s_date=String(month)+'/'+String(day)+'/'+String(year);
					var e_date=String(next_month)+'/'+String(day)+'/'+String(next_year);
				}
				
				
				mode='month';
				tickSize[0]=4;
				tickSize[1]="day";
				end_date=new Date(e_date);
				start_date=new Date(s_date);
				get_data(s_date,e_date);
				
				$('#yearly').attr('disabled', false);
				$('#monthly').attr('disabled', true);
				$('#weekly').attr('disabled', false);
				var title= String(start_date.getDate())+' '+getMonthName(start_date.getMonth()+1)+' '+String(start_date.getFullYear())+' - '+String(end_date.getDate())+' '+getMonthName(end_date.getMonth()+1)+' '+String(end_date.getFullYear());
				$('#time_period').html(title);
			}
			
			function draw_week_chart(){
				
				if(nav=='prev') {
					var day=end_date.getDate();
					var month=end_date.getMonth()+1;
					var year=end_date.getFullYear();
					var week_start=new Date();
					week_start.setTime(end_date.getTime()-(7*one_day));										
					var s_date = String(week_start.getMonth()+1)+'/'+week_start.getDate()+'/'+week_start.getFullYear();
					var e_date=String(month)+'/'+String(day)+'/'+String(year);
				}
				else if(nav=='next') {
					var day=start_date.getDate();
					var month=start_date.getMonth()+1;
					var year=start_date.getFullYear();
					var week_end=new Date();
					week_end.setTime(end_date.getTime()+(7*one_day));										
					var s_date = String(month)+'/'+String(day)+'/'+String(year);
					var e_date=String(week_end.getMonth()+1)+'/'+week_end.getDate()+'/'+week_end.getFullYear();
				}
				
				mode='week';
				tickSize[0]=1;
				tickSize[1]="day";
				end_date=new Date(e_date);
				start_date=new Date(s_date);
				get_data(s_date,e_date);
				
				$('#yearly').attr('disabled', false);
				$('#monthly').attr('disabled', false);
				$('#weekly').attr('disabled', true);
				var title= String(start_date.getDate())+' '+getMonthName(start_date.getMonth()+1)+' '+String(start_date.getFullYear())+' - '+String(end_date.getDate())+' '+getMonthName(end_date.getMonth()+1)+' '+String(end_date.getFullYear());
				$('#time_period').html(title);
			}
			
			
			function draw_prev_chart(){
				nav='prev';
				end_date.setTime(start_date.getTime());
				if(mode=='year'){
					draw_year_chart();
				}
				if(mode=='month'){
					draw_month_chart();
				}
				if(mode=='week'){
					draw_week_chart();
				}
				$('#next').attr('disabled', false);
			}
			function draw_next_chart(){
				nav='next';
				start_date.setTime(end_date.getTime());
				if(mode=='year'){
					draw_year_chart();
				}
				if(mode=='month'){
					draw_month_chart();
				}
				if(mode=='week'){
					draw_week_chart();
				}
				if(end_date>=curr_date) {
					$('#next').attr('disabled', true);
				}
			}
			
			
			function get_data(s_date,e_date)
			{
				var dataString = 's_date='+s_date+'&e_date='+e_date+'&mode='+mode;
				
				var ids=$("#arr_sites").val();
				if(ids){
					ID = ids.join();
					dataString += '&site_id='+ID;							
				}
								
				jsonRow = 'active_inactive_jobs.php?'+dataString;
		
				$.getJSON(jsonRow,function(result){
					if(result){				
						var active_arr=new Array();
						var inactive_arr=new Array();
						$.each(result, function(i,item)
						{
							
							var new_date= new Date(item.date_time).getTime();
							var new_arr1=new Array();
							new_arr1[0]=new_date;
							new_arr1[1]=Number(item.active_jobs);	
							active_arr.push(new_arr1);
							
							var new_arr2=new Array();
							new_arr2[0]=new_date;
							new_arr2[1]=Number(item.inactive_jobs);	
							inactive_arr.push(new_arr2);
						});
						
						
						load_chart(active_arr, inactive_arr);
					}
					else{
						$("#fl_2").html("NO DATA FOUND");
					}
				});
			}
			
			function reload_chart()
			{
				var s_date=String(start_date.getMonth()+1)+'/'+String(start_date.getDate())+'/'+String(start_date.getFullYear());
				var e_date=String(end_date.getMonth()+1)+'/'+String(end_date.getDate())+'/'+String(end_date.getFullYear());
				get_data(s_date,e_date);
			}
			
			
			function load_chart(active_arr, inactive_arr)
			{
			
            // Setup the placeholder reference
            elem = $('#fl_2');

			var d1 = active_arr;			
			var d2 = inactive_arr;
			

			for (var i = 0; i < d1.length; ++i) {d1[i][0] += 60 * 120 * 1000};
			for (var i = 0; i < d2.length; ++i) {d2[i][0] += 60 * 120 * 1000};
			

			var options = {
				series: {
					curvedLines: { active: true }
				},
				/*yaxes: [
					{min: 0},
                    {position: "right"}
				],*/
				xaxis: {
					mode: "time",
					tickSize: tickSize,
					minTickSize: [1, "day"],
					autoscaleMargin: 0.10
					
				},
				grid: { hoverable: true },
				legend: { position: 'nw' },
				//colors: [ "#8cc7e0", "#3ca0ca" ]
			};

			// Setup the flot chart using our data
            fl_d_plot = $.plot(elem,
				[
					{ 	data: d1,
						label: "Active Jobs",
						curvedLines: {
                            active: true,
                            show: true,
                            lineWidth: 3
                        },
						//yaxis: 2,
						points: { show: true },
						stack: null
					},
					{   data: d2,
                        label: "Inactive Jobs",
                        curvedLines: {
                            active: true,
                            show: true,
                            lineWidth: 3
                        },
						//yaxis: 2,
						points: { show: true },
						stack: null
					},
					
				], options);
			
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
            elem.on('plothover', function(event, coords, item) {
                // Grab the API reference
                var self = $(this),
                    api = $(this).qtip(),
                    previousPoint, content,
         
                // Setup a visually pleasing rounding function
                round = function(x) { return Math.round(x * 1000) / 1000; };
         
                // If we weren't passed the item object, hide the tooltip and remove cached point data
                if(!item) {
                    api.cache.point = false;
                    return api.hide(event);
                }
				
                // Proceed only if the data point has changed
                previousPoint = api.cache.point;
                if(previousPoint !== item.seriesIndex)
                {
                    // Update the cached point data
                    api.cache.point = item.seriesIndex;
					
                    // Setup new content
                    content = item.series.label +': '+ round(item.datapoint[1]);
         
                    // Update the tooltip content
                    api.set('content.text', content);
         
                    // Make sure we don't get problems with animations
                    api.elements.tooltip.stop(1, 1);
         
                    // Show the tooltip, passing the coordinates
                    api.show(coords);
                }
            });
        }
			
				$(document).ready(function() {
					
					draw_year_chart();
					
					//setup before functions
					var typingTimer;                //timer identifier
					var doneTypingInterval = 1000;  //time in ms, 5 second for example
					
					//on keyup, start the countdown
					$('#s_sites').keyup(function(){
						clearTimeout(typingTimer);
						if ($('#s_sites').val) {
							typingTimer = setTimeout(function(){
								$.__siteSearch();
							}, doneTypingInterval);
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
				
				
				
				
			</script>
		
		</div>
	</body>
</html>
<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); ?>
<style>
.highlighttxt{
  background-color: #3993ba;border-radius: 4px;padding: 5px 4px;margin: 2px;color: #fff;                                                                                                     
 }
 .custom-combobox-input {
 	width: 150px;
 }
table{
  margin: 0 auto;
  width: 100%;
  clear: both;
  border-collapse: collapse;
  table-layout: fixed; 
  word-wrap:break-word;
}
#appendDomUI {
  display: inline-block;
  padding-left: 10px;
  float: right;
}
</style>

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
	<ul>
		<li>
			<a href="home.php"><i class="icon-home"></i></a>
		</li>
		<li>
			<a href="index.php">Dashboard</a>
		</li>
		<li>
			<a href="#">Job Applications</a>
		</li>
		<?php	if($user_access_level>1) {	?>
		<li>
			<a href="jobapp.php"> Add new Job Application</a>
		</li>
		<?php }?>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
					
					
                   <div id="validation" ><span style="color:#00CC00;font-size:18px">
                   <?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
					</span></div><br/>
                    <div class="row-fluid" style="margin-top:2px;">
						<div class="span12">
								<table class="table table-striped table-bordered" width="100%" id="job_apps">
										<thead>
											<tr>
												<th>ID</th>
												<th>Site Name</th>
												<th>Name</th>
												<th>Email</th>
												<th>Telephone</th>
												<th>Last Modified</th>
												<th>GUID</th>
												<th>Download CV</th>
												<th>CV Content</th>
												<?php if($user_access_level>1) { ?>
												<th>Edit</th>
												 <th>Delete</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
										
										<tr>
                                        <td class="dataTables_empty" colspan="10">Loading data from server</td>
										
                                    </tr>						
										</tbody>
												
										
									</table>
					  </div>
                    </div>
                        
                </div>
            </div>
												
					  
           <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
			<?php require_once('include/footer.php');?>
			<!-- datatable -->
            <script src="lib/datatables/jquery.dataTables.min.js"></script>
            <script src="lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
			<!-- datatable functions -->
            <script>
            var radiusNum='<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications_radius_search']) && isset($_SESSION['last_search']['jobapplications_radius_search']->radius)) { echo $_SESSION['last_search']['jobapplications_radius_search']->radius; } else { echo ''; } ?>';
            var locationStr='<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications_radius_search']) && isset($_SESSION['last_search']['jobapplications_radius_search']->location)) { echo $_SESSION['last_search']['jobapplications_radius_search']->location; } else { echo ''; } ?>';
            	
            function initialseDataTables () {
            	datatbles.job_apps();
            	$(".dataTables_filter input").attr("placeholder", "Search Candidates with Skills...");
            	//insert the select and some options
            	
            	var drawHtml='<div class="ui-widget" style="display: inline-block;">Location: <select id="location" placeholder="Enter location or postcode"></select></div>';
            	drawHtml+='<div style="display: inline-block;padding-left:20px;padding-right:10px;">with in Radius: <select style="width: 130px;" name="radius" id="radius" onChange="redrawTable();return false;"><option value="">--Select radius--</option>';
				drawHtml+='<option value="1">Within 1 Mile</option><option value="2">Within 2 Miles</option><option value="5">Within 5 Miles</option><option value="15">Within 15 Miles</option><option value="25">Within 25 Miles</option><option value="50">Within 50 Miles</option><option value="100">Within 100 Miles</option>';
				drawHtml+='</select></div><a style="display: inline-block;" href="javascript:void(0)" onClick="resetSearch(); return false;" class="btn" title="Reset Search"><i class="icon-refresh"></i></a><a style="display: inline-block;padding-left:10px;" href="javascript:void(0)" onClick="previewdisplayedData(); return false;" class="" title="Preview displayed applications"><img src="img/preview-icon.png" alt="Preview displayed"></a>';
				$select = $(drawHtml).appendTo('#appendDomUI');
				var radiusNum='<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications_radius_search']) && isset($_SESSION['last_search']['jobapplications_radius_search']->radius)) { echo $_SESSION['last_search']['jobapplications_radius_search']->radius; } else { echo ''; } ?>';
            	$('#radius').val(radiusNum);
            }
            function resetSearch (){
            	$("#radius").val('');
            	$("#location").val('');
            	radiusNum= ''; locationStr= '';
				$( "#location" ).combobox('destroy');
				$( "#location" ).combobox();	//initiale location dropdown
				$('#job_apps').DataTable().search('').draw();
            }
            function fetchLocations() 	{
            	var htmlStr='<option value=""></option>';
            	if(locationStr!="")	{
            		$.getJSON("uktownsautocomplete.php?term="+locationStr,function(result){
            			if(result.error){
							//
						}else {
							$.each(result, function(i,item){
								if(item.postcode!=""){
									htmlStr += '<option value="'+item.postcode+'" selected>'+item.name+'</option>';
								}
							});
						}
						initialseDataTables();	//initiale datatables
						$("#location").html(htmlStr);
						$( "#location" ).combobox();	//initiale location dropdown
					});
				}	else	{
					initialseDataTables();	//initialise datatables on load
					
					$.getJSON("uktownsautocomplete.php?forceDisplay=true",function(result){
            			if(result.error){
							//
						}else {
							$.each(result, function(i,item){
								if(item.postcode!=""){
									htmlStr += '<option value="'+item.postcode+'">'+item.name+'</option>';
								}
							});
						}
						$("#location").html(htmlStr);
						$( "#location" ).combobox();	//initiale location dropdown
					});
				}
			}
            function redrawTable()	{
				radiusNum=$("#radius").val();
				locationStr=$("#location").val();
				$('#job_apps').DataTable().ajax.reload();
            }
            function previewdisplayedData(){
            	var listCount=0;
            	$('.list_guids').each(function(){
            		if(listCount==0){
            			window.location.href= "jobapp.php?GUID="+$(this).val();
            		}
            		listCount++;
            	});
            }
            
				$(document).ready(function() {
					fetchLocations();
					
					//expire authenticate tokens
					$.getJSON("expire_auth_tokens.php",function(response){
						console.log(response);
					});
				});
				
				datatbles = {
					
					job_apps: function(){
						if($('#job_apps').length) {
							
							var oTable;
							oTable = $('#job_apps').dataTable( {
								<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications']) && isset($_SESSION['last_search']['jobapplications']['sSearch'])) { ?>
								"oSearch": {"sSearch": "<?php  echo $_SESSION['last_search']['jobapplications']['sSearch'];  ?>"},
								<?php } ?>
								"bProcessing": true,
								"bServerSide": true,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications']) && isset($_SESSION['last_search']['jobapplications']['iDisplayLength'])) { echo $_SESSION['last_search']['jobapplications']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications']) && isset($_SESSION['last_search']['jobapplications']['iDisplayStart'])) { echo $_SESSION['last_search']['jobapplications']['iDisplayStart']; } else { echo '0'; } ?>,
								
								//"iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150,"All"]],
								"sPaginationType": "bootstrap",
								"aaSorting": [[ 5, "desc" ]],
								"dom":'l<"#appendDomUI">frtip',
								//"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_jobapps.php",
								
									"fnServerParams": function ( aoData ) {
									<?php if(isset($_GET['job_guid']) && $_GET['job_guid']!='') { ?>
										aoData.push( { "name": "job_guid", "value": "<?php echo $_GET['job_guid']; ?>" } );
									<?php } ?>
									if(radiusNum!=""){
										aoData.push( { "name": "radius", "value": radiusNum } );
									}
									if(locationStr!="" || locationStr!=null){
										aoData.push( { "name": "location", "value": locationStr } );
									}
									},
								
								"aoColumnDefs": [
								<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 1 ] },
								<?php } ?>
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 6 ] },
						{  "bSortable":false,  "aTargets": [ 7 ] },
						<?php if($user_access_level>1) { ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 9 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 10 ] }
						<?php } ?>
                    ],
								"oLanguage": {
      "sInfoFiltered": ""
    }
							} );
							
							

						}
					}
				};
			</script>
           <script src="js/datatables.js"></script>
		</div>
	</body>
</html>
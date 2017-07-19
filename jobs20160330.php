<?php 
require_once("include/lib.inc.php");
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
	<ul>
		<li>
			<a href="home.php"><i class="icon-home"></i></a>
		</li>
		<li>
			<a href="index.php">Dashboard</a>
		</li>
		<li>
			<a href="#">Jobs</a>
		</li>
		 <?php
					//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user_access_level>1) {
					?>
		<li>
			<a href="job.php">Add new Job</a>
		</li>
		<?php }?>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                   
                   <div id="validation" ><span style="color:#00CC00;font-size:18px">
                   <?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
					</span></div><br/>
					
                     
                    <div class="row-fluid">
                        <div class="span12">
                            <div style="margin-bottom:10px; width:100%; text-align:right; ">
								<input type="checkbox" name="show_inactive" id="show_inactive" onChange="_show_inactive_jobs(this)" value="0"/>  Also Show Inactive Jobs
							</div>
							<div id="jobs_datatables"></div>
                        </div>
                    </div>
                        
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
			<?php require_once('include/footer.php');?>
			<!-- datatable -->
            <script src="lib/datatables/jquery.dataTables.min.js"></script>
            <script src="lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
			<!-- datatable functions -->
            <script>
			var status=false;
				$(document).ready(function() {
					var tab4=$("#jobs_datatables").html();
					if(tab4=='')
					{
					var dt_html='<table id="dt_e" class="table table-striped table-bordered"><thead><tr><th>ID</th><th>Site Name</th><th>Ref no.</th><th>Job title</th><th>Window title</th><th>Owner</th><th>GUID</th><th>Job Apps</th><th>Last modified</th><th>Posted</th><th>Status</th><th>Preview</th>';
					<?php if($user_access_level>1) { ?>
					dt_html+='<th>Edit</th><th>Delete</th>';
					<?php } ?>
					dt_html+='</tr></thead><tbody><tr><td class="dataTables_empty" colspan="8">Loading data from server</td></tr></tbody></table>';
					$("#jobs_datatables").html(dt_html);
					}
									
							var oTable = $('#dt_e').dataTable( {
								"oSearch": {"sSearch": "<?php if(isset($_GET['srch']) && $_GET['srch']!='') { echo $_GET['srch']; } elseif(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobs']) && isset($_SESSION['last_search']['jobs']['sSearch'])) { echo $_SESSION['last_search']['jobs']['sSearch']; } ?>"},
							
								"bProcessing": true,
								"bServerSide": true,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobs']) && isset($_SESSION['last_search']['jobs']['iDisplayLength'])) { echo $_SESSION['last_search']['jobs']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobs']) && isset($_SESSION['last_search']['jobs']['iDisplayStart'])) { echo $_SESSION['last_search']['jobs']['iDisplayStart']; } else { echo '0'; } ?>,
								
								//"iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 200, 350, 500, -1], [10, 25, 50, 100, 200, 350, 500,"All"]],
								"sPaginationType": "bootstrap",
								"aaSorting": [[8,'desc'],[9,'desc']],
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_jobs.php",
								"fnServerParams": function ( aoData ) {
									aoData.push( { "name": "status", "value": status } );
								<?php if(isset($_GET['cat_id']) && $_GET['cat_id']!='' && isset($_GET['cat_guid']) && $_GET['cat_guid']!='') { ?>
									aoData.push( { "name": "cat_id", "value": "<?php echo $_GET['cat_id']; ?>" } );
									aoData.push( { "name": "cat_guid", "value": "<?php echo $_GET['cat_guid']; ?>" } );
								<?php } ?>
								},
								"aoColumnDefs": [
								<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 1 ] },
								<?php } ?>
							{ "bSearchable": false, "bVisible": false, "aTargets": [ 6 ] },
							{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 7 ] },
							{ "bSearchable": false, "bSortable":false, "aTargets": [ 10 ] },
							{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 11 ] },
						<?php if($user_access_level>1) { ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 12 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 13 ] }
						<?php } ?>
                    ],
								//"aaSorting": [[1, 'asc']],
								"oLanguage": {
									  "sInfoFiltered": ""
									},
								
							} );
						});	
							

				function _show_inactive_jobs(id){
					if($(id).is(":checked")){
						status= true;
					}else{
						status= false;
					}
					// console.log(status);
					
					var tab4=$("#jobs_datatables").html('');
					var dt_html='<table id="dt_e" class="table table-striped table-bordered"><thead><tr><th>ID</th><th>Site Name</th><th>Ref no.</th><th>Job title</th><th>Window title</th><th>Owner</th><th>GUID</th><th>Job Apps</th><th>Last modified</th><th>Posted</th><th>Status</th><th>Preview</th>';
					<?php if($user_access_level>1) { ?>
					dt_html+='<th>Edit</th><th>Delete</th>';
					<?php } ?>
					dt_html+='</tr></thead><tbody><tr><td class="dataTables_empty" colspan="8">Loading data from server</td></tr></tbody></table>';
					$("#jobs_datatables").html(dt_html);
									
					var oTable = $('#dt_e').dataTable( {
						"oSearch": {"sSearch": "<?php if(isset($_GET['srch']) && $_GET['srch']!='') { echo $_GET['srch']; } elseif(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobs']) && isset($_SESSION['last_search']['jobs']['sSearch'])) { echo $_SESSION['last_search']['jobs']['sSearch']; } ?>"},
					
						"bProcessing": true,
						"bServerSide": true,
						"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobs']) && isset($_SESSION['last_search']['jobs']['iDisplayLength'])) { echo $_SESSION['last_search']['jobs']['iDisplayLength']; } else { echo '25'; } ?>,
						"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobs']) && isset($_SESSION['last_search']['jobs']['iDisplayStart'])) { echo $_SESSION['last_search']['jobs']['iDisplayStart']; } else { echo '0'; } ?>,
						
						"aLengthMenu": [[10, 25, 50, 100, 200, 350, 500, -1], [10, 25, 50, 100, 200, 350, 500,"All"]],
						"sPaginationType": "bootstrap",
						"aaSorting": [[8,'desc'],[9,'desc']],
						"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
						"sAjaxSource": "lib/datatables/server_jobs.php",
						
						"fnServerParams": function ( aoData ) {
							aoData.push( { "name": "status", "value": status } );
						<?php if(isset($_GET['cat_id']) && $_GET['cat_id']!='' && isset($_GET['cat_guid']) && $_GET['cat_guid']!='') { ?>
							aoData.push( { "name": "cat_id", "value": "<?php echo $_GET['cat_id']; ?>" } );
							aoData.push( { "name": "cat_guid", "value": "<?php echo $_GET['cat_guid']; ?>" } );
						<?php } ?>
						},
						
						"aoColumnDefs": [
						<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
							{ "bSearchable": false, "bVisible": false, "aTargets": [ 1 ] },
									<?php } ?>
								{ "bSearchable": false, "bVisible": false, "aTargets": [ 6 ] },
								{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 7 ] },
								{ "bSearchable": false, "bSortable":false, "aTargets": [ 10 ] },
								{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 11 ] },
							<?php if($user_access_level>1) { ?>
							{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 12 ] },
							{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 13 ] }
							<?php } ?>
							],
						//"aaSorting": [[1, 'asc']],
						"oLanguage": {
							  "sInfoFiltered": ""
							},
					} );
				}
			</script>
           <script src="js/datatables.js"></script>
		</div>
	</body>
</html>
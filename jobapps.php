<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); ?>
<style>
.highlighttxt{
  background-color: #3993ba;border-radius: 4px;padding: 5px 4px;margin: 2px;color: #fff;                                                                                                     
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
		<?php
					//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user_access_level>1) {
					?>
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
					
					
					
                    
                    <div class="row-fluid">
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
				$(document).ready(function() {
					datatbles.job_apps();
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
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_jobapps.php",
								<?php if(isset($_GET['job_guid']) && $_GET['job_guid']!='') { ?>
									"fnServerParams": function ( aoData ) {
										aoData.push( { "name": "job_guid", "value": "<?php echo $_GET['job_guid']; ?>" } );
									},
								<?php } ?>
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
<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); ?>
</head>
<body>

<style>
.highlighttxt{
	background-color: #3993ba;border-radius: 4px;padding: 5px 4px;margin: 2px;color: #fff;
}
</style>
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
												<li> <a href="home.php"><i class="icon-home"></i></a> </li>
												<li> <a href="index.php">Dashboard</a> </li>
												<li> <a href="#">Job Applications</a> </li>
												<?php
					//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user_access_level>1) {
					?>
												<li> <a href="jobapp.php"> Add new Job Application</a> </li>
												<?php }?>
												<?php include_once("include/curr_selection.php"); ?>
										</ul>
								</div>
						</nav>
						<div id="validation" ><span style="color:#00CC00;font-size:18px">
								<?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
								</span></div>
						<br/>
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
										<table class="table table-striped table-bordered dataTable no-footer" id="job_apps" role="grid" aria-describedby="job_apps_info" style="width: 100%;" width="100%">
												<thead>
														<tr role="row">
																<th class="sorting" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 71px;" aria-label="ID: activate to sort column ascending">ID</th>
																<th class="sorting_asc" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 230px;" aria-label="Name: activate to sort column ascending">Name</th>
																<th class="sorting" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 287px;" aria-label="Email: activate to sort column ascending">Email</th>
																<th class="sorting" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 146px;" aria-label="Telephone: activate to sort column ascending">Telephone</th>
																<th class="sorting_desc" tabindex="0" aria-controls="job_apps" rowspan="1" colspan="1" style="width: 193px;" aria-label="Last Modified: activate to sort column ascending" aria-sort="descending">Last Modified</th>
																<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 418px;" aria-label="Download CV">Download CV</th>
																<th class="center sorting_disabled" rowspan="1" colspan="1" style="width: 43px;" aria-label="Edit">Edit</th>
																<th class="center sorting_disabled" rowspan="1" colspan="1" style="width: 66px;" aria-label="Delete">Delete</th>
														</tr>
												</thead>
												<tbody>
														<tr role="row" class="odd">
																<td>132157</td>
																<td class="sorting_2">Tony Roberts</td>
																<td>jobs4tony.roberts@btinternet.com</td>
																<td>07872109017</td>
																<td class="sorting_1">19 Jul 2017,12:52 AM</td>
																<td>
Lorem Ipsum is simply dummy<span CLASS="highlighttxt" > text of the printing</span> and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has <span CLASS="highlighttxt" >survived not only</span> five centuries
</td>
																<td>
Lorem Ipsum is simply dummy text of the printing and typesetting industry.  <span CLASS="highlighttxt" >Lorem Ipsum has </span> been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and <span CLASS="highlighttxt" >scrambled it </span> to make a type specimen book. It hassurvived not only five centuries
</td>
																<td>
Lorem Ipsum is simply dummy<span CLASS="highlighttxt" > text of the printing</span> and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has <span CLASS="highlighttxt" >survived not only</span> five centuries
</td>
														</tr>
														<tr role="row" class="even">
																<td>132156</td>
																<td class="sorting_2">Andrew Booth</td>
																<td>andrew.booth@boothie.co.uk</td>
																<td>+447889510489</td>
																<td class="sorting_1">19 Jul 2017,12:48 AM</td>
																<td>&nbsp;</td>
																<td class=" center"><a href="jobapp.php?GUID=538F6629-D32A-6924-0EEF-7B14BEB462ED" title="Edit this job application"><i class="splashy-pencil"></i></a><a></a></td>
																<td class=" center"><a href="delete-jobapp.php?GUID=538F6629-D32A-6924-0EEF-7B14BEB462ED" title="Delete this job application" onclick="return confirm('Are you sure to delete');"><i class="splashy-remove"></i></a><a></a></td>
														</tr>
														<tr role="row" class="odd">
																<td>132155</td>
																<td class="sorting_2">Stephen Giles</td>
																<td>steve-giles1@hotmail.co.uk</td>
																<td>07843244008</td>
																<td class="sorting_1">19 Jul 2017,12:46 AM</td>
																<td>&nbsp;</td>
																<td class=" center"><a href="jobapp.php?GUID=222DC278-8A4B-DB2D-5EE1-D92C5F61F94E" title="Edit this job application"><i class="splashy-pencil"></i></a><a></a></td>
																<td class=" center"><a href="delete-jobapp.php?GUID=222DC278-8A4B-DB2D-5EE1-D92C5F61F94E" title="Delete this job application" onclick="return confirm('Are you sure to delete');"><i class="splashy-remove"></i></a><a></a></td>
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
								"aaSortingFixed": [[ 5, "desc" ]],
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
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 8 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 9 ] }
						<?php } ?>
                    ],
								"aaSorting": [[1, 'asc']],
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
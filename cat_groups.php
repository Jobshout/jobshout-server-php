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
			<a href="#">Category Groups</a>
		</li>
		 <?php
					//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user_access_level>1) {
					?>
		<li>
			<a href="cat_group.php">Add new Category Group</a>
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
                            
                            <table id="dt_e" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                       
										<th>ID</th>
										 <th>Site Name</th>
										<th>Name</th>
                                         <th>Type</th>
										 <th>Last Modified</th>
										 <th>Status</th>
										<th>GUID</th>
										<?php if($user_access_level>1) { ?>
										<th>Edit</th>
										<th>Delete</th>
										<?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="dataTables_empty" colspan="9">Loading data from server</td>
                                    </tr>
                                </tbody>
                            </table>
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
				$(document).ready(function() {
					datatbles.dt_e();
				});
				
				datatbles = {
					
					dt_e: function(){
						if($('#dt_e').length) {
							
							var oTable;
							oTable = $('#dt_e').dataTable( {
								"oSearch": {"sSearch": "<?php if(isset($_GET['srch']) && $_GET['srch']!='') { echo $_GET['srch']; } elseif(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['category_groups']) && isset($_SESSION['last_search']['category_groups']['sSearch'])) { echo $_SESSION['last_search']['category_groups']['sSearch']; } ?>"},
							
								"bProcessing": true,
								"bServerSide": true,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['category_groups']) && isset($_SESSION['last_search']['category_groups']['iDisplayLength'])) { echo $_SESSION['last_search']['category_groups']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['category_groups']) && isset($_SESSION['last_search']['category_groups']['iDisplayStart'])) { echo $_SESSION['last_search']['category_groups']['iDisplayStart']; } else { echo '0'; } ?>,
								
								//"iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150,"All"]],
								"sPaginationType": "bootstrap",
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"aaSorting": [[5,'desc'],[4,'desc']],
								"sAjaxSource": "lib/datatables/server_cat_groups.php",
								
								"aoColumnDefs": [
								<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 1 ] },
								<?php } ?>
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 6 ] },
						<?php if($user_access_level>1) { ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 7 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 8 ] }
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
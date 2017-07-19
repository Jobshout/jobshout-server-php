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
			<a href="#">Pages</a>
		</li>
		<?php
					//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user_access_level>1) {
					?>
				<li>
				
			<a href="page.php">Add new Page</a>
			
		</li>
<?php } ?>

		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
					
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
                   <?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
					</span></div><br/>
                    
					
                    <div class="row-fluid">
                        <div class="span12">
                            
                            <table id="dt_e" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th>ID</th>
										<th>Site Name</th>
										<th>Heading</th>
										<th>Window Title</th>
										<th>Last Modified</th>
                                        <th>Published Date</th>
										<th>Short Url</th>
										<th>Status</th>
                                        <th>Ref no.</th>
										 <th>uuid</th>
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
								"oSearch": {"sSearch": "<?php if(isset($_GET['srch']) && $_GET['srch']!='') { echo $_GET['srch']; } elseif(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['pages']) && isset($_SESSION['last_search']['pages']['sSearch'])) { echo $_SESSION['last_search']['pages']['sSearch']; } ?>"},
							
								"bProcessing": true,
								"bServerSide": true,
								//"iDisplayLength": 25,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['pages']) && isset($_SESSION['last_search']['pages']['iDisplayLength'])) { echo $_SESSION['last_search']['pages']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['pages']) && isset($_SESSION['last_search']['pages']['iDisplayStart'])) { echo $_SESSION['last_search']['pages']['iDisplayStart']; } else { echo '0'; } ?>,
								
								"aLengthMenu": [[10, 25, 50, 100, 200, 350, 500, 1000], [10, 25, 50, 100, 200, 350, 500, 1000]],
								"sPaginationType": "bootstrap",
								// "aaSorting": [[8,'desc']],
								<?php if(isset($_SESSION['site_id']) && $_SESSION['site_id']!="") { ?>
									"aaSorting": [[5,'desc'],[4,'desc']],
								<?php }else{ ?>
									"aaSorting": [[5,'desc']],
								<?php } ?>
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_side.php",
								<?php if(isset($_GET['cat_id']) && $_GET['cat_id']!='' && isset($_GET['cat_guid']) && $_GET['cat_guid']!='') { ?>
								"fnServerParams": function ( aoData ) {
									aoData.push( { "name": "cat_id", "value": "<?php echo $_GET['cat_id']; ?>" } );
									aoData.push( { "name": "cat_guid", "value": "<?php echo $_GET['cat_guid']; ?>" } );
								},
								<?php } ?>
								"aoColumnDefs": [
								<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bSortable":false, "bVisible": false, "aTargets": [ 1 ] },
								<?php } ?>
								<?php if(!isset($_GET['cat_id']) && !isset($_GET['cat_guid'])) { ?>
									{ "bVisible": false,"bSortable":false, "aTargets": [ 8 ] },
								<?php } ?>
								
								{ "bSearchable": false, "bVisible": false, "aTargets": [ 9 ] },
								{ "bSortable":false, "aTargets": [ 6 ] },
								{ "bSearchable": false, "bSortable":false, "aTargets": [ 7 ] },
								<?php if($user_access_level>1) { ?>
								{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 10 ] },
								{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 11 ] }
								<?php } ?>
                    ],
								
								"oLanguage": {
									  "sInfoFiltered": ""
									},
								"fnDrawCallback": function( oSettings ) {
									  //alert( 'DataTables has redrawn the table' );
									  $('#dt_e tbody tr td').unbind();
									  $('#dt_e tbody tr td').click(function(){
									  	//console.log($(this).find('.page_code').attr('href'));
										if($(this).parents('tr').find('.page_code').attr('href')!='' && !($(this).find('a').html())){
											window.open($(this).parents('tr').find('.page_code').attr('href'));
										}
									  });
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
<?php
require_once("include/lib.inc.php");
require_once('include/main-header.php');
		
		
		if(isset($_POST['delete']))
		{
		$arr_usr = $_REQUEST['arr_usr'];
	if(is_array($arr_usr)) {
	    if(isset($_REQUEST['delete']))
		{
	    foreach($arr_usr as $key=> $value)
		    {
		       $db->query("delete from wi_users where uuid = '$value'");
			//db_query($sql);
			   echo "Successfully Deleted";
			 }
		   //  @unlink("UP_FILES_FS_PATH.'/'.$prd_picture");
			//$sql = "delete from eu_products where prd_id in ($str_prd_ids)";
			//db_query($sql);
		}
		
		
					
	}
	

	
}
	
	
	

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
	<ul>
		<li>
			<a href="home.php"><i class="icon-home"></i></a>
		</li>
		<li>
			<a href="index.php">Dashboard</a>
		</li>
		<li>
			<a href="#">Users</a>
		</li>
		<?php
					// $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user_access_level>1) {
					?>
		<li>
			<a href="user.php">Add new User</a>
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
							
							
							
							
								
									<table class="table table-striped table-bordered" width="100%" id="tbl_usr">
										<thead>
											<tr>
												<th >Site Name</th>
												<th >Email</th>
												<th >First Name</th>
												<th >Last Name</th>
												<th >Login Code</th>
												
												<th >Last Modified</th>
												<th >Status</th>
												<th >Uuid</th>
												<?php if($user_access_level>1) { ?>
												<th >Edit</th>
												 <th >Delete</th>
												 <?php } ?>
												
												
												
												
												
												
											</tr>
										</thead>
										<tbody>
										
		<tr>
                                        <td class="dataTables_empty" colspan="7">Loading data from server</td>
										
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
					datatbles.tbl_usr();
				});
				
				datatbles = {
					
					tbl_usr: function(){
						if($('#tbl_usr').length) {
							
							var oTable;
							oTable = $('#tbl_usr').dataTable( {
								<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['wi_users']) && isset($_SESSION['last_search']['wi_users']['sSearch'])) { ?>
								"oSearch": {"sSearch": "<?php  echo $_SESSION['last_search']['wi_users']['sSearch'];  ?>"},
								<?php } ?>
								"bProcessing": true,
								"bServerSide": true,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['wi_users']) && isset($_SESSION['last_search']['wi_users']['iDisplayLength'])) { echo $_SESSION['last_search']['wi_users']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['wi_users']) && isset($_SESSION['last_search']['wi_users']['iDisplayStart'])) { echo $_SESSION['last_search']['wi_users']['iDisplayStart']; } else { echo '0'; } ?>,
								
								//"iDisplayLength": 25,
								"sPaginationType": "bootstrap",
								"aaSorting": [[6,'desc'],[5,'desc']],
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/user_side.php",
								
								"aoColumnDefs": [
								<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] },
								<?php } ?>
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 7 ] },
						<?php if($user_access_level>1) { ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 8 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 9 ] }
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
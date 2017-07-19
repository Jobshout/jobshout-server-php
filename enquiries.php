<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); ?>
<style>
.list_chk { }
.add_to_list { display:inline; }
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
			<a href="#">Enquiries</a>
		</li>
		<?php
						if($user_access_level>1) {
					?>
		<li>
			<a href="enquiry.php">Add new Enquiry</a>
		</li>
		<?php }?>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                   
					<div id="valid" ><span style="color:#00CC00;font-size:18px">
                   <?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
					</span></div><br/>
                    
					
					<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data"> 
                    <div class="row-fluid">
                        <div class="span12">
                           
							<div style="margin-bottom:10px; width:100%; text-align:right" >
							
							
							
							
							
							<label style="display:inline;margin-bottom:0px">Show </label>					
							<select name="en_type" id="en_type" style="margin-bottom:0px; width:160px" >
							<option value="">--All--</option>
							<?php
							
							if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
			 				{
								$where = "WHERE SiteID in ('".$_SESSION['site_id']."')";
							}
							else
							{
								$where = "WHERE 1";
							}
							
							$enq_type=$db->get_results("select distinct(enquiry_type) from web_enquiries $where");
							foreach($enq_type as $en)
							{ ?>
							<option value="<?php echo $en->enquiry_type; ?>"><?php echo $en->enquiry_type; ?></option>
							<?php
							}
							?>
							</select>
							<label style="display:inline;margin-bottom:0px"> enquiries</label>
							
							
							
							</div>
							<div id="datatable"></div>
                            
							
							
							
										
							
							
							
                        </div>
                    </div>
					</form>
                        
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
var dt_html='<table id="dt_e" class="table table-striped table-bordered"><thead><tr><th>GUID</th><th>Name</th><th>Email</th><th>Telephone</th><th>Enquiry Type</th><th>Status</th><th>Last Modified</th><th width="7%">Site Name</th><th width="5%">ID</th>';
<?php if($user_access_level>1) { ?>
dt_html+='<th>Edit</th><th>Delete</th>';
<?php } ?>
dt_html+='</tr></thead><tbody><tr><td class="dataTables_empty" colspan="11">Loading data from server</td></tr></tbody></table>';

				$(document).ready(function() {
					//$('#div_add').hide();
				
					var tab4=$("#datatable").html();
if(tab4=='')
{

$("#datatable").html(dt_html);
}
        var oTable = $('#dt_e').dataTable( {
								<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['web_enquiries']) && isset($_SESSION['last_search']['web_enquiries']['sSearch'])) { ?>
								"oSearch": {"sSearch": "<?php  echo $_SESSION['last_search']['web_enquiries']['sSearch'];  ?>"},
								<?php } ?>
								"bProcessing": true,
								"bServerSide": true,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['web_enquiries']) && isset($_SESSION['last_search']['web_enquiries']['iDisplayLength'])) { echo $_SESSION['last_search']['web_enquiries']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['web_enquiries']) && isset($_SESSION['last_search']['web_enquiries']['iDisplayStart'])) { echo $_SESSION['last_search']['web_enquiries']['iDisplayStart']; } else { echo '0'; } ?>,
								
								//"iDisplayLength": 25,
								"sPaginationType": "bootstrap",
								"iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150,"All"]],
								"aaSorting": [[5,'asc'],[6,'desc']],
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_enquiries.php",
								
								"aoColumnDefs": [
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] },
						{ "bSearchable": false, "aTargets": [ 5 ] },
						<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 7 ] },
								<?php } ?>
						<?php if($user_access_level>1) { ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 9 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 10 ] }
						<?php } ?>
                    ],
								
								"oLanguage": {
      "sInfoFiltered": ""
    },
							
                         "fnServerData": function (sSource,aoData,fnCallback){
         
                                                $.ajax({
                                                         "dataType":'json',
                                                         
                                                         "url":sSource,
                                                         "cache":false,
                                                         "data":aoData,
                                                         "success":function(json){fnCallback(json); }
              });
              },
                } );
					
					
					
					
					
					
					$('#en_type').change(function(){
						var en_type=$(this).val();
						
						$("#datatable").html('');


$("#datatable").html(dt_html);

        var oTable = $('#dt_e').dataTable( {
                    "bProcessing": true,
       "bServerSide": true,
	   
								"sPaginationType": "bootstrap",
								"iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150,"All"]],
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_enquiries.php",
								"fnServerParams": function ( aoData ) {
            aoData.push( { "name": "en_type", "value": en_type } );
        },
								
								"aoColumnDefs": [
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] },
						{ "bSearchable": false, "aTargets": [ 5 ] },
						<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 7 ] },
								<?php } ?>
						<?php if($user_access_level>1) { ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 9 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 10 ] }
						<?php } ?>
                    ],
								
								"oLanguage": {
      "sInfoFiltered": ""
    },
							
                         "fnServerData": function (sSource,aoData,fnCallback){
         
                                                $.ajax({
                                                         "dataType":'json',
                                                         
                                                         "url":sSource,
                                                         "cache":false,
                                                         "data":aoData,
                                                         "success":function(json){fnCallback(json); }
														 
              });
              },
                } );
						
					});
					

					
					
				});
				
				
				
				
			</script>
           <script src="js/datatables.js"></script>
		   
		</div>
	</body>
</html>
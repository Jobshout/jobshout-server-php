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
					
                    <div id="valid" ></div><br/>
                    <div class="row-fluid">
                        <div class="span12">
                        	<div style="margin-bottom:10px; width:100%;  ">
                            	<button class="btn btn-gebo" type="button" name="btn_del" id="btn_del">Delete</button>
								<span style="float:right;"><input type="checkbox" name="show_inactive" id="show_inactive" onChange="_show_inactive_jobs(this)" value="0"/>  Also Show Inactive Jobs</span>
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
            			<!--Datatable Reload-->		
			$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
{
    // DataTables 1.10 compatibility - if 1.10 then versionCheck exists.
    // 1.10s API has ajax reloading built in, so we use those abilities
    // directly.
    if ( $.fn.dataTable.versionCheck ) {
        var api = new $.fn.dataTable.Api( oSettings );
 
        if ( sNewSource ) {
            api.ajax.url( sNewSource ).load( fnCallback, !bStandingRedraw );
        }
        else {
            api.ajax.reload( fnCallback, !bStandingRedraw );
        }
        return;
    }
 
    if ( sNewSource !== undefined && sNewSource !== null ) {
        oSettings.sAjaxSource = sNewSource;
    }
 
    // Server-side processing should just call fnDraw
    if ( oSettings.oFeatures.bServerSide ) {
        this.fnDraw();
        return;
    }
 
    this.oApi._fnProcessingDisplay( oSettings, true );
    var that = this;
    var iStart = oSettings._iDisplayStart;
    var aData = [];
 
    this.oApi._fnServerParams( oSettings, aData );
 
    oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
        /* Clear the old information from the table */
        that.oApi._fnClearTable( oSettings );
 
        /* Got the data - add it to the table */
        var aData =  (oSettings.sAjaxDataProp !== "") ?
            that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;
 
        for ( var i=0 ; i<aData.length ; i++ )
        {
            that.oApi._fnAddData( oSettings, aData[i] );
        }
         
        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
 
        that.fnDraw();
 
        if ( bStandingRedraw === true )
        {
            oSettings._iDisplayStart = iStart;
            that.oApi._fnCalculateEnd( oSettings );
            that.fnDraw( false );
        }
 
        that.oApi._fnProcessingDisplay( oSettings, false );
 
        /* Callback user function - for event handlers etc */
        if ( typeof fnCallback == 'function' && fnCallback !== null )
        {
            fnCallback( oSettings );
        }
    }, oSettings );
};
	<!--Datatable Reload-->	
	
	
			var status=false;
				$(document).on('ready change',function() {
					$('#chk_all').click(function(){
						if($(this).attr('checked')=='checked') {
							$('.list_chk').attr('checked', 'checked');
						}else{
							$('.list_chk').attr('checked', false);
						}
					});
				});
				$(document).ready(function() {
					$('#btn_del').click(function(){
						$("#valid").html("");
						var sel_id=''; var countJobs=0;
						$('.list_chk').each(function(){
							if($(this).attr('checked')=='checked'){
								if(sel_id==''){
									sel_id+=$(this).val();
									countJobs++;
								}else {
									sel_id+=','+$(this).val();
									countJobs++;
								}
							}
						});
						if(sel_id!='') {	
							var r = confirm("Are you sure to delete "+countJobs+" job(s)?");
							if (r == true) {
   								$.ajax({
									type: "POST",
									url: "delete_multiple_jobs.php",
									data: "sel_id=" + sel_id ,
									dataType: 'json',
									cache: false,
									success: function(response){
										if(response.success){
											$("#valid").html("<span style='color:#00CC00;font-size:16px'>"+response.success+"</span>");
											$('.list_chk').attr('checked', false);
											$('#chk_all').attr('checked', false);
											oTable.fnReloadAjax();
										}else if(response.error){
											$("#valid").html("<span style='color:#CC0000;font-size:16px'>"+response.error+"</span>");
											
										}
									}
								});
							}
						}else{
							$("#valid").html("<span style='color:#CC0000;font-size:16px'>Please select job(s) to delete</span><br/><br/>");
						}
					});
				
				
					var tab4=$("#jobs_datatables").html();
					if(tab4=='')
					{
					var dt_html='<table id="dt_e" class="table table-striped table-bordered"><thead><tr><th>GUID</th><th><input type="checkbox" name="chk_all" id="chk_all"></th><th>ID</th><th>Site Name</th><th>Ref no.</th><th>Job title</th><th>Window title</th><th>Owner</th><th>Last modified</th><th>Posted</th><th>Status</th><th>Job Apps</th><th>Preview</th>';
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
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 3 ] },
								<?php } ?>
							{ "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] },
							{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 11 ] },
							{ "bSearchable": false, "bSortable":false, "aTargets": [ 10 ] },
							{ "bSearchable": false, "bSortable":false, "aTargets": [ 1 ] },
							{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 12 ] },
						<?php if($user_access_level>1) { ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 13 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 14 ] }
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
					var dt_html='<table id="dt_e" class="table table-striped table-bordered"><thead><tr><th>GUID</th><th><input type="checkbox" name="chk_all" id="chk_all"></th><th>ID</th><th>Site Name</th><th>Ref no.</th><th>Job title</th><th>Window title</th><th>Owner</th><th>Last modified</th><th>Posted</th><th>Status</th><th>Job Apps</th><th>Preview</th>';
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
							{ "bSearchable": false, "bVisible": false, "aTargets": [ 3 ] },
									<?php } ?>
								{ "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] },
								{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 11 ] },
								{ "bSearchable": false, "bSortable":false, "aTargets": [ 10 ] },
								{ "bSearchable": false, "bSortable":false, "aTargets": [ 1 ] },
								{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 12 ] },
							<?php if($user_access_level>1) { ?>
							{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 13 ] },
							{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 14 ] }
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
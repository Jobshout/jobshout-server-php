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
			<a href="#">Job Briefs</a>
		</li>
		<?php
					
						if($user_access_level>1) {
					?>
		<li>
			<a href="jobbrief.php">Add new Job Brief</a>
		</li>
		<?php } ?>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
					
					<div id="valid" ></div><br/>
                    
                   <?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo ' <div id="validation" ><span style="color:#00CC00;font-size:18px">'.$_SESSION['ins_message'].'</span></div><br/>'; unset($_SESSION['ins_message']); }?>
				
                    <div class="row-fluid">
                        <div class="span12" >
                            
							<div style=" width:100%; text-align:left; position:relative; z-index:50000;" >
							<button class="btn btn-gebo" type="button" style="position:absolute;left:200px; top:18px; cursor:pointer;" name="btn_del" id="btn_del">Delete</button>&nbsp;
							
                            </div>
							
                            <table id="dt_e" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>
									<th>uuid</th>
                                       <th><input type="checkbox" name="chk_all" id="chk_all"></th>
                                        <th>ID</th>
										<th>Site Name</th>
                                       
                                        <th>Name</th>
										 <th>Email</th>
										<th>Mobile</th>
										
										<th>Last Modified</th>                                       
										 
										 <th>Download File</th>
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
	
	var oTable;
				$(document).ready(function() {
					datatbles.dt_e();
					
					$('#btn_del').click(function(){

						var sel_id='';
						$('.list_chk').each(function(){
							if($(this).attr('checked')=='checked'){
								if(sel_id==''){
									sel_id+=$(this).val();
								}
								else {
									sel_id+=','+$(this).val();
								}
							}
						});
					if(sel_id!='') {	
							$.ajax({
						type: "POST",
						url: "del_jobbriefs.php",
						data: "sel_id=" + sel_id,
						
						success: function(response){
						//alert(response);
							$("#valid").html(response);
							oTable.fnReloadAjax();
						}
						});
					}
					else{
						$("#valid").html("<span style='color:red;font-size:18px'>Please select Jobbrief(s) to delete</span><br/><br/>");
					}
					});
				});
				
				$(document).on('ready change',function() {
					$('#chk_all').click(function(){
						if($(this).attr('checked')=='checked') {
							$('.list_chk').attr('checked', 'checked');
						}
						else
						{
							$('.list_chk').attr('checked', false);
						}
					});
				});
				
				datatbles = {
					
					dt_e: function(){
						if($('#dt_e').length) {
							
							
							oTable = $('#dt_e').dataTable( {
								<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobbriefs']) && isset($_SESSION['last_search']['jobbriefs']['sSearch'])) { ?>
								"oSearch": {"sSearch": "<?php  echo $_SESSION['last_search']['jobbriefs']['sSearch'];  ?>"},
								<?php } ?>
								"bProcessing": true,
								"bServerSide": true,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobbriefs']) && isset($_SESSION['last_search']['jobbriefs']['iDisplayLength'])) { echo $_SESSION['last_search']['jobbriefs']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobbriefs']) && isset($_SESSION['last_search']['jobbriefs']['iDisplayStart'])) { echo $_SESSION['last_search']['jobbriefs']['iDisplayStart']; } else { echo '0'; } ?>,
								
								//"iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150,"All"]],
								"sPaginationType": "bootstrap",
								"aaSorting": [[6,'desc']],

								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_jobbriefs.php",
								
								"aoColumnDefs": [
								<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 3 ] },
								<?php } ?>
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 1 ] },
						{  "bSortable":false, "aTargets": [ 8 ] },
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
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
			<a href="#">Blog Comments</a>
		</li>
		
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>

                    </nav>
                    
					<div id="valid" ></div><br/>
                    
					
                   
                    <div class="row-fluid">
                        <div class="span12">
						
						<div style="margin-bottom:10px; width:100%; text-align:center " >
							<button class="btn btn-gebo" type="button" name="btn_del" id="btn_del">Delete</button>&nbsp;
							<button class="btn btn-gebo" type="button" name="btn_act" id="btn_act">Activate</button>&nbsp;
							<button class="btn btn-gebo" type="button" name="btn_deact" id="btn_deact">Deactivate</button>&nbsp;
                            </div>
                            <table id="dt_e" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                       <th>GUID</th>
										<th><input type="checkbox" name="chk_all" id="chk_all"></th>
									   	<th>ID</th>
										 <th>Site Name</th>
										 <th>Blog</th>
										 <th>Name</th>
										<th>Email</th>
                                         <th>Comment</th>
										 <th>IP Address</th>
										 <th>Modified</th>
										  <th>Status</th>
										 
										 <?php if($user_access_level>1) { ?>
										 <th>Edit</th>
										 
										 <?php } ?>                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="dataTables_empty" colspan="12">Loading data from server</td>
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
						url: "act_blog_comments.php",
						data: "sel_id=" + sel_id + "&mode=del" ,
						
						success: function(response){
						//alert(response);
							$("#valid").html(response);
							oTable.fnReloadAjax();
						}
						});
					}
					else{
						$("#valid").html("<span style='color:red;font-size:18px'>Please select comment(s) to delete</span><br/><br/>");
					}
					});
					
					$('#btn_act').click(function(){

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
						url: "act_blog_comments.php",
						data: "sel_id=" + sel_id + "&mode=act" ,
						
						success: function(response){
						//alert(response);
							$("#valid").html(response);
							oTable.fnReloadAjax();
						}
						});
					}
					else{
						$("#valid").html("<span style='color:red;font-size:18px'>Please select comment(s) to activate</span><br/><br/>");
					}
					});
					
					$('#btn_deact').click(function(){

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
						url: "act_blog_comments.php",
						data: "sel_id=" + sel_id + "&mode=deact",
						
						success: function(response){
						//alert(response);
							$("#valid").html(response);
							oTable.fnReloadAjax();
						}
						});
					}
					else{
						$("#valid").html("<span style='color:red;font-size:18px'>Please select comment(s) to deactivate</span><br/><br/>");
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
								"oSearch": {"sSearch": "<?php if(isset($_GET['srch']) && $_GET['srch']!='') { echo $_GET['srch']; } elseif(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['blog_comments']) && isset($_SESSION['last_search']['blog_comments']['sSearch'])) { echo $_SESSION['last_search']['blog_comments']['sSearch']; } ?>"},
							
								"bProcessing": true,
								"bServerSide": true,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['blog_comments']) && isset($_SESSION['last_search']['blog_comments']['iDisplayLength'])) { echo $_SESSION['last_search']['blog_comments']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['blog_comments']) && isset($_SESSION['last_search']['blog_comments']['iDisplayStart'])) { echo $_SESSION['last_search']['blog_comments']['iDisplayStart']; } else { echo '0'; } ?>,
								
								//"iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200,"All"]],
								"sPaginationType": "bootstrap",
								"aaSorting": [[9,'desc'],[10,'asc']],
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_comments.php",
								
								"aoColumnDefs": [
									{ "bSearchable": false, "bVisible": false, "sClass": "center", "aTargets": [ 0 ] },
									{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 1 ] },
									<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
										{ "bSearchable": false, "bVisible": false, "aTargets": [ 3 ] },
									<?php } ?>
									
									<?php if($user_access_level>1) { ?>
									{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 11 ] },
									
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
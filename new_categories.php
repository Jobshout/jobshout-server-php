<?php 
session_start();
if($_SESSION['UserEmail'] =='') {
       header("location:index.php");
}
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
						<?php require_once('include/breadcrum.php');?>
                    </nav>
                    
                    <div><a href="new_category.php">Add new category</a></div><br/><br/>

                    <div class="row-fluid">
                        <div class="span12">
                            
                            <table id="dt_e" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                       
                                        <th>Job ref.</th>
                                        <th>Title</th>
                                        <th>owner</th>
                                        <th>Modified</th>
										<th>Posted</th>
										 <th>GUID</th>
										<th>Delete</th>
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
					datatbles.dt_e();
				});
				
				datatbles = {
					
					dt_e: function(){
						if($('#dt_e').length) {
							
							var oTable;
							oTable = $('#dt_e').dataTable( {
								"bProcessing": true,
								"bServerSide": true,
								"sPaginationType": "bootstrap",
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/new_server_side.php",
								
								"aoColumnDefs": [
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 5 ] }
                    ],
								"aaSorting": [[1, 'asc']]
							} );
							
							$("#dt_e tbody tr").live('dblclick', function( e ) {
								var sData = oTable.fnGetData( this );
									document.location.href='job.php?GUID=' +sData[5];
							} );

						}
					}
				};
			</script>
           <script src="js/datatables.js"></script>
		</div>
	</body>
</html>
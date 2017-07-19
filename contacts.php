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
			<a href="#">Contacts</a>
		</li>
		<?php
					// $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user_access_level>1) {
					?>
		<li>
			<a href="contact.php">Add new Contact</a>
		</li>
		<?php }?>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                   
					<div id="valid" ><span style="color:#00CC00;font-size:18px">
                   <?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
					</span></div><br/>
                    <?php
					if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
					{
						$mailing_lists=$db->get_results("select * from wi_mailinglists where status='1' and SiteID in ('".$_SESSION['site_id']."')");
					}
					else
					{
						$mailing_lists=$db->get_results("select * from wi_mailinglists where status='1'");
					}
					?>
					
					<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data"> 
                    <div class="row-fluid">
                        <div class="span12">
                           
							<div style="margin-bottom:10px; width:100%; text-align:center " >
							<button class="btn btn-gebo" type="button" name="btn_add" id="btn_add" 
							<?php if(!(isset($_SESSION['site_id']) && strpos($_SESSION['site_id'], ",") === false)){ ?> style="visibility:hidden"  <?php } ?>
							>Add to List</button>&nbsp;
							<div class="add_to_list" style="min-width:200px; visibility:hidden" id="div_add" >
							
												
							<select name="add_mail_list" id="add_mail_list" style="margin-bottom:0px;  width:160px" >
							<option value="">--Select Mailing List--</option>
							<?php
							
							foreach($mailing_lists as $mailing_list)
							{ ?>
							<option value="<?php echo $mailing_list->uuid; ?>"><?php echo $mailing_list->list_name; ?></option>
							<?php
							}
							?>
							</select>
							</div>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="add_to_list" >
							<label style="display:inline;margin-bottom:0px">Show </label>
												
							<select name="show_mail_list" id="show_mail_list" style="margin-bottom:0px; width:160px" >
							<option value="">--All--</option>
							<?php
							
							foreach($mailing_lists as $mailing_list)
							{ ?>
							<option value="<?php echo $mailing_list->uuid; ?>"><?php echo $mailing_list->list_name; ?></option>
							<?php
							}
							?>
							</select>
							<label style="display:inline;margin-bottom:0px"> Contacts</label>
							</div>
							&nbsp;
							<button class="btn btn-gebo" type="button" name="btn_remove" id="btn_remove" style="visibility:hidden"  >Remove from List</button>&nbsp;
							
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
				$(document).ready(function() {
					//$('#div_add').hide();
				
					var tab4=$("#datatable").html();
if(tab4=='')
{
var dt_html='<table id="dt_e" class="table table-striped table-bordered"><thead><tr><th><input type="checkbox" name="chk_all" id="chk_all"></th><th>Name</th><th>Email</th><th>Telephone</th><th>Status</th><th>Registered</th><th>Last Modified</th><th width="7%">Site Name</th><th width="5%">ID</th>';
<?php if($user_access_level>1) { ?>
dt_html+='<th>Edit</th><th>Delete</th>';
<?php } ?>
dt_html+='</tr></thead><tbody><tr><td class="dataTables_empty" colspan="11">Loading data from server</td></tr></tbody></table>';
$("#datatable").html(dt_html);
}
        var oTable = $('#dt_e').dataTable( {
								<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['contacts']) && isset($_SESSION['last_search']['contacts']['sSearch'])) { ?>
								"oSearch": {"sSearch": "<?php  echo $_SESSION['last_search']['contacts']['sSearch'];  ?>"},
								<?php } ?>
								"bProcessing": true,
								"bServerSide": true,
								"iDisplayLength": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['contacts']) && isset($_SESSION['last_search']['contacts']['iDisplayLength'])) { echo $_SESSION['last_search']['contacts']['iDisplayLength']; } else { echo '25'; } ?>,
								"iDisplayStart": <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['contacts']) && isset($_SESSION['last_search']['contacts']['iDisplayStart'])) { echo $_SESSION['last_search']['contacts']['iDisplayStart']; } else { echo '0'; } ?>,
								
								//"iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150,"All"]],
								"sPaginationType": "bootstrap",
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/con_contacts.php",
								"aaSorting": [[4,'desc'],[6,'desc']],
								"aoColumnDefs": [
                        <?php  if(isset($_SESSION['site_id']) && strpos($_SESSION['site_id'], ",") === false){ ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 0 ] },
						<?php } else { ?>
						{ "bSearchable": false, "bSortable":false, "bVisible": false, "sClass": "center", "aTargets": [ 0 ] },
						<?php } ?>
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
					
					//* regular validation
					gebo_validation.reg();
					
					$('#btn_add').click(function(){
						var list_id=$('#show_mail_list').val();
						$('#add_mail_list option[value="'+list_id+'"]').prop('selected',true);
						$('#div_add').css('visibility','visible');
						$("#valid").html('');
					});
					
					$('#add_mail_list').change(function(){
						var list_id=$(this).val();
						
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
						
						$.ajax({
					type: "POST",
					url: "addtolist.php",
					data: "list_id=" + list_id + "&sel_id=" + sel_id ,
					
					success: function(response){
					//alert(response);
						$("#valid").html(response);
						$('#add_mail_list option[value=""]').prop('selected',true);
						$('#div_add').css('visibility','hidden');
						$('.list_chk').attr('checked', false);
					}
			 });
					});
					
					$('#show_mail_list').change(function(){
						var show_list_id=$(this).val();
						
						$("#datatable").html('');

var dt_html='<table id="dt_e" class="table table-striped table-bordered"><thead><tr><th><input type="checkbox" name="chk_all" id="chk_all"></th><th>Name</th><th>Email</th><th>Telephone</th><th>Status</th><th>DateRegistered</th><th>Modified</th><th width="7%">Site Name</th><th width="5%">ID</th>';
<?php if($user_access_level>1) { ?>
dt_html+='<th>Edit</th><th>Delete</th>';
<?php } ?>
dt_html+='</tr></thead><tbody><tr><td class="dataTables_empty" colspan="11">Loading data from server</td></tr></tbody></table>';
$("#datatable").html(dt_html);

        var oTable = $('#dt_e').dataTable( {
						"bProcessing": true,
						"bServerSide": true,
						"iDisplayLength": 25,
						"aLengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150,"All"]],
						"sPaginationType": "bootstrap",
						"aaSorting": [[4,'desc'],[6,'desc']],
						"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
						"sAjaxSource": "lib/datatables/con_contacts.php",
						"fnServerParams": function ( aoData ) {
            				aoData.push( { "name": "list_id", "value": show_list_id } );
        				},
								
						"aoColumnDefs": [                        
							<?php  if(isset($_SESSION['site_id']) && strpos($_SESSION['site_id'], ",") === false){ ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 0 ] },
						<?php } else { ?>
						{ "bSearchable": false, "bSortable":false, "bVisible": false, "sClass": "center", "aTargets": [ 0 ] },
						<?php } ?>
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
                                                         "success":function(json){fnCallback(json); 
														 if(show_list_id!=''){
														 $('#btn_remove').css('visibility','visible'); 
														 }
														 else
														 {
														 $('#btn_remove').css('visibility','hidden'); 	
														 }
														 $('#add_mail_list option[value="'+show_list_id+'"]').prop('selected',true);
														 }
              });
              },
                } );
						
					});
					
					
					
					
					
					
					$('#btn_remove').click(function(){
						var list_id=$('#show_mail_list').val();
						
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
						
						$.ajax({
					type: "POST",
					url: "delfromlist.php",
					data: "list_id=" + list_id + "&sel_id=" + sel_id ,
					
					success: function(response){
					//alert(response);
						$("#valid").html(response);
						$('.list_chk').attr('checked', false);
						$("#datatable").html('');

var dt_html='<table id="dt_e" class="table table-striped table-bordered"><thead><tr><th>GUID</th><th><input type="checkbox" name="chk_all" id="chk_all"></th><th>Name</th><th>Email</th><th>Telephone</th><th>DateRegistered</th><th>Modified</th><th width="7%">SITEID</th><th width="5%">ID</th>';
<?php if($user_access_level>1) { ?>
dt_html+='<th>Edit</th><th>Delete</th>';
<?php } ?>
dt_html+='</tr></thead><tbody><tr><td class="dataTables_empty" colspan="11">Loading data from server</td></tr></tbody></table>';
$("#datatable").html(dt_html);
					
					
					 var oTable = $('#dt_e').dataTable( {
                    "bProcessing": true,
       "bServerSide": true,
	   "iDisplayLength": 25,
								"aLengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150,"All"]],
								"sPaginationType": "bootstrap",
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/con_contacts.php",
								"fnServerParams": function ( aoData ) {
            aoData.push( { "name": "list_id", "value": list_id } );
        },
								
								"aoColumnDefs": [
                        <?php  if(isset($_SESSION['site_id']) && strpos($_SESSION['site_id'], ",") === false){ ?>
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 0 ] },
						<?php } else { ?>
						{ "bSearchable": false, "bSortable":false, "bVisible": false, "sClass": "center", "aTargets": [ 0 ] },
						<?php } ?>
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
                                                         "success":function(json){fnCallback(json); 
														 if(list_id!=''){
														 $('#btn_remove').css('visibility','visible'); 
														 }
														 else
														 {
														 $('#btn_remove').css('visibility','hidden'); 	
														 }
														 }
              });
              },
                } );
						
					}
			 });
					});
					
					$('#chk_all').click(function(){
						if($(this).attr('checked')=='checked') {
							$('.list_chk').attr('checked', 'checked');
						}
						else
						{
							$('.list_chk').attr('checked', false);
						}
					});
					
					$(document).on('change',function() {
					$('#dt_e').click(function(){
							
							$("#valid").html('');
						});
						
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
					
					
				});
				
				
				
				//* validation
				gebo_validation = {
					
					reg: function() {
						reg_validator = $('.form_validation_reg').validate({
							onkeyup: false,
							errorClass: 'error',
							validClass: 'valid',
							highlight: function(element) {

								$(element).closest('div').addClass("f_error");
							},
							unhighlight: function(element) {
								$(element).closest('div').removeClass("f_error");
							},
							errorPlacement: function(error, element) {
								$(element).closest('div').append(error);
							},
							rules: {
								mail_list: { required: true },
								
								
								
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
			</script>
           <script src="js/datatables.js"></script>
		   
		</div>
	</body>
</html>
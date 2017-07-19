<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); 

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from links where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: link.php");
	}
}


// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$site_id=$_POST["site_id"];
	$link_label=$_POST["link_label"];
	$link_url=$_POST["link_url"];
	$order_num=$_POST["order_num"];
	$short_url=$_POST["short_url"];
	$link_type=$_POST["link_type"];
	$Source=$_POST["source"];
	$Active=$_POST["status"];
	$DocumentID=intval($_POST["docu"]);
	$Document_GUID=$db->get_var("select GUID from documents where ID='$DocumentID'");
	$time= time();
	
	$site_guid=$db->get_var("select GUID from sites WHERE ID ='$site_id'");
	
	$GUID=$_POST["GUID"];
	if(isset($_GET['GUID'])) {
		$GUID= $_GET['GUID'];
	if($db->query("UPDATE links SET SiteID='".$site_id."', Modified='$time', Label='$link_label', Link='$link_url', short_url='$short_url', Type='$link_type', OrderNum='$order_num', DocumentID='$DocumentID', Document_GUID='$Document_GUID', Site_GUID='$site_guid', Source='$Source', Active='$Active' WHERE GUID ='$GUID'")) {
		
		$link_id=$db->get_var("select ID from links WHERE GUID ='$GUID'");
		
		$del_cats=$db->query("delete from links_categories where LinkID='$link_id' or Link_GUID='$GUID'");
		
		if(isset($_POST['cats'])) {
			$link_cat_ids=$_POST['cats'];
			foreach($link_cat_ids as $link_cat_id){
				if($link_cat_id!=''){
					$curr_guid= UniqueGuid('categories', 'GUID');
					$cat_guid=$db->get_var("select GUID from categories where ID='$link_cat_id'");
					$insert_cat=$db->query("insert into links_categories(Created, Modified, SiteID, CategoryID, LinkID, GUID, Site_GUID, Category_GUID, Link_GUID) values('$time', '$time', '".$site_id."', '$link_cat_id', '$link_id', '$curr_guid', '$site_guid', '$cat_guid', '$GUID' )");
				}
			}
		}
	
	$_SESSION['up_message'] = "Successfully updated";
	}
	 //$db->debug();
	}else{
	
	//$GUID=UniqueGuid('links', 'GUID');

	 if($db->query("INSERT INTO links (GUID, Created, Modified, SiteID, Label, Link, short_url, Type, DocumentID, Document_GUID, Source, Active, Site_GUID,OrderNum) VALUES ('$GUID', '$time', '$time', '".$site_id."', '$link_label', '$link_url', '$short_url', '$link_type', '$DocumentID', '$Document_GUID', '$Source', '$Active', '$site_guid','$order_num')")) {
	 
	 $link_id=$db->get_var("select ID from links WHERE GUID ='$GUID'");
	if(isset($_POST['cats'])) {
		$link_cat_ids=$_POST['cats'];
		foreach($link_cat_ids as $link_cat_id){
			if($link_cat_id!=''){
				$curr_guid= UniqueGuid('categories', 'GUID');
				$cat_guid=$db->get_var("select GUID from categories where ID='$link_cat_id'");
				$insert_cat=$db->query("insert into links_categories(Created, Modified, SiteID, CategoryID, LinkID, GUID, Site_GUID, Category_GUID, Link_GUID) values('$time', '$time', '".$site_id."', '$link_cat_id', '$link_id', '$curr_guid', '$site_guid', '$cat_guid', '$GUID' )");
			}
		}
	}
	
	$_SESSION['ins_message'] = "Successfully Inserted";
	header("Location:links.php");
	}
	//$db->debug();
	
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$link = $db->get_row("SELECT * FROM links where GUID ='$guid'");

		$site_id=$link->SiteID;
		$link_label=$link->Label;
		$link_url=$link->Link;
		$order_num=$link->OrderNum;
		$short_url=$link->short_url;
		$link_type=$link->Type;
		$DocumentID=intval($link->DocumentID);
		$Source=$link->Source;
		$Active=$link->Active;
		$where_cond=" and SiteID ='".$site_id."' ";

$arr_link_cats=array();
$link_cats = $db->get_results("SELECT CategoryID FROM `links_categories` WHERE SiteID='".$site_id."' AND LinkID='".$link->ID."'");
//$db->debug();
if($link_cats != ''){
 foreach($link_cats as $link_cat){
	$arr_link_cats[]= $link_cat->CategoryID;
	
}
}		

}
else
{
	$guid=UniqueGuid('links', 'GUID');
	$site_id='';
		$link_label='';
		$link_url='';
		$order_num=0;
		$short_url='';
		$link_type='';
		$DocumentID='';
		$Source=2;
		$Active=2;
		$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$arr_link_cats=array();
}

 ?>
<style>
label{
font-size: 14px;
line-height: 20px;
}
.close {
    float: right;
    font-size: 13px;
    /* font-weight: bold; */
    line-height: 13px;
    color: #000;
    /* text-shadow: 0 1px 0 #fff; */
    opacity: 0.6;
    filter: alpha(opacity=20);
    background: #000;
}

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
			<a href="links.php">Links</a>
		</li>
		<li>
			<a href="javascript:void(0)">Link</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
	</nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category</h3>-->
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    <div class="row-fluid">
                       <div class="span12">
                       <form class="form_validation_reg" method="post" action="">
						<div class="row-fluid">
							<div class="span8">
							
							
							<?php
											//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="control-group">
									<div class="row-fluid">
										<div class="span10">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												
												
													<select name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												if($site=$db->get_row("select id, GUID, name,Code from sites where ID='$site_id'")){ ?>
														<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php 
												}else{
													$sites=$db->get_results("select id, GUID, name,Code from sites order by zStatus asc, Name ASC limit 0,100 ");
													foreach($sites as $site){ ?>
													<option value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php }
												}				
												?>
												</select>
													
													</div>
												</div>
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="control-group">
									<div class="row-fluid">
										<div class="span10">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												
												
													<select onChange="" name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												if($sites=$db->get_results("select id, GUID, name,Code from sites where ID='$site_id' ")){
													foreach($sites as $site){ ?>
														<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php }
												}else {
													$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."') order by zStatus asc, Name ASC limit 0,100");
													foreach($sites as $site)
													{
													?>
													<option value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
													<?php } 
												} ?>
											</select>
													
													</div>
												</div>
											</div>
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
							
								
								<div class="control-group">
									<div class="row-fluid">
										<div class="span10">
											<label><strong>Categories</strong></label>
											<select name="cats[]" id="cats" multiple="multiple" class="span6" size="6">
												<option value="">-- Select Category --</option>
												<?php if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
													if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond and show_in_links=1 and Active=1 ORDER BY Name")){
														foreach($categorygroups as $group){
															$categorygroupId = $group->ID;
															$categorygroupName = $group->Name;
											
															if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' and Active=1 $where_cond ORDER BY Name")){
												?>
												<optgroup label="<?php echo $categorygroupName; ?>">
											<?php foreach($categories as $category){
													$categoryID = $category->ID;
													$categoryName = $category->Name;
													$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
											?>
												<option <?php if(in_array($categoryID, $arr_link_cats)) { echo "selected"; } ?> value='<?php echo $categoryID; ?>' >
													<?php echo $categoryName;  if($top_level){ echo ' ('.$top_level.')'; } ?>
												</option>
											<?php 	}	?>
												</optgroup>
											<?php	}	?>
											<?php	}
												 }
											 } 		?>
											</select>
										</div>
									</div>
								</div>
								<div class="control-group">
									<div class="row-fluid">
										<div class="span10">
											<label><strong>Type</strong><span class="f_req">*</span></label>
											<select onChange="change_url(this)" name="link_type" id="link_type" class="span6" >
												<option value="url" <?php if($link_type=='url') { ?>  selected="selected" <?php } ?> >URL</option>
												<option value='html' <?php if($link_type=='html') { ?>  selected="selected" <?php } ?> >HTML</option>
												<option value='items' <?php if($link_type=='items') { ?>  selected="selected" <?php } ?> >Items</option>
											</select>
										</div>
									</div>
								</div>
								<div class="control-group">
									<div class="row-fluid">
										<div class="span10">
											<label><strong>Label</strong><span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" id="GUID" name="GUID" class="textbox">
											<input type="text" name="link_label" class="span6" id="link_label" value="<?php echo $link_label; ?>"/>
										</div>
									</div>
								</div>
								<div class="control-group" id="linkurlSection">
									<div class="row-fluid">
										<div class="span10">
											<label id="urlSectionLabel"><strong><?php if($link_type=='html') { echo "Content"; }else{ echo "URL";	} ?><span class="f_req">*</span></strong></label>
					 						<?php if($link_type=='html') { ?>
												<textarea class="span6" name="link_url" id="link_url" rows="15"><?php echo $link_url;?></textarea>
					 						<?php } else{	?>
					 							<input type="text" class="span6" name="link_url" id="link_url" value="<?php echo $link_url;?>" />
					 						<?php }	?>
										</div>
									</div>
								</div>
								
								<div class="control-group" id="urlSectionDoc">
									<div class="row-fluid">
										<div class="span10">
											<label><strong>Or Select any web page as URL (Search for web page)</strong></label>

											 <div class="ui-widget">
												<select onChange="" name="docu" id="docu"  style="width:350px">
												<option value=""></option>
												<?php	if($doc = $db->get_row("SELECT ID,Document,Code FROM `documents` WHERE ID='$DocumentID' ")){
														?>
														<option  value="<?php echo $doc->ID; ?>" selected><?php echo $doc->Document." (".$doc->Code.")"; ?></option>	
														<?php
													}
													elseif(isset($_GET['GUID'])) {
													if($docs = $db->get_results("SELECT ID,Document,Code FROM `documents` WHERE 1 $where_cond ORDER BY `Document` limit 0,100 ")){
													foreach($docs as $doc) {
													?>
													<option  value="<?php echo $doc->ID; ?>" ><?php echo $doc->Document." (".$doc->Code.")"; ?></option>
													<?php
													} } }
												?>
												</select>
											</div>
										</div>
									</div>
								</div>
								
								
							
							</div>
							<div class="span4">
								<div class="control-group">
									<div class="row-fluid">
										<div class="span10">
											<label><strong>Order</strong><span class="f_req">*</span></label>
											<input type="text" class="span12" name="order_num" id="order_num" value="<?php echo $order_num;?>" />
										</div>
									</div>
								</div>
								<div class="control-group" id="linkTargetDivID">
									<div class="row-fluid">
										<div class="span12">
											<label><strong>Target</strong><span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="source" <?php if($Source == 1 || $Source == 2) { echo ' checked'; } ?> />
												Open in same window
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="source" <?php if($Source == 0) { echo ' checked'; } ?> />
												Open in new window
											</label>
										</div>
									</div>
								</div>
								
								<div class="control-group">
									<div class="row-fluid">
										<div class="span12">
											<label><strong>Status</strong><span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($Active == 1 || $Active == 2) { echo ' checked'; } ?> />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($Active == 0) { echo ' checked'; } ?> />
												Inactive
											</label>
										</div>
									</div>
								</div>
								<div class="control-group">
									<div class="row-fluid">
										<div class="span12">
											<label><strong>Short URL</strong><span class="f_req">*</span></label>
					 						<span class="span6" style="margin-left:0px;" >
											<input type="text" class="span12" name="short_url" id="short_url" value="<?php echo $short_url;?>" />
											<div id="unique_err" style="font-size:11px;font-weight:700;color:#C62626"></div>
											</span>
											&nbsp;OR &nbsp;
											<button class="btn btn-gebo" type="button" name="auto_generate" id="auto_generate">Auto Generate</button>
										</div>
									</div>
								</div>
							</div>
                            <div class="control-group" id="itemsTable" style="display:none;">
									<div class="row-fluid">
										<div class="span10">
											<label class="span4"><strong>Bookmark Items</strong></label>
											
											<span style="float:right; font-weight:bold;">
												<button type="button" class="btn btn-info btn-lg" style="display:none;" id="duplicateItemsID" onClick="duplicate_items();">Create copy of item(s)</button>
												<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">+ Add new Item</button>
											</span>
											<table id="rel_images" class="items table table-condensed table-striped" data-provides="rowlink">
												<thead>
												  <tr class="item">
												  	  <th><input id="chk_all" class="" type="checkbox" name="select"></th>
													  <th>Label</th>
													  <th>Type</th>
													  <th>Content</th>
													  <th>Related Document</th>
													  <th>Query String</th>
													  <th>Level</th>
													  <th>Sort Order</th>
													  <th>Target</th>
													  <th>Status</th>
													  <th colspan="2" align="center">Actions</th>
												  </tr>
											  </thead>
											  <tbody id="link_items">
											  
											  </tbody>
											</table>
										</div>
									</div>
								</div>
                                </div>
								<div style="margin-top:15px;" >
									<button class="btn btn-gebo" type="submit" name="submit">Save changes</button>
									<a href="links.php" class="btn" >Cancel</a>
									<!--<button class="btn" onclick="window.location.href='categories.php'">Cancel</button>-->
								</div>
						
						</form>
                    </div>
                    </div>
                 </div>
            </div>
            
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" onClick="resetItemForm()">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Bookmark item
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
            	<div class="form-group" id="messageId">
                    <label for="item_label"><strong>Label</strong><span class="f_req">*</span></label>
                      <input type="text" class="form-control span5" id="item_label" placeholder="Enter Label"/>
                      <input type="hidden" class="form-control span5" id="item_uuid"/>
                  </div>
                  <div class="form-group">
                    <label for="item_type"><strong>Type</strong><span class="f_req">*</span></label>
                	<select onChange="change_item_div(this)" name="item_type" id="item_type" class="span5" >
						<option value="url">URL</option>
						<option value='html'>HTML</option>
					</select>
                  </div>
                  <div class="form-group">
                    <label for="item_type" id="itemlinklabel"><strong>Link</strong><span class="f_req">*</span></label>
                	<input type="text" class="form-control span5" id="item_link" placeholder="Link"/>
                  </div>
                  <div class="form-group" id="relatedLinkDocID">
                    <label for="item_type"><strong>Or select any web page for URL</strong><span class="f_req">*</span></label>
                	<!--<input type="text" class="form-control" id="item_related_doc" placeholder="Related Document"/>-->
                		<div class="ui-widget">
							<select name="item_related_doc" id="item_related_doc" >
								<option value=""></option>
						
							</select>
						</div>
                  </div>
                   <div class="form-group">
                    <label for="item_type" id="itemlinklabel"><strong>Query String</strong></label>
                	<input type="text" class="form-control span5" id="item_query_string" placeholder="Query String"/>
                  </div>
                  <div class="form-group" style="margin-top:5px;">
                    <label for="item_type"><strong>Level</strong></label>
                	<input type="text" class="form-control span2" id="item_level" placeholder="Level" value="0" style="margin-right:10px;"/>
                 
                    <span for="item_type"><strong>Sort Order</strong></span>
                	<input type="text" class="form-control span2" id="item_sort_order" placeholder="Sort Order" value="0"/>
                  </div>
                  <div class="form-group">
                    <label for="item_type" class="itemTargetDivClass"><strong>Target</strong></label>
                	<select name="item_target" id="item_target" class="itemTargetDivClass span2" style="margin-right:37px;">
						<option value='1'>Open in same tab</option>
						<option value='0'>Open in new window</option>
					</select>
					
                    <span for="item_type"><strong>Status</strong></span>
                	<select name="item_active" id="item_active" class="span2">
						<option value='1'>Active</option>
						<option value='0'>Inactive</option>
					</select>
                  </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onClick="resetItemForm()">
                            Close
                </button>
                <button type="button" class="btn btn-primary" onClick="savelinkitem()">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- sidebar -->
<aside>
    <?php require_once('include/sidebar.php');?>
</aside>
            
<?php require_once('include/footer.php');?>
<script src="js/links.js"></script>
<script>
$(function() {
	<?php if(isset($link_type) && $link_type!="" ){ ?>
	var linkTypeStr="<?php echo $link_type; ?>";
	$("select#link_type").val(linkTypeStr).trigger('change');
	<?php } ?>
					$('#short_url').blur(function(){
						if($(this).val()!=''){
							var short_url=$(this).val();
							var dataString = 'short_url='+short_url;
							<?php if(isset($_GET['GUID'])) { ?>
							dataString += '&guid=<?php echo $_GET['GUID']; ?>';
							<?php } ?>
							get_check_short_url(dataString);					
						}
						else{
							$('#unique_err').html('');
						}
					});					
});
			</script>
			
		</div>
	</body>
</html>
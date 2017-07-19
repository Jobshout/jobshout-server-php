<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');
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
		<li>Settings</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
					<?php if(isset($error_msg) && $error_msg!=''){ ?>
					<div id="validation" ><span style="color:#FF0000;font-size:18px">
					<?php echo implode("<br>",$error_msg); ?>
					 </span></div><br>
					 <?php } ?>
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					 </span></div><br>
					
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Admin Settings</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Page Settings</a></li>
								</ul>
								<div class="tab-content">
								
									<div class="tab-pane active" id="tab1">

                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<!-- Site dropdown starts here-->
											<?php include_once("sites_dropdown.php"); ?>
											<!-- Site dropdown ends here-->
											<div class="control-group">
												<label class="control-label">Select Menu options<span class="f_req">*</span></label>
												<div class="controls">
													<input class="menu" type="checkbox" value="banners" name="check[]"> Banners<br/>
													<input class="menu" type="checkbox" value="bookmarks" name="check[]"> Bookmarks<br/>
													<input class="menu" type="checkbox" value="blogs" name="check[]"> Blog Posts<br/>
													<input class="menu" type="checkbox" value="campaigns" name="check[]"> Campaigns<br/>
													<input class="menu" type="checkbox" value="contacts" name="check[]"> Contacts<br/>
													<input class="menu" type="checkbox" value="components" name="check[]"> Components<br/>
													<input class="menu" type="checkbox" value="customers" name="check[]"> Customers<br/>
													<input class="menu" type="checkbox" value="enquiries" name="check[]"> Enquiries<br/>
													<input class="menu" type="checkbox" value="images" name="check[]"> Images<br/>
													<input class="menu" type="checkbox" value="jobs" name="check[]"> Job board<br/>
													<input class="menu" type="checkbox" value="leads" name="check[]"> Leads<br/>
													<input class="menu" type="checkbox" value="knowledge_base" name="check[]"> Knowledge Base<br/>
													<input class="menu" type="checkbox" value="mailing_list" name="check[]"> Mailing List<br/>
													<input class="menu" type="checkbox" value="newsletter" name="check[]"> Newsletter Subscribers<br/>
													<input class="menu" type="checkbox" value="pdf_documents" name="check[]"> PDF Documents<br/>
													<input class="menu" type="checkbox" value="talent_search" name="check[]"> Talent Search<br/>
													<input class="menu" type="checkbox" value="templates" name="check[]"> Templates<br/>
													<input class="menu" type="checkbox" value="tokens" name="check[]"> Tokens<br/>
													<input class="menu" type="checkbox" value="uk_locations" name="check[]"> UK Locations<br/>
													<input class="menu" type="checkbox" value="videos" name="check[]"> Videos<br/>
													<input class="menu" type="checkbox" value="browser_categories" name="check[]"> View Content by Category<br/>
													<input class="menu" type="checkbox" value="categories" name="check[]"> Web Categories<br/>
													<input class="menu" type="checkbox" value="category_groups" name="check[]"> Web Category Groups<br/>
													<input class="menu" type="checkbox" value="pages" name="check[]"> Web Pages<br/>
													<input class="menu" type="checkbox" value="sites" name="check[]"> Web Sites<br/>
													<input class="menu" type="checkbox" value="users" name="check[]"> Web Users<br/>
												</div>
											</div>
											<a class="btn btn-gebo" href="javascript:void(0)" onClick="save_menu_settings()">Save changes</a>
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>
                        


									</div>
									
									
									
									<div class="tab-pane" id="tab2">
										
									</div>
																							
									</div>	
								
								
								
								
								
							</div>
						</div>
					</div>
					
					

                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
            <?php require_once('include/footer.php');?>

            </div>
			<script type="text/javascript">
			 function save_menu_settings(){
				selected='';
				$('.menu').each(function(){
					if($(this).is(":checked")){
						if(selected==''){
							selected=$(this).val();
						}
						else{
							selected+=","+$(this).val();
						}
					}
				});
				console.log(selected);
			 }
			</script>
	</body>
</html>
<?php require_once('include/main-header.php'); ?>
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
                    
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab1" data-toggle="tab">Content Editor</a></li>
									<li><a href="#tab2" data-toggle="tab">Search Optimisation</a></li>
									<li><a href="#tab3" data-toggle="tab">Categories</a></li>
									<li><a href="#tab4" data-toggle="tab">Settings</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab1">

                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<form class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Page </p>
											<div class="control-group">
												<label class="control-label">Title</label>
												<div class="controls">
													<input type="text" class="span10" />
													<span class="help-block">block help text</span>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Body</label>
												<div class="controls">
													<textarea cols="30" rows="35" class="span10"></textarea>
													<span class="help-block">block help text</span>
												</div>
											</div>
										</fieldset>
									</form>
                                </div>

                                <div class="span3">
									<form class="well form-inline">
										<p class="f_legend">Published By</p>
										<input type="text" placeholder="Author" class="input-small" />
										&nbsp;At&nbsp;
										<input type="text" placeholder="Published_DateTime" class="input-small" />
									</form>
                                </div>


                            </div>

                        </div>
                    </div>
                        


									</div>
									
									
									
									<div class="tab-pane" id="tab2">
										<p>


                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<form class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Page </p>
											<div class="control-group">
												<label class="control-label">Code</label>
												<div class="controls">
													<input type="text" class="span10" />
													<span class="help-block">URL (SEO friendly)</span>

												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Meta Data Keywords</label>
												<div class="controls">
													<textarea cols="30" rows="5" class="span10"></textarea>
													<span class="help-block">Search terms relevant to this page to find content of this page easily</span>
												</div>
												
												
																								<label class="control-label">Meta Data Description</label>
												<div class="controls">
													<textarea cols="30" rows="5" class="span10"></textarea>
													<span class="help-block">Overview which describes this page (About 300 words)</span>
												</div>

											</div>
										</fieldset>
									</form>
                                </div>

                            </div>

                        </div>
                    </div>


										</p>
									</div>
									
									
									<div class="tab-pane" id="tab3">
										<p>
											TAB 3 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla et tellus felis, sit amet interdum tellus. Suspendisse sit amet scelerisque dui. Vivamus faucibus magna quis augue venenatis ullamcorper. Proin eget mauris eget orci lobortis luctus ac a sem. Curabitur feugiat, eros consectetur egestas iaculis,
											Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla et tellus felis, sit amet interdum tellus. Suspendisse sit amet scelerisque dui. Vivamus faucibus magna quis augue venenatis ullamcorper. Proin eget mauris eget orci lobortis luctus ac a sem. Curabitur feugiat, eros consectetur egestas iaculis,
										</p>
									</div>
									
									
									
																		<div class="tab-pane" id="tab4">
										<p>
											TAB 4 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla et tellus felis, sit amet interdum tellus. Suspendisse sit amet scelerisque dui. Vivamus faucibus magna quis augue venenatis ullamcorper. Proin eget mauris eget orci lobortis luctus ac a sem. Curabitur feugiat, eros consectetur egestas iaculis,
											Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla et tellus felis, sit amet interdum tellus. Suspendisse sit amet scelerisque dui. Vivamus faucibus magna quis augue venenatis ullamcorper. Proin eget mauris eget orci lobortis luctus ac a sem. Curabitur feugiat, eros consectetur egestas iaculis,
										</p>
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
            
            <script src="js/jquery.min.js"></script>
			<!-- smart resize event -->
			<script src="js/jquery.debouncedresize.min.js"></script>
			<!-- js cookie plugin -->
			<script src="js/jquery.cookie.min.js"></script>
			<!-- main bootstrap js -->
			<script src="bootstrap/js/bootstrap.min.js"></script>
			<!-- code prettifier -->
			<script src="lib/google-code-prettify/prettify.min.js"></script>
			<!-- tooltips -->
			<script src="lib/qtip2/jquery.qtip.min.js"></script>
			<!-- jBreadcrumbs -->
			<script src="lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
            <!-- common functions -->
			<script src="js/gebo_common.js"></script>
	
			<script>
				$(document).ready(function() {
					//* calculate sidebar height
					gebo_sidebar.make();
					//* show all elements & remove preloader
					setTimeout('$("html").removeClass("js")',1000);
				});
			</script>
		
		</div>
	</body>
</html>	
	
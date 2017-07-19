<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Gebo Admin Panel</title>
    
        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
        <!-- jQuery UI theme-->
            <link rel="stylesheet" href="lib/jquery-ui/css/Aristo/Aristo.css" />
        <!-- gebo blue theme-->
            <link rel="stylesheet" href="css/blue.css" />
        <!-- breadcrumbs-->
            <link rel="stylesheet" href="lib/jBreadcrumbs/css/BreadCrumb.css" />
        <!-- tooltips-->
            <link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <!-- notifications -->
            <link rel="stylesheet" href="lib/sticky/sticky.css" />
        <!-- code prettify -->
            <link rel="stylesheet" href="lib/google-code-prettify/prettify.css" />    
        
        <!-- splashy icons -->
            <link rel="stylesheet" href="img/splashy/splashy.css" />
		<!-- datepicker -->
            <link rel="stylesheet" href="lib/datepicker/datepicker.css" />
       
            
        <!-- main styles -->
            <link rel="stylesheet" href="css/style.css" />
			
            <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans" />
	
        <!-- Favicon -->
            <link rel="shortcut icon" href="favicon.ico" />
		
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/ie.css" />
        <![endif]-->
        	
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script src="lib/flot/excanvas.min.js"></script>
        <![endif]-->
		
    </head>
    <body>
		<div id="maincontainer" class="clearfix">
			
            
            <!-- main content -->
            <div id="contentwrapper">
                <div class="main_content">
                    
					
                    
					
					
					
					
					
						
							
								<div class="input-append date" id="dp2" data-date-format="dd/mm/yyyy">
									<input class="span6" type="text" readonly="readonly" /><span class="add-on"><i class="splashy-calendar_day"></i></span>
								</div>
								<span class="help-block">As component</span>
							
														
						<p><span class="label label-inverse">WYSIWG Editor with integrated File Manager</span></p>
					<textarea name="wysiwg_full" id="wysiwg_full" cols="30" rows="10"></textarea>
                        
					
					
					
					
					
                        
                </div>
            </div>
            
			<!-- sidebar -->
            
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
			<!-- sticky messages -->
            <script src="lib/sticky/sticky.min.js"></script>
            <!-- common functions -->
			<script src="js/gebo_common.js"></script>
	
           
			
			<!-- datepicker -->
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>

			
             <!-- TinyMce WYSIWG editor -->
            <script src="lib/tiny_mce/jquery.tinymce.js"></script>
            
          <script type="text/javascript">
			$(document).ready(function() {
			//* masked inputs
		//gebo_mask_input.init();
		//* datepicker
		gebo_datepicker.init();
		});
		
		
	
	//* bootstrap datepicker
	gebo_datepicker = {
		init: function() {
			$('#dp2').datepicker();
			}
			};

			</script>

    
			
		
		</div>
	</body>
</html>
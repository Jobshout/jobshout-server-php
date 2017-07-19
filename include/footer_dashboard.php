            <script src="js1_8/jquery.min.js"></script>
            <script src="js1_8/jquery-migrate.min.js"></script>
            <!-- smart resize event -->
            <script src="js1_8/jquery.debouncedresize.min.js"></script>
            <!-- hidden elements width/height -->
            <script src="js1_8/jquery.actual.min.js"></script>
            <!-- js cookie plugin -->
            <script src="js1_8/jquery_cookie.min.js"></script>
            <!-- main bootstrap js -->
            <script src="bootstrap/js/bootstrap.min.js"></script>
            <!-- bootstrap plugins -->
            <script src="js1_8/bootstrap.plugins.min.js"></script>
            <!-- tooltips -->
            <script src="lib1_8/qtip2/jquery.qtip.min.js"></script>
            <!-- jBreadcrumbs -->
            <script src="lib1_8/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
            <!-- lightbox -->
            <script src="lib1_8/colorbox/jquery.colorbox.min.js"></script>
            <!-- fix for ios orientation change -->
            <script src="js1_8/ios-orientationchange-fix.js"></script>
            <!-- scrollbar -->
            <script src="lib1_8/antiscroll/antiscroll.js"></script>
            <script src="lib1_8/antiscroll/jquery-mousewheel.js"></script>
            <!-- to top -->
            <script src="lib1_8/UItoTop/jquery.ui.totop.min.js"></script>
            <!-- mobile nav -->
            <script src="js1_8/selectNav.js"></script>
            <!-- common functions -->
            <script src="js1_8/gebo_common.js"></script>
            
            <script src="lib1_8/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
            <!-- touch events for jquery ui-->
            <script src="js1_8/forms/jquery.ui.touch-punch.min.js"></script>
            <!-- multi-column layout -->
            <script src="js1_8/jquery.imagesloaded.min.js"></script>
            <script src="js1_8/jquery.wookmark.js"></script>
            <!-- responsive table -->
            <script src="js1_8/jquery.mediaTable.min.js"></script>
            <!-- small charts -->
            <script src="js1_8/jquery.peity.min.js"></script>
            <!-- charts -->
            <script src="lib1_8/flot/jquery.flot.min.js"></script>
            <script src="lib1_8/flot/jquery.flot.resize.min.js"></script>
            <script src="lib1_8/flot/jquery.flot.pie.min.js"></script>
            <!-- calendar -->
            <script src="lib1_8/fullcalendar/fullcalendar.min.js"></script>
            <!-- sortable/filterable list -->
            <script src="lib1_8/list_js/list.min.js"></script>
            <script src="lib1_8/list_js/plugins/paging/list.paging.js"></script>
            <!-- dashboard functions -->
            <script src="js1_8/gebo_dashboard.js"></script>

			<script>
				$(document).ready(function() {
					//* calculate sidebar height
					gebo_sidebar.make();
					//* show all elements & remove preloader
					setTimeout('$("html").removeClass("js")',1000);
					
					
					var url = window.location.pathname;
					var filename = url.substring(url.lastIndexOf('/')+1);
					if(filename=='home.php'){
						var maindiv = $('.accordion').children('.accordion-group').eq(0);						
						var div_head = maindiv.children('.accordion-heading');
						var div_body = maindiv.children('.accordion-body'); 
						div_body.addClass('in');
						div_head.addClass('sdb_h_active');
					}
					else{
					var filelink = $("a[href='"+filename+"']");
					//alert(filelink.html());
					if(!filelink.html()){
					
						var nav_li=$('.breadCrumb ul').children().eq(2);
						filename=nav_li.children('a').attr('href');
						//alert(filename);
						filelink = $("a[href='"+filename+"']");
					}
					var liparent = filelink.parents('li');
					var div_body = liparent.parents('.accordion-body');
					var div_parent = div_body.parent('.accordion-group');
					var div_head = div_parent.children('.accordion-heading');
					liparent.addClass('active');
					div_body.addClass('in');
					div_head.addClass('sdb_h_active');
					}
 
					
				});
			</script>
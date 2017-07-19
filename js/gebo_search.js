/* [ ---- Gebo Admin Panel - search page ---- ] */

	$(document).ready(function() {
		//* toggle between list/boxes view
        $(".search_view a").click(function(){
            $(".search_view a").toggleClass("active");
            $(".search_panel").fadeOut("fast", function() {
                $(this).fadeIn("fast").toggleClass("thumb_view");
                //* recalculate sidebar height
                gebo_sidebar.make();
            });
        });

	});

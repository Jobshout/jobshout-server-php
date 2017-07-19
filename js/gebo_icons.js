/* [ ---- Gebo Admin Panel - icons ---- ] */

	$(document).ready(function() {
		//* show code for icons
		$('.icon_list li').css('cursor','pointer').on('click', function(){
            var ico_title = $(this).attr('title');
            $('.icon_copy span').html('[ <i class="'+ico_title+'"></i> <code>&lt;i class="'+ico_title+'"&gt;&lt;/i&gt;</code> ]')
        });
	});

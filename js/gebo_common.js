/* [ ---- Gebo Admin Panel - common ---- ] */

	$(document).ready(function() {
		//* search typeahead
		$('.search_query').typeahead({
			source: ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"],
			items: 4
		});
		//* resize elements on window resize
		var lastWindowHeight = $(window).height();
		var lastWindowWidth = $(window).width();
		$(window).on("debouncedresize",function() {
			if($(window).height()!=lastWindowHeight || $(window).width()!=lastWindowWidth){
				lastWindowHeight = $(window).height();
				lastWindowWidth = $(window).width();
				//* rebuild sidebar
				gebo_sidebar.make();
			}
		});
		//* rebuild sidebar on accordion change
		$('#side_accordion').on('hidden shown', function () {
			gebo_sidebar.make();
			gebo_sidebar.make_active();
		});
		if(!is_touch_device){
            //* tooltips
            gebo_tips.init();
            //* popovers
            gebo_popOver.init();
        }
		//* sidebar
        gebo_sidebar.init();
		gebo_sidebar.make_active();
		//* breadcrumbs
        gebo_crumbs.init();
		//* pre block prettify
		prettyPrint();
		//* external links
		gebo_external_links.init();
		//* style switcher
        gebo_style.init();
	});
    
	//* detect touch devices 
    is_touch_device = ('ontouchstart' in document.documentElement);
	
    gebo_sidebar = {
        init: function() {
			// sidebar onload state
			if($(window).width() > 767){
                if(!$('body').hasClass('sidebar_hidden')) {
                    if( $.cookie('gebo_sidebar') == "hidden") {
                        $('body').addClass('sidebar_hidden');
                        $('.sidebar_switch').toggleClass('on_switch off_switch').attr('title','Show Sidebar');
                    }
                } else {
                    $('.sidebar_switch').toggleClass('on_switch off_switch').attr('title','Show Sidebar');
                }
            } else {
                $('body').addClass('sidebar_hidden');
                $('.sidebar_switch').removeClass('on_switch').addClass('off_switch');
            }
			//* sidebar visibility switch
            $('.sidebar_switch').click(function(){
                if(!is_touch_device){
                    $(this).qtip('hide');
                }
                $('.sidebar_switch').removeClass('on_switch off_switch');
                if( $('body').hasClass('sidebar_hidden') ) {
                    $.cookie('gebo_sidebar', null);
                    $('body').removeClass('sidebar_hidden');
                    $('.sidebar_switch').addClass('on_switch').show();
                    $('.sidebar_switch').attr( 'title', "Hide Sidebar" );
                } else {
                    $.cookie('gebo_sidebar', 'hidden');
                    $('body').addClass('sidebar_hidden');
                    $('.sidebar_switch').addClass('off_switch');
                    $('.sidebar_switch').attr( 'title', "Show Sidebar" );
                }
                $(window).resize();
                //* recalculate sidebar height
                gebo_sidebar.make();
            });
            //* sidebar info box
            $('.sidebar').css('min-height','100%');
            var s_box = $('.sidebar_info');
            s_box.css({
                'margin-top'    : '-'+s_box.outerHeight()+'px',
                'position'      : 'absolute',
                'bottom'        : '52px'
            });
            $('.sidebar_inner').css('padding-bottom',s_box.outerHeight() + 62 );
			//* prevent accordion link click
            $('.sidebar .accordion-toggle').click(function(e){e.preventDefault()});
        },
		make: function() {
			$('.main_content').css('min-height','');
            var mH = $('.main_content').height();
            var wH = $(window).height();
            var sH = $('.sidebar_inner').height();
            
            if( (wH > sH) && (wH > mH) ) {
                $('.main_content').css('min-height', wH - 94 );
            } else if( sH > mH ) {
                $('.main_content').css('min-height', sH );
            } else {
                $('.main_content').css('min-height', mH );
            }
		},
		make_active: function() {
			var thisAccordion = $('#side_accordion');
			thisAccordion.find('.accordion-heading').removeClass('sdb_h_active');
			var thisHeading = thisAccordion.find('.accordion-body.in').prev('.accordion-heading');
			if(thisHeading.length) {
				thisHeading.addClass('sdb_h_active');
			}
		}
    };

	//* tooltips
	gebo_tips = {
		init: function() {
			var shared = {
				style		: {
                    classes: 'ui-tooltip-shadow ui-tooltip-tipsy'
				},
				show		: {
					delay: 100,
					event: 'mouseenter focus'
				},
				hide		: { delay: 0 }
			};
			if($('.ttip_b').length) {
				$('.ttip_b').qtip( $.extend({}, shared, {
					position	: {
						my		: 'top center',
						at		: 'bottom center',
						viewport: $(window)
					}
				}));
			}
			if($('.ttip_t').length) {
				$('.ttip_t').qtip( $.extend({}, shared, {
					position: {
						my		: 'bottom center',
						at		: 'top center',
						viewport: $(window)
					}
				}));
			}
			if($('.ttip_l').length) {
				$('.ttip_l').qtip( $.extend({}, shared, {
					position: {
						my		: 'right center',
						at		: 'left center',
						viewport: $(window)
					}
				}));
			}
			if($('.ttip_r').length) {
				$('.ttip_r').qtip( $.extend({}, shared, {
					position: {
						my		: 'left center',
						at		: 'right center',
						viewport: $(window)
					}
				}));
			}
		}
	};
    
    //* popovers
    gebo_popOver = {
        init: function() {
            $(".pop_over").popover();
        }
    };
    
    //* breadcrumbs
    gebo_crumbs = {
        init: function() {
            $('#jCrumbs').jBreadCrumb({
                endElementsToLeaveOpen: 0,
                beginingElementsToLeaveOpen: 0,
                timeExpansionAnimation: 500,
                timeCompressionAnimation: 500,
                timeInitialCollapse: 500,
                previewWidth: 30
            });
        }
    };
	
	//* external links
	gebo_external_links = {
		init: function() {
			$("a[href^='http']").each(function() {
				$(this).attr('target','_blank').addClass('external_link');
			});
		}
	};
	
	//* style switcher
    gebo_style = {
        init: function() {
            $('.style_switcher a').click(function() {
                $('#link_theme').attr('href','css/'+$(this).attr('title')+'.css');
                $('.style_switcher a').removeClass('th_active');
                $(this).addClass('th_active');
            });
        }
    };
    
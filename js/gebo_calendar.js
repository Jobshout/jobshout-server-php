/* [ ---- Gebo Admin Panel - calendar ---- ] */

	$(document).ready(function() {
		gebo_calendar.init();
		//* resize elements on window resize
		var lastWindowHeight = $(window).height();
		var lastWindowWidth = $(window).width();
		$(window).on("debouncedresize",function() {
			if($(window).height()!=lastWindowHeight || $(window).width()!=lastWindowWidth){
				lastWindowHeight = $(window).height();
				lastWindowWidth = $(window).width();
				//* rebuild calendar
				$('#calendar').fullCalendar('render');
			}
		});
	});
	
	//* calendar
	gebo_calendar = {
		init: function() {
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			var calendar = $('#calendar').fullCalendar({
				header: {
					left: 'prev next',
					center: 'title,today',
					right: 'month,agendaWeek,agendaDay'
				},
				buttonText: {
					prev: '<i class="icon-chevron-left cal_prev" />',
					next: '<i class="icon-chevron-right cal_next" />'
				},
				aspectRatio: 1.5,
				selectable: true,
				selectHelper: true,
				select: function(start, end, allDay) {
					var title = prompt('Event Title:');
					if (title) {
						calendar.fullCalendar('renderEvent',
							{
								title: title,
								start: start,
								end: end,
								allDay: allDay
							},
							true // make the event "stick"
						);
					}
					calendar.fullCalendar('unselect');
				},
				editable: true,
				theme: false,
				events: [
					{
						title: 'All Day Event',
						start: new Date(y, m, 1)
					},
					{
						title: 'Long Event',
						start: new Date(y, m, d-5),
						end: new Date(y, m, d-2)
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(2011, 11, 7, 16, 0),
						allDay: false
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d-16, 16, 0),
						allDay: false
					},
					{
						title: 'Meeting',
						start: new Date(2011, 10, 21, 10, 30),
						end: new Date(2011, 10, 21, 12, 30),
						allDay: false
					},
					{
						title: 'Lunch',
						start: new Date(y, m, d, 12, 0),
						end: new Date(y, m, d, 14, 0),
						allDay: false
					},
					{
						title: 'Birthday Party',
						start: new Date(y, m, d+1, 19, 0),
						end: new Date(y, m, d+1, 22, 30),
						allDay: false
					},
					{
						title: 'Click for Google',
						start: new Date(y, m, 28),
						end: new Date(y, m, 29),
						url: 'http://google.com/'
					}
				],
				eventColor: '#bcdeee'
			});
		}
	};
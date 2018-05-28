<h1>Calendario </h1>

<link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='fullcalendar/lib/moment.min.js'></script>
<script src='fullcalendar/fullcalendar.min.js'></script>
<script src='fullcalendar/lang/it.js'></script>

		<script>
 $("#container").show(); 

			$(document).ready( function() {
				//Setup the ViewNavigator

		
		$('#calendar').fullCalendar({
			 lang: 'it',
			 header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			//defaultDate: '2015-03-14',
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				
				var title = prompt('Inserisci scadenza:');
				var eventData;
				if (title) {
					eventData = {
						title: title,
						start: start,
						end: end
					};
					//$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
					$('#calendar').fullCalendar('renderEvent',
				{
					id: 2,
					title: 'title',
					start: start,
					end: end,
					allDay: true
				},
				true
			);
					console.log(eventData.start.format('DD/MM/YYYY'));
				}
				$('#calendar').fullCalendar('unselect');
			},
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: {
				url: 'fullcalendar/demos/php/get-events.php',
				error: function() {
					$('#script-warning').show();
				}
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}

		});
		
	});

</script>
<style>

	#calendar {
			margin: 20px;
	}

</style>
	</head>
	<body>
	<div id='calendar'></div>
	</body>
</html>

<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>



<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
	<!-- Tablesorter: required -->
	<link rel="stylesheet" href="css/theme.blue.css">
	<script src="js/jquery.tablesorter.js"></script>
	<script src="js/widgets/widget-resizable.js"></script>
	<script src="js/widgets/widget-storage.js"></script>

	<style id="css">th.tablesorter-header.resizable-false {
	background-color: #e6bf99;
}
/* ensure box-sizing is set to content-box, if using jQuery versions older than 1.8;
 this page is using jQuery 1.4 */
*, *:before, *:after {
	-moz-box-sizing: content-box;
	-webkit-box-sizing: content-box;
	box-sizing: content-box;
}
/* overflow table */
.wrapper {
	overflow-x: auto;
	overflow-y: hidden;
	width: 450px;
}
.wrapper table {
	width: auto;
	table-layout: fixed;
}
.wrapper .tablesorter td {
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	min-width: 10px;
}
.wrapper .tablesorter th {
	overflow: hidden;
	text-overflow: ellipsis;
	min-width: 10px;
}</style>

<script id="js">$(function() {

	$('#data-sheet').tablesorter({
		theme : 'blue',
		// initialize zebra striping and resizable widgets on the table
		widgets: [ 'zebra', 'resizable', 'stickyHeaders' ],
		widgetOptions: {
			storage_useSessionStorage : true,
			resizable_addLastColumn : true
		}
	});

	// overflow table
	$('table').tablesorter({
		theme: 'blue',
		widgets: ['zebra', 'resizable', 'stickyHeaders'],
		widgetOptions: {
			resizable_addLastColumn : true,
			storage_useSessionStorage : true,
			resizable_widths : [ '100px', '60px', '30px', '50px', '60px', '140px' ]
		}
	});

	$('.full-width-table').tablesorter({
		theme : 'blue',
		// initialize zebra striping and resizable widgets on the table
		widgets: [ 'zebra', 'resizable', 'stickyHeaders' ],
		widgetOptions: {
			resizable: true,
			// These are the default column widths which are used when the table is
			// initialized or resizing is reset; note that the "Age" column is not
			// resizable, but the width can still be set to 40px here
			resizable_widths : [ '10%', '10%', '40px', '10%', '100px' ]
		}
	});

});</script>

<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); ?>


<?php /* Inclusione Pagina */ if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else {
	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

 include("mod_home.php");


 } ?>


<?php if(!isset($_GET['external'])) include("../../fl_inc/footer.php"); ?>
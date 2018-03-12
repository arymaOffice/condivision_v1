<?php


$lead_id = check($_GET['lead_id']);
$date_del_lead = GQS($tables[138],'id,DATE_FORMAT(data_disponibile,"%d/%m/%Y") as data_da_selezionare,note,ambiente_id','lead_id = '.$lead_id);
$lead_info = GQD('fl_leads_hrc','*','id='.$lead_id);
$intestazione = '<h2>Proposta Disponibilità '.$lead_info['nome'].' '.$lead_info['cognome'].'</h2>';

$date_del_lead = GQS($tables[138],'id,DATE_FORMAT(data_disponibile,"%d/%m/%Y") as data_da_selezionare,note,ambiente_id','lead_id = '.$lead_id.' ORDER BY ambiente_id ASC');
$ambienteSelect = 0;
$inputForm = '';


foreach ($date_del_lead as $value) {
   if($ambienteSelect != $value['ambiente_id']) { 
   	$inputForm .= '<tr><td colspan="2"><span class="msg orange">Disponibilità '.$ambienti[$value['ambiente_id']].'</span></td></tr>
   	<tr><td>Data</td><td>Note</td></tr>'; 
 	$ambienteSelect = $value['ambiente_id'];
 }
  $inputForm .=  '<tr><td>'.$value['data_da_selezionare'].'</td><td> '.$value['note'].'</td></tr>';

}
mysql_close(CONNECT);

$content = '

<style type="text/css">
/* Table */

body { 

	border: none;
	background: #FFFFFF;
	padding: 4mm;
	color: black;
	font-size: 14px;
	text-align: center;
	font-family: sans-serif;
	line-height: 160%;
}

table{
	border-collapse: collapse;
	border-spacing: 0 0px;
	border: 1px solid #ccc !important;
	table-layout: auto;
}
tr{

	border:none !important;

}

td{
	font-size:14px;
	border:none !important;
	padding: 5px;
	text-align:left;
}
th{
	padding: 5px;
	text-align:left;
}
.divImg{
	width: 46%;text-align: center;display: inline-block;float:left
}

.blank_row
{
	height: 20px !important; /* overwrites any other rules */
	background-color: #FFFFFF;

}
</style>'; ?>


<page backtop="70mm" backbottom="5mm" backleft="14mm" backright="0mm">

<body>
<page_footer>

<div style=" font-style:  serif; font-size: 9px; color: #666; padding:  0 10px 10px 10px;">   

 
 <table style="width: 100%;">
    <tr>
      <td style="text-align: left; vertical-align: top; width: 70%; font-style: italic; font-size: 9px; "><p>Proposte disponibilità<?php echo ''; ?></p></td>
      <td style="text-align: right;  vertical-align: top;   width: 30%; font-style: italic; font-size: 9px;"><p></p></td>
    </tr>
  </table>

</div>

</page_footer>



<page_header>
<p style="text-align:center; ">
<?php if(defined('LOGO_FATTURA')) { ?><img src="<?php echo LOGO_FATTURA; ?>" alt="" style="width: 200px; " /><?php } ?></p>
</page_header>

<h3><?php echo $intestazione;  ?></h3>
<table style="width:95%; text-align: left;" class="dati">
<?php echo $inputForm;  ?>
</table>


<p style="text-align: center;"><a href="javascript:history.back();" class="button noprint" style="font-size: 16px">
 Indietro </a>
<a href="#" class="button noprint" style="font-size: 16px" onClick="print();">
<i class="fa fa-print"></i> Stampa </a> </p>
</body>
</page>


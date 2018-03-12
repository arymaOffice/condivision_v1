
<?php


require_once('../../fl_core/autentication.php');
if(!isset($_GET['evento_id'])) die('Manca Evento ID');	
$evento_id = check($_GET['evento_id']);
$categoria_prodotto_id = check($_GET['categoria_prodotto']);
include('fl_settings.php');

$nochat = 1;
include("../../fl_inc/headers.php");



	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			
	?>
  
<body style="background: white;">



<style type="text/css"> 

input[type="checkbox"] + label { padding: 5px; display: block; width: 100%; margin: 10px; background: none; font-size: 14px; } </style>
<script>


$(document).ready(function(){


$(".smartSelect").change(function(e){
	
	var url = "mod_opera.php";																			//url della pagin di richiesta
	var type1 = "6";																					//tabella fl_menu_portate
	var type2 = "23";																					//tabella ricettario
	var data_rel = $(this).attr("data-rel");															//id della ricettario
	var data_qta = $(this).attr("data-qta");	
	var prezzo_base = $(this).attr("data-prezzo");		
	var data_prezzo_variabile = $(this).attr("data-prezzo-variabile");	
	var data_note = $(this).attr("data-note");	
	var data_qty = $(this).attr("data-qty");																//quantità
												//iserito o non inserito
	var evento_id = <?php echo $evento_id ?> ;													
	 $("#info").html('Aggiornamento Evento in corso...');
	 $("#info").removeClass( "green orange red" ).addClass( "orange" );
	
	if($(this).is(":checked")) {
        var remove = 0;
        } else { 
        var remove = 1; 
    	}
        
    if(remove == 0 && data_prezzo_variabile == 1) {
    	prezzo_base = prompt("Confermi prezzo base?", prezzo_base);
    	if (prezzo_base === null) {
    	$("#info").html('Operazione annullata');
		$("#info").removeClass( "green orange red" ).addClass( "red" );
		$(this).attr("checked",false);
        return; //esci dalla funzione
   		} else { $("label[for='prodotto"+data_rel+"'] > span").html(prezzo_base);  }
    } 

    if(remove == 0 && data_qty == 1) {
    	data_qty = prompt("Vuoi specificare una quantità?", data_qty);
    	$("label[for='prodotto"+data_rel+"'] > span").html(prezzo_base*data_qty+' ('+data_qty+'x'+prezzo_base+')');
    } 

    if(remove == 0 && data_note == 1) {
    	data_note = prompt("Vuoi aggiungere delle note?", '');
    	$("label[for='prodotto"+data_rel+"'] > div").html('<br>'+data_note);
    } 


	$.ajax({
		type: "GET",
		url: url,
		data: {"type1":type1, "type2":type2, "id1":evento_id, "id2":data_rel, "remove":remove,"valore":prezzo_base,"qty":data_qty,"note":data_note},
		success: function(data){
			$("#info").html('Evento aggiornato');
			$("#info").removeClass( "green orange red" ).addClass( "green" );
		},
		error: function(xhr, status, error){
			console.log(url+type1+status+error);
			$("#info").html('Errore di Aggiornamento');
			$("#info").removeClass( "green orange red" ).addClass( "red" );
				
		}

	});
	
	

	return false;
});

});

</script>
<div id="info" class"msg"></div>
<br class="clear" />
	 
	<?php 
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	

 
			
	
		
			echo '<div>';	
			echo '<h2 style="margin: 5px 0;"><a title="'.$riga['codice'].'">'.ucfirst($riga['label']).'</a></h2>';
  			echo "</div>";

			
			$query2 = "SELECT label,id,prezzo_base,prezzo_variabile,quantita_variabile,richiedi_note FROM `fl_prodotti` WHERE prodotto_id = ".$riga['id'];
			$risultato2 = mysql_query($query2, CONNECT);
			if(mysql_affected_rows() < 1) echo "Nessun elemento inserito!";
			
			while ($riga2 = mysql_fetch_array($risultato2)) 
			{

					$isIn = GQD('fl_synapsy','id,valore',' type1 = 6 AND id1 = '.$evento_id.' AND type2 = 23 AND id2 = '.$riga2['id']);
					$checked = (isset($isIn['valore'])) ? 'checked' : '';

					$type = 'checkbox' ;
					
					echo '<input '.$checked.' class="smartSelect" data-qta="1" data-prezzo="'.$riga2['prezzo_base'].'" data-prezzo-variabile="'.$riga2['prezzo_variabile'].'" data-qty="'.$riga2['quantita_variabile'].'" data-note="'.$riga2['richiedi_note'].'"  data-rel="'.$riga2['id'].'" type="'.$type.'" value="'.$riga2['id'].'" id="prodotto'.$riga2['id'].'" name="prodotto'.$riga['id'].'"><label for="prodotto'.$riga2['id'].'">'.$riga2['label'].' &euro; <span id="price'.$riga2['id'].'" class="price-show">'.$riga2['prezzo_base'].'</span><div></div></label>';	
	
			}
	

	

		  

	}

	
		mysql_close(CONNECT);
		


?>	


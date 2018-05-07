/*
Questo script richiede la libreria fabric.min.js e fabric.ext.js

Crea il canvas con all'interno i tavoli (con determinate caratteristiche)

Questi tavoli sono cliccabili, ma non scalabili 'object:scaling'

al click si possono spostare ma non sovrapporre 'object:moving' gestisce questa feature

al doppio click grazie a fabric.ext.js possiamo gestire questo evento
il quale ci permette di cambiare nome del tavolo per ora

la scalabilità di tutto il canvas è da definire

il form permette di aggiungere tavoli (sino ad un massimo)

*/

$(document).ready(function () {


	/*************************************************/
	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	};
	/*************************************************/

	var canvas = this.__canvas = new fabric.CanvasEx('disposizioneTavoli'); //seleziono il canvas presente nell'html
	canvas.backgroundColor = "white";
	evento_id = 1; 															//evento default
	evento_id = getUrlParameter('evento_id');									//numero evento dall' url
	ambiente_id = getUrlParameter('ambiente_id');									//numero evento dall' url
	localStorage.orientamento = getUrlParameter('orientamento');
	tables = 0;
	counter = 0;

	$.get('mod_opera.php', { evento_id: evento_id, ambiente_id: ambiente_id, info: 1 }, function (data) {
		var parsed = $.parseJSON(data);
		$('#sala').html(parsed.nome_ambiente);
		$('#data').html(parsed.data);
		localStorage.maxTable = 7;
		localStorage.maxTable = parsed.Ntavoli;
		localStorage.ciSono = parsed.ciSono;
		localStorage.idTavoli = $.makeArray(parsed.idTavoli);
		localStorage.idTavoliOpachi = $.makeArray(parsed.idTavoliOpachi);
		localStorage.idTavoliOpachi2 = parsed.idTavoliOpachi;


		if (parsed.idTavoliOpachi != null) { // se l'evento è gia settato recupero i tavoli

			$.each(parsed.idTavoliOpachi, function (index, value) {
				createTableOneP(value['numero_tavolo'], value['evento_id']); //funzione che accetta due parametri
				counter++;
			});

		}//end if

		if (localStorage.ciSono != '0') { // se l'evento è gia settato recupero i tavoli



			$.each(parsed.idTavoli, function (index, value) {
				createTableOneP(value['numero_tavolo']);
				tables = value['numero_tavolo'];
				counter++;
			});

		}//end if


		//canvas non editabile se settato layout
		layout = getUrlParameter('layout');

		if (layout == 1 || layout == '1') {
			canvas.deactivateAll();
			canvas.renderAll();
			canvas.forEachObject(function (object) {
				object.selectable = false;
			});//end foreach
		}//end if
	});//end get request

	var maxTable = Math.round(Number(localStorage.maxTable) / 2);		//MASSIMO DI TAVOLI DIVISISO NELLE DUE FILE


	orientamento = getUrlParameter('orientamento');


	if (orientamento == 1) {
		canvasHeight = 1200;
		canvasWidth = 792;
		canvas.setHeight(canvasWidth);										//setto altezza
		canvas.setWidth(canvasHeight);


	} else {
		canvasHeight = 1200;
		canvasWidth = 792;

		canvas.setHeight(canvasHeight);										//setto altezza
		canvas.setWidth(canvasWidth);
	}
	canvas.selection = false; 											// non è possibile selezionare il canvas
	snap = 20; 															//Pixels to snap (da gestire in percentuale)
	textColor = '#333';												//colore del testo
	counterName = 0;													//variabile d'appoggio
	definedMargin = canvasHeight * 0.17; 									//si è dato un margine dall'alto
	tableName = 0 														//id univoco del tavolo

	var startLeft = canvasWidth * 0.04; 									//punto di partenza del primo tavolo
	var startTop = canvasWidth * 0.06;									//punto di partenza del primo tavolo
	var radius = canvasWidth * 0.05; 										//creo il raggio in base all'altezza
	var padding = canvasWidth * 0.055;									//padding per tenere i tavoli distanti
	var fila = 1;														//prima fila
	var textTop = radius * 0.10;											//margine dall'alto del testo


	/*------------------------------------TIPI DI TAVOLI ------------------------------------------------------*/

	// create a circle object
	var tavoloBase = new fabric.Circle({								//tavolo preso a modello
		radius: radius, 												//ogni tavolo ha diametro 28.+
		left: startLeft,												//punto di partenza da sinistra
		top: startTop,													//punto di partenza dall'alto
		padding: padding, 												//padding del cerchio
		stroke: '#333',												//colore bordi del cerchio
		strokeWidth: 1,													//grandezza dei bordi
		fill: '#fff'													//colore che riempie il cerchio
	});

	// create a rectangle or square object
	var tavoloBase1 = new fabric.Rect({
		left: startLeft,												//punto di partenza da sinistra
		top: startTop,													//punto di partenza dall'alto
		padding: padding, 												//padding del rettangolo
		stroke: '#333',												//colore bordi del rettangolo
		strokeWidth: 1,													//grandezza dei bordi
		fill: '#fff'
	});

	// create a ellipse object
	var tavoloBase2 = new fabric.Ellipse({
		left: startLeft,												//punto di partenza da sinistra
		top: startTop,													//punto di partenza dall'alto
		padding: padding, 												//padding dell'ellisse
		stroke: '#333',												//colore bordi dell'ellisse
		strokeWidth: 1,													//grandezza dei bordi
		fill: '#fff'
	});

	/*------------------------------------FINE TIPI DI TAVOLI --------------------------------------------------*/

	var text = new fabric.Text('tavolo', { 								//testo di default sui tavoli
		fontFamily: 'Calibri',											//famiglia del testo
		fontSize: 13,													//grandezza del testo
		fill: textColor,												//colore
		left: 0,														//margine sinistro per il primo tavolo
		top: 0												//margine dall'alto per il primo tavolo

	});

	fabric.Object.prototype.set({										// Do some initializing stuff
		transparentCorners: false,										//angoli non trasparenti
		cornerColor: '#4eb997',											//colore degli angoli
		cornerSize: 7,													//grandezza degli angoli
		padding: 10,													//padding dalla forma
		lockScalingX: false, 											//blocca il resize
		lockScalingY: false, 											//blocca il resize
		hasRotatingPoint: true
	});

	/*+++++++++++++++++++++++++++++++++++++++++ GESTIONE EVENTI ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	/*--------------------------------------------MODALE-----------------------------------------------------------------------*/
	dialog = $("#dialog-form").dialog({								//caratteristiche del modale
		autoOpen: false,												//se devi auto aprirsi
		height: 600,													//altezza
		width: 600,														//larghezza
		modal: true,													//è un modale
		closeOnEscape: false,											//non si chiude se si va fuori dal modale
		position: { my: "center", at: "center", of: window },			//posizione nello schermo del modale
		resizable: true,
		draggable: true
	});

	/*--------------------------------------------DOPPIO CLICK OGGETTI------------------------------------------------------------*/
	canvas.on('mouse:dblclick', function (options) { 					//evento doppio click
		var object = canvas.getActiveObject();							//mi ritorna l'oggetto corrente
		if (object != null) {
			var valore_da_inviare = evento_id;
			if (object._objects[0].diverso != false) { valore_da_inviare = object._objects[0].diverso; };
			//se si è cliccato su un oggeto si apre il modale
			dialog.dialog("open");									//open modale
			$('#addCommesale input[name=cognome]').focus();				//seleziona il primo campo cognome
			var tableId = object._objects[0].name;						//recupero id del tavolo
			$('#tableId').val(tableId);
			$('#evento_id').val(valore_da_inviare);
			$('#color').attr('data-rel', object._objects[0].id);
			$("#catTable option[value='" + object._objects[0].categoria + "']").attr('selected', 'selected');//seleziono l'opzione del tavolo
			$('#numTable').val(object._objects[0].numero);				//numero selezionato
			$('#U_name').val(object._objects[3].text);
			$('#color').val(object._objects[0].stroke);
			setTimeout(chiedoOspiti(tableId, valore_da_inviare), 5000);
			$('#dialog-form').on('dialogclose', function (e) {
				object._objects[0].stroke = $('#color').val();
				canvas.deactivateAll();
				canvas.renderAll();
			});
			return true;
		}
	});

	/*--------------------------------------------CAMBIO NOME TAVOLO NEL MODALE----------------------------------------------------*/
	$('#U_name').keyup(function (e) {           						//azione nel modale riguardo il nome del tavolo
		var retrievedtext = $('#U_name').val();							//recupero il testo
		var object = canvas.getActiveObject();							//prendo l'oggeto selezionato
		var valore_da_inviare = evento_id;
		if (object._objects[0].diverso != false) { valore_da_inviare = object._objects[0].diverso; };
		var tableId = object._objects[0].name;							//recupera id del tavolo nel canvas
		$.get('mod_opera.php', { tableNameUser: escape(retrievedtext), tableId: tableId, evento_id: valore_da_inviare, ambiente_id: ambiente_id });	//inserisce il tavolo con il nome scritto
		object._objects[3].setText(retrievedtext);//setto il testo ricevuto nel tavolo
	});

	$('#catTable').change(function (e) {
		var categoria = $("#catTable option:selected").text();        //recupero la categoria cambiata
		var object = canvas.getActiveObject();							//prendo l'oggeto selezionato
		var valore_da_inviare = evento_id;
		if (object._objects[0].diverso != false) { valore_da_inviare = object._objects[0].diverso; };
		var numero = object._objects[0].numero;								//recupero il numero
		var tableId = object._objects[0].name;							//recupera id del tavolo nel canvas
		object._objects[0].set('categoria', categoria);
		$.get('mod_opera.php', { ambiente_id: ambiente_id, categoria: categoria, tableId: tableId, evento_id: valore_da_inviare });	//inserisce il tavolo con il nome della categoria
		object._objects[2].setText(categoria + ' ' + numero);//setto il testo ricevuto nel tavolo
	});

	$('#numTable').keyup(function (e) {
		var object = canvas.getActiveObject();							//prendo l'oggeto selezionato
		var valore_da_inviare = evento_id;
		if (object._objects[0].diverso != false) { valore_da_inviare = object._objects[0].diverso; };
		var categoria = object._objects[0].categoria;        			//recupero la categoria cambiata
		var numero = $("#numTable").val();								//recupero il numero
		var tableId = object._objects[0].name;							//recupera id del tavolo nel canvas
		object._objects[0].set('numero', numero);
		$.get('mod_opera.php', { ambiente_id: ambiente_id, numero: numero, tableId: tableId, evento_id: valore_da_inviare });	//inserisce il tavolo con il nome della categoria
		object._objects[2].setText(categoria + ' ' + numero);//setto il testo ricevuto nel tavolo
	});

	/*--------------------------------------------OSPITI DEL TAVOLO----------------------------------------------------*/
	function chiedoOspiti(table, evento_id) {
		$('#nascosta').html('');										//pulisce i precendeti commensali
		$.get('mod_opera.php', { chiedoOspiti: 1, table: table, evento_id: evento_id, ambiente_id: ambiente_id }, //richiede gli ospiti da mod_opera
			function (data) {
				var parsed = $.parseJSON(data);							//parsa i risultati
				var template = '';										//variabile vuota del template
				$.each(parsed.result, function (index, el) {				//crea per ogni commensale il suo template
					template += '<div class="rigaOspite"><span style="float: left;width:22%;">' + el.tipo_commensale + '</span><span style="float: right;width:60%;text-align:right;word-spacing:30px;margin-right:82px;">A B S H</span><br><br><span style="float: left;width: 60%;margin-top: -14px;">' +
						'<input type="text" name="cognome" placeholder="Cognome*" id="' + el.tcId + '"  class="readyTochange "  data-rel="' + el.tcId + '" data-gtx="128"  value="' + unescape(el.cognome) + '" style="width: 40%;padding: 10; margin-right: 5px;height: 10px;" required>' +
						'<input type="text" name="nome" value="' + el.nome + '" id="' + el.tcId + '"  class="readyTochange updateField"  data-rel="' + el.tcId + '" data-gtx="128" style="height: 10px;width: 40%;padding: 10;" placeholder="Nome"><br><br>' +
						'<input type="text" name="note_intolleranze" id="' + el.tcId + '"  class="readyTochange "  data-rel="' + el.tcId + '" data-gtx="128" value="' + unescape(el.note_intolleranze) + '" style="width: 82%; margin-top: -5px;height: 10px;" placeholder="Note intolleranze"></span><span style="float: left;margin-top: -14px; width: 36%; text-align: right;">' +
						'<input id="' + el.tcId + '" class="readyTochange "  data-rel="' + el.tcId + '" data-gtx="128" style="width: 20%;height: 10px;	float: left;margin: 0px 5px 5px 0px;" type="number" name="adulti" value="' + el.adulti + '">' +
						'<input id="' + el.tcId + '" class="readyTochange "  data-rel="' + el.tcId + '" data-gtx="128" style="width: 20%;height: 10px;	float: left;margin: 0px 5px 5px 0px;" type="number" value="' + el.bambini + '" name="bambini">' +
						' <input id="' + el.tcId + '" class="readyTochange "  data-rel="' + el.tcId + '" data-gtx="128"  style="width: 20%;height: 10px;	float: left;margin: 0px 5px 5px 0px;" type="number" value="' + el.sedie + '" name="sedie">' +
						' <input id="' + el.tcId + '" class="readyTochange "  data-rel="' + el.tcId + '" data-gtx="128"  style="width: 20%;	height: 10px;float: left;margin: 0px 5px 5px 0px;" type="number" value="' + el.seggioloni + '" name="seggioloni">' +
						'</span>' +
						'<button class="button" style="color:#cb2c2c !important; background: none;" id="del" data-rel="' + el.tcId + '"><i class="fa fa-times fa-2x"></i></button></div>';

				});
				localStorage.adulti = (parsed.resultTOT != null) ? parsed.resultTOT['aTot'] : '0';
				localStorage.bambini = (parsed.resultTOT != null) ? parsed.resultTOT['bTot'] : '0';
				localStorage.sedie = (parsed.resultTOT != null) ? parsed.resultTOT['sTot'] : '0';
				localStorage.seggioloni = (parsed.resultTOT != null) ? parsed.resultTOT['hTot'] : '0';
				localStorage.sera = (parsed.resultTOT != null) ? parsed.resultTOT['seraTot'] : '0';
				localStorage.operatori = (parsed.resultTOT != null) ? parsed.resultTOT['opTot'] : '0';
				//riga finale con i totali
				var totale = '<span style="margin-bottom: 0px;position: relative;"><h2>Ospiti al tavolo: ' + localStorage.adulti + ' Adulti, ' + localStorage.bambini + ' Bambini, ' + localStorage.sedie + ' sedie, ' + localStorage.seggioloni + ' seggioloni, ' + localStorage.sera + 'serali, ' + localStorage.operatori + 'operatori</h2>';
				$('#nascosta').append(totale + template); //aggiunge tutto alla div

				var myobject = canvas.getActiveObject();

				var coperti = (parsed.resultTOT != null && localStorage.adulti != '0') ? (parseInt(localStorage.operatori) + parseInt(localStorage.adulti)) + 'A ' : '';
				coperti += (parsed.resultTOT != null && localStorage.bambini != '0') ? localStorage.bambini + 'B ' : '';
				coperti += (parsed.resultTOT != null && localStorage.sedie != '0') ? localStorage.sedie + 'S ' : '';
				coperti += (parsed.resultTOT != null && localStorage.seggioloni != '0') ? localStorage.seggioloni + 'H' : '';

				var serali = (parsed.resultTOT != null && localStorage.sera != '0') ? localStorage.sera + ' Serali' : '';
				var noteInt = (parsed.resultTOT != null && parsed.resultTOT['noteInt'] != null && parsed.resultTOT['noteInt'] != '0') ? '*' : '';


				myobject._objects[1].setText(noteInt);							//setto l'asterisco se ci sono intolleranze
				myobject._objects[4].setText(coperti);							//cambio conteggio persone al tavolo
				myobject._objects[5].setText(serali);							//cambio il testo dei serali

				coperti = '';
				serali = '';
			});//end get request
	}//end chiedoOspiti

	//------------------------------------aggiunge un commensale al tavolo--------------------------------------------------------
	$('#addCommesale').submit(function (event) {
		event.preventDefault();													//evita eventi di default
		var formSerialize = $(this).serializeArray();							//torna tutti i valori del form
		tipo_commensale = formSerialize[7]['value'];
		idTavolo = formSerialize[8]['value'];
		valore_da_inviare = $('#evento_id').val();

		//aggiunge il commesale
		$.get('mod_opera.php', { a: formSerialize[2]['value'], b: formSerialize[3]['value'], s: formSerialize[4]['value'], h: formSerialize[5]['value'], cognome: escape(formSerialize[0]['value']), nome: formSerialize[1]['value'], intolleranze: escape(formSerialize[6]['value']), tipo_commensale: tipo_commensale, tableId: idTavolo, evento_id: valore_da_inviare, ambiente_id: ambiente_id }, function (data) {
			setTimeout(chiedoOspiti(idTavolo, valore_da_inviare), 5000); //aggiorna gli ospiti
			$('#primoCampo').focus();
		});
		$(this)[0].reset();														//pulisce il form
	});
	//-------------------------------------cambiamento dei dati per un commensale ------------------------------------------------
	$(document).on('change', '.readyTochange', function () {
		var idCommensale = $(this).prop('id');
		var nomeCampo = $(this).prop('name');
		var valoreCampo = $(this).val();
		valore_da_inviare = $('#evento_id').val();

		$.get('mod_opera.php', { idCommensale: idCommensale, nomeCampo: nomeCampo, valoreCampo: valoreCampo });
		var tableId = $('#tableId').val();
		setTimeout(chiedoOspiti(tableId, valore_da_inviare), 100000);
	});

	//-------------------------------------crea tavolo----------------------------------------------------------------------------


	$('#createElement').on('click', function () {
		tables++;
		var formserialize = $('#Form').serializeArray();							//torna tutti i valori del form
		var tipo = formserialize[0]['value'];										//tipo di tavolo scelto
		var categoria = formserialize[1]['value']; 									//categoria del tavolo
		var numero = (formserialize[2]['value'] != '') ? formserialize[2]['value'] : null;	//numero utente
		var retrievedtext = formserialize[3]['value'];								//nome tavolo utente
		createTable(canvasHeight / 2, canvasWidth / 2, tables, retrievedtext, 2, tipo, categoria, numero);
		//nuovo tavolo
	});

	$(document).on("click", 'input[type="number"]', function () { //per facilitare il cambiamento dati
		$(this).select();
	});

	//-------------------------------------cancella il commensale dal tavolo-------------------------------------------------------
	$(document).on("click", '#del', function () {
		if (confirm("Vuoi cancellare questo commensale?")) {
			var commensaleId = $(this).attr('data-rel');
			$.get('mod_opera.php', { delete: 1, commensaleId: commensaleId });
		}
		var tableId = $('#tableId').val();
		valore_da_inviare = $('#evento_id').val();
		setTimeout(chiedoOspiti(tableId, valore_da_inviare), 1000);

	});

	//-------------------------------------cancella il tavolo-------------------------------------------------------
	$(document).on("click", '#delTable', function () {
		delTable = $('#tableId').val();
		var activeObject = canvas.getActiveObject(),
			activeGroup = canvas.getActiveGroup();
		valore_da_inviare = $('#evento_id').val();
		if (activeObject) {
			if (window.confirm('Sei sicuro di voler eliminare il tavolo?')) {
				$.get('mod_opera.php', { deleteTable: 1, delTable: delTable, evento_id: valore_da_inviare, ambiente_id: ambiente_id }); //cancella tavolo
				canvas.remove(activeObject);
			}
		}
		else if (activeGroup) {
			if (confirm('Sei sicuro di voler eliminare il tavolo?')) {
				$.get('mod_opera.php', { deleteTable: 1, delTable: delTable, evento_id: valore_da_inviare, ambiente_id: ambiente_id }); //cancella tavolo
				var objectsInGroup = activeGroup.getObjects();
				canvas.discardActiveGroup();
				objectsInGroup.forEach(function (object) {
					canvas.remove(object);
				});
			}
		}
		dialog.dialog("close");									//chiude modale
	});

	//-------------------------------------stampa il canvas-------------------------------------------------------
	$(document).on('click', '#print', function () {
		var data = canvas.toDataURL('image/png');

		$.post('mod_opera.php', { evento_id: evento_id, img: data, ambiente_id: ambiente_id }, function (data) {
			var parsed = $.parseJSON(data);
			if (parsed.esito == 1) {

				//window.open('mod_stampa.php?evento_id='+evento_id);

				$.fancybox({
					href: 'mod_stampa.php?ambiente_id=' + ambiente_id + '&orientamento=' + orientamento + '&evento_id=' + evento_id,
					autoSize: false,
					width: "100%",
					height: "100%",
					title: 'Schema Tavoli',
					'type': 'iframe'
				});

			} else {
				alert('errore nella creazione dell\' immagine');
			}
		});
	});

	/*
	*
	* 		FUNZIONI PER MODIFICA SU CANVAS
	*
	*/


	// //funzioni per far rimanere gli oggetti all'interno del canvas
	// canvas.observe('object:scaling', function (e) { //nel caso si scala
	// 	var obj = e.target;

	// 	var zoomX =obj.zoomX;
	// 	var zoomY = obj.zoomY;


	// 	var tableId = obj._objects[0].id;
	// 	$.get('mod_opera.php', { updateTable: 1, TableId: tableId,zoomX : zoomX, zoomY : zoomY });
	// 	canvas.deactivateAll();
	// 	canvas.renderAll();
	// });


	canvas.on('object:modified', function (options) { //modifica coordinate dell'oggeto spostato

		var numero_canvas = options.target._objects[0].numero_canvas;

		var zoomX = options.target.zoomX;
		var zoomY = options.target.zoomY;


		canvas.deactivateAll();
		canvas.renderAll();

	
		var object = canvas.item(numero_canvas);							//mi ritorna l'oggetto corrente
		
		console.log(object);
		var zoomX = options.target.zoomX;
		var zoomY = options.target.zoomY;


		var tableId = options.target._objects[0].id;
		var x = options.target.left;
		var y = options.target.top;
		var OLDangle = options.target._objects[0].OLDangle;
		var angle = (options.target.get('angle') + OLDangle) % 360;
		options.target._objects[0].OLDangle = angle;
		$.get('mod_opera.php', { updateTable: 1, TableId: tableId, x: x, y: y, angle: angle });


	

	});

	// $(document).on('mouseup', function (e) { 
	// 	var options = canvas.getActiveObject();							//mi ritorna l'oggetto corrente
	// 	if (options != null) {
	// 	var zoomX = options.zoomX;
	// 	var zoomY = options.zoomY;
	// 	var tableId = options._objects[0].id;
	// 	var x = options.left;
	// 	var y = options.top;
	// 	var OLDangle = options._objects[0].OLDangle;
	// 	var angle = (options.get('angle') + OLDangle) % 360;
	// 	options._objects[0].OLDangle = angle;
	// 	$.get('mod_opera.php', { updateTable: 1, TableId: tableId, x: x, y: y, angle: angle,zoomX:zoomX,zoomY:zoomY });

	// 	canvas.deactivateAll();
	// 	canvas.renderAll();
	// 	}

	
	// });




	/*************************************************/
	function createTable(marginTop, marginLeft, name, textInsert, fila, type, categoria, numero) {

		localStorage.marginT = marginTop;
		localStorage.marginL = marginLeft;
		localStorage.nameT = name;
		localStorage.textInsert = textInsert;
		localStorage.type = type;
		localStorage.categoria = categoria;
		localStorage.numero = numero;

		$.get('mod_opera.php', {
			insertTableId: localStorage.nameT, evento_id: evento_id, text: escape(textInsert),
			x: marginLeft, y: marginTop, type: localStorage.type, categoria: categoria, numero: numero, diverso: false, ambiente_id: ambiente_id
		}, function (data) {
			marginTop = localStorage.marginT;							//margine dall'alto del tavolo
			marginLeft = localStorage.marginL;							//margine sinistro del tavolo
			name = localStorage.nameT;									//id univoco del tavolo
			textInsert = localStorage.textInsert;						//nome visualizzato sul tavolo
			a = '';														//adulti
			b = '';														//bambini
			s = '';														//sedie
			h = '';														//seggiolini
			noteInt = '';												//asterisco delle intolleranze
			seraText = '';												//ospiti serali
			specialMargin = 0;
			categoria = localStorage.categoria;							//categoria selezionato
			numero = (localStorage.numero != 'null') ? localStorage.numero : '';	//numero selezionato


			switch (localStorage.type) {
				case '2':
					var newOne = fabric.util.object.clone(tavoloBase); //crea tavolo tondo clonando l'oggetto
					break;
				case '3':
					var newOne = fabric.util.object.clone(tavoloBase1); //crea tavolo quadrato clonando l'oggetto
					newOne.set('height', radius * 2);
					newOne.set('width', radius * 2);
					break;
				case '4':
					var newOne = fabric.util.object.clone(tavoloBase1); //crea tavolo rettangolare clonando l'oggetto
					newOne.set('height', radius * 2);
					newOne.set('width', (radius * 2) * 2);
					specialMargin = (radius * 2) * 2 * 0.2;
					break;
				case '5':
					var newOne = fabric.util.object.clone(tavoloBase2); //crea tavolo ovale clonando l'oggetto
					newOne.set('ry', radius * 1.2);
					newOne.set('rx', radius * 2);
					specialMargin = radius * 2 * 0.45;
					break;
				default:
					var newOne = fabric.util.object.clone(tavoloBase); //crea tavolo tondo clonando l'oggetto
			}

			newOne.set('diverso', false);
			newOne.set("top", Number(marginTop));					//setto margine dall'alto (che cambia per ogni tavolo)
			newOne.set("name", name);									//ed il nome identificativo
			newOne.set("numero", numero);								//numero inserito
			newOne.set("categoria", categoria);							//categoria inserita
			/* testo intolleranze */
			var into = fabric.util.object.clone(text);					//clona il testo base
			into.set("top", Number(marginTop) + radius - 20);			//definisce il margine
			into.setText(noteInt);										//definisco il testo
			into.setColor('red');										//coloro il testo
			into.set({ fontSize: 20 });
			/* testo titolo tavolo */
			var tito = fabric.util.object.clone(text);					//clona il testo base
			tito.set("top", Number(marginTop) + radius - 10);			//definisce il margine
			tito.setText(categoria + ' ' + numero);						//definisco il testo
			/* testo personalizzato */
			var titoUser = fabric.util.object.clone(text);					//clona il testo base
			titoUser.set("top", Number(marginTop) + radius);			//definisce il margine
			titoUser.setText(textInsert);	//definisco il testo
			/* testo commensali */
			var comm = fabric.util.object.clone(text);					//clona il testo base
			comm.set("top", Number(marginTop) + radius + 10);				//definisce il margine
			comm.setText(a + ' ' + b + ' ' + s + ' ' + h);				//definisco il testo
			comm.setColor('red');										//coloro il testo
			/* testo ospiti serali */
			var sera = fabric.util.object.clone(text);					//clona il testo base
			sera.set("top", Number(marginTop) + radius + 20);			//definisce il margine
			sera.setText(seraText);	//definisco il testo
			if (fila == 2) {												//se la fila è 2
				newOne.set('left', Number(marginLeft));				//imposto un margine sinistro
				into.set('left', Number(marginLeft) + radius + specialMargin);//imposto un margine sinistro
				tito.set('left', Number(marginLeft) + radius + specialMargin);//imposto un margine sinistro
				titoUser.set('left', Number(marginLeft) + radius + specialMargin);//imposto un margine sinistro
				comm.set('left', Number(marginLeft) + radius + specialMargin);//imposto un margine sinistro
				sera.set('left', Number(marginLeft) + radius + specialMargin);//imposto un margine sinistro
			}

			newOne.set("numero_canvas", counter++);						//numero nel canavas caricato ora 
			var parsed = $.parseJSON(data);
			
			newOne.set("id", parsed.id);						//id tavolo 



			var GROUP = new fabric.Group([newOne, into, tito, titoUser, comm, sera], {});//tavolo + testo fornamo un gruppo
			canvas.add(GROUP);											//gli aggiungo al canvas
			canvas.renderAll();											//fa il render di tutti gli oggetti\
			return true;
		});

		return true;
	}

	/*************************************************/
	function createTableOneP(name, diverso = false) {

		$.get('mod_opera.php', { ambiente_id: ambiente_id, insertTableId: name, evento_id: evento_id, diverso: diverso }, function (data) {

			var parsed = $.parseJSON(data);
			marginTop = parsed.asse_y;
			marginLeft = parsed.asse_x;
			name = parsed.numero_tavolo;
			zoomX = Number(parsed.zoomX);
			zoomY = Number(parsed.zoomY);
			textInsert = unescape(parsed.nome_tavolo);
			numInsert = (unescape(parsed.numero_tavolo_utente) != 'null' && unescape(parsed.numero_tavolo_utente) != '0') ? unescape(parsed.numero_tavolo_utente) : '';
			textInsertUser = unescape(parsed.nome_tavolo_utente);
			angle = Number(parsed.angolare);

			specialMargin = 0;
			specialTop = 0;


			switch (parsed.tipo_tavolo_id) {
				case '2':
					var newOne = fabric.util.object.clone(tavoloBase); //crea tavolo tondo clonando l'oggetto
					newOne.set('angle', angle);
					newOne.set('OLDangle', angle);
					newOne.set('radius', tavoloBase.radius * zoomY);

					break;
				case '3':
					var newOne = fabric.util.object.clone(tavoloBase1); //crea tavolo quadrato clonando l'oggetto
					newOne.set('height', radius * 2 * zoomY);
					newOne.set('width', radius * 2 * zoomX);
					newOne.set('OLDangle', angle);

					newOne.set('angle', angle);
					break;
				case '4':
					var newOne = fabric.util.object.clone(tavoloBase1); //crea tavolo rettangolare clonando l'oggetto
					newOne.set('height', radius * 2 * zoomY);
					newOne.set('width', (radius * 2) * 2 * zoomX);
					specialMargin = (radius * 2) * 2 * 0.2;
					newOne.set('OLDangle', angle);

					newOne.set('angle', angle);
					if (angle >= 0 && angle <= 90) {
						specialMargin = 10;
					}
					break;
				case '5':
					var newOne = fabric.util.object.clone(tavoloBase2); //crea tavolo ovale clonando l'oggetto
					newOne.set('ry', radius * 1.2 * zoomY);
					newOne.set('rx', radius * 2 * zoomX);
					specialMargin = radius * 2 * 0.45;
					newOne.set('angle', angle);
					newOne.set('OLDangle', angle);
					if (angle >= 0 && angle < 90) {
						specialMargin = 30;
					}
					if (angle >= 90 && angle <= 180) {
						specialMargin = - 70;
					}
					break;
				default:
					var newOne = fabric.util.object.clone(tavoloBase); //crea tavolo tondo clonando l'oggetto
					newOne.set('angle', angle);
					newOne.set('radius', tavoloBase.radius * zoomY);

			}




			a = (parsed.a != null && parsed.a != '0') ? parsed.a + 'A' : '';
			b = (parsed.b != null && parsed.b != '0') ? parsed.b + 'B' : '';
			s = (parsed.s != null && parsed.s != '0') ? parsed.s + 'S' : '';
			h = (parsed.h != null && parsed.h != '0') ? parsed.h + 'H' : '';
			noteInt = (parsed.noteInt != null && parsed.noteInt != '0') ? '*' : '';
			seraTot = (parsed.seraTot != null && parsed.seraTot != '0') ? parsed.seraTot + ' Serali' : '';

			//newOne.set("top", Number(marginTop));					//setto margine dall'alto (che cambia per ogni tavolo)
			newOne.set("name", name);									//ed il nome identificativo
			newOne.set("numero", numInsert);								//numero inserito
			newOne.set("categoria", textInsert);							//categoria inserita
			isOk = /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(parsed.color);
			if (isOk) { newOne.set("stroke", parsed.color); } else { newOne.set("stroke", "#333"); }
			newOne.set("stroke", parsed.color);						//colori bordi 
			newOne.set("id", parsed.id);						//id tavolo 
			newOne.set("numero_canvas", counter);						//numero nel canavas caricato ora 

			/* testo intolleranze */
			var into = fabric.util.object.clone(text);					//clona il testo base
			into.set("top", newOne.top + 5 + specialTop);			//definisce il margine
			into.setText(noteInt);										//definisco il testo
			into.setColor('red');										//coloro il testo
			into.set({ fontSize: 20 });									//setto dimensione del testo
			/* testo titolo tavolo */
			var tito = fabric.util.object.clone(text);					//clona il testo base
			tito.set("top", newOne.top + into.height);			//definisce il margine
			tito.setText(textInsert + ' ' + numInsert);	//definisco il testo
			/* testo personalizzato*/
			var titoUser = fabric.util.object.clone(text);					//clona il testo base
			titoUser.set("top", newOne.top + into.height + tito.height);			//definisce il margine
			titoUser.setText(textInsertUser);	//definisco il testo
			/* testo commensali */
			var comm = fabric.util.object.clone(text);					//clona il testo base
			comm.set("top", newOne.top + into.height + tito.height + titoUser.height);				//definisce il margine
			comm.setText(a + ' ' + b + ' ' + s + ' ' + h);				//definisco il testo
			comm.setColor('red');										//coloro il testo
			/* testo ospiti serali */
			var sera = fabric.util.object.clone(text);					//clona il testo base
			sera.set("top", newOne.top + into.height + tito.height + titoUser.height + comm.height);			//definisce il margine
			sera.setText(seraTot);										//definisco il testo

			newOne.set('diverso', diverso);
			newOne.set('left', Number(marginLeft));					//imposto un margine sinistro

			into.set('left', 10 +newOne.left + specialMargin);//imposto un margine sinistro
			tito.set('left', 10 +newOne.left + specialMargin);//imposto un margine sinistro
			titoUser.set('left', 10 +newOne.left + specialMargin);//imposto un margine sinistro
			comm.set('left', 10 +newOne.left + specialMargin);//imposto un margine sinistro
			sera.set('left', 10 +newOne.left + specialMargin);//imposto un margine sinistro


			//if (diverso != false) { newOne.set('stroke', '#666'); newOne.set('strokeDashArray', [5, 5]); tito.set('fill', '#666'); }
			var GROUP = new fabric.Group([newOne, into, tito, titoUser, comm, sera], {
				left: Number(marginLeft),
				top: Number(marginTop),
				originX: 'center',
				originY: 'center'

			}); //tavolo + testo fornamo un gruppo
			canvas.add(GROUP);											//gli aggiungo al canvas
			canvas.renderAll();											//fa il render di tutti gli oggetti
			return true;
		});

		return true;

	}
	/**************************************************/



}); // on ready

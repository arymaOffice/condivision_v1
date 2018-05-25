<?php
/*
	Classe dedicata alla  gestione degli oggetti
	provenienti dai webhook FB 

	questa classe contiente

	 1° spacchettamento oggetto 

*/

class GeneralFbIntegration {

	 	var $data;
	 	
	 	function __construct($objWebhook){
	 		$this->data = array();
	 		self::readObj($objWebhook);
	 	}

	 	private function readObj($obj){
	 		if(isset($obj['entry'])){
					// da implemetare per più changes
					foreach ($obj['entry'][0]['changes'] as $key => $value) {
						//file contenuto in classi per field
						$classeDaistanziare = $value['field'];

				 		foreach ($value['value'] as $key => $value) {
				 			$this->data[$key] = $value;		 			
				 		}//fine foreach
				 		if(require_once 'classiPerField/'.$classeDaistanziare.'.php'){
				 			$istanzaClasse = new $classeDaistanziare();
				 			$nomeFunzione = $classeDaistanziare."Function";
				 			$istanzaClasse -> $nomeFunzione($this->data);
				 			return true;
				 		}else{
				 			echo "classe non esistente";
				 			mail('asaracino@aryma.it','nuovo elemento fb',$classeDaistanziare);
				 			mail('mfazio@aryma.it','nuovo elemento fb',$classeDaistanziare);
				 		}
					}			 		
			}//fine if

	 	}//fine readObj

}//fine classe

?> 
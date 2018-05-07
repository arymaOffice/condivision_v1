<?php
    require_once('../../fl_core/autentication.php');
  
    if(isset($_GET['transID'])){ 

    //Dati Connessione MySql Client
    $host2 = "192.168.101.235";
    $db2 = "vpnclien_db_client";
    $login2 = "vpnclien_client";
    $pwd2 = "InjuryLonerAidesTie19";
    define( 'CONNECT_CLIENT', connect($host2, $login2, $pwd2,$db2) ); 

    function GRD2($table,$id){
    $query = "SELECT * FROM $table WHERE id = $id LIMIT 1";
    $dati = mysql_fetch_assoc(mysql_query($query,CONNECT_CLIENT));
    return $dati;
    };  


    $scontrino = GRD2('fl_transazioni',check($_GET['transID']));   

    $SRV = $scontrino['srv'];
    //foreach($scontrino as $f => $n) { echo '$'.$f.' = $scontrino[\''.$f.'\'];<br>'; }   
    
    if($_SESSION['usertype'] > 1 && $scontrino['user'] != $_SESSION['number']) { echo "Operazione non consentita!"; exit; }

    $SRV = $scontrino['srv'];
    $OFFERCODE = $scontrino['offercode'];
    $USERCODE = base64_decode($scontrino['usercode']);
    $time = $scontrino['time'];
    $origID = $scontrino['origID'];
    $Trid = $scontrino['Trid'];
    $Rc = $scontrino['Rc'];
    $RcDs = $scontrino['RcDs'];
    $RRN = $scontrino['RRN'];
    $AUTH = $scontrino['Auth'];
    $PINCODE = $scontrino['PinCode'];
    $AMOUNT = numdec($scontrino['amount'],2);
    $Serial = $scontrino['Serial'];
    $Expiration = $scontrino['Expiration'];
    $Receipt = $scontrino['Receipt'];
    $ChkSum = $scontrino['ChkSum'];
    $OfferExpDate = $scontrino['OfferExpDate'];
    $RechargeID = $scontrino['RechargeID'];
    $DATA = mydatetime($scontrino['data_creazione']);

    } else {

    $SRV = (isset($_GET['srv']) && !isset($_GET['prescontrino'])) ? check($_GET['srv']) : 'prescontrino';	
	$PINCODE = (isset($_GET['PinCode'])) ? check($_GET['PinCode']) : '';
	$OFFERCODE = (isset($_GET['offercode'])) ? check($_GET['offercode']) : '';
	$AMOUNT  = (isset($_GET['amount'])) ? check($_GET['amount']) : '';
	$USERCODE  = (isset($_GET['UserCode'])) ? base64_decode(check($_GET['UserCode'])) : '';
	$DATA  = (isset($_GET['data'])) ? check($_GET['data']) : date('d/m/Y H:i');
	$Expiration  = (isset($_GET['Expiration'])) ? check($_GET['Expiration']) : '';
	$AUTH  = (isset($_GET['auth'])) ? check($_GET['auth']) : '';
	$Receipt  = (isset($_GET['Receipt'])) ? check($_GET['Receipt']) : '';
	$ChkSum  = (isset($_GET['ChkSum'])) ? check($_GET['ChkSum']) : '';
	$Serial  = (isset($_GET['Serial'])) ? check($_GET['Serial']) : '';
	$RechargeID  = (isset($_GET['RechargeID'])) ? check($_GET['RechargeID']) : '';
	$origID  = (isset($_GET['OrigId'])) ? check($_GET['OrigId']) : '';
	$RcDs  = (isset($_GET['RcDs'])) ? check($_GET['RcDs']) : '';

	}


	$merchant = '314801700001001';
	$termID = '30122019';
	
    require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

    // get the HTML
    ob_start();
    if(file_exists(dirname(__FILE__).'/template_scontrini/'.$SRV.'.php')){
          include(dirname(__FILE__).'/template_scontrini/'.$SRV.'.php');             //template dello scontrino
        }else{
          include(dirname(__FILE__).'/template_scontrini/default.php');             //template dello scontrino
    }

    $content = ob_get_clean();
    
  
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 0);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($SRV.'.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
?>
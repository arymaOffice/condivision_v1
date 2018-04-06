<?php

class ParseMyJson{
    
    static private $flag_th_printed = false;

    /*--------------------------------------- FUNZIONE CHE PRENDE UN JSON E LO RESTITUISCE IN FORMATO TABELLA HTML --------------------------------------------------*/
    static function jsonToTable($json){
        $array_appoggio = json_decode($json,true);
        echo "<table>";
            //echo "<tr>";
                self::constructTD($array_appoggio);
            //echo "</tr>";
        echo "</table>";
        
    }

    /*---------------------------------------------- FUNZIONE CHE COSTRUISCE LE RICHE E ICAMPI IN MODO RICORSIVO ----------------------------------------------------*/
    function constructTD($array){
        self::$flag_th_printed;
            
        echo "<tr>";//costruisco le intestazioni della tavella
        foreach ($array as $chiave => $valore) {
            if(is_array($chiave)){
                self::constructTD($chiave); 
            }else{
                if(is_array($valore)){
                    self::constructTD($valore);
                }else{
                    if(!self::$flag_th_printed){
                        if(isset($chiave)){
                            echo "<th style='border:1px solid #000;'>$chiave</th>";               
                        }
                    }
                }
            } 
        }
        self::$flag_th_printed = true;
        echo "</tr>";
        
        echo "<tr>";//costruisco i campi della tabella
        foreach ($array as $chiave => $valore) {
            if(is_array($chiave)){
                self::constructTD($chiave); 
            }else{
                if(is_array($valore)){
                    self::constructTD($valore);
                }else{
                    echo "<td style='border:1px solid #000;'>$valore</td>";
                }
            }
        }
        echo "</tr>";
    }
}

?>
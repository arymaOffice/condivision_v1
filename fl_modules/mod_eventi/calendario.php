
<style>
#tabella_calendario{
    
    margin-left:18px
}

.calendario{
    border:  1px solid #000;
    width: 170px;
    height: 248px ;
    float: left;
    overflow: hidden;
}

#calendar_content{
    margin-top:10px;
    border: 1px solid #000;
}
#calendar_content h1, h2{
    text-align: center;
}

.calendario td {
    width:10px;
    height: 13px;
    border: 1px solid #000;
    padding: 2px;
    min-height: 25px;
    font-size: 84%;
}

</style>


<page backtop="18mm" backbottom="18mm" backleft="4mm" backright="4mm" style="">

<page_footer>
    <div style=" font-style:  serif; font-size: 9px; color: #666; padding:  0;">                                 
        <p style="text-align:  center;  font-style: italic; font-weight: normal; ">
            <?php echo FOOTER_STAMPA; ?>
        </p>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left; vertical-align: top; width: 70%; font-style: italic; font-size: 9px; ">
                    <p>
                        Calendario Disponibilità 
                    </p>
                </td>
                <td style="text-align: right;  vertical-align: top;   width: 30%; font-style: italic; font-size: 9px;">
                    <?php echo client; ?>
                </td>
            </tr>
        </table>
    </div>
</page_footer>





<div id="calendar_content">
    <h1><?php echo client; ?></h1>
    <h2><?php  echo  $anno; ?></h2>
    <div id="wrapper_calendario">
    <?php
        require_once 'CalendarEvents.php';

            
            
  

            echo CalendarEvents::getEvents($tables[6],$anno,@$_GET['mesi'],@$_GET['ambienti']);                                          //prelevo il calendario senza i giorni in cui ci sono eventi    
            //echo CalendarEvents::getCalendar($anno);                                                      //calendario completo
     
    ?>
    </div>
</div>

</page>
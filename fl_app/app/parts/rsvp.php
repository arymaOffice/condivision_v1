<?php require_once('./code/rsvp.php');?>

    <!-- CONTACT -->
    <article id="rsvp" class="isg-panel">
        <div class="isg-panel-inner" data-bordercolor="#D691B7">
            <!-- CLOSE ICON -->
            <div class="isg-panel-close">
                <span class="fa fa-times-circle-o"></span>
            </div>
            <!-- ROTATED TEXT -->
            <div class="isg-panel-rotated-text">
                <span class="isg-panel-rotated-text-inner"><?php echo $mod_3_subtitle ?></span>
            </div>
            <!-- UP ICON -->
            <div class="isg-panel-up">
                <span class="fa fa-arrow-circle-o-up"></span>
            </div>
            <!-- PANEL IMG
            <div class="isg-panel-img">
               <img src="./images/logo.jpg" alt="Logo" />
           </div>-->
           <!-- PANEL CONTENT -->
           <h2><?php echo $mod_3_title ?></h2>
          
           <!-- TABLE -->
           <div class="isg-circle-icon-box">
            <div class="isg-circle-icon-left">
                <div class="isg-circle-icon-container">
                    <a href="#" class="fa fa-calendar-check-o"></a>
                </div>
                <div class="isg-circle-icon-text">
                    <h5><?php echo $mod_3_subtitle ?></h5>
                   <?php echo $mod_3_content ?>
                </div>
            </div>
        </div>

        <hr/>
        <!-- CONTACT FORM -->
        <form id="ajax-form1" action="code/insertCommensale.php" method="post">
            <label>Nome e Cognome</label>
            <input type="text" name="sendername" id="sendername" required="required" maxlength="50" />
            <label>Cellulare</label>
            <input type="text" name="senderphone" id="senderphone" maxlength="50" />
            <label>Email</label>
            <input type="email" name="senderemail" id="senderemail" required="required" maxlength="50" />
  
            <label>Parteciperai?</label>
            <select id="opzioni" name="opzioni">
                <option value="0"">Seleziona  risposta</option>
                <option value="4">Parteciperò al vostro matrimonio</option>
                <option value="7">Parteciperò solo alla cerimonia</option>
                <option value="8">Non potrò partecipare</option>
            </select>
            <div id="showOnly" style="display: none">
                <label  style="width: 29%;display: inline-block;margin: 0 10px 0 0;">Adulti</label>
                <label style="width: 29%;display: inline-block;margin: 0 10px 0 0;">Bambini</label>
                <label style="width: 29%;display: inline-block;">Neonati</label>

                <input style="width: 29%;display: inline-block;margin:0 10px 0 0;" type="number" name="adulti" value="2">

                <input style="width: 29%;display: inline-block;margin: 0 10px 0 0;" type="number" name="bambini" value="1">

                <input style="width: 29%;display: inline-block;" type="number" name="neonati" value="0">

                <label>Vuoi segnalarci intolleranze o esigenze particolari?</label>
                <textarea name="sendermessage" id="sendermessage" placeholder="Es: No Lattosio, Sedia alta per bimbi"></textarea>
            </div>

        
            <div class="submit-container">
                <input type="submit" id="sForm" class="isg-button" value="Invia" /> <i id="ajax-spinner1" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>
            </div>
        </form>
        <div id="form-messages1"></div>
    </div>
</article>
<script type="text/javascript" src="./js/ajax-contact-form.js"></script>
<script type="text/javascript">
    jQuery('#opzioni').change(function (){
       if($(this).val() == 4 || $(this).val() == 5 ){
        $('#showOnly').css('display','block');
       }else{
        $('#showOnly').css('display','none');
       }
    });
</script>

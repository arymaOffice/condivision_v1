<?php require_once('./code/locations.php');?>

    <!-- CONTACT -->
    <article id="contact" class="isg-panel">
        <div class="isg-panel-inner" data-bordercolor="#B5C0BE">
            <!-- CLOSE ICON -->
            <div class="isg-panel-close">
                <span class="fa fa-times-circle-o"></span>
            </div>
            <!-- ROTATED TEXT -->
            <div class="isg-panel-rotated-text">
                <span class="isg-panel-rotated-text-inner"><?php echo $mod_5_subtitle ?></span>
            </div>
            <!-- UP ICON -->
            <div class="isg-panel-up">
                <span class="fa fa-arrow-circle-o-up"></span>
            </div>
            <!-- PANEL IMG -->
            <div class="isg-panel-img">
            </div>
            <!-- PANEL CONTENT -->
            <h2><?php echo $mod_5_title ?></h2>
            <h4><?php echo $mod_5_subtitle ?></h4>
            <?php echo $mod_5_content ?>
            <br>
            <br>
            <!-- TABLE -->
            <?php echo $pDamostrare; ?>

            <hr/>
        <div id="form-messages" style="display: block;"><a href="#rsvp" onclick="$('#ajax-form1').css('display','block'); " class="isg-panel-open">Conferma partecipazione</a> per rimanare aggiornato sul nostro evento!</div>

        </div>
    </article>

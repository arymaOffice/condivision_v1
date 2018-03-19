<?php require_once('./code/wishlist.php'); ?>
    <!-- ABOUT -->
    <article id="wishlist" class="isg-panel">
        <div class="isg-panel-inner" data-bordercolor="rgb(154, 181, 186) ">
            <!-- CLOSE ICON -->
            <div class="isg-panel-close">
                <span class="fa fa-times-circle-o"></span>
            </div>
            <!-- ROTATED TEXT -->
            <div class="isg-panel-rotated-text">
                <span class="isg-panel-rotated-text-inner"><?php echo $mod_4_subtitle ?></span>
            </div>
            <!-- UP ICON -->
            <div class="isg-panel-up">
                <span class="fa fa-arrow-circle-o-up"></span>
            </div>
            <!-- PANEL CONTENT -->
            <h2><?php echo $mod_4_title ?></h2>
            <h4><?php echo $mod_4_subtitle ?></h4>
            <?php echo $mod_4_content ?>
            <!-- TABLE -->



            <!-- RESPONSIVE TABLE -- >

            <!-- Responsive table starts here -->
  <!-- For correct display on small screens you must add 'data-title' to each 'td' in your table -->
  <div class="table-responsive-vertical shadow-z-1">

<!--

   <h5>Lista Viaggio</h5>
  <table id="table" class="table table-hover table-mc-light-blue">

      <tbody>
        <tr>
          <td data-title="ID"><strong></strong></td>
          <td data-title="Name">Puoi partecipare versando una quota sul conto dell'agenzia VIAGGI TOUR SRL</td>
          <td data-title="Link">
            <strong></strong>
          </td>

        </tr>
         <tr>
          <td data-title="ID"><strong></strong></td>
          <td data-title="Name">IBAN: 0000-0000-0000-0000-0000</td>
          <td data-title="Link">
            <a href="" target="_blank"><strong></strong></a>
          </td>

      </tbody>
    </table>
<br><br>
-->
<?php echo $pDamostrare ?>



  </div>



        </div>
    </article>

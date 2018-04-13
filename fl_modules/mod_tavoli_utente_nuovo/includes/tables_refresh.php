<?php
require('../config/query.config.php');

?>

<?php foreach($result_tavoli as $table) : ?>
  <div class="table" data-rel="<?php echo $table['nome_tavolo'].$table['numero_tavolo']; ?>">
    <div class="table-header">
      <p class="table-id" hidden><?php echo $table['id']; ?></p>
      <p class="table-name"><strong><?php echo $table['nome_tavolo'].' '.$table['numero_tavolo']; ?></strong></p>
    </div>
    <div class="table-body connectedSortable" data-rel="<?php echo $table['id'] ?>">
      <?php
        foreach($result_commensali as $guest) : ?>
          <?php if ($guest['tavolo_id'] > 0) : ?>
            <?php if ($guest['tavolo_id'] == $table['id']) : ?>
              <div id="<?php echo $guest['id'] ?>" tavolo-id="<?php echo $guest['tavolo_id'] ?>" class="guest" >
                <p class="family-name"><?php echo $guest['nome']." ".$guest['cognome'] ?></p>
                <p class="number-adults"><?php echo $guest['adulti'] ?></p>
                <p class="number-babies"><?php echo $guest['bambini'] ?></p>
                <p class="number-highchair"><?php echo $guest['seggioloni'] ?></p>
                <p class="number-intolerant"><?php echo $guest['note_intolleranze'] ?></p>
              </div>
            <?php endif; ?>
          <?php endif; ?>
      <?php endforeach; ?> 
    </div>
  </div>
<?php endforeach; ?>


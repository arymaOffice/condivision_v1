<?php
  require('../config/query.config.php');
?>

<?php foreach($result_commensali as $guest) : ?>
  <div class="guest" id="<?php echo $guest['id'] ?>"  tavolo-id="<?php echo $guest['tavolo_id'] ?>">
    <p class="family-name"><?php echo $guest['nome'].' '.$guest['cognome'] ?></p>
    <p class="number-adults"><?php echo $guest['adulti'] ?></p>
    <p class="number-babies"><?php echo $guest['bambini'] ?></p>
    <p class="number-highchair"><?php echo $guest['seggioloni'] ?></p>
    <p class="number-intolerant"><?php echo $guest['note_intolleranze'] ?></p>
  </div>
<?php endforeach; ?>
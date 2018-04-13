<?php
  require('./config/query.config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Tableau Manager</title>
  <link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css">
  <link href="css/toastr.min.css" rel="stylesheet" type="text/css">
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <script defer src="js/fontawesome-all.js"></script>
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/jquery-ui.min.js"></script>
  <script src="js/toastr.min.js"></script>
  <script src="js/script.js"></script>
</head>

<body>
  <div class="container">
    <div class="guest-section">

      <!-- GUEST MENU SECTION -->
      <div class="guest-menu">
        <div class="searchbox">
          <input type="search" class="search-txt" onkeyup="search()" placeholder="Search">
          <button type="button" id="search-btn">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <div>
          <button type="button" id="add-guest" class="btn add-guest"><i class="fa fa-plus-circle"></i> Add Guest</button>
        </div>
      </div>

      <!-- GUEST LIST HEADER -->
      <div class="guest-list-header">
        <label class="list-item list-name">Name</label>
        <abbr class="list-item" title="Adult">A</abbr>
        <abbr class="list-item" title="Babies">B</abbr>
        <abbr class="list-item" title="Highchair">H</abbr>
        <abbr class="list-item" title="Intolerant">I</abbr>
        <div></div>
      </div>
      <!-- GUEST LIST SECTION -->
      <div id="guest-list" class="guest-list connectedSortable">
        <?php foreach($result_commensali as $guest) : ?>
          <div class="guest" id="<?php echo $guest['id'] ?>"  tavolo-id="<?php echo $guest['tavolo_id'] ?>">
            <p class="family-name"><?php echo $guest['nome'].' '.$guest['cognome'] ?></p>
            <p class="number-adults"><?php echo $guest['adulti'] ?></p>
            <p class="number-babies"><?php echo $guest['bambini'] ?></p>
            <p class="number-highchair"><?php echo $guest['seggioloni'] ?></p>
            <p class="number-intolerant"><?php echo $guest['note_intolleranze'] ?></p>
          </div>
        <?php endforeach; ?> 
      </div>

      <!-- ASSIGN BUTTONS TO FILTER-->
      <div class="assign-btns">
        <label for="to-assign">
          <input type="radio" id="to-assign" name="assigned-toggle">
          <span class="btn toggle-btn">To Assign</span>
        </label>
        <label for="assigned">
          <input type="radio" id="assigned" name="assigned-toggle">
          <span class="btn toggle-btn">Assigned</span>
        </label>
      </div>

    </div>
    
    <!-- TABLE SECTION -->
    <div class="table-section">
      <div class="table-btn">
        <button type="button" class="btn btn-add-table" id="add-table"><i class="fa fa-plus-circle"></i> Add Table</button>

        <div class="sposi-radio">
          <label for="sposo">
            <input type="radio" id="sposo" name="sposi-toggle">
            <span class="btn">Sposo</span>
          </label>
          <label for="sposa">
            <input type="radio" id="sposa" name="sposi-toggle">
            <span class="btn">Sposa</span>
          </label>
          <label for="tutti">
            <input type="radio" id="tutti" name="sposi-toggle" checked>
            <span class="btn">Tutti</span>
          </label>
        </div>

      </div>

      <!-- TABLE CONTAINER -->
      <div id="table-container" class="table-container">
        <?php foreach($result_tavoli as $table) : ?>
          <div class="table" data-rel="<?php echo $table['nome_tavolo'].$table['numero_tavolo']; ?>">
            <div class="table-header">
              <p class="table-id" hidden><?php echo $table['id']; ?></p>
              <p class="table-name"><strong><?php echo $table['nome_tavolo'].' '.$table['numero_tavolo']; ?></strong></p>
            </div>

            <!-- TABLE BODY CONTAINER-->
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
      </div>
    </div>
  </div>

  <!-- MODAL SECTION -->
  <div id="modal-section">
      <!-- GUEST MODAL -->
      <div id="add-guest-modal" class="modal">
        <div class="modal-content">
          <i id="close1" class="close-btn fas fa-times"></i>
          <form method="POST" action="api/guests_submit.php">
            <h3><i class="far fa-address-card"></i> Add Guest</h3>
            <br>
            <div id="message"></div>
            <p class="form-input">
              <label class="label" for="input-name">Name</label>
              <br>
              <input id="input-name" name="nome" class="form-text" type="text" pattern="^[a-zA-Z]+$">
            </p>
            <p class="form-input">
              <label class="label" for="input-last-name">Last Name</label>
              <br>
              <input id="input-last-name" name="cognome" class="form-text" type="text" pattern="^[a-zA-Z]+$">
            </p>
            <div class="numerical-textbox-container">
              <p class="form-input-numbers">
                <label class="label" for="input-adults">Adults</label>
                <input id="input-adults" name="adulti" class="form-text" type="number" value="1" pattern="^[0-9]+$">
              </p>
              <p class="form-input-numbers">
                <label class="label" for="input-babies">Babies</label>
                <input id="input-babies" name="bambini" class="form-text " type="number" value="0" pattern="^[0-9]+$">
              </p>
              <p class="form-input-numbers">
                <label class="label" for="input-highchair">Highchair</label>
                <input id="input-highchair" name="seggioloni" class="form-text" type="number" value="0" pattern="^[0-9]+$">
              </p>
            </div>
            <p class="form-input">
              <label class="label" for="input-intolerant">Intolerant</label>
              <br>
              <input id="input-intolerant" name="note_intolleranze" class="form-text" type="text" pattern="^[a-zA-Z]+$">
            </p>
            <div class="submit">
              <button id="submit-guest" name="submit-guest" class="btn" type="submit">Submit</button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- TABLE MODAL -->
      <div id="add-table-modal" class="modal">
        <div class="modal-content">
          <i id="close2" class="close-btn fas fa-times"></i>
          <form method="POST" action="api/tables_submit.php">
            <h3><i class="fas fa-table"></i> Aggiungi Tabella</h3>
            <div id="message"></div>
            <p class="form-input-numbers">
              <label class="label" for="numero_tavolo">Numero Tavolo</label>
              <br>
              <input id="numero_tavolo" name="numero_tavolo" class="form-text" type="number" pattern="^[0-9]+$">
            </p>
            <p class="form-input">
              <label class="label">Nome Tavolo</label>
              <select name="nome_tavolo" id="input-table-name">
                <option value="SPOSO">SPOSO</option>
                <option value="SPOSA">SPOSA</option>
              </select>
            </p>
            <div class="submit">
              <button type="submit" class="btn" id="submit-table" name="submit-table" >Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</body>
</html>
// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app = new Framework7({
  root: '#app', // App root element
  id: 'io.framework7.testapp', // App bundle ID
  name: 'Bluemotive', // App name
  theme: 'md', // Automatic theme detection
  // App routes
  routes: routes,
  smartSelect: {
    closeOnSelect: true,
  },
});

// Init/Create main view
var mainView = app.views.create('.view-main');

var endpoint = 'https://dev.bluemotive.it/fl_api/';
var current_lead_id = '';
// Show/hide preloader for remote ajax loaded pages
$$(document).on('ajaxStart', function (e) {
  // Show Preloader
  app.preloader.show();
});
$$(document).on('ajaxComplete', function (e) {
  // Show Preloader
  app.preloader.hide();
});

var GlobalToken = localStorage.getItem('token');

if (GlobalToken != null && GlobalToken != '') {
  $$('#form-lead')[0].reset();
  retrieve_data();

  // Close login screen
  app.loginScreen.close('#my-login-screen');
}

old_modello = '';//variabile utilizzata per non mttere piu check a permuta

$$('#my-permuta').on('popup:close', function (e, popup) {
  modello_retrieve = $$('input[name="modello"]').val();
  if (modello_retrieve != '' && old_modello != $$('input[name="modello"]').val()) {
    old_modello = $$('input[name="modello"]').val();
    $$('#permuta-button').append(' <i class="f7-icons">check</i>');
  }
  if (modello_retrieve == '') {
    $$('#permuta-button').html('Permuta');

  }

});


// Login Screen Demo
$$('#my-login-screen .login-button').on('click', function () {
  var username = $$('#my-login-screen [name="username"]').val();
  var password = $$('#my-login-screen [name="password"]').val();
  if (username != '' && password != '') {
    var d = new Date(); var n = d.getTime();
    app.request.json(endpoint + '?app_login', { client_id: 103, time: n, request_id: n, go: 1 }, function (data) {
      if (data.esito == 1) {
        app.request.json(endpoint, { usr_login: 1, user: username, password: password, token: data.token }, function (response) {
          if (response.esito == 1) {
            window.usr_id = response.usr_id;
            $$('#nomeuser').html(response.user);
            $$('#form-lead')[0].reset();
            window.GlobalToken = data.token;
            localStorage.setItem('token',window.GlobalToken);
            // Close login screen
            app.loginScreen.close('#my-login-screen');
            //retrieve data
            retrieve_data();
          } else {
            app.dialog.alert('Esito: ' + response.info_txt);
          }
        });
      }//if data.esito ==1
    });
  } else { app.dialog.alert('Inserisci email e password'); }
});

function retrieve_data() {

  $$('#selectAcivity').empty();
  $$('#selectAcivity').append('<option value="NULL" selected >Non selezionata</option>');
  //recupera attività -----------------------------------------
  Framework7.request.json(endpoint, { get_attivita: 1, token: window.GlobalToken }, function (response) {
    leads = response.leads;
    for (i = 0; i <= leads.length; i++) {
      $$('#selectAcivity').append('<option value="' + leads[i].id + '" >' + leads[i].oggetto + '</option>');
    }
  });
  //recupera attività -----------------------------------------

  //recupera marche macchine -----------------------------------------
  var marche = [];

  Framework7.request.json(endpoint, { get_marche: 1, token: window.GlobalToken }, function (response) {
    leads = response.leads;
    for (i = 0; i <= leads.length; i++) {
      marche[leads[i].id] = leads[i].label;
    }
  });
  //recupera marche macchine -----------------------------------------

  //recupera modelli macchine -----------------------------------------
  var macchine = [];

  Framework7.request.json(endpoint, { get_modelli: 1, token: window.GlobalToken }, function (response) {
    cars = response.leads;
    for (i = 0; i <= cars.length; i++) {
      macchine.push(marche[cars[i].id_marca_eurotax] + ' ' + cars[i].modello + ', anno: ' + cars[i].anno + ' cc: ' + cars[i].cilindrata + ' al: ' + cars[i].codice_alimentazione);
    }
  });
  /*rende il campo autocompletante*/
  var autocompleteDropdownSimple = app.autocomplete.create({
    inputEl: '#autocomplete-dropdown-modelli',
    openIn: 'dropdown',
    source: function (query, render) {
      var results = [];
      if (query.length === 0) {
        render(results);
        return;
      }
      // Find matched items
      for (var i = 0; i < macchine.length; i++) {
        if (macchine[i].toLowerCase().indexOf(query.toLowerCase()) >= 0) {
          results.push(macchine[i]);
          $$('#hide-modello').val(cars[i].id);
          $$('option[value="' + cars[i].codice_alimentazione + '"]').prop('selected', true);
          $$('input[name="marca"]').val(marche[cars[i].id_marca_eurotax]);
          $$('input[name="anno_immatricolazione"]').val(cars[i].anno);
          $$('input[name="descrizione"]').val('cilindrata: '+cars[i].cilindrata);
        }
      }
      // Render items by passing array with result items
      render(results);
    }
  });
  //recupera modelli macchine -----------------------------------------

  //recupera modelli gamma -----------------------------------------
  $$('#gamma').empty();
  $$('#gamma').append('<option value="NULL" selected >Non selezionata</option>');
  
  Framework7.request.json(endpoint, { get_items: '1', item_rel: 161, token: window.GlobalToken }, function (response) {
    leads = response.dati;
    for (i = 0; i <= leads.length; i++) {
      $$('#gamma').append('<option value="' + leads[i].id + '" >' + leads[i].label + '</option>');
    }
  });
  //recupera modelli gamma  -----------------------------------------

  // //recupera modelli gamma commerciale -----------------------------------------
  $$('#gamma2').empty();
  $$('#gamma2').append('<option value="NULL" selected >Non selezionata</option>');
  
  Framework7.request.json(endpoint, { get_items: '1', item_rel: 162, token: window.GlobalToken }, function (response) {
    leads = response.dati;
    for (i = 0; i <= leads.length; i++) {
      $$('#gamma2').append('<option value="' + leads[i].id + '" >' + leads[i].label + '</option>');
    }
  });
  //recupera modelli gamma commerciale -----------------------------------------


  //recupera sedi -----------------------------------------
  Framework7.request.json(endpoint, { get_items: '1', item_rel: 145, token: window.GlobalToken }, function (response) {
    leads = response.dati;
    for (i = 0; i <= leads.length; i++) {
      $$('#location_testdrive').append('<option value="' + leads[i].id + '" >' + leads[i].label + '</option>');
    }
  });
  //recupera sedi -----------------------------------------

  //recupera vetture test drive -----------------------------------------
  Framework7.request.json(endpoint, { get_items: '1', item_rel: 146, token: window.GlobalToken }, function (response) {
    leads = response.dati;
    for (i = 0; i <= leads.length; i++) {
      $$('#veicolo').append('<option value="' + leads[i].id + '" >' + leads[i].label + '</option>');
    }
  });
  //recupera vetture test drive  -----------------------------------------

}//end retrieve_data

$$('.invia').on('click', function (e) {
  e.preventDefault();
  var nome = $$('input[name="nome"]').val();
  var telefono = $$('input[name="telefono"]').val();
  var formData = app.form.convertToData('#form-lead');

  var source_potential = $$('select[name="source_potential"]').val(); formData['source_potential'] = source_potential;


  var id_marca = $$('input[name="id-marca"]').val(); formData['id_marca'] = id_marca;

  var id_modello = $$('input[name="id-modello"]').val(); formData['id_modello'] = id_modello;

  var modello = $$('input[name="modello"]').val(); formData['modello'] = modello;

  var anno_immatricolazione = $$('input[name="anno_immatricolazione"]').val(); formData['anno_immatricolazione'] = anno_immatricolazione;

  var chilometri_percorsi = $$('input[name="chilometri_percorsi"]').val(); 

  if(isNaN(chilometri_percorsi)) chilometri_percorsi = 0; //controllo se km sono numerici

  formData['chilometri_percorsi'] = chilometri_percorsi;

  var alimentazione = $$('input[name="alimentazione"]').val(); formData['alimentazione'] = alimentazione;

  var targa = $$('input[name="targa"]').val(); formData['targa'] = targa;

  var descrizione = $$('input[name="descrizione"]').val(); formData['descrizione'] = descrizione;

  localStorage.setItem('email', $$('input[name="email"]').val());

  var storedData = JSON.stringify(formData);


  if (nome != '' && telefono != '' && source_potential != 'NULL') {
    app.request({
      url: endpoint + '?insert_lead_app&token=' + window.GlobalToken,
      method: 'POST',
      dataType: 'json',
      //send "query" to server. Useful in case you generate response dynamically
      data: storedData,
      complete: function (e) {
        //for (var key in e) {  console.log('COMPLETE: '+key+' '+e[key]); }
      },
      error: function (e) {
        //for (var key in e) {  console.log('ERRROR: '+key+' '+e[key]);}
      },
      success: function (data) {

        if (data.class == 'green') {

          localStorage.setItem('insert_id',data.insert_id);
          app.dialog.alert('Contatto inserito con successo',function(){
            //mainView.router.navigate('/firma/');
            window.open('popover.html','_self');
          });
          //notificationCallbackOnClose.open();

        } else {
          app.dialog.alert('errore nell\'inserimento');
        }

        $$('#form-lead')[0].reset();
        $$('#permuta-button').html('Permuta');
        $$('option[value="' + source_potential + '"]').prop('selected', true);


      }
    });

  } else {
    app.dialog.alert('Seleziona una Attività ed inserisci Nome e Numero di Telefono');
  }


});


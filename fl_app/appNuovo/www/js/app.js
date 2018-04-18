// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app = new Framework7({
  root: '#app', // App root element
  id: 'io.framework7.testapp', // App bundle ID
  name: 'Bluemotive', // App name
  theme: 'md', // Automatic theme detection
  // App root data
  data: function () {
    return {
      user: {
        firstName: 'John',
        lastName: 'Doe',
      },
    };
  },
  // App root methods
  methods: {
    helloWorld: function () {
      app.dialog.alert('Hello World!');
    },
  },
  // App routes
  routes: routes,
});

// Init/Create main view
var mainView = app.views.create('.view-main', {
  url: '/'
});

var endpoint = 'https://authos.bluemotive.it/fl_api/';

// Show/hide preloader for remote ajax loaded pages
$$(document).on('ajaxStart', function (e) {
  // Show Preloader
  app.preloader.show();
});
$$(document).on('ajaxComplete', function (e) {
  // Show Preloader
  app.preloader.hide();
});

var GlobalToken = "";

if (GlobalToken.leght == 26) {

  // Close login screen
  app.loginScreen.close('#my-login-screen');
}

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
            var usr_id = response.usr_id;
            $$('#nomeuser').html(response.user);
            window.GlobalToken = data.token;
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
      marche.push(leads[i].label);
    }
  });
  /*rende il campo autocompletante*/
  var autocompleteDropdownSimple = app.autocomplete.create({
    inputEl: '#autocomplete-dropdown',
    openIn: 'dropdown',
    source: function (query, render) {
      var results = [];
      if (query.length === 0) {
        render(results);
        return;
      }
      // Find matched items
      for (var i = 0; i < marche.length; i++) {
        if (marche[i].toLowerCase().indexOf(query.toLowerCase()) >= 0) {
          results.push(marche[i]);
          $$('#hide-marca').val(leads[i].id);
        }
      }
      // Render items by passing array with result items
      render(results);
    }
  });
  //recupera marche macchine -----------------------------------------

  //recupera modelli macchine -----------------------------------------
  var macchine = [];

  Framework7.request.json(endpoint, { get_modelli: 1, token: window.GlobalToken }, function (response) {
    leads = response.leads;
    for (i = 0; i <= leads.length; i++) {
      macchine.push(leads[i].modello + ' anno: ' + leads[i].anno + ' cc: ' + leads[i].cilindrata + ' al: ' + leads[i].codice_alimentazione);
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
          $$('#hide-modello').val(leads[i].id);
        }
      }
      // Render items by passing array with result items
      render(results);
    }
  });
  //recupera modelli macchine -----------------------------------------

  //recupera modelli gamma -----------------------------------------
  nonCommerciali = ['Ka', 'Fiesta', 'B-Max', 'Ecosport', 'Focus', 'C-Max', 'Kuga', 'Galaxy', 'S-Max', 'Mustang', 'Mondeo', 'Vignale'];

  for (i = 1; i < nonCommerciali.length; i++) {
    $$('#gamma').append('<option value="' + nonCommerciali[i] + '" >' + nonCommerciali[i] + '</option>');
  }
  //recupera modelli gamma  -----------------------------------------

  // //recupera modelli gamma commerciale -----------------------------------------
  commerciali = ['Fiesta Van', 'Tourneo Connect', 'Tourneo Custom', 'Tourneo Courier', 'Ranger', 'Transit Connect', 'Transit Custom', 'Transit Courier', 'Transit'];
  for (i = 1; i < commerciali.length; i++) {
    $$('#gamma2').append('<option value="' + commerciali[i] + '" >' + commerciali[i] + '</option>');
  }
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
  var storedData = JSON.stringify(formData);

  if (nome != '' && telefono != '') {
    app.request({
      url: endpoint + '?insert_lead&token=' + window.GlobalToken,
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
          app.dialog.alert('Contatto inserito con successo');
        }else{
          app.dialog.alert('errore nell\'inserimento');          
        }

        app.form.removeFormData('form-lead');
      }
    });

  } else {
    app.dialog.alert('Inserisci nome e telefono');
  }


});


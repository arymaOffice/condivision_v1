// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app = new Framework7({
  root: '#app', // App root element
  id: 'io.framework7.testapp', // App bundle ID
  name: 'Bluemotive', // App name
  theme: 'auto', // Automatic theme detection
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

        app.request({
          url: endpoint,
          method: 'GET',
          dataType: 'json',
          //send "query" to server. Useful in case you generate response dynamically
          data: {
            usr_login: 1,
            user: username,
            password: password,
            token: data.token
          },
          success: function (response) {

            if (response.esito == 1) {
              var usr_id = response.usr_id;
              $$('#nomeuser').html(response.user);
              window.GlobalToken = data.token;
              // Close login screen
              app.loginScreen.close('#my-login-screen');

              //fill selectAcivity
              activity = app.request.json(endpoint + '?get_attivita', { token: data.token });

              console.log(activity['response']);

              response = activity.response;

              console.log(repsponse);
              

              $$.each(response.leads , function(i,val){
                  console.log(val);
              })





            } else {
              app.dialog.alert('Esito: ' + response.info_txt);
            }
          }
        });

      }

    });

  } else {
    app.dialog.alert('Inserisci email e password');
  }

});

// In page events:
$$(document).on('page:init', function (e) {
  // Page Data contains all required information about loaded and initialized page
  var page = e.detail;
})
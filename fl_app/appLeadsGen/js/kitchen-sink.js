

// Init App
var myApp = new Framework7({
    // App root element
    root: '#app',
    name: 'Bluemotive',
    // Enable Material theme
    material: true,
    routes: [
        {
            name: 'nuovo contatto',
            path: '/new/',
            url: 'nuovo-lead.php',
        },
        {
            name: 'lista contatti',
            path: '/list/',
            url: 'lista-contatti.php',
        },
        {
            name: 'home',
            path: '',
            url: 'index.php',
        },
        {
            path: '(.*)',
            url: './pages/404.html',
        },
    ],

});

// Expose Internal DOM library
var $$ = Dom7;

var endpoint = 'https://authos.bluemotive.it/fl_api/';
// Add main view
var mainView = myApp.views.create('.view-main');

// Show/hide preloader for remote ajax loaded pages
// Probably should be removed on a production/local app
$$(document).on('ajaxStart', function (e) {

    if (e.detail.xhr.requestUrl.indexOf('autocomplete-languages.json') >= 0) {
        // Don't show preloader for autocomplete demo requests

        return;
    }
    myApp.showIndicator();
});
$$(document).on('ajaxComplete', function (e) {
    if (e.detail.xhr.requestUrl.indexOf('autocomplete-languages.json') >= 0) {
        // Don't show preloader for autocomplete demo requests
        return;
    }
    myApp.hideIndicator();


});

var GlobalToken = "";

if (GlobalToken.leght == 26) {

    myApp.closeModal('.login-screen');
}

// Dom Events
$$('.panel-left').on('panel:open', function () {
    console.log('Panel left: open');
  });
  $$('.panel-left').on('panel:opened', function () {
    console.log('Panel left: opened');
  });




//$$(document).on('page:init', '.page[data-name="about"]', function (e) {
$$(document).on('page:init', '.page[data-name="nuovo-lead"]', function (page) {

    /*	
    $$.getJSON('http://ford.bluemotive.it/fl_api/?get_items&item_rel=146&token=api', function (data) {
      console.log(data);
    });*/

    $$(page.container).find('#permuta2').on('tap click', function () {
        if ($$('#permuta2_box').css('display') == 'none') { $$('#permuta2_box').show(); } else { $$('#permuta2_box').hide(); }
    });


    $$(page.container).find('#azienda').on('change', function () {
        var isChecked = $$(this).prop('checked');
        if (isChecked == true) { $$('#azienda_box').show(); } else { $$('#azienda_box').hide(); }
    });

    //box della permuta per la prima auto
    $$(page.container).find('#permuta').on('change', function () {
        var isChecked = $$(this).prop('checked');
        if (isChecked == true) {
            $$('#permuta_box').show();

            var options = '';


            //gestione della select sulle marche auto
            myApp.request.json(endpoint, { get_marche: 1, token: window.GlobalToken }, function (data) {
                $$.each(data.leads, function (i, val2) {
                    options += val2.label + ' ';
                });
            });

            splitted = options.split(' ');

            var autocompleteDropdownSimple = myApp.autocomplete.create({
                inputEl: '#autocomplete-dropdown-marca',
                openIn: 'dropdown',
                source: function (query, render) {
                    var results = [];
                    if (query.length === 0) {
                        render(results);
                        return;
                    }
                    // Find matched items
                    for (var i = 0; i < splitted.length; i++) {
                        if (splitted[i].toLowerCase().indexOf(query.toLowerCase()) >= 0) results.push(splitted[i]);
                    }
                    // Render items by passing array with result items
                    render(results);
                }
            });



        } else { $$('#permuta_box').hide(); }
    });

    $$(page.container).find('#marca').on('change', function () {

        console.log($$(this).val());

    });


    $$(page.container).find('#test_drive').on('change', function () {
        var isChecked = $$(this).prop('checked');
        if (isChecked == true) {
            $$('#test_drive_box').show();
            $$('#veicolo').html('');
            $$('#location_testdrive').html('');

            $$('#location_testdrive').append('<option value="-1">Seleziona...</option>');
            $$('#veicolo').append('<option value="-1">Seleziona...</option>');

            $$.getJSON(endpoint, { get_items: '1', item_rel: 145, token: 'api' }, function (data) {
                $$.each(data.dati, function (i, val2) {
                    option = '<option value="' + val2.id + '">' + val2.label + '</option>';
                    $$('#location_testdrive').append(option);
                });
            });

            myApp.request.json(endpoint, { get_items: '1', item_rel: 146, token: 'api' }, function (data) {

                $$.each(data.dati, function (i, val) {

                    option = '<option value="' + val.id + '">' + val.label + '</option>';
                    $$('#veicolo').append(option);
                });
            });




        } else { $$('#test_drive_box').hide(); }
    });



    $$(page.container).find('.button').on('click', function () {
        var nome = $$(page.container).find('input[name="nome"]').val();
        var telefono = $$(page.container).find('input[name="telefono"]').val();
        var forml = $$(page.container).find('#form-lead')
        var storedData = $$.serializeObject(myApp.formToJSON(forml));

        if (nome != '' && telefono != '') {
            $$.ajax({
                url: endpoint + '?insert_lead&token=api',
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
                    console.log(data);
                    if (data.class == 'green') {

                        myApp.addNotification({
                            message: data.esito,
                            button: {
                                text: 'Firma autorizzazione',
                                color: 'lightgreen'
                            },
                            onClose: function () {
                                window.location.assign('popover.html');
                            }
                        });
                        window.location.assign('popover.html');
                        //mainView.router.back(); 
                    } else {
                        myApp.alert('Esito: ' + data.esito);
                    }
                }
            });

        } else {
            myApp.alert('Inserisci nome e telefono');
        }


    });
});


$$('.login-screen').find('.button').on('click', function (e) {

    var username = $$('.login-screen').find('input[name="username"]').val();
    var password = $$('.login-screen').find('input[name="password"]').val();
    if (username != '' && password != '') {

        var d = new Date(); var n = d.getTime();

        myApp.request.json(endpoint + '?app_login', { client_id: 103, time: n, request_id: n, go: 1 }, function (data) {

            console.log(data);

            if (data.esito == 1) {

                myApp.request({
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
                            $$('#welkome').html("Benvenuto " + response.user);
                            window.GlobalToken = data.token;
                            myApp.loginScreen.close('.login-screen');

                            //fill selectAcivity
                            /*myApp.request.json(endpoint + '?get_attivita', { token: data.token }, function (data) {

                                $$.each(data.leads, function (i, val) {
                                    $$('#selectAcivity').append('<option value="' + val.id + '">' + val.oggetto + '</option>');
                                });
                            });*/

                        } else {
                            myApp.alert('Esito: ' + response.info_txt);
                        }
                    }
                });

            }

        });

    } else {
        myApp.alert('Inserisci email e password');
    }

    e.preventDefault();


});

/* ===== Generate Content Dynamically ===== */
var dynamicPageIndex = 0;
function createContentPage() {
    mainView.router.loadContent(
        '  <!-- Page, data-page contains page name-->' +
        '  <div data-page="dynamic-content" class="page">' +
        '    <!-- Top Navbar-->' +
        '    <div class="navbar">' +
        '      <div class="navbar-inner">' +
        '        <div class="left"><a href="#" class="back link icon-only"><i class="icon icon-back"></i></a></div>' +
        '        <div class="center">Dynamic Page ' + (++dynamicPageIndex) + '</div>' +
        '      </div>' +
        '    </div>' +
        '    <!-- Scrollable page content-->' +
        '    <div class="page-content">' +
        '      <div class="content-block">' +
        '        <p>Here is a dynamic page created on ' + new Date() + ' !</p>' +
        '        <p>Go <a href="#" class="back">back</a> or generate <a href="#" class="ks-generate-page">one more page</a>.</p>' +
        '      </div>' +
        '    </div>' +
        '  </div>'
    );
    return;
}
$$(document).on('click', '.ks-generate-page', createContentPage);


function showSign(idImg) {
    var dataImage = localStorage.getItem('imgData');
    var bannerImg = document.getElementById(idImg);
    bannerImg.src = "data:image/png;base64," + dataImage;
}

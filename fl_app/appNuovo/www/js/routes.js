routes = [
  {
    path: '/',
    url: './index.html',
  
  },
  {
    path: '/daylead/',
    name:'daylead',
    url: './daylead.html',
    async: function (routeTo, routeFrom, resolve, reject) {
      var campi = 'id,nome,cognome';

      Framework7.request.json(endpoint, { campi:campi, get_data_lead: 1, user_id: window.usr_id ,token: window.GlobalToken , when : 'TODAY'}, function (response) {
       console.log('sgf');
      });
    }
  
  },
  {
    path: '/logout/',
    async: function (routeTo, routeFrom, resolve, reject) {
      localStorage.clear();
      
      Framework7.request.json(endpoint, { usr_logout: 1, usr_id: window.usr_id ,token: window.GlobalToken }, function (response) {
        window.location.assign('./index.html');
      });
    }
  
  },
  // Default route (404 page). MUST BE THE LAST
  {
    path: '(.*)',
    url: './pages/404.html',
  },
];

routes = [
  {
    path: '/',
    url: './index.html',
  
  },
  {
    path: '/logout',
    async: function (routeTo, routeFrom, resolve, reject) {
      localStorage.setItem('token','');
      
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

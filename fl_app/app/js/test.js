$(window).on('hashchange', function() {
    location.hash = "noBack";
    alert(location.href);
});
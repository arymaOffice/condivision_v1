/* DV MAP PLUGIN */
(function ($) {
    "use strict";



    // Plugin Options
    $.fn.dvmap = function (options) {
        var selector = $(this);
        var settings = $.extend({
            container: 'google-map',
            latitute: ' 41.413331',
            longitude: '12.851881',
            zoom: 1,
            zoomcntrl: false,
            maptypecntrl: false,
            dvcustom: true
        }, options);

        // Start building the map
        function googlemap() {

            // Custom Map Skin
            var customstyle = [{
                "featureType": "all",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#1F2041"
                }]
            }, {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{
                    "color": "#2E4057"
                }, {
                    "lightness": -20
                }]
            }, {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{
                    "color": "#1F2041"
                }]
            }, {
                "featureType": "road",
                "elementType": "all",
                "stylers": [{
                    "color": "#1F2041"
                }, {
                    "lightness": 5
                }]
            }, {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "road",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#E0D3DE"
                }]
            }, {
                "featureType": "road",
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "administrative",
                "elementType": "all",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "administrative.country",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#782347"
                }, {
                    "visibility": "on"
                }]
            }, {
                "featureType": "administrative.province",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#782347"
                }, {
                    "visibility": "on"
                }]
            }, {
                "featureType": "administrative.locality",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#782347"
                }, {
                    "visibility": "on"
                }]
            }, {
                "featureType": "administrative.neighborhood",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#782347"
                }, {
                    "visibility": "on"
                }]
            }, {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [{
                    "visibility": "off"
                }]
            }];

            // Coordinates
            var myLatLng = new google.maps.LatLng('41.117527', '16.850244');

            // Map Options
            var myMapOptions = {
                zoom: settings.zoom,
                scrollwheel: false,
                disableDefaultUI: true,
                mapTypeControl: settings.maptypecntrl,
                zoomControl: settings.zoomcntrl,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL,
                    position: google.maps.ControlPosition.LEFT_TOP
                },
                center: myLatLng
            };

            // Map Selector
            var map = new google.maps.Map(document.getElementById(settings.container), myMapOptions);
          
            // Add Custom Map Marker
            map.addListener('idle', function () {
                if (!selector.find(".isg-pin > div").hasClass("isg-pulse")) {
                    jQuery(".isg-pin", selector).append("<div class='isg-pulse'></div>");
                }
            });
        }

        // Build the Map
        googlemap();

    };
}(jQuery));

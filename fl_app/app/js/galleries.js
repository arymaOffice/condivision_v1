

// GALLERY 1
jQuery('#gallery1').on('click', function (e) {    
    "use strict";   
    e.preventDefault();
    
    // Initialize the plugin
    jQuery(this).lightGallery({
        
        // Settings
        dynamic: true,
        zoom: true,
        fullScreen: true,
        autoplay: false,
        autoplayControls: true,
        thumbnail: true,
        download: true,
        counter: true,
        actualSize: true,
        
        // Images
        dynamicEl: [{
            // Image Url
            'src': 'images/photos/1.jpg',
            // Thumbnail url
            'thumb': 'images/photos/1.jpg',
            // Image caption
            'subHtml': 'Marvillosas Vistas'
        }, {
            'src': 'images/photos/2.jpg',
            'thumb': 'images/photos/2.jpg',
            'subHtml': "Tocate el papoo"
        }, {
            'src': 'images/photos/3.jpg',
            'thumb': 'images/photos/3.jpg',
            'subHtml': "Quae expetendis"
        }, {
            'src': 'images/photos/4.jpg',
            'thumb': 'images/photos/4.jpg',
            'subHtml': 'Eram labore nescius'
        }, {
            'src': 'images/photos/5.jpg',
            'thumb': 'images/photos/5.jpg',
            'subHtml': "Ne esse mandaremus"
        }, {
            'src': 'images/photos/6.jpg',
            'thumb': 'images/photos/6.jpg',
            'subHtml': "Elit vidisse ab philosophari"
        }]
        
    });
    
    return false;
});




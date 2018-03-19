jQuery(function ($) {
    "use strict";
    // Get the loader.
    var spinner = $('#ajax-spinner1');
    // Hide the spinner
    spinner.css('visibility', 'hidden');

    // Get the messages div.
    var formMessages = $('#form-messages1');

    // Set up an event listener for the contact form.
    $('#sForm').click(function (e) {
        // Stop the browser from submitting the form.
        e.preventDefault();
        
        // Show the spinner
        spinner.css('visibility', 'visible');

        // Serialize the form data.
        var formData = $('#ajax-form1').serialize();

        // Submit the form using AJAX.
        $.ajax({
            type: 'POST',
            url: 'code/insertCommensale.php',
            data: formData
        }).done(function (response) {
            
            // Make sure that the formMessages div has the 'ajax-success' class.
            $(formMessages).removeClass('ajax-error');
            $(formMessages).addClass('ajax-success');

            // Set the message text.
            $(formMessages).text(response);

            // Clear the form.
            $('#sendername').val('');
            $('#senderemail').val('');
            $('#senderphone').val('');
            $('#sendermessage').val('');
            $('#ajax-form1')[0].reset();
            $('#ajax-form1').css('display','none');
            
            // Hide the spinner
            spinner.css('visibility', 'hidden');
            
        }).fail(function (data) {
            
            // Make sure that the formMessages div has the 'ajax-error' class.
            $(formMessages).removeClass('ajax-success');
            $(formMessages).addClass('ajax-error');

            // Set the message text.
            if (data.responseText !== '') {
                // Display the message
                $(formMessages).text(data.responseText);
                // Hide the spinner
                spinner.css('visibility', 'hidden');
            } else {
                // Display the error message
                $(formMessages).text('Oops! An error occured and your message could not be sent.');
                // Hide the spinner
                spinner.css('visibility', 'hidden');
            }
        });
    });
});
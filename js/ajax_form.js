
$(document).ready(function() {

    // process the form
    $('form').submit(function(event) {

        // set variables
        var name     = $('#name').val();
        var email    = $('#email').val();
        var company  = $('#company').val();
        var comment  = $('#comment').val();
        var captcha  = $('#captcha').val();


        // Must remove previous messages otherwise they will continue to append.
        $('.form-group').removeClass('has-error'); // remove the error class
        $('.help-block').remove(); // remove the error text
        $('.alert-success').remove();


        // Set data to be sent.
        var formData = {
            'name' 			: name,
            'email' 		: email,
            'company' 		: company,
            'comment'       : comment,
            'captcha'       : captcha
        };


        // Process form
        $.ajax({
            type 		: 'POST',
            url 		: 'form_process.php', // action
            data 		: formData, // Data object to send
            dataType 	: 'json', // Expect back from the server
            encode 		: true
        })


        .done(function(data) {

            // log data to the console
            console.log(data);

            // Handle errors and validation messages
            if ( ! data.success) {

                // NAME input
                if (data.errors.name) {
                    $('#name-group').addClass('has-error')
                                    .append('<div class="help-block">' + data.errors.name + '</div>');
                }
                // EMAIL input
                if (data.errors.email) {
                    $('#email-group').addClass('has-error')
                                     .append('<div class="help-block">' + data.errors.email + '</div>');
                }
                // COMMENT textarea
                if (data.errors.comment) {
                    $('#comment-group').addClass('has-error')
                                       .append('<div class="help-block">' + data.errors.comment + '</div>');
                }
                // CAPTCHA input
                if (data.errors.captcha) {
                    $('#captcha-group').addClass('has-error')
                                       .append('<div class="help-block">' + data.errors.captcha + '</div>');
                }

            } else {

                if(data.email_success) {

                    // Success!
                    $('fieldset').append('<div class="alert-success">' + data.message + '</div>');
                    $('.submit').hide();

                } else {

                    // Email has failed, show message!
                    $('fieldset').append('<div class="alert-success">' + data.message + '</div>');

                }
            }
        })

        // If fail
        .fail(function(data) {

            // show any errors
            console.log(data);
        });

        // prevent normal submission
         event.preventDefault();
    });

});
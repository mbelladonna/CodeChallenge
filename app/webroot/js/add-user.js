$(function(){
    // DOM elements
    var user_form = $("#UserAddForm"),
        expiration_date_month = $('#ccExpirationDateMonth'),
        expiration_date_year = $('#ccExpirationDateYear'),
        submit_button = $('input[type="submit"]'),
        stripe_error_container = $('#stripe-error');
    
    // Custom validator for alphanumeric
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    });
    
    // Stripe JS client-side validation helpers
    $.validator.addMethod("validateCardNumber", function(value, element) {
        return this.optional(element) || Stripe.card.validateCardNumber(value);
    });
    $.validator.addMethod("validateExpirationDate", function(value, element) {
        return this.optional(element) || Stripe.card.validateExpiry(expiration_date_month.val(), expiration_date_year.val());
    });
    $.validator.addMethod("validateCVC", function(value, element) {
        return this.optional(element) || Stripe.card.validateCVC(value);
    });
    
    // Setup form client side validation
    user_form.validate({
        groups: {
            cc_expiration_date: "data[cc][expiration_date][month] data[cc][expiration_date][year]"
        },
        rules: {
            "data[User][first_name]": {
                required: true,
                alphanumeric: true,
                rangelength: [3, 50]
            },
            "data[User][last_name]": {
                required: true,
                alphanumeric: true,
                rangelength: [3, 50]
            },
            "data[User][email]": {
                required: true,
                email: true
            },
            "data[User][password]": {
                required: true,
                rangelength: [5, 15]
            },
            "data[cc][card_number]": {
                required: true,
                validateCardNumber: true
            },
            "data[cc][expiration_date][month]": {
                required: true,
                validateExpirationDate: true
            },
            "data[cc][expiration_date][year]": {
                required: true,
                validateExpirationDate: true
            },
            "data[cc][cvv2_cvc2]": {
                required: true,
                validateCVC: true
            }
        },
        messages: {
            "data[User][first_name]": {
                required: "First Name required.",
                alphanumeric: 'Alphabets and numbers only.',
                rangelength: 'First name must be between 3 and 50 characters long.'
            },
            "data[User][last_name]": {
                required: "Last Name required.",
                alphanumeric: 'Alphabets and numbers only.',
                rangelength: 'Last name must be between 3 and 50 characters long.'
            },
            "data[User][email]": {
                required: "Email required.",
                email: "Please enter a valid email address."
            },
            "data[User][password]": {
                required: "Password required.",
                rangelength: "Passwords must be between 5 and 15 characters long."
            },
            "data[cc][card_number]": {
                validateCardNumber: "Invalid card number.",
            },
            "data[cc][expiration_date][month]": {
                required: "Expiration month required.",
                validateExpirationDate: "Invalid expiration date."
            },
            "data[cc][expiration_date][year]": {
                required: "Expiration year required.",
                validateExpirationDate: "Invalid expiration date."
            },
            "data[cc][cvv2_cvc2]": {
                validateCVC: "Invalid"
            }
        },
        errorClass: "jquery-validate-error",
        errorPlacement: function(error, element) {
            if (element.attr("name") === "data[cc][expiration_date][month]" || element.attr("name") === "data[cc][expiration_date][year]") {
                error.insertAfter(expiration_date_year);
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form){
            beforeCreateToken();
            if ($('#stripe_token').length === 0) {
                Stripe.card.createToken(form, stripeResponseHandler);
                return false;
            } else {
                form.submit();
            }
        } 
    });
    
    /**
     * Pre stripe createToken hanlder
     */
    function beforeCreateToken() {
        submit_button.addClass('disabled').attr('disabled', 'disabled');
        stripe_error_container.hide();
    }
    
    /**
     * Post stripe createToken handler     
     */
    function afterCreateToken() {
        submit_button.removeClass('disabled').removeAttr('disabled');
        if ((stripe_error_container.html()).length > 0) {
            stripe_error_container.slideDown('slow');
        }
    }
    
    /**
     * Stripe createToken response handler
     */
    function stripeResponseHandler(status, response) {
        if (response.error) {
            stripe_error_container.html(response.error.message);
        } else {
            stripe_error_container.html('');
            user_form.append("<input type='hidden' name='data[cc][stripe_token]' id='stripe_token' value='" + response['id'] + "'/>").submit();            
        }
        afterCreateToken();
    }
});
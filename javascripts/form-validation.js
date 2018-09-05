jQuery(document).ready(function() {
    jQuery('.form-guide').click(function(){
        jQuery('.form-guide-content').slideToggle();
    });
    jQuery('.storyline-form').validate({
        debug: true,
        highlight: function(element) {
            $(element)
                .closest('.form-group')
                .addClass('has-error');
        },
        unhighlight: function(element) {
            $(element)
                .closest('.form-group')
                .removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            // <- pass 'form' argument in
            $('.story-submit').attr('disabled', true);
            form.submit(); // <- use 'form' argument here.
        },
        messages: {
            name: 'Please specify your name',
            number: {
                maxvalue: 'We need your email address to contact you',
                email: 'Your email address must be in the format of name@domain.com'
            }
        }
    });
});

jQuery.extend(jQuery.validator.messages, {
    required: 'Dette felt er påkrævet.',
    remote: 'Please fix this field.',
    email: 'Skriv venligst en korrekt email adresse.',
    url: 'Please enter a valid URL.',
    date: 'Please enter a valid date.',
    dateISO: 'Please enter a valid date (ISO).',
    number: 'Please enter a valid number.',
    digits: 'Please enter only digits.',
    creditcard: 'Please enter a valid credit card number.',
    equalTo: 'Please enter the same value again.',
    accept: 'Please enter a value with a valid extension.',
    maxlength: jQuery.validator.format('Please enter no more than {0} characters.'),
    minlength: jQuery.validator.format('Please enter at least {0} characters.'),
    rangelength: jQuery.validator.format('Please enter a value between {0} and {1} characters long.'),
    range: jQuery.validator.format('Please enter a value between {0} and {1}.'),
    max: jQuery.validator.format('Skriv venligst en ikke en værdi højere end {0}.'),
    min: jQuery.validator.format('Skriv venligst ikke en værdi lavere end {0}.')
});

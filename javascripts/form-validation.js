jQuery(document).ready(function() {
	jQuery(".storyline-form").validate({
		debug: true,
		highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    },
		submitHandler: function(form) { // <- pass 'form' argument in
        $(".story-submit").attr("disabled", true);
        form.submit(); // <- use 'form' argument here.
    }
	});
})

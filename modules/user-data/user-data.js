jQuery(document).ready( function() {
	jQuery( 'button.user_passchange' ).click( function(e) {
		e.preventDefault();
		jQuery( '.form-user_passchange' ).slideToggle();
	})
	if (jQuery.isFunction('datepicker')) {
		jQuery( '#user_expire_date' ).datepicker({
				dateFormat: 'dd-mm-yy',
				onClose: function( selectedDate ){
						jQuery( '#user_expire_date' ).datepicker( 'option', 'minDate', selectedDate );
				}
		});
	}
	jQuery( '.isl-item' ).each( function() {
		var edit_button = jQuery('<button class="edit-story"><i class="fa fa-pencil" aria-hidden="true"></i> Rediger</button>');
		var current_date = Date.now();
		var isl_date = jQuery(this).data('date');
		var date_diff = Math.abs(current_date-parseDanishDate(isl_date)) / (1000 * 60 * 60);
		var isl_id = jQuery(this).data('id');
		var isl_type = jQuery(this).data('type');
		if (date_diff <= 48) {
			jQuery(this).append(edit_button);
			edit_button.click( function() {
				window.location.href = '?update=story&date=' + isl_date + '&id=' + isl_id + '&type=' + isl_type;
			});
		}
	});
});

function parseDanishDate(s) {
  var d = s.split(/\-/);
  return Date.parse(d[2] + '-' + d[1] + '-' + d[0]);
}


function filterSelectFields() {
	// Get selection Value
	var selectionValue = $( "#type option:selected" ).val().replace("option_", "");
	console.log(selectionValue);
	// Hide fields not related to selection value
	$( "p.question[class^=question_]" ).removeClass('active');
	$( "p[class^=question_"+selectionValue+"]" ).each(function( index ) {
  	$( this ).toggleClass('active');
	});

}
jQuery(document).ready( function() {
	filterSelectFields();
	$( "#type" ).change(function() {
		filterSelectFields();
	});
});

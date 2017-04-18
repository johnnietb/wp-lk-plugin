var last_update_id = 0;
function inzite_chat_check_updates(previous) {
	jQuery.post(
		inzite_chat.ajaxurl,
		{
			action: 'inzite_check_updates',
			inzite_chat_slug: inzite_chat.inzite_chat_slug,
			inzite_chat_id: inzite_chat.inzite_chat_id,
			last_update_id: last_update_id,
			previous: previous
		},
		function (response) {
			console.log(response);
			chats = jQuery.parseJSON( response );
			previous_data = '';
			if ($('div.chat-container').is(':empty')){
				var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();
				var output = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
				jQuery('div.chat-container').html( '<h4>' + output + '</h4>');
			}
			if ( chats !== null ) {
				for ( i = 0; i < chats.length; i++ ) {

						if ( jQuery('div.chat-container div.chat-message-'+chats[i].id).length )
							continue;

						if (previous == undefined || previous == '') {
							jQuery('div.chat-container').html( jQuery('div.chat-container').html() + inzite_chat_strip_slashes(chats[i].html) );
							jQuery('div.chat-container').animate({ scrollTop: jQuery('div.chat-container')[0].scrollHeight - jQuery('div.chat-container').height() }, 50);
						} else {
							previous_data += inzite_chat_strip_slashes(chats[i].html);

						}
						if (last_update_id < chats[i].id) {
							last_update_id = chats[i].id;
						}

				}

				if (previous != undefined && previous != '' && previous_data != '') {
					previous_data = '<h4>' + previous + '</h4>' + previous_data + '<hr />';
					jQuery('div.chat-container').html( previous_data + jQuery('div.chat-container').html() );
					jQuery('div.chat-container').animate( { scrollTop: 0 }, 50);
				}
			}
		}
	);
	if (previous == undefined || previous == '') {
		inzite_chat_recheck = setTimeout( "inzite_chat_check_updates('')", 2500 );
		console.log(last_update_id);
	}
}

function inzite_chat_strip_slashes(str) {
    return (str + '').replace(/\\(.?)/g, function (s, n1) {
        switch (n1) {
        case '\\':
            return '\\';
        case '0':
            return '\u0000';
        case '':
            return '';
        default:
            return n1;
        }
    });
}

jQuery(document).ready( function() {
	last_update_id = 0;
	shifted = false;
	inzite_chat_check_updates('');

	jQuery( 'textarea.chat-text-entry' ).on('keyup keydown', function(e){
		shifted = e.shiftKey;
	});
	jQuery( 'textarea.chat-text-entry' ).keypress( function( event ) {

		if (( event.charCode == 13 || event.keyCode == 13 ) && (!shifted)) {
			inzite_chat_send_message();
			return false;
		}

	});

	jQuery( 'button.chat-submit' ).click( function(  ) {
			inzite_chat_send_message();
	});

	jQuery( 'button.user_passchange' ).click( function(e) {
		e.preventDefault();
		jQuery( '.form-user_passchange' ).slideToggle();
	})

	jQuery( '.chat-sessions a' ).click( function(e) {
		e.preventDefault();
		inzite_chat_check_updates( $(this).data('date') );
		$(this).fadeOut();
	})
});

function inzite_chat_send_message() {
	message = jQuery( 'textarea.chat-text-entry' ).val();
	attachment_nonce = jQuery( 'input#attachment_nonce' ).val();
	//attachment = jQuery( 'input#attachment-upload' )[0].files[0];
	var form_data = new FormData();
	form_data.append( 'file', jQuery( 'input#attachment-upload' )[0].files[0] );
	form_data.append( 'action', 'inzite_send_message');
	form_data.append( 'inzite_chat_slug', inzite_chat.inzite_chat_slug);
	form_data.append( 'inzite_chat_id', inzite_chat.inzite_chat_id);
	form_data.append( 'attachment_nonce', attachment_nonce);
	form_data.append( 'message', message);

	// {
	// 	action: 'inzite_send_message',
	// 	inzite_chat_slug: inzite_chat.inzite_chat_slug,
	// 	inzite_chat_id: inzite_chat.inzite_chat_id,
	// 	attachment: attachment,
	// 	attachment_nonce: attachment_nonce,
	// 	message: message,
	// }

	jQuery( 'textarea.chat-text-entry' ).fadeTo( "fast", 0.25 ).prop("readonly",true);
	jQuery( 'input#attachment-upload' ).fadeTo( "fast", 0.25 ).prop("readonly",true);
	jQuery( 'button.chat-submit' ).fadeTo( "fast", 0.25 ).prop("readonly",true);
	jQuery.ajax({
		url : inzite_chat.ajaxurl,
		method: "POST",
		data : form_data,
		processData: false,
		contentType: false,
		success: function (response) {
			jQuery( 'textarea.chat-text-entry' ).fadeTo( "fast", 1 ).val('').prop("readonly",false);
			jQuery( 'input#attachment-upload' ).fadeTo( "fast", 1 ).val('').prop("readonly",false);
			jQuery( 'button.chat-submit' ).fadeTo( "fast", 1 ).prop("readonly",false);
			inzite_chat_check_updates();
		}
	});


}

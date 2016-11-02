jQuery(function($){


	 // Only show the "remove image" button when needed
	 if ( ! jQuery('#dw_tmce_editor_icon').val() )
	 	jQuery('.dw_tmce_remove_image_button').hide();

	// Uploading files
	var file_frame;
	
	jQuery(document).on( 'click', '.dw_tmce_upload_image_button', function( event ){

		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media({
			title: 'Select or Upload Logo',
			button: {
				text: 'Use this image'
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			attachment = file_frame.state().get('selection').first().toJSON();

			jQuery('#dw_tmce_editor_icon').val( attachment.id );
			jQuery('#editor_logo img').attr('src', attachment.url );
			jQuery('.dw_tmce_remove_image_button').show();
		});

		// Finally, open the modal.
		file_frame.open();
	});

	jQuery(document).on( 'click', '.dw_tmce_remove_image_button', function( event ){
		jQuery('#editor_logo img').attr('src', '');
		jQuery('#dw_tmce_editor_icon').val('');
		jQuery('.dw_tmce_remove_image_button').hide();
		return false;
	});

});
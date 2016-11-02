(function() {
	function dw_tmce_imgur_button_menu(  editor ) {
			var menu = [
                { 
                	text: 'Upload', 
                	onclick: function() {
						jQuery('#dw_tmce_upload_hidden_button').attr( 'name', editor.id );
						jQuery('#dw_tmce_upload_hidden_button').click();
					}
            	},
                {
                	text: 'Library', 
                	onclick: function() {

                		jQuery('#dw-tmce-imgur-gallery-modal').attr( 'name', editor.id );
                		jQuery.ajax({
							url: editor.getParam('dw_tmce_upload_ajax_url'),
							type: 'POST',
							dataType: 'json',
							data: {
								action  : 'dw-tmce-imgur-get-user-library',
							},
							success: function( response ){
								if ( response.success == true ) {
									var $_library = jQuery('#dw-tmce-imgur-gallery-modal').find('#dw-tmce-imgur-library-area');
									var items = response.data.data;
									jQuery.each( items, function( i, item ){
										var item_html = '<div class="item dw-tmce-imgur-library-item" data-mime="'+item.imgur_type+'" data-date="'+item.imgur_date+'" ><span class="dw-tmce-imgur-direct-link"><a href="http://imgur.com/'+item.imgur_id+'" target="_blank"><i class="fa fa-check-square" aria-hidden="true"></i></a></span><img src="'+item.imgur_link+'"></div>';
										$_library.append( item_html );


									})
								} else {
									jQuery('#dw-tmce-imgur-gallery-modal').find('.modal-body').html('<h3>'+response.data.message+'</h3>')
								}
							},
							complete: function(xhr, textStatus) {
								var modal = document.getElementById('dw-tmce-imgur-gallery-modal');
			  					modal.style.display = "block";

							    var closeButton = document.getElementById('dw-tmce-dismiss-modal');
								closeButton.onclick = function() {
								    modal.style.display = "none";
								    jQuery('#dw-tmce-imgur-library-area').html('');
								}

								window.onclick = function(event) {
								    if (event.target == modal) {
								        modal.style.display = "none";
								        jQuery('#dw-tmce-imgur-library-area').html('');
								    }
								}
							}
						});
              		}
                },
            ]

		return menu;
	}


	tinymce.PluginManager.add('dw_tmce_upload_buttons', function( editor, url ) {
		var editorIcon = editor.getParam('dw_tmce_upload_home_url') + 'assets/img/imgur.png';

		if ( editor.getParam('dw_tmce_upload_icon') != '' ) {
			editorIcon = editor.getParam('dw_tmce_upload_icon');
		}

		editor.addButton( 'dw_tmce_upload_imgur_button', {
			tooltip: editor.getLang('dw_tmce_imgur_translate.uploadString'),
			image: editorIcon,
			type: 'listbox',
			menu: dw_tmce_imgur_button_menu( editor ),
		});
	});
})();

// UPLOAD Functions
(function($) {

	function dw_tmce_upload_getBase64Image(img) {
	    var canvas = document.createElement("canvas");
	    canvas.width = img.width;
	    canvas.height = img.height;

	    var ctx = canvas.getContext("2d");
	    ctx.drawImage(img, 0, 0);

    	var dataURL = canvas.toDataURL("image/	");
  		
  		return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	}

	$('#dw_tmce_upload_hidden_button').on( 'change', function(e){
		var t = $(this);
		var $_editor = t.attr( 'name' );

		var file    = document.querySelector('#dw_tmce_upload_hidden_button').files[0];

		var editor = tinyMCE.get( $_editor );

		var contentArea = editor.contentAreaContainer.id ;
		$( '#'+ contentArea ).css({'position-color': 'relative'});

		if (file) {
			var reader  = new FileReader();
			reader.onload = function(readerEvt) {
            var binaryString = readerEvt.target.result;
            binaryString = btoa( binaryString );

           		$( '#'+ contentArea ).find('iframe').after('<div class="dw-tinymce-imgur-upload-loading"><div class="loading"></div></div>');
					$.ajax({
					    url: 'https://api.imgur.com/3/upload',
					    headers: {
					        'Authorization': 'Client-ID '+editor.getParam('dw_tmce_upload_imgur_client_id'),
					    },
					    type: 'POST',
					    data: {
					        'image': binaryString
					    },
					    success: function( response ) {
					    	if ( response ) {
						    	var image = '<img src="'+response.data.link+'" width="auto" height="auto" class="dw-tmce-image-for-upload" alt="">';
						    	tinymce.activeEditor.execCommand( 'mceInsertContent', false, image );

						    	var imgur_image_id = response.data.id;
								jQuery.ajax({
									url: editor.getParam('dw_tmce_upload_ajax_url'),
									type: 'POST',
									dataType: 'json',
									data: {
										action  : 'dw-tmce-imgur-update-user-library',
										imgur_id : imgur_image_id,
										imgur_type: response.data.type,
										imgur_link: response.data.link,
										imgur_date: response.data.datetime,
									},
									complete: function(xhr, textStatus) {
										if ( textStatus == 'error' ) {
								    		alert("Unable to update, please try again.");
								    	}
									}

								});    		
					    	}
					    },
					    complete: function(xhr, textStatus) {
					    	if ( textStatus == 'error' ) {
					    		alert("Unable to upload, please try again.");
					    	}
							$( '#'+ contentArea ).find('.dw-tinymce-imgur-upload-loading').remove();
						}
					});	

		        };
			reader.readAsBinaryString(file);
		}
	});
})(jQuery);

// Library handle functions

(function($) {

	var dw_tmce_imgur_lib = [];

	$(document).on( 'click', '.dw-tmce-imgur-library-item', function(e){
		e.preventDefault();
		if ( e.shiftKey || e.ctrlKey ) {

			var library_item = $(document).find('.dw-tmce-imgur-library-item');
			var t = $(this);
			var image = t.find('img').attr( 'src' );

			if ( t.hasClass( 'in-active' ) ) {
				var i = dw_tmce_imgur_lib.indexOf(image);
				if(i != -1) {
					dw_tmce_imgur_lib.splice(i, 1);
				}
				t.removeClass( 'in-active' );
			} else {
				dw_tmce_imgur_lib.push(image);
				t.addClass( 'in-active' );
			}
			

		} else {
			var library_item = $(document).find('.dw-tmce-imgur-library-item');
			var t = $(this);

			var library_active_items = $(document).find('.dw-tmce-imgur-library-item.in-active');

			var image = t.find('img').attr( 'src' );
			
			dw_tmce_imgur_lib = [ image ];
			if ( t.hasClass( 'in-active' ) ) {
				if ( library_active_items.length > 1 ) {
					library_item.removeClass('in-active');
					t.addClass( 'in-active' );
				} else {
					t.removeClass( 'in-active' );
					dw_tmce_imgur_lib = [];
				}
			} else {
				library_item.removeClass('in-active');
				t.addClass( 'in-active' );
			}
		}
	})

	$(document).on( 'click', '#dw-tmce-imgur-insert-picture-button', function(e){
		e.preventDefault();

		var $_editor = $('#dw-tmce-imgur-gallery-modal').attr( 'name' );

		var editor = tinyMCE.get( $_editor );

		var contentArea = editor.contentAreaContainer.id ;

		if ( dw_tmce_imgur_lib.length >= 1 ) {
			jQuery.each( dw_tmce_imgur_lib, function( i, item ){
				var image = '<img src="'+item+'" width="auto" height="auto" class="dw-tmce-image-for-upload" alt="">';
		    	tinymce.activeEditor.execCommand( 'mceInsertContent', false, image );
			})
			$('#dw-tmce-imgur-gallery-modal').css( 'display', 'none' );
			$('#dw-tmce-imgur-library-area').html('');
		} else {
			$('#dw-tmce-imgur-gallery-modal').css( 'display', 'none' );
			$('#dw-tmce-imgur-library-area').html('');
		}

		dw_tmce_imgur_lib = [];

	});
})(jQuery);
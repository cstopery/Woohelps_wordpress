(function() {

	tinymce.PluginManager.add('dw_tmce_upload_buttons', function( editor, url ) {

		var editorIcon = editor.getParam('dw_tmce_upload_home_url') + 'assets/img/imgur.png';

		if ( editor.getParam('dw_tmce_upload_icon') != '' ) {
			editorIcon = editor.getParam('dw_tmce_upload_icon');
		}

		editor.addButton( 'dw_tmce_upload_imgur_button', {
			tooltip: editor.getLang('dw_tmce_imgur_translate.uploadString'),
			image: editorIcon,
			onclick: function() {
				jQuery('#dw_tmce_upload_hidden_button').attr( 'name', editor.id );
				jQuery('#dw_tmce_upload_hidden_button').click();
			}
		});
	});
})();


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
						    	var image = '<img src="'+response.data.link+'" width="auto" height="auto" class="dw-tmce-image-for-upload" alt="">'
						    	tinymce.activeEditor.execCommand( 'mceInsertContent', false, image );
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
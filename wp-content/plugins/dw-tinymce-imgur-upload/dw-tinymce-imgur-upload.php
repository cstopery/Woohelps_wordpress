<?php
/**
 *  Plugin Name: DW TinyMCE Imgur Upload
 *  Description: Add a button to your TinyMCE allow uploading image direct to <a href="https://imgur.com/">Imgur.com</a> and return the image use for post, comment via text editor.
 *  Author: DesignWall
 *  Author URI: http://www.designwall.com
 *  Version: 1.0.5
 *  Text Domain: dw-tmce-imgur-upload
 *  @since 1.0.0
 */

// DW_EMBED plugin dir path
if ( ! defined( 'DW_TMCE_IMGUR_UPLOAD_DIR' ) ) {
	define( 'DW_TMCE_IMGUR_UPLOAD_DIR', plugin_dir_path( __FILE__ ) );
}
// DW_EMBED plguin dir URI
if ( ! defined( 'DW_TMCE_IMGUR_UPLOAD_URI' ) ) {
	define( 'DW_TMCE_IMGUR_UPLOAD_URI', plugin_dir_url( __FILE__ ) );
}

class DW_TMCE_IMGUR_UPLOAD {

	public function __construct() {
		$this->dir = DW_TMCE_IMGUR_UPLOAD_DIR;
		$this->uri = DW_TMCE_IMGUR_UPLOAD_URI;
		global $pagenow;
		$options = get_option( 'dw_tmce_upload', array() );

		add_filter( 'tiny_mce_before_init', array( $this, 'dw_tmce_imgur_upload_format_TinyMCE' ) );

		$enable = isset( $options['enable'] ) ? $options['enable'] : 'yes';
		$enable_tmce_comment = isset( $options['enable_tmce_comment'] ) ? $options['enable_tmce_comment'] : 'no';
		$imgur_client_id = isset( $options['imgur_client_id'] ) ? $options['imgur_client_id'] : null;
		$media_enable =isset( $options['media_enable'] ) ? $options['media_enable'] : 'no';

		add_action( 'wp_enqueue_scripts', array( $this, 'dw_tmce_imgur_enqueue_script' ) );

		if ( 'options-general.php' == $pagenow && 'dw-tmce-imgur-upload-settings' == $_GET['page'] ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'dw_tmce_imgur_enqueue_admin_script' ) );
		}
		

		if ( $imgur_client_id && 'yes' == $enable ) {
			add_action( 'init', array( $this, 'dw_tmce_imgur_upload_button_init') );
			if ( is_admin() ) {
				add_action( 'admin_footer', array( $this, 'dw_tmce_imgur_hidden_upload_button_init') );
				add_action( 'admin_enqueue_scripts', array( $this, 'dw_tmce_imgur_upload_plugin_style'), 1 );
			} else {
				add_action( 'wp_footer', array( $this, 'dw_tmce_imgur_hidden_upload_button_init') );
				add_action( 'wp_enqueue_scripts', array( $this, 'dw_tmce_imgur_upload_plugin_style'), 1 );
			}
		}

		if ( $media_enable && 'yes' == $media_enable ) {

			add_action( 'wp_ajax_dw-tmce-imgur-update-user-library', array( $this, 'dw_tmce_imgur_update_user_library' ) );
			add_action( 'wp_ajax_nopriv_dw-tmce-imgur-update-user-library', array( $this, 'dw_tmce_imgur_update_user_library' ) );

			add_action( 'wp_ajax_dw-tmce-imgur-get-user-library', array( $this, 'dw_tmce_imgur_get_user_library' ) );
			add_action( 'wp_ajax_nopriv_dw-tmce-imgur-get-user-library', array( $this, 'dw_tmce_imgur_get_user_library' ) );

			add_action( 'wp_head', array( $this, 'dw_tmce_imgur_gallery_modal' ) );
			add_action( 'admin_head', array( $this, 'dw_tmce_imgur_gallery_modal' ) );
		}

		if ( 'yes' == $enable_tmce_comment  ) {
				add_action('init', array( $this, 'wpb_allowedtags_comments' ) );
				add_filter( 'comment_form_field_comment', array( $this, 'dw_tmce_imgur_comment_editor' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'dw_tmce_imgur_upload_fixscripts') );
				add_filter( 'comment_reply_link', array( $this, 'dw_tmce_imgur_upload_fixcomment_reply_link') );
				add_action( 'wp_head', array( $this, 'dw_tmce_imgur_upload_fixwp_head') );
		}


		add_action( 'init', array( $this, 'dwqa_active') );

		add_action( 'admin_menu', array( $this, 'dw_tmce_imgur_upload_menu_page' ) );
		add_action( 'admin_init', array( $this, 'dw_tmce_upload_save' ) );
		add_filter( 'mce_external_languages', array( $this, 'dw_tmce_imgur_add_locale') );
		add_action( 'plugins_loaded', array( $this, 'dw_load_textdomain' ) );
	}

	public function dw_tmce_imgur_upload_menu_page(){
		add_submenu_page( 'options-general.php', __( 'DW Imgur', 'dw-tmce-imgur-upload' ), __( 'DW Imgur', 'dw-tmce-imgur-upload' ), 'manage_options', 'dw-tmce-imgur-upload-settings', array( $this, 'dw_tmce_upload_setting_layout' )  );

	}

	function dw_tmce_upload_setting_layout() {
		$options = get_option( 'dw_tmce_upload', array() );
		$enable = isset( $options['enable'] ) ? $options['enable'] : 'yes';
		$enable_tmce_comment = isset( $options['enable_tmce_comment'] ) ? $options['enable_tmce_comment'] : 'no';
		$media_enable =isset( $options['media_enable'] ) ? $options['media_enable'] : 'no';
		$imgur_client_id = isset( $options['imgur_client_id'] ) ? $options['imgur_client_id'] : '';

		$tmce_editor_icon = isset( $options['tmce_editor_icon'] ) ? $options['tmce_editor_icon'] : '';
		if ( $tmce_editor_icon && $tmce_editor_icon != '' ) {
			$image = wp_get_attachment_thumb_url( $tmce_editor_icon );
		}else {
			$image = '';
		}
		?>

		<div class="wrap">
			<h2><?php echo get_admin_page_title(); ?></h2>


			<form method="post">
				<h2>API setting</h2>
				<p>To use this plugin, you need to register app at <a href="https://api.imgur.com/oauth2/addclient" rel="nofollow" target="_blank">Imgur</a> and fill the textbox below with your client ID.</p>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Imgur Client ID</th>
							<td>
								<p>
									<input type="text" name="dw_tmce_upload[imgur_client_id]" value="<?php echo $imgur_client_id; ?>" class="medium-text">
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				<h2>Plugin settings</h2>
				<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">Enable media library</th>
						<td>
							<p>
							<select name="dw_tmce_upload[media_enable]">
								<option value="yes" <?php selected( 'yes', $media_enable ) ?>>Yes</option>
								<option class="level-0" value="no" <?php selected( 'no', $media_enable ) ?>>No</option>
							</select>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Enable Imgur Upload in WP Editor</th>
						<td>
							<p>
							<select name="dw_tmce_upload[enable]">
								<option value="yes" <?php selected( 'yes', $enable ) ?>>Yes</option>
								<option class="level-0" value="no" <?php selected( 'no', $enable ) ?>>No</option>
							</select>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Enable Imgur Upload in Comment Form</th>
						<td>
							<p>
							<select name="dw_tmce_upload[enable_tmce_comment]">
								<option value="yes" <?php selected( 'yes', $enable_tmce_comment ) ?>>Yes</option>
								<option class="level-0" value="no" <?php selected( 'no', $enable_tmce_comment ) ?>>No</option>
							</select>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Editor icon</th>
						<td>
							<div id="editor_logo" style="float:left;margin-right:10px;"><img src="<?php echo esc_url( $image );?>" width="60px" height="60px" /></div>
							<p style="line-height:60px;">
								<input type="hidden" id="dw_tmce_editor_icon" name="dw_tmce_upload[tmce_editor_icon]" value="<?php echo esc_attr( $tmce_editor_icon ) ? esc_attr( $tmce_editor_icon ) : '';?>" />
								<button type="button" class="dw_tmce_upload_image_button button"><?php _e( 'Upload/Add image', 'dw-tmce-imgur-upload' ); ?></button>
								<button type="button" class="dw_tmce_remove_image_button button"><?php _e( 'Remove image', 'dw-tmce-imgur-upload' ); ?></button>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
				<button type="submit" class="button button-primary"><?php esc_attr_e( 'Save Changes', 'dw-tmce-imgur-upload' ) ?></button>
			</form>
		</div>

		<?php
	}

	public function dw_tmce_upload_save() {
		if ( isset( $_POST['dw_tmce_upload'] ) ) {
			update_option( 'dw_tmce_upload', $_POST['dw_tmce_upload'] );
		}
	}

	function dw_tmce_imgur_upload_button_init() {
		add_filter("mce_external_plugins", array( $this, 'dw_tmce_imgur_upload_tc_plugin') );
		add_filter('mce_buttons', array( $this, 'dw_tmce_imgur_upload_tc_buttons') );
	}

	function dw_tmce_imgur_upload_tc_plugin( $plugin_array ) {
		$options = get_option( 'dw_tmce_upload', array() );
		$media_enable =isset( $options['media_enable'] ) ? $options['media_enable'] : 'no';
		if ( $media_enable && 'yes' == $media_enable ) {
			$plugin_array['dw_tmce_upload_buttons'] = DW_TMCE_IMGUR_UPLOAD_URI .'assets/js/library.js';
		} else {
			$plugin_array['dw_tmce_upload_buttons'] = DW_TMCE_IMGUR_UPLOAD_URI .'assets/js/editor.js';
		}

	   
	    return $plugin_array;
	}

	function dw_tmce_imgur_upload_tc_buttons( $buttons ) {
	   array_push($buttons, 'dw_tmce_upload_imgur_button');
	   return $buttons;
	}

	function dw_tmce_imgur_hidden_upload_button_init() { ?>
		<input type="file" id="dw_tmce_upload_hidden_button" style="display:none!important;" title="Load File" />
	<?php }

	function dwqa_active() {
		if ( class_exists( 'DW_Question_Answer' ) ) {
				return add_filter( 'dwqa_tinymce_toolbar1', array( $this, 'add_dwqa_tc_button' ) );
		} else {
			return;
		}

	}

	function add_dwqa_tc_button( $buttons ) {
		return $buttons. 'dw_tmce_upload_imgur_button,|,';
	}

	function dw_tmce_imgur_upload_format_TinyMCE( $in ) {

	   $options = get_option( 'dw_tmce_upload', array() );
		$imgur_client_id = isset( $options['imgur_client_id'] ) ? $options['imgur_client_id'] : null;
		$ajax_url = admin_url( 'admin-ajax.php' );
		$tmce_editor_icon = isset( $options['tmce_editor_icon'] ) ? $options['tmce_editor_icon'] : '';
		if ( $tmce_editor_icon && $tmce_editor_icon != '' ) {
			$image = wp_get_attachment_thumb_url( $tmce_editor_icon );
		}else {
			$image = '';
		}

		    $in['dw_tmce_upload_home_url'] = DW_TMCE_IMGUR_UPLOAD_URI;
		    $in['dw_tmce_upload_imgur_client_id'] = $imgur_client_id;
		    $in['dw_tmce_upload_ajax_url'] = $ajax_url;
		    $in['dw_tmce_upload_icon'] = $image;

	    return $in;
	}

	// editor comment

	function dw_tmce_imgur_comment_editor() {
		global $post;

		if ( is_singular( 'post' ) || is_page() ) {
			ob_start();

			$plugins = apply_filters( 'mce_external_plugins', array( 'colorpicker', 'lists', 'fullscreen', 'image', 'wordpress', 'wpeditimage', 'wplink', 'dw_tmce_upload_buttons' ), 'comment' );

			$toolbar1 = apply_filters( 'dw_tmce_imgur_upload_custon_mce_buttons', 'bold,italic,underline,|,' . 'bullist,numlist,blockquote,|,' . 'link,unlink,|,' . 'image,code,|,'. 'spellchecker,fullscreen,dw_tmce_upload_imgur_button,|,' );

			wp_editor( '', 'comment', array(
				'textarea_rows' => 15,
				'quicktags' => false,
				'media_buttons' => false,
				'plugins' => $plugins,
				'tinymce' => array(
					'toolbar1' => $toolbar1,
				),
			) );

			$editor = ob_get_contents();

			ob_end_clean();

			$editor = str_replace( 'post_id=0', 'post_id='.get_the_ID(), $editor );

			return $editor;
		} else {
			return false;
		}

	}

	// comment editor fix


		function wpb_allowedtags_comments() {
			global $allowedtags;
			$allowedtags = array(
				'a' => array(
				'href' => true,
				'title' => true,
			),
			'abbr' => array(
				'title' => true,
			),
			'acronym' => array(
				'title' => true,
			),
			'b' => array(),
			'blockquote' => array(
				'cite' => true,
			),
			'cite' => array(),
			'code' => array(),
			'del' => array(
				'datetime' => true,
			),
			'em' => array(),
			'i' => array(),
			'q' => array(
				'cite' => true,
			),
			's' => array(),
			'strike' => array(),
			'strong' => array(),
			'img' => array(
				'class' => array(),
				'src' => array(),
				'alt' => array(),
				'width' => array(),
				'height' => array(),
				'data-mce-src' => array(),
			),

	  );
	}
	function dw_tmce_imgur_upload_fixscripts() {
		wp_enqueue_script('jquery');
	}

	function dw_tmce_imgur_upload_fixcomment_reply_link($link) {
	return str_replace( 'onclick=', 'data-onclick=', $link );
	}

	function dw_tmce_imgur_upload_fixwp_head() {
	?>
		<script type="text/javascript">
			jQuery(function($){
				$('.comment-reply-link').click(function(e){
					e.preventDefault();
					var args = $(this).data('onclick');
					args = args.replace(/.*\(|\)/gi, '').replace(/\"|\s+/g, '');
					args = args.split(',');
					tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
					addComment.moveForm.apply( addComment, args );
					tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
					});
			});
		</script>
	<?php }

	function dw_tmce_imgur_upload_plugin_style(){
		wp_enqueue_style( 'dw-tinymce-imgur-upload-plugin', DW_TMCE_IMGUR_UPLOAD_URI .'assets/css/style.css' );
	}

	// handle imgur library

	function dw_tmce_imgur_gallery_modal() { ?>
	<div class="dw-tmce-modal dw-tmce-imgur-modal" id="dw-tmce-imgur-gallery-modal" tabindex="-1" role="dialog" >
		<div class="dw-tmce-modal-dialog dw-tmce-modal-lg unique-modal-lg dw-tmce-imgur-modal-lg" role="document">
			<div class="dw-tmce-modal-content">
				<div class="dw-tmce-modal-header">
					<h3 class="dw-tmce-modal-title">Imgur image library</h3>
				</div>
				<!-- <div class="dw-tmce-imgur-lib-filter">
					<select id="dw-tmce-imgur-extension-filter">


					</select>
				</div> -->
				<div class="dw-tmce-modal-body imgur-upload-modal">
					<div id="dw-tmce-imgur-library-area">

					</div>
				</div>
				<div class="dw-tmce-modal-footer">
					<button type="button" id="dw-tmce-dismiss-modal" class="btn btn-default" data-dismiss="modal">Close</button>
 					<button id="dw-tmce-imgur-insert-picture-button" type="button" class="btn btn-primary">Insert</button>
				</div>
			</div>
		</div>
	</div>
	<?php }

	function dw_tmce_imgur_update_user_library() {
		if ( is_user_logged_in () ) {
			$user_id = get_current_user_id();

			$imgur_id = isset( $_POST['imgur_id'] ) ? $_POST['imgur_id'] : null;
			$imgur_type = isset( $_POST['imgur_type'] ) ? $_POST['imgur_type'] : null;
			$imgur_link = isset( $_POST['imgur_link'] ) ? $_POST['imgur_link'] : null;
			$imgur_date = isset( $_POST['imgur_date'] ) ? $_POST['imgur_date'] : null;

			$image = array();

			$image['imgur_id'] = $imgur_id;
			$image['imgur_type'] = $imgur_type;
			$image['imgur_link'] = $imgur_link;
			$image['imgur_date'] = $imgur_date;

			add_user_meta( $user_id, '_dw_tmce_imgur_image_library', $image, false );

		} else {
			die(0);
		}
		die(0);
	}

	function dw_tmce_imgur_get_user_library() {
		if ( is_user_logged_in () ) {
			$user_id = get_current_user_id();

			$library = get_user_meta( $user_id, '_dw_tmce_imgur_image_library', false );
			$data = array(
				'data' => $library,
			);
			wp_send_json_success( $data );
		} else {
			$data = array(
				'message' => 'You need to login to use this function.'
			);
			wp_send_json_error( $data );
		}
		die(0);
	}

	function dw_tmce_imgur_enqueue_script() {
		wp_enqueue_script( 'dw-tmce-imgur-library-js', DW_TMCE_IMGUR_UPLOAD_URI. 'assets/js/library.js', array( 'jquery' ) );
	}

	function dw_tmce_imgur_enqueue_admin_script() {
		wp_enqueue_media();
		wp_enqueue_script( 'dw-tmce-admin-script', DW_TMCE_IMGUR_UPLOAD_URI. 'assets/js/admin.js', array( 'jquery' ) );
	}

	function dw_tmce_imgur_add_locale($locales) {
	    $locales['dw_tmce_upload_buttons'] = DW_TMCE_IMGUR_UPLOAD_DIR . 'dw-tmce-imgur-langs.php';
	    return $locales;
	}

	function dw_load_textdomain() {
		$locale = get_locale();
		$mo = 'dw-tmce-imgur-upload-' . $locale . '.mo';
		
		load_textdomain( 'dw-tmce-imgur-upload', WP_LANG_DIR . '/dw-tmce-imgur-upload/' . $mo );
		load_textdomain( 'dw-tmce-imgur-upload', DW_TMCE_IMGUR_UPLOAD_DIR . 'languages/' . $mo );
		load_plugin_textdomain( 'dw-tmce-imgur-upload' );
	}
}
$GLOBALS['dw_tmce_imgur_upload'] = new DW_TMCE_IMGUR_UPLOAD();

<?php  

function dwqa_init_tinymce_editor( $args = array() ) {
	global $dwqa;
	$dwqa->editor->display( $args );
}

function dwqa_paste_srtip_disable( $mceInit ){
	$mceInit['paste_strip_class_attributes'] = 'none';
	return $mceInit;
}

class DWQA_Editor {

	public function __construct() {

		add_action( 'init', array( $this, 'tinymce_addbuttons' ) );
		//Ajaxs
		add_action( 'wp_ajax_dwqa-editor-update-answer-init', array( $this, 'ajax_create_update_answer_editor' ) );
		add_action( 'wp_ajax_dwqa-editor-update-question-init', array( $this, 'ajax_create_update_question_editor' ) );

		add_filter( 'dwqa_prepare_edit_answer_content', 'wpautop' );
		add_filter( 'dwqa_prepare_edit_question_content', 'wpautop' );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	public function enqueue() {
		global $dwqa_general_settings;
		if ( isset( $dwqa_general_settings['markdown-editor'] ) && $dwqa_general_settings['markdown-editor'] ) {
			wp_enqueue_script( 'dwqa_simplemde', DWQA_URI . 'assets/js/simplemde.min.js', array(), true );
			wp_enqueue_style( 'dwqa_simplemde', DWQA_URI . 'assets/css/simplemde.min.css' );
		}
	}
	
	public function tinymce_addbuttons() {
		if ( get_user_option( 'rich_editing' ) == 'true' && ! is_admin() ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_custom_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_custom_button' ) );
		}
	}

	public function register_custom_button( $buttons ) {
		array_push( $buttons, '|', 'dwqaCodeEmbed' );
		return $buttons;
	} 

	public function add_custom_tinymce_plugin( $plugin_array ) {
		global $dwqa_options;
		if ( is_singular( 'dwqa-question' ) || ( $dwqa_options['pages']['submit-question'] && is_page( $dwqa_options['pages']['submit-question'] ) ) ) {
			$plugin_array['dwqaCodeEmbed'] = DWQA_URI . 'assets/js/code-edit-button.js';
		}
		return $plugin_array;
	}
	public function display( $args ) {
		global $dwqa_general_settings;
		extract( wp_parse_args( $args, array(
				'content'       => '',
				'id'            => 'dwqa-custom-content-editor',
				'textarea_name' => 'custom-content',
				'rows'          => 5,
				'wpautop'       => false,
				'media_buttons' => false,
		) ) );

		$dwqa_tinymce_css = apply_filters( 'dwqa_editor_style', DWQA_URI . 'templates/assets/css/editor-style.css' );
		$toolbar1 = apply_filters( 'dwqa_tinymce_toolbar1', 'bold,italic,underline,|,' . 'bullist,numlist,blockquote,|,' . 'link,unlink,|,' . 'image,code,|,'. 'spellchecker,fullscreen,dwqaCodeEmbed,|,' );

		if ( isset( $dwqa_general_settings['markdown-editor'] ) && $dwqa_general_settings['markdown-editor'] ) {
			$this->editor( $content, $id, $args );
		} else {
			wp_editor( $content, $id, array(
				'wpautop'       => $wpautop,
				'media_buttons' => $media_buttons,
				'textarea_name' => $textarea_name,
				'textarea_rows' => $rows,
				'tinymce' => array(
						'toolbar1' => $toolbar1,
						'toolbar2'   => '',
						'content_css' => $dwqa_tinymce_css
				),
				'quicktags'     => true,
			) );
		}
	}

	public function editor( $content, $id, $settings = array() ) {
		$default = array(
			'editor_class' => 'dwqa_editor',
			'placeholder' => '',
			'spellchecker' => false,
			'toolbar' => "['bold','italic','|','link','image','|','preview','fullscreen']",
			'tabsize' => 4,
			'tabindex' => false,
			'textarea_rows' => 5,
			'textarea_cols' => 10,
			'autofocus' => false,
		);

		$set = wp_parse_args( $settings, $default );
		$set['textarea_name'] = isset( $set['textarea_name'] ) ? $set['textarea_name'] : $id;
		echo '<textarea class="'.$set['editor_class'].'" id="'.$id.'" name="'.$set['textarea_name'].'" rows="'.$set['textarea_rows'].'" cols="'.$set['textarea_cols'].'" placeholder="'.$set['placeholder'].'" tabindex="'.$set['tabindex'].'">'.$content.'</textarea>';
		?>
		<script type="text/javascript">
			var dwqa_simplemde = new SimpleMDE({
				element: document.getElementById("<?php echo $id ?>"),
				initialValue: '<?php echo $content ?>',
				autofocus: <?php echo $set['autofocus'] ? 'true' : 'false' ?>,
				placeholder: '<?php echo $set['placeholder'] ?>',
				spellChecker: <?php echo $set['spellchecker'] ? 'true' : 'false' ?>,
				tabSize: '<?php echo $set['tabsize'] ?>',
				toolbar: <?php echo !empty( $set['toolbar'] ) ? $set['toolbar'] : '' ?>
			});
		</script>
		<?php
	}

	public function ajax_create_update_answer_editor() {

		if ( ! isset( $_POST['answer_id'] ) || ! isset( $_POST['question'] ) ) {
			return false;
		}
		extract( $_POST );

		ob_start();
		?>
		<form action="<?php echo admin_url( 'admin-ajax.php?action=dwqa-add-answer' ); ?>" method="post">
			<?php wp_nonce_field( '_dwqa_add_new_answer' ); ?>

			<?php if ( 'draft' == get_post_status( $answer_id ) && current_user_can( 'manage_options' ) ) { 
			?>
			<input type="hidden" name="dwqa-action-draft" value="true" >
			<?php } ?> 
			<input type="hidden" name="dwqa-action" value="update-answer" >
			<input type="hidden" name="answer-id" value="<?php echo $answer_id; ?>">
			<input type="hidden" name="question" value="<?php echo $question; ?>">
			<?php 
				$answer = get_post( $answer_id );
				$answer_content = get_post_field( 'post_content', $answer_id );
				$answer_content = apply_filters( 'dwqa_prepare_edit_answer_content', $answer_content );
				dwqa_init_tinymce_editor( array(
					'content'       => $answer_content, 
					'textarea_name' => 'answer-content',
					'wpautop'       => false,
				) ); 
			?>
			<script type="text/javascript">
				var id = 'dwqa-custom-content-editor';
				var settings = tinyMCEPreInit.mceInit['dwqa-answer-question-editor'];

                settings.elements = id;
                settings.body_class = id + ' post-type-dwqa-answer';
                //settings.editor_selector = id; // deprecated in TinyMCE 4.x
                settings.selector = '#' + id;
                //init tinymce
                if( tinyMCE.get(id) ) {
                    tinymce.remove('#'+id);   
                }
                tinyMCE.init(settings);
			</script>
			<p class="dwqa-answer-form-btn">
				<input type="submit" name="submit-answer" class="dwqa-btn dwqa-btn-default" value="<?php _e( 'Update','dwqa' ) ?>">
				<a type="button" class="answer-edit-cancel dwqa-btn dwqa-btn-link" ><?php _e( 'Cancel','dwqa' ) ?></a>
				<?php if ( 'draft' == get_post_status( $answer_id ) && current_user_can( 'manage_options' ) ) { 
				?>
				<input type="submit" name="submit-answer" class="btn btn-primary btn-small" value="<?php _e( 'Publish','dwqa' ) ?>">
				<?php } ?>
			</p>
			<div class="dwqa-privacy">
				<input type="hidden" name="privacy" value="<?php echo $answer->post_status ?>">
				<span class="dwqa-change-privacy">
					<div class="dwqa-btn-group">
						<button type="button" class="dropdown-toggle" ><span><?php echo 'private' == get_post_status() ? '<i class="fa fa-lock"></i> '.__( 'Private','dwqa' ) : '<i class="fa fa-globe"></i> '.__( 'Public','dwqa' ); ?></span> <i class="fa fa-caret-down"></i></button>
						<div class="dwqa-dropdown-menu">
							<div class="dwqa-dropdown-caret">
								<span class="dwqa-caret-outer"></span>
								<span class="dwqa-caret-inner"></span>
							</div>
							<ul role="menu">
								<li data-privacy="publish" <?php if ( $answer->post_status == 'publish' ) { echo 'class="current"'; } ?> title="<?php _e( 'Everyone can see','dwqa' ); ?>"><a href="#"><i class="fa fa-globe"></i> <?php _e( 'Public','dwqa' ); ?></a></li>
								<li data-privacy="private"  <?php if ( $answer->post_status == 'private' ) { echo 'class="current"'; } ?>  title="<?php _e( 'Only Author and Administrator can see','dwqa' ); ?>" ><a href="#"><i class="fa fa-lock"></i> <?php _e( 'Private','dwqa' ) ?></a></li>
							</ul>
						</div>
					</div>
				</span>
			</div>
		</form>
		<?php
		$editor = apply_filters( 'dwqa_answer_edit_content_editor', ob_get_contents(), $_POST );
		ob_end_clean();
		wp_send_json_success( array( 'editor' => $editor ) );
	}

	public function ajax_create_update_question_editor() {

		check_ajax_referer( '_dwqa_edit_question', 'nonce' );

		if ( ! isset( $_POST['post'] ) ) {
			return false;
		}

		extract( $_POST );
		$title = get_the_title( $post );
		$title = apply_filters( 'dwqa_prepare_edit_question_title', $title, $post );
		$content = get_post_field( 'post_content', $post );
		$content = apply_filters( 'dwqa_prepare_edit_question_content', $title, $post );
		$args = array(
			'content' => $content,
			'id' => 'dwqa-question-edit-form',
			'textarea_name' => 'question-content-edit'
		);

		ob_start();
		?>
		<form method="post" class="dwqa-answer-form">
			<input name="question_title" value="<?php echo $title ?>">
			<?php dwqa_init_tinymce_editor( $args ); ?>
			<input type="hidden" name="question_id" value="<?php echo esc_attr( $post ) ?>">
		</form>
		<?php
		$editor = apply_filters( 'dwqa_question_edit_content_editor', ob_get_contents(), $_POST );
		ob_end_clean();
		wp_send_json_success( array( 'editor' => $editor ) );
	}
}

?>

<div class="modal fade" id="newQuestionModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="modal-dialog-title-text" id=":4s" role="heading">提问</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form method="post" class="dwqa-content-edit-form">
					<p>
						<?php $title = isset($_POST['question-title']) ? $_POST['question-title'] : ''; ?>
						<input type="text" placeholder="请输入你的问题" data-nonce="<?php echo wp_create_nonce('_dwqa_filter_nonce') ?>" id="question-title" name="question-title" value="<?php echo $title ?>" tabindex="1">
					</p>
					<span style="float:left">问题说明（可选）：</span>
					<?php $content = isset($_POST['question-content']) ? $_POST['question-content'] : ''; ?>
					<p><?php dwqa_init_tinymce_editor(array('content' => $content, 'textarea_name' => 'question-content', 'id' => 'question-content')) ?></p>
					<?php global $dwqa_general_settings; ?>
					<?php if (isset($dwqa_general_settings['enable-private-question']) && $dwqa_general_settings['enable-private-question']) : ?>
						<p>
						<label for="question-status"><?php _e('Status', 'dwqa') ?></label>
						<select class="dwqa-select" id="question-status" name="question-status">
							<optgroup label="<?php _e('Who can see this?', 'dwqa') ?>">
								<option value="publish"><?php _e('Public', 'dwqa') ?></option>
								<option value="private"><?php _e('Only Me &amp; Admin', 'dwqa') ?></option>
							</optgroup>
						</select>
					</p>
					<?php endif; ?>
					<p>
						<label for="question-category"><?php _e('City', 'dwqa') ?></label>
						<?php
						wp_dropdown_categories(array(
												   'name' => 'question-category',
												   'id' => 'question-category',
												   'taxonomy' => 'dwqa-question_category',
												   'show_option_none' => __('Select question category', 'dwqa'),
												   'hide_empty' => 0,
												   'quicktags' => array('buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close'),
												   'selected' => isset($_POST['question-category']) ? $_POST['question-category'] : false,
											   ));
						?>
					</p>
					<?php if (dwqa_current_user_can('post_question') && !is_user_logged_in()) : ?>
						<p>
						<label for="_dwqa_anonymous_email"><?php _e('Your Email', 'dwqa') ?></label>
							<?php $email = isset($_POST['_dwqa_anonymous_email']) ? $_POST['_dwqa_anonymous_email'] : ''; ?>
							<input type="email" class="" name="_dwqa_anonymous_email" value="<?php echo $email ?>">
					</p>
						<p>
						<label for="_dwqa_anonymous_name"><?php _e('Your Name', 'dwqa') ?></label>
							<?php $name = isset($_POST['_dwqa_anonymous_name']) ? $_POST['_dwqa_anonymous_name'] : ''; ?>
							<input type="text" class="" name="_dwqa_anonymous_name" value="<?php echo $name ?>">
					</p>
					<?php endif; ?>
					<?php wp_nonce_field('_dwqa_submit_question') ?>
					<?php dwqa_load_template('captcha', 'form'); ?>
					<input type="submit" name="dwqa-question-submit" value="<?php _e('Submit', 'dwqa') ?>">
				</form>
			</div>
		</div>
	</div>
</div>


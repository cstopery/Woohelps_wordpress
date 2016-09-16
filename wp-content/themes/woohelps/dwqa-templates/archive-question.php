<?php
/**
 * The template for displaying question archive pages
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */
?>

<div class="dwqa-questions-archive">
	<div class="row">
		<div class="btn-group pull-right margin-bottom" role="group" aria-label="dw-qa-buttons">
			<button class="btn btn-default btn-lg" id="askButton"><i class="glyphicon glyphicon-question-sign"></i> 提问</button>
			<button class="btn btn-default btn-lg" id="answerButton"><i class="glyphicon glyphicon-edit"></i> 回答</button>
		</div>
	</div>
		<div class="dwqa-questions-list">
		<?php do_action( 'dwqa_before_question_stickies' ); ?>
		<?php if ( dwqa_has_question_stickies() && 'all' == dwqa_current_filter() ) : ?>
			<?php while( dwqa_has_question_stickies() ) : dwqa_the_sticky() ?>
				<?php dwqa_load_template( 'content', 'question' ) ?>
			<?php endwhile; ?>
		<?php endif; ?>
		<?php do_action( 'dwqa_after_question_stickies' ); ?>

		<?php do_action( 'dwqa_before_questions_list' ) ?>
		<?php if ( dwqa_has_question() ) : ?>
			<?php while ( dwqa_has_question() ) : dwqa_the_question(); ?>
				<?php if ( get_post_status() == 'publish' || ( get_post_status() == 'private' && dwqa_current_user_can( 'edit_question', get_the_ID() ) ) ) : ?>
					<?php dwqa_load_template( 'content', 'question' ) ?>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php dwqa_load_template( 'content', 'none' ) ?>
		<?php endif; ?>
		<?php do_action( 'dwqa_after_questions_list' ) ?>
		</div>
		<div class="dwqa-questions-footer">
			<button class="btn btn-default btn-block" id="loadMore">载入更多问题</button>
			<?php // dwqa_question_paginate_link() ?>
		</div>

	<?php do_action( 'dwqa_after_questions_archive' ); ?>
</div>
<div class="to-top" id="toTop">
	<div class="inner">
		<i class="fa fa-arrow-up"></i>
	</div>
</div>

<div class="modal fade" id="newQuestionModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				提问
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form method="post" class="dwqa-content-edit-form">
					<p>
						<label for="question_title"><?php _e('Title', 'dwqa') ?></label>
						<?php $title = isset($_POST['question-title']) ? $_POST['question-title'] : ''; ?>
						<input type="text" data-nonce="<?php echo wp_create_nonce('_dwqa_filter_nonce') ?>" id="question-title" name="question-title" value="<?php echo $title ?>" tabindex="1">
					</p>
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
						<label for="question-category"><?php _e('Category', 'dwqa') ?></label>
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
					<p>
						<label for="question-tag"><?php _e('Tag', 'dwqa') ?></label>
						<?php $tags = isset($_POST['question-tag']) ? $_POST['question-tag'] : ''; ?>
						<input type="text" class="" name="question-tag" value="<?php echo $tags ?>">
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

<script>
	var $ = jQuery;
	var page = 1;
	var user_logged_in = <?=(is_user_logged_in())? 'true' : 'false' ?>;

	$(function() {
		var offset = 250;
		var duration = 300;
		var elToTop = $('#toTop');

		$(window).scroll(function() {
			if ($(this).scrollTop() > offset) {
				elToTop.fadeIn(duration);
			}
			else {
				elToTop.fadeOut(duration);
			}
		});

		elToTop.on('click', function(e) {
			e.preventDefault();
			$('html, body').animate({
				scrollTop: 0
			}, duration);

			return false;
		});

		$('#askButton').on('click', function(e) {
			e.preventDefault();

			if (!user_logged_in) {
				$('#loginModal').modal('show');
			}
			else {
				$('#newQuestionModal').modal('show');
			}
		});

		$('#newQuestionModal').on('hidden.bs.modal', function(){
			$(this).find('iframe').html('');
			$(this).find('iframe').attr('src', '');
		});

		$('#answerButton').on('click', function(e) {
			e.preventDefault();

			if (!user_logged_in) {
				$('#loginModal').modal('show');
			}
			else {
				window.location.href = "/dwqa-questions/?filter=unanswered";
			}
		});

		$('.subscribe-button').on('click',function(e){
			e.preventDefault();
			var t = $(this);

			if (!user_logged_in) {
				$('#loginModal').modal('show');
				return false;
			}

			if (t.hasClass('processing')) {
				return false;
			}

			t.addClass('processing');

			var data = {
				action: 'dwqa-follow-question',
				nonce: t.data('nonce'),
				post: t.data('post')
			};

			$.ajax({
				url: '/wp-admin/admin-ajax.php',
				data: data,
				type: 'POST',
				dataType: 'json',
				success: function(data){
					t.removeClass('processing');
					if (data.success && data.success == true) {
						if (data.data.code == 'unfollowed') {
							t.html('关注问题');
						}
						else if (data.data.code == 'followed') {
							t.html('已关注');
						}
					}
				}
			});
		});

		$('#loadMore').on('click', function (e) {
			e.preventDefault();

			var _this = $(this);

			if (_this.hasClass('disabled')) {
				return false;
			}

			_this.addClass('disabled');
			_this.html('正在载入...');

			var newPage = parseInt(page) + 1;

			var param = '';
			if (queryString) {
				console.log(queryString);
				param = '/?' + queryString;
			}

			$.ajax({
				url: currentLocation + 'page/' + newPage + param,
				type: 'GET',
				dataType: 'html',
				success: function(data) {
					var list = $(data).find('.dwqa-questions-list').html();
					if (~list.indexOf('dwqa-question-item')) {
						$(list).appendTo($('.dwqa-questions-list'));
						page++;
						_this.removeClass('disabled');
						_this.html('载入更多问题');
					}
					else {
						_this.html('没有更多内容了');
					}
				},
				fail: function() {
					_this.html('没有更多内容了');
				},
				complete: function() {

				}
			});
		})
	});

	var currentLocation = (window.location.href.split('?')[0] || window.location);
	var queryString = (window.location.search.split('?')[1] || '');
</script>
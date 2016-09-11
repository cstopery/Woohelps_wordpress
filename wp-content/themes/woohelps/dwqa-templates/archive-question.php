<?php
/**
 * The template for displaying question archive pages
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */
?>

<?php
if( is_user_logged_in() && dwqa_current_user_can('post_question')){
	$ask_link =  dwqa_get_ask_link();
	$answer_link = get_option('siteurl') . '/dwqa-questions/?filter=unanswered';
} else {
	$ask_link = $answer_link = get_option( 'siteurl' ) . "/wp-login.php";
}
?>

<div class="dwqa-questions-archive">
	<div class="row">
		<div class="btn-group pull-right margin-bottom" role="group" aria-label="dw-qa-buttons">
			<a class="btn btn-default btn-lg" href="<?=$ask_link?>"><i class="glyphicon glyphicon-question-sign"></i> 提问</a>
			<a class="btn btn-default btn-lg" href="<?=$answer_link?>"><i class="glyphicon glyphicon-edit"></i> 回答</a>
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
			<?php dwqa_question_paginate_link() ?>
		</div>

	<?php do_action( 'dwqa_after_questions_archive' ); ?>
</div>
<div class="to-top" id="toTop">
	<div class="inner">
		<i class="fa fa-arrow-up"></i>
	</div>
</div>
<script>
	var $ = jQuery;
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

		$('.subscribe-button').on('click',function(e){
			e.preventDefault();
			var t = $(this);

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
	});
</script>
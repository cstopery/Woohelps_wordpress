<?php
/**
 * The template for displaying single questions
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */
?>
<?php do_action( 'dwqa_before_single_question_content' ); ?>
<div class="dwqa-question-item">
	<div><?php echo get_the_term_list( get_the_ID(), 'dwqa-question_tag', '<span class="dwqa-question-tag">' . __( '', 'dwqa' ), ' ', '</span>' ); ?></div>
	<div class="dwqa-question-title"><?php echo get_the_title();?></div>
	<div class="dwqa-question-meta">
		<?php $user_id = get_post_field( 'post_author', get_the_ID() ) ? get_post_field( 'post_author', get_the_ID() ) : false ?>
		<span class="dwqa-question-actions"><?php dwqa_question_button_action() ?></span>
	</div>
	<div class="dwqa-question-content"><?php the_content(); ?></div>

	<?php comments_template(); ?>
</div>
<?php do_action( 'dwqa_after_single_question_content' ); ?>

<?php
/**
 * The template for displaying question archive pages
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */
?>
<?php
$query_url = home_url( add_query_arg( null, null ));

?>

<div class="dwqa-questions-archive">
	<div class="row">
		<div class="btn-group pull-right margin-bottom" role="group" aria-label="dw-qa-buttons">
			<button class="btn btn-default btn-lg" id="askButton"><i class="glyphicon glyphicon-question-sign"></i> 提问</button>
			<?php if(strpos($query_url, 'filter=unanswered') > 0):?>
				<button class="btn btn-default btn-lg" id="backButton"><i class="glyphicon glyphicon-circle-arrow-left"></i> 返回</button>
			<?php else: ?>
				<button class="btn btn-default btn-lg" id="answerButton"><i class="glyphicon glyphicon-edit"></i> 回答</button>
			<?php endif;?>

		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?=dwqa_search_form();?>
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

<?php get_template_part('parts/question/newquestion', 'modal'); ?>

<script>
	var user_logged_in = <?=(is_user_logged_in())? 'true' : 'false' ?>;
</script>
<script src="<?=get_stylesheet_directory_uri()?>/js/dwqa-util.js"></script>
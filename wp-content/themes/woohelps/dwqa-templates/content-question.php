<?php
/**
 * The template for displaying question content
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */
?>
<div class="<?php echo dwqa_post_class(); ?>">
	<header class="dwqa-question-title"><?php dwqa_question_print_status() ?><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></header>
	<div class="dwqa-question-meta">
		<?php
			global $post;
			$user_id = get_post_field( 'post_author', get_the_ID() ) ? get_post_field( 'post_author', get_the_ID() ) : false;
			$time = human_time_diff( get_post_time( 'U' ) );
			$text = __( 'asked', 'dwqa' );
			$latest_answer = dwqa_get_latest_answer();

			if ( $latest_answer ) {
				$time = human_time_diff( strtotime( $latest_answer->post_date ) );
				$text = __( 'answered', 'dwqa' );
			}

			// fetch and display one answer data
			// by mogita
			$args = [
				'post_type' => 'dwqa-answer', // get answer
				'posts_per_page' => 1, // show 5 answer
				'orderby' => 'meta_value_num id',
				'meta_key' => '_dwqa_votes',
				'order'	=> 'DESC',
				'meta_query' => [
					'key' => '_question',
					'value' => [get_the_ID()],
					'compare' => 'IN'
				]
			];

			$best_answers = get_posts($args);
			if (is_array($best_answers) && count($best_answers) > 0) {
				$best_answer = $best_answers[0]->post_content;
				if (mb_strlen($best_answer, 'utf8') > 150) $best_answer = mb_substr($best_answer, 0, 150, 'utf8') . '...';
				?>
				<div class="best-answer-excerpt">
					<?=$best_answer?>
				</div>
				<?php
			}
		?>
		<?php printf( __( '<span><a href="%s">%s%s</a> %s %s ago</span>', 'dwqa' ), dwqa_get_author_link( $user_id ), get_avatar( $user_id, 48 ), dwqa_get_author(), $text, $time ) ?>
		<?php echo get_the_term_list( get_the_ID(), 'dwqa-question_category', '<span class="dwqa-question-category">' . __( '&nbsp;&bull;&nbsp;', 'dwqa' ), ', ', '</span>' ); ?>
	</div>

</div>

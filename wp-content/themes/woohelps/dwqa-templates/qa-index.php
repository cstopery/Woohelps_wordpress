<?php
/**
 * The template for displaying question content
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */
?>
<div class="<?php echo dwqa_post_class(); ?>">

    <header class="dwqa-question-title">
        <a href="<?php the_permalink(); ?>">
            <span class="dwqa-status">
                <?php echo dwqa_question_answers_count(); ?> 回答
            </span>
            <?php the_title(); ?>
        </a>
    </header>

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
        ?>
        <?php printf( __( '<span><a href="%s">%s%s</a> %s %s ago</span>', 'dwqa' ), dwqa_get_author_link( $user_id ), get_avatar( $user_id, 48 ), dwqa_get_author(), $text, $time ) ?>
        <?php echo get_the_term_list( get_the_ID(), 'dwqa-question_category', '<span class="dwqa-question-category">' . __( '&nbsp;&bull;&nbsp;', 'dwqa' ), ', ', '</span>' ); ?>
	</div>
</div>

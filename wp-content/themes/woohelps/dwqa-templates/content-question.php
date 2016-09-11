<?php
/**
 * The template for displaying question content
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */

// fetch and display one answer data
// by mogita
$args = [
	'post_type' => 'dwqa-answer', // get answer
	'posts_per_page' => 1,
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

// display only questions with at least one answer
if (is_array($best_answers) && count($best_answers) > 0) :

    $answer_id = $best_answers[0]->ID;
    $user_id = $best_answers[0]->post_author;
    $best_answer = strip_tags($best_answers[0]->post_content);
    if (mb_strlen($best_answer, 'utf8') > 150) $best_answer = mb_substr($best_answer, 0, 150, 'utf8') . '...';

?>

<div class="<?php echo dwqa_post_class(); ?>">
    <div class="question-meta">
        <?php printf( __( '<span><a href="%s">%s</a>', 'dwqa' ), dwqa_get_author_link( $user_id ), get_avatar( $user_id, 48 ) ) ?>
        <a href="<?=dwqa_get_author_link($user_id)?>"><span class="best-answer-author"><?=dwqa_get_author($user_id)?></span></a>
        <span class="pull-right">
            <?php echo get_the_term_list( get_the_ID(), 'dwqa-question_category', '<span class="dwqa-question-category">' . __( '&nbsp;', 'dwqa' ), ', ', '</span>' ); ?>
        </span>
    </div>

	<header class="dwqa-question-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></header>
	<div class="dwqa-question-meta">
        <div class="best-answer-excerpt">
            <?=$best_answer?>
        </div>
	</div>
</div>
<?php endif; ?>
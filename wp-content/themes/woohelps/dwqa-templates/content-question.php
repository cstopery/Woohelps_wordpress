<?php
/**
 * The template for displaying question content
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */
$has_answer = false;

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
if (is_array($best_answers) && count($best_answers) > 0) {

    $answer_id = $best_answers[0]->ID;
    $user_id = $best_answers[0]->post_author;
    $best_answer = strip_tags($best_answers[0]->post_content);

    // trim answer preview content
    if (mb_strlen($best_answer, 'utf8') > 150) $best_answer = mb_substr($best_answer, 0, 150, 'utf8') . '... ';

    $best_answer = $best_answer . '<a class="" href="' . get_permalink() . '" target="_blank">   查看详细 </a>';
    $has_answer = true;
}
?>
<?php if(!$has_answer) : ?>
    <div class="dwqa-question-item-no-answer">
<?php else: ?>
   <div class="<?php echo dwqa_post_class(); ?>">
<?php endif ?>
    <?php if ($has_answer && isset($user_id) && isset($answer_id)) {?>
    <div class="question-meta">
        <div class="view-count" title="查看次数">
            <?=dwqa_question_views_count()?>
        </div>
        <?php printf( __( '<span><a href="%s">%s</a>', 'dwqa' ), bp_core_get_user_domain($user_id), get_avatar( $user_id, 48 ) ) ?>
        <?php
            $xProfileArr = getXprofile($user_id);
            $xWord = isset($xProfileArr['一句话描述']) ? $xProfileArr['一句话描述'] : '';
            $display_name = dwqa_get_author($answer_id) . ' ' . $xWord;
        ?>
        <a href="<?=bp_core_get_user_domain($user_id)?>">
            <span class="best-answer-author"><?=$display_name ?></span></a>
            <div class="dwqa-questions-desc"><?php echo isset($xProfileArr['微信显示名']) ? ' 微信显示名: '.$xProfileArr['微信显示名'] : '' ?></div>
            <div class="dwqa-questions-desc"><?php echo isset($xProfileArr['手机号']) ? '手机号:'.$xProfileArr['手机号'] : '' ?></div>
        <span class="pull-right">
            <?php echo get_the_term_list( get_the_ID(), 'dwqa-question_category', '<span class="dwqa-question-category">' . __( '&nbsp;', 'dwqa' ), ', ', '</span>' ); ?>
        </span>
    </div>
    <?php } ?>

	<header class="dwqa-question-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></header>

    <?php if ($has_answer) { ?>
	<div class="dwqa-question-meta">
        <div class="best-answer-excerpt">
            <?php if (isset($best_answer)) echo $best_answer; ?>
        </div>
	</div>
    <?php } ?>

    <footer class="dwqa-question-footer">
        <span>
            <?=dwqa_question_answers_count()?> 个回答
        </span>
         ·
        <span>
            <?=count(get_post_meta(get_the_ID(), '_dwqa_followers'));?> 人关注
        </span>

        <?php if (is_user_logged_in()) {?>
            <span class="subscribe-button" data-nonce="<?=wp_create_nonce( '_dwqa_follow_question' )?>" data-post="<?=get_the_ID()?>">
                <?php if (dwqa_is_followed()) { ?>
                    已关注
                <?php } else { ?>
                    关注问题
                <?php } ?>
            </span>
        <?php } else { ?>
            <span class="subscribe-button">
                关注问题
            </span>
        <?php } ?>
    </footer>
</div>

<?php

/**
 * Replies Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_replies_loop' ); ?>

<?php
$topic_id = bbp_get_topic_id();
$meta['date_and_time'] = get_post_meta( $topic_id, 'date_and_time', true);
$meta['organizer'] = get_post_meta( $topic_id, 'organizer', true);
$meta['attendee_count_limit'] = get_post_meta( $topic_id, 'attendee_count_limit', true);
$meta['enroll_deadline'] = get_post_meta( $topic_id, 'enroll_deadline', true);
$meta['fee'] = get_post_meta( $topic_id, 'fee', true);
$meta['location'] = get_post_meta( $topic_id, 'location', true);
$meta['attendee_count'] = get_post_meta( $topic_id, 'attendee_count', true);
?>

<ul id="topic-<?php bbp_topic_id(); ?>-replies" class="forums bbp-replies">

	<li class="bbp-meta">
		<div>
			<strong>时间日期：</strong> <?=$meta['date_and_time']?>
		</div>

		<div>
			<strong>发起人：</strong> <?=$meta['organizer']?>
		</div>

		<div>
			<strong>限制人数：</strong> <?=$meta['attendee_count_limit']?>
		</div>

		<div>
			<strong>报名截止日：</strong> <?=$meta['enroll_deadline']?>
		</div>

		<div>
			<strong>费用：</strong> <?=$meta['fee']?>
		</div>

		<div>
			<strong>地址：</strong> <?=$meta['location']?>
		</div>

		<div>
			<strong>参加人数：</strong> <?=$meta['attendee_count']?>
		</div>
	</li>

	<li class="bbp-header">

		<div class="bbp-reply-author"><?php  _e( 'Author',  'bbpress' ); ?></div><!-- .bbp-reply-author -->

		<div class="bbp-reply-content">

			<?php if ( !bbp_show_lead_topic() ) : ?>

				<?php _e( 'Posts', 'bbpress' ); ?>

				<?php bbp_topic_subscription_link(); ?>

				<?php bbp_user_favorites_link(); ?>

			<?php else : ?>

				<?php _e( 'Replies', 'bbpress' ); ?>

			<?php endif; ?>

		</div><!-- .bbp-reply-content -->

	</li><!-- .bbp-header -->

	<li class="bbp-body">

		<?php if ( bbp_thread_replies() ) : ?>

			<?php bbp_list_replies(); ?>

		<?php else : ?>

			<?php while ( bbp_replies() ) : bbp_the_reply(); ?>

				<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>

			<?php endwhile; ?>

		<?php endif; ?>

	</li><!-- .bbp-body -->

	<li class="bbp-footer">

		<div class="bbp-reply-author"><?php  _e( 'Author',  'bbpress' ); ?></div>

		<div class="bbp-reply-content">

			<?php if ( !bbp_show_lead_topic() ) : ?>

				<?php _e( 'Posts', 'bbpress' ); ?>

			<?php else : ?>

				<?php _e( 'Replies', 'bbpress' ); ?>

			<?php endif; ?>

		</div><!-- .bbp-reply-content -->

	</li><!-- .bbp-footer -->

</ul><!-- #topic-<?php bbp_topic_id(); ?>-replies -->

<?php do_action( 'bbp_template_after_replies_loop' ); ?>

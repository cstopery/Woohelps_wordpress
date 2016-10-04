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
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<td><strong>时间日期：</strong></td>
					<td><?=$meta['date_and_time']?></td>
				</tr>

				<tr>
					<td><strong>发起人：</strong></td>
					<td><?=$meta['organizer']?></td>
				</tr>

				<tr>
					<td><strong>限制人数：</strong></td>
					<td><?=$meta['attendee_count_limit']?></td>
				</tr>

				<tr>
					<td><strong>报名截止日：</strong></td>
					<td><?=$meta['enroll_deadline']?></td>
				</tr>

				<tr>
					<td><strong>费用：</strong></td>
					<td><?=$meta['fee']?></td>
				</tr>

				<tr>
					<td><strong>地址：</strong></td>
					<td><?=$meta['location']?></td>
				</tr>

				<tr>
					<td><strong>参加人数：</strong></td>
					<td><?=$meta['attendee_count']?></td>
				</tr>
			</table>
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

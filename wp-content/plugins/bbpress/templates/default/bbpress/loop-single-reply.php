<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="post-<?php bbp_reply_id(); ?>" class="bbp-reply-header">

	<div class="bbp-meta">

		<span class="bbp-reply-post-date"><?php bbp_reply_post_date(); ?></span>

		<?php if ( bbp_is_single_user_replies() ) : ?>

			<span class="bbp-header">
				<?php // _e( 'in reply to: ', 'bbpress' ); ?>
				<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a>
			</span>

		<?php endif; ?>

		<a href="<?php bbp_reply_url(); ?>" class="bbp-reply-permalink">#<?php bbp_reply_id(); ?></a>

		<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

		<?php bbp_reply_admin_links(); ?>

		<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>

	</div><!-- .bbp-meta -->

</div><!-- #post-<?php bbp_reply_id(); ?> -->

<div <?php bbp_reply_class(); ?>>

	<div class="bbp-reply-content">

		<h4 class="bbp-reply-title">
			<?= bbp_get_topic_title(); ?>
		</h4>

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>

		<div class="bbp-reply-inner-content">
			<?php bbp_reply_content(); ?>
		</div>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

		<hr>

		<?php
		$subscribers = bbp_get_topic_subscribers( $topic_id );
		?>
		<div class="attendee-list">
			<?php if (is_array($subscribers) && count($subscribers) > 0) : ?>
				<h5>参加人数：<?=count($subscribers)?> 人</h5>
				<ul>
					<?php foreach($subscribers as $id): $user = get_user_by( 'id', $id ); ?>
						<li>
							<a href="<?=bbp_get_user_profile_url($user->ID);?>" target="_blank">
								<?=get_avatar( $user->ID, 45 );?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<h4>尚无参与者</h4>
			<?php endif; ?>
		</div>

	</div><!-- .bbp-reply-content -->

	<?php
	$topic_id = bbp_get_topic_id();
	$meta['date_and_time'] = get_post_meta( $topic_id, 'date_and_time', true);
	$meta['organizer'] = get_post_meta( $topic_id, 'organizer', true);
	$meta['attendee_count_limit'] = get_post_meta( $topic_id, 'attendee_count_limit', true);
	$meta['enroll_deadline'] = get_post_meta( $topic_id, 'enroll_deadline', true);
	$meta['fee'] = get_post_meta( $topic_id, 'fee', true);
	$meta['location'] = get_post_meta( $topic_id, 'location', true);
	?>

	<div class="bbp-reply-sidebar">
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
			</table>
		</div>

		<div class="subscription-button">
			<?php bbp_topic_subscription_link(); ?>
		</div>
	</div>

</div><!-- .reply -->

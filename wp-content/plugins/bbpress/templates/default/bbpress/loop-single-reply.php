<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="post-<?php bbp_reply_id(); ?>" class="bbp-reply-header">
	<?php
	global $groups_template;
	if ( empty( $group ) ) {
		$group =& $groups_template->group;
	}

	$can_post = 0;

	$group_admins = groups_get_group_admins( $group->id );
	$group_mods = groups_get_group_mods( $group->id );
	if ( ( 1 == count( $group_admins ) ) && ( bp_loggedin_user_id() === (int) $group_admins[0]->user_id ) ) {
		$can_post = 1;
	}
	if ( ( 1 == count( $group_mods ) ) && ( bp_loggedin_user_id() === (int) $group_mods[0]->user_id ) ) {
		$can_post = 1;
	}
	?>
	<?php if (is_user_logged_in() && $can_post === 1): ?>
		<div class="pull-right" style="padding-right: 8px;">
				<?=bbp_get_topic_edit_link()?>
		</div>
	<?php endif; ?>
</div>

<?php
$topic_id = bbp_get_topic_id();
$meta['date_and_time'] = get_post_meta( $topic_id, 'date_and_time', true);
$meta['organizer'] = get_post_meta( $topic_id, 'organizer', true);
$meta['attendee_count_limit'] = get_post_meta( $topic_id, 'attendee_count_limit', true);
$meta['enroll_deadline'] = get_post_meta( $topic_id, 'enroll_deadline', true);
$meta['fee'] = get_post_meta( $topic_id, 'fee', true);
$meta['location'] = get_post_meta( $topic_id, 'location', true);
$meta['attendee_count'] = bbp_get_attendee_count( $topic_id );
?>

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
				<h5>参加人数：<?=$meta['attendee_count']?> 人</h5>
				<ul>
					<?php foreach($subscribers as $id): $user = get_user_by( 'id', $id ); ?>
						<li>
							<a href="<?=bbp_get_user_profile_url($user->ID);?>" target="_blank">
								<?=get_avatar( $user->ID, 45 );?>
							</a>
							<span class="attendee-info">
								<?=bbp_get_user_nicename($user->ID)?> 预定 <?=get_user_meta($user->ID, 'subscribe-' . $topic_id, true)?> 个名额
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<h4>尚无参与者</h4>
			<?php endif; ?>
		</div>

	</div><!-- .bbp-reply-content -->

	<div class="bbp-reply-sidebar">
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<td style="width: 70px;"><strong>日期</strong></td>
					<td><?=date('Y 年 m 月 d 日', $meta['date_and_time'] / 1000); ?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>时间</strong></td>
					<td><?=date('H:i', $meta['date_and_time'] / 1000);?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>发起人</strong></td>
					<td><?=$meta['organizer']?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>限制人数</strong></td>
					<td><?=$meta['attendee_count_limit']?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>报名截止</strong></td>
					<td><?=date('Y 年 m 月 d 日', $meta['enroll_deadline'] / 1000); ?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>费用</strong></td>
					<td><?=$meta['fee']?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>地址</strong></td>
					<td><?=$meta['location']?></td>
				</tr>
			</table>
		</div>

		<?php if (is_page('meetups-list')): ?>
			<div class="subscription-button">
				<a href="<?=bbp_get_topic_permalink()?>" class="btn btn-success btn-xs">查看详情</a>
			</div>
		<?php else: ?>
			<?php if (is_user_logged_in()): ?>
				<div class="subscription-button">
					<?php bbp_topic_subscription_link(); ?>
				</div>
			<?php else: ?>
				<div class="subscription-button">
					<a class="btn btn-success btn-xs" href="#" data-toggle="modal" data-target="#loginModal">报名</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>

</div><!-- .reply -->

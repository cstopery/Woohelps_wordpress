<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */
$class = bbp_get_reply_class();

?>

<div id="post-<?php bbp_reply_id(); ?>" class="bbp-reply-header">
	<?php if (is_user_logged_in() && bbp_current_user_can_access_create_topic_form() && (strpos($class, 'bbp-reply-position-1') || strpos($class, 'type-topic'))): ?>
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

	<div class="<?= (strpos($class, 'bbp-reply-position-1') || strpos($class, 'type-topic')) ? 'bbp-reply-content' : '';?>">

		<h4 class="bbp-reply-title">
			<?= (strpos($class, 'bbp-reply-position-1') || strpos($class, 'type-topic')) ? '<a href="' . bbp_get_topic_permalink() . '">' . bbp_get_topic_title() . '</a>': ''; ?>
		</h4>

		<div class="bbp-reply-inner-content">
			<h5>
				<?= (!strpos($class, 'bbp-reply-position-1') && !strpos($class, 'type-topic')) ? bbp_get_reply_author_link() : ''; ?>
			</h5>
		</div>

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>

		<div class="bbp-reply-inner-content">
			<?php bbp_reply_content(); ?>
		</div>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

		<?php
		if (strpos($class, 'bbp-reply-position-1') || strpos($class, 'type-topic')) :
		?>

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
					<td><?=date('Y 年 m 月 d 日', $meta['date_and_time'] / 1000 + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS); ?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>时间</strong></td>
					<td><?=date('H:i', $meta['date_and_time'] / 1000 + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>发起人</strong></td>
					<td><?=$meta['organizer']?></td>
				</tr>

				<tr>
					<td style="width: 70px;"><strong>限制人数</strong></td>
					<td><?=$meta['attendee_count_limit']?>（剩余 <?=$meta['attendee_count_limit'] - $meta['attendee_count']?>）</td>
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

		<style>
			#resv {
				width: 100%;
				text-align: center;
			}

			.minus, .plus {
				margin-top: -3px;
				font-size: 14px !important;
			}

			.attendee_count {
				display: inline !important;
				max-width: 20%;
				max-height: 30px;
			}

			.resv-label {
				margin-right: 10px;
			}
		</style>

		<?php if (is_page('meetups-list')) { ?>
			<div class="subscription-button">
				<a href="<?=bbp_get_topic_permalink()?>" class="btn btn-success btn-xs">查看详情</a>
			</div>
		<?php } else { ?>
			<?php if (is_user_logged_in()) { ?>
				<?php
				$user_id = bbp_get_current_user_id();
				$is_subscribed = bbp_is_user_subscribed_to_topic( $user_id, $topic_id );
				if (!$is_subscribed && ($meta['attendee_count_limit'] - $meta['attendee_count'] > 0)) {
				?>
					<div id="resv">
						<strong class="resv-label">预定人数</strong>
						<button class='btn btn-default minus' id="minus-<?=$topic_id?>">-</button>
						<input type='text' name='resv' id="resv-<?=$topic_id?>" value='1' class='attendee_count' disabled />
						<button class='btn btn-default plus' id="plus-<?=$topic_id?>">+</button>
						<input type="hidden" id="left-<?=$topic_id?>" value="<?=$meta['attendee_count_limit'] - $meta['attendee_count']?>">
					</div>
				<?php } ?>
				<div class="subscription-button">
					<?php bbp_topic_subscription_link(); ?>
				</div>
			<?php } else { ?>
				<div class="subscription-button">
					<a class="btn btn-success with-radius" href="#" data-toggle="modal" data-target="#loginModal">报名</a>
				</div>
			<?php } ?>
		<?php } ?>
	</div>

	<?php endif; ?>

</div><!-- .reply -->

<script>
	if (typeof $ === 'undefined') $ = jQuery;
	$(function() {
		var url_<?=$topic_id?> = $('#subscribe-<?=$topic_id?>').find('a').attr('href');
		$('#minus-<?=$topic_id?>').on('click', function() {
			if (parseInt($('#resv-<?=$topic_id?>').val()) > 1) {
				$('#resv-<?=$topic_id?>').val(parseInt($('#resv-<?=$topic_id?>').val()) - 1);
				updateSubscribeUrl();
			}
		});

		$('#plus-<?=$topic_id?>').on('click', function() {
			if (parseInt($('#resv-<?=$topic_id?>').val()) < $('#left-<?=$topic_id?>').val()) {
				$('#resv-<?=$topic_id?>').val(parseInt($('#resv-<?=$topic_id?>').val()) + 1);
				updateSubscribeUrl();
			}
		});

		function updateSubscribeUrl() {
			$('#subscribe-<?=$topic_id?>').find('a').attr('href', url_<?=$topic_id?> + '&resv=' + $('#resv-<?=$topic_id?>').val());
		}
	});
</script>
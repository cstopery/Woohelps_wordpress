<?php

/**
 * Pagination for pages of replies (when viewing a topic)
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_pagination_loop' ); ?>

<div class="bbp-pagination">
	<div class="bbp-pagination-count">

		<?php //bbp_topic_pagination_count(); ?>
	</div>

	<?php
	$subscribers = bbp_get_topic_subscribers( $topic_id );
	?>
	<div class="attendee-list">
		<?php if (is_array($subscribers) && count($subscribers) > 0) : ?>
			<h5>参与者 <span class="label label-info"><?=count($subscribers)?></span></h5>
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

	<div class="bbp-pagination-links">

		<?php bbp_topic_pagination_links(); ?>

	</div>
</div>

<?php do_action( 'bbp_template_after_pagination_loop' ); ?>

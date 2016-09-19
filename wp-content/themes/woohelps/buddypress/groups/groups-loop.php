<?php
/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter().
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php

/**
 * Fires before the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action('bp_before_groups_loop'); ?>

<?php if (bp_has_groups(bp_ajax_querystring('groups'))) : ?>

    <?php

    /**
     * Fires before the listing of the groups list.
     *
     * @since 1.1.0
     */
    do_action('bp_before_directory_groups_list'); ?>

	<div class="row">
		<?php while (bp_groups()) : bp_the_group(); ?>
			<div class="col-xs-6">
				<a href="<?php bp_group_permalink(); ?>">
					<div class="bp-group-item-inner">
						<?php // Get the Cover Image
						$group_cover_image_url = bp_attachments_get_attachment('url', array(
							'object_dir' => 'groups',
							'item_id' => bp_get_group_id(),
						));
						?>
						<img src="<?=$group_cover_image_url;?>" alt="group-<?=bp_get_group_id();?>">
						<div class="bp-group-meta">
							<div class="bp-group-title">
								<?php bp_group_name(); ?>
							</div>
							<div class="bp-group-member-count">
								<?php bp_group_member_count(); ?>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php endwhile; ?>
	</div>

    <?php

    /**
     * Fires after the listing of the groups list.
     *
     * @since 1.1.0
     */
    do_action('bp_after_directory_groups_list'); ?>

    <div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-dir-count-bottom">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-bottom">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

    <div id="message" class="info">
		<p><?php _e('There were no groups found.', 'buddypress'); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action('bp_after_groups_loop'); ?>

<?php

/**
 * Single Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">

	<?php bbp_breadcrumb(); ?>

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
		<div style="margin-bottom: 10px;">
	<a class="btn btn-success" href="#" data-toggle="modal" data-target="#newPost">创建活动</a>
</div>

		<div class="modal fade" id="newPost" tabindex="-1" role="dialog" aria-labelledby="newPost">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
				<strong>发布新活动</strong>
			</div>

			<div class="modal-body">
				<?php bbp_get_template_part( 'form',       'topic'     ); ?>
			</div>

		</div>
	</div>
</div>
	<?php endif; ?>

	<?php bbp_forum_subscription_link(); ?>

	<?php do_action( 'bbp_template_before_single_forum' ); ?>

	<?php if ( post_password_required() ) : ?>

		<?php bbp_get_template_part( 'form', 'protected' ); ?>

	<?php else : ?>

		<?php bbp_single_forum_description(); ?>

		<?php if ( bbp_has_forums() ) : ?>

			<?php bbp_get_template_part( 'loop', 'forums' ); ?>

		<?php endif; ?>

		<?php if ( !bbp_is_forum_category() && bbp_has_topics() ) : ?>

			<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

			<?php bbp_get_template_part( 'loop',       'topics'    ); ?>

			<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

			<?php //bbp_get_template_part( 'form',       'topic'     ); ?>

		<?php elseif ( !bbp_is_forum_category() ) : ?>

			<?php bbp_get_template_part( 'feedback',   'no-topics' ); ?>

			<?php //bbp_get_template_part( 'form',       'topic'     ); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_single_forum' ); ?>

</div>

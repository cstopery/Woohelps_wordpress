<?php

/**
 * Topics Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_topics_loop' ); ?>

<?php if (is_user_logged_in() && current_user_can('publish_topics')): ?>
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

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="bbp-topics">

	<li class="bbp-header">

		<strong>所有活动列表</strong>

	</li>

	<li class="bbp-body">

		<?php while ( bbp_topics() ) : bbp_the_topic(); ?>

			<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>

		<?php endwhile; ?>

	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->

<?php do_action( 'bbp_template_after_topics_loop' ); ?>

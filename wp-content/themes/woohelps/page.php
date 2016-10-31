<?php get_header(); ?>
<?php get_header('bar'); ?>

	<div class="page-content doc-main">
		<div class="container">
			<div class="row">
				<?php
				if (is_page('meetup')) {
					?>
					<div class="main-content page col-xs-12">
						<div class="group-toggle btn-group" role="group" aria-label="group-toggle">
							<a href="#" class="btn btn-primary">显示所有活动群</a>
							<a href="/meetups-list/" class="btn btn-info">显示所有活动日历</a>
						</div>
					<?php
				}
				else {
					echo '<div class="main-content page col-xs-12 col-md-8">';
				}
				?>
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', 'page' ); ?>
							<?php endwhile; ?>
							<?php
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
						<?php else : ?>
							<?php get_template_part( 'content', 'none' ); ?>
					<?php endif; ?>
				</div>
				<?php
				if (!is_page('meetup')) :
				?>
				<div class="col-md-4 col-xs-12">
					<?php if (is_page('meetups-list')): ?>
						<div class="group-toggle btn-group" role="group" aria-label="group-toggle">
							<a href="/meetup/" class="btn btn-info">Groups</a>
							<a href="#" class="btn btn-primary">Calendar</a>
						</div>
						<?php
						$topics = array();

						if ( bbp_has_topics() ) {
							while ( bbp_topics() ) {
								bbp_the_topic();

								$topic_id = bbp_get_topic_id();

								array_push($topics, [
									'id' => $topic_id,
									'title' => bbp_get_topic_title(),
									'url' => bbp_get_topic_permalink(),
									'date' => get_post_meta( $topic_id, 'date_and_time', true)
								]);
							}
						}

						$calendar = new Calendar($topics);
						echo $calendar->show();
						?>
					<?php endif;?>
				    <?php get_sidebar(); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
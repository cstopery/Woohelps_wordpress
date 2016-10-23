<?php get_header(); ?>
<?php get_header('bar'); ?>

	<div class="page-content doc-main">
		<div class="container">
			<div class="row">
				<div class="main-content page col-xs-12 col-md-8">
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
				<div class="col-md-4 col-xs-12">
					<?php if (is_page('meetups-list')): ?>
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
			</div>
		</div>
	</div>

<?php get_footer(); ?>
<?php get_header(); ?>
<?php get_header('bar'); ?>

	<div class="page-content doc-main">
		<div class="container">
			<div class="row">
				<div class="content-area col-xs-12 col-md-8">
					<div class="main-content single">
						<?php if ( have_posts() ) : ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'content', 'single' ); ?>
							<?php endwhile; ?>
							<div class="pagination">
									<div class="clearfix">
										<span class="left"><?php previous_post_link(); ?></span>
										<span class="right"><?php next_post_link(); ?></span>
									</div>
								</div>
							<?php
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
						<?php else : ?>
							<?php get_template_part( 'content', 'none' ); ?>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-md-4 col-xs-12">
				    <?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
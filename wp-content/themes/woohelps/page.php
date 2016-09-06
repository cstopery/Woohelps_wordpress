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
				    <?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
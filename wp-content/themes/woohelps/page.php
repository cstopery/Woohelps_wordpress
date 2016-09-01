<?php get_header(); ?>

	<div class="doc-main">
		<div class="container">
			<div class="row">
				<div class="main-content page col-sm-8 col-md-8">
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
				<?php get_sidebar(); ?>
				<?php if ( is_active_sidebar( 'sidebar-widgets' ) ) : ?>
				<div class="content-area col-sm-4 col-md-4">
				<?php else: ?>
				<div class="content-area col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2">
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
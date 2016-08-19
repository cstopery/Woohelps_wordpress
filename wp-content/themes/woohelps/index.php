<?php get_header(); ?>

<?php get_header('bar'); ?>
<?php get_header('banner'); ?>

	<div class="doc-main">
		<div class="container">
			<div class="row">

				<!-- add question list part start -->
				<div class="dwqa-questions-archive">

						<div class="dwqa-questions-list">
						<?php do_action( 'dwqa_before_questions_list' ) ?>
						<?php if ( dwqa_has_question() ) : ?>
							<?php while ( dwqa_has_question() ) : dwqa_the_question(); ?>
								<?php if ( get_post_status() == 'publish' || ( get_post_status() == 'private' && dwqa_current_user_can( 'edit_question', get_the_ID() ) ) ) : ?>
									<?php dwqa_load_template( 'content', 'question' ) ?>
								<?php endif; ?>
							<?php endwhile; ?>
						<?php else : ?>
							<?php dwqa_load_template( 'content', 'none' ) ?>
						<?php endif; ?>

						</div>


					<!-- add question list part end -->

					<!-- add city list part start -->


					<?php
					var_dump(do_shortcode('[adverts_categories show="all" columns="4" sub_count="10"]')) ;
				 	?>
					<!-- add city list part end -->

				</div>
			</div>
		</div>
	</div>


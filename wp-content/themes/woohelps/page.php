<?php get_header(); ?>
<?php get_header('homebar'); ?>

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
							<a href="/meetup/" class="btn btn-info">显示所有活动群</a>
							<a href="#" class="btn btn-primary">显示所有活动日历</a>
						</div>
						<?php
						$topics = array();

						if ( bbp_has_topics() ) {
							while ( bbp_topics() ) {
								bbp_the_topic();

								$topic_id = bbp_get_topic_id();

								array_push($topics, [
									'date' => date('Y-m-d', get_post_meta( $topic_id, 'date_and_time', true) / 1000),
									'badge' => true,
									'title' => '活动详情预览',
									'body' => '<h4>' . bbp_get_topic_title() . '</h4><br><p>' . bbp_get_reply_content() . '</p>',
									'footer' => '<a href="' . bbp_get_topic_permalink() . '">点击前往报名</a>',
									'modal' => true
								]);
							}
						}
						?>
						<div class="calendar-zone" style="margin-top: 80px;">
							<div id="z-calendar" class="z-calendar"></div>
							<button class="btn btn-primary btn-sm go-to-today pull-right" style="margin-bottom: 20px;">今天</button>
						</div>
					<?php else: ?>
						<?php get_sidebar(); ?>
					<?php endif;?>

				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<script src="/wp-content/themes/woohelps/js/zabuto_calendar.js"></script>

	<script>
		if (typeof $ === 'undefined') $ = jQuery;

		var calData = <?=json_encode($topics)?>;

		$(function () {
			$('#z-calendar').zabuto_calendar({
				data: calData,
				language: 'zh-cn',
				cell_border: true,
				today: true,
				show_previous: false
			});
		});
		
		$('.go-to-today').on('click', function() {
			$('.z-calendar').empty();
			$('.z-calendar').zabuto_calendar({
				data: calData,
				language: 'zh-cn',
				cell_border: true,
				today: true,
				show_previous: false
			});
		});
	</script>

<?php get_footer(); ?>
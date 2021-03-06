	<header class="jumbotron" style="background:linear-gradient(0deg, #21abc7, #616db3)">
		<div class="container text-center">
		<?php
			$docpress_header_title = get_theme_mod('docpress_header_title',__( 'Need Help? Try me!', 'docpress' ));
			$docpress_header_subtitle = get_theme_mod('docpress_header_subtitle',__( 'Your answer is just one search away!', 'docpress' ));
			$docpress_header_search_display = get_theme_mod('docpress_header_search_display');
		?>
		<?php if(!empty($docpress_header_title)) : ?>
			<h2><?php echo esc_html($docpress_header_title); ?></h2>
		<?php else: ?>
			<h2><?php bloginfo( 'description' ); ?></h2>
		<?php endif; ?>
		<?php if(!empty($docpress_header_subtitle)) : ?>
			<h3><?php echo esc_html($docpress_header_subtitle); ?></h3>
		<?php endif; ?>
		<?php if( isset($docpress_header_search_display) && $docpress_header_search_display != 1 ): ?>
			<form action="/dwqa-questions/" method="get">
				<div class="form-group">
					<input type="text" class="form-control with-radius" name="qs" value="<?php echo isset( $_GET['qs'] ) ? $_GET['qs'] : '' ?>" placeholder="试试我知道什么">
				</div>
			</form>
		<?php endif; ?>
		</div>
	</header>
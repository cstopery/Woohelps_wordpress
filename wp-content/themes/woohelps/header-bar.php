	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header page-scroll">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#docpress-navbar-collapse">
					<span class="sr-only"><?php _e( 'Toggle navigation', 'docpress' ) ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<h1 class="site-title"><a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'title' ); ?></a></h1>
			</div>
			<div class="collapse navbar-collapse" id="docpress-navbar-collapse">
			<?php
				wp_nav_menu( array(
					'menu'			  => 'main_menu',
					'theme_location'	=> 'main_menu',
					'menu_class'		=> 'nav navbar-nav navbar-left',
					'fallback_cb'	   => 'wp_bootstrap_navwalker::fallback',
					'walker'			=> new wp_bootstrap_navwalker())
				);
			?>
				<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control with-radius" placeholder="试试我知道什么">
					</div>
				</form>

				<ul class="nav navbar-nav navbar-right">
					<li><a class="btn btn-warning register-button with-radius" href="#">注册</a></li>
					<li><a href="#">登录</a></li>
				</ul>
			</div>
		</div>
	</nav>
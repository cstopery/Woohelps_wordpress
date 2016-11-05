<nav class="navbar navbar-default navbar-fixed-top home_navbar-fixed-top">
		<div class="container">
			<div class="navbar-header page-scroll">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#docpress-navbar-collapse">
					<span class="sr-only"><?php _e('Toggle navigation', 'docpress') ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<h1 class="site-title"><a class="home_navbar-brand" href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('title'); ?></a></h1>
			</div>
			<div class="collapse navbar-collapse" id="docpress-navbar-collapse">
				<?php
                wp_nav_menu(array(
                                'menu' => 'main_menu',
                                'theme_location' => 'main_menu',
                                'menu_class' => 'nav navbar-nav navbar-left',
                                'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                'walker' => new wp_bootstrap_navwalker()
                            )
                );
                ?>
                <form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control with-radius" placeholder="试试我知道什么">
					</div>
				</form>

				<?php if (!is_user_logged_in()): ?>
					<ul class="nav navbar-nav navbar-right">
						<li><a class="btn register-button with-radius" href="#" data-toggle="modal" data-target="#registerModal">注册</a></li>
						<li><a class="btn register-button with-radius" href="#" data-toggle="modal" data-target="#loginModal">登录</a></li>
					</ul>
					<?php else: ?>
						<?php $user = wp_get_current_user(); ?>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown user-badge">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=get_avatar($user->ID, 128, '', '', ['class' => 'img-response']);?><?=$user->display_name?> <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="<?=bp_loggedin_user_domain()?>">我的主页</a></li>
									<li><a href="<?=bp_loggedin_user_domain() . bp_get_messages_slug()?>">我的私信</a></li>
									<li><a href="<?=bp_loggedin_user_domain() . bp_get_settings_slug()?>">设置</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="<?php echo wp_logout_url( home_url() . $_SERVER['REQUEST_URI']); ?>">退出</a></li>
								</ul>
							</li>
						</ul>
					<?php endif; ?>
			</div>
		</div>
	</nav>

	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal">
		<div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
					<strong>登录</strong>
				</div>

				<div class="modal-body">

					<?php
                    $args = array(
                        'echo' => true,
                        'redirect' => $_SERVER[HTTP_REFERER],
                        'form_id' => 'loginform',
                        'label_username' => __('Username'),
                        'label_password' => __('Password'),
                        'label_remember' => __('Remember Me'),
	                    'label_lostpassword' => __('Lost your password?'),
                        'label_log_in' => __('Log In'),
                        'id_username' => 'user_login',
                        'id_password' => 'user_pass',
                        'id_remember' => 'rememberme',
                        'id_submit' => 'wp-submit',
                        'remember' => true,
	                    'lostpassword' => true,
                        'value_username' => NULL,
                        'value_remember' => true
                    );

                    // Calling the login form.
                    wp_login_form($args);
                    ?>
				</div>

			</div>
		</div>
	</div>

	<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModal">
		<div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
					<strong>注册</strong>
				</div>

				<div class="modal-body">
					<form name="registerform" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post">
						<div class="form-group">
							<label for="user_login"><?php echo __('Username'); ?></label>
							<input type="text" name="user_login" id="user_login" value="">
						</div>
						<div class="form-group">
							<label for="user_email"><?php echo __('Email'); ?></label>
							<input type="text" name="user_email" id="user_email" value="">
						</div>
						<div style="display:none">
							<label for="confirm_email">Please leave this field empty</label>
							<input type="text" name="confirm_email" id="confirm_email" value="">
						</div>
						<div class="form-group">
							<label for="user_password"><?php echo __('Password'); ?></label>
							<input type="password" name="user_password" id="user_password" value="">
						</div>

						<!--<div class="form-group">
							<small>你会收到一封包含初始密码的电子邮件</small>
						</div>-->

						<input type="hidden" name="redirect_to" value="/wp-login.php" />
						<div class="form-group">
							<input type="submit" name="register-submit" id="register-submit" value="<?php echo __('Register'); ?>" />
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
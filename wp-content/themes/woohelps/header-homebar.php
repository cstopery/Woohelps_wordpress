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
                <form class="navbar-form navbar-left" role="search" action="/dwqa-questions/" method="get">
					<div class="form-group">
						<input type="text" class="form-control with-radius" name="qs" value="<?php echo isset( $_GET['qs'] ) ? $_GET['qs'] : '' ?>" placeholder="试试我知道什么">
					</div>
				</form>

			<?php if (!is_user_logged_in()): ?>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a class="btn register-button with-radius" href="#" data-toggle="modal" data-target="#registerModal">注册</a>
					</li>
					<li>
						<a class="btn register-button with-radius" href="#" data-toggle="modal" data-target="#loginModal">登录</a>
					</li>
				</ul>
			<?php else: ?>
				<?php $user = wp_get_current_user(); ?>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown user-badge">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= get_avatar($user->ID, 128, '', '', ['class' => 'img-response']); ?><?= $user->display_name ?>
							<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="<?= bp_loggedin_user_domain() ?>">我的主页</a></li>
							<li><a href="<?= bp_loggedin_user_domain() . bp_get_messages_slug() ?>">我的私信</a></li>
							<li><a href="<?= bp_loggedin_user_domain() . bp_get_settings_slug() ?>">设置</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="<?php echo wp_logout_url(home_url() . $_SERVER['REQUEST_URI']); ?>">退出</a></li>
						</ul>
					</li>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</nav>

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal">
	<div class="modal-dialog modal-sm modal-dialog-woohelps modal-dialog-woohelps-login" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span></button>
				<h2>登录Woohelps</h2>
				<h1>我为人人,人人为我</h1>
			</div>

			<div class="modal-body">

				<?php
				$args = array('echo' => true, 'redirect' => $_SERVER[HTTP_REFERER], 'form_id' => 'loginform', 'label_username' => __('Username'), 'label_password' => __('Password'), 'label_remember' => __('Remember Me'), 'label_lostpassword' => __('Lost your password?'), 'label_log_in' => __('Log In'), 'id_username' => 'user_login', 'id_password' => 'user_pass', 'id_remember' => 'rememberme', 'id_submit' => 'wp-submit', 'remember' => true, 'lostpassword' => true, 'value_username' => null, 'value_remember' => true);

				// Calling the login form.
				wp_login_form($args);
				?>
			</div>

		</div>
	</div>
</div>

<div class="modal fade" id="lostpasswordModal" tabindex="-1" role="dialog" aria-labelledby="lostpasswordModal">
	<div class="modal-dialog modal-sm modal-dialog-woohelps modal-dialog-woohelps-lostpassword" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span></button>
				<h1>无法登陆</h1>
				<h2>使用找回密码重置密码登陆</h2>
			</div>

			<div class="modal-body">
				<form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url(network_site_url('wp-login.php?action=lostpassword_by_ajax', 'login_post')); ?>" method="post">

					<div class="form-group">
						<label for="user_login"><?php _e('Username or Email') ?></label>
						<input type="text" name="user_login" id="user_login" value="" autocomplete="off" data-is-domestic="true" placeholder="<?php echo __('Username or Email'); ?>">
						<p class="clue clue-login"></p>
					</div>

					<div class="form-group">
						<!--<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Get New Password'); ?>" />-->
						<a class="submit" href="javascript:void(0);"><?php esc_attr_e('Get New Password'); ?></a>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModal">
	<div class="modal-dialog modal-sm modal-dialog-woohelps modal-dialog-woohelps-register" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span></button>
				<h2>加入Woohelps</h2>
				<h1>我为人人,人人为我</h1>
			</div>

			<div class="modal-body">
				<form name="registerform" action="<?php echo site_url('wp-login.php?action=register_by_ajax', 'login_post') ?>" method="post">
					<div class="form-group">
						<label for="user_login"><?php echo __('Username'); ?></label>
						<input type="text" name="user_login" id="user_login" value="" autocomplete="off" data-is-domestic="true" placeholder="<?php echo __('Username'); ?>">
						<p class="clue clue-username"></p>
					</div>
					<div class="form-group">
						<label for="user_email"><?php echo __('Email'); ?></label>
						<input type="text" name="user_email" id="user_email" value="" autocomplete="off" data-is-domestic="true" placeholder="<?php echo __('Email'); ?>">
						<p class="clue clue-email"></p>
					</div>
					<div style="display:none">
						<label for="confirm_email">Please leave this field empty</label>
						<input type="text" name="confirm_email" id="confirm_email" value="" autocomplete="off" data-is-domestic="true">
					</div>
					<div class="form-group">
						<label for="user_password"><?php echo __('Password'); ?></label>
						<input type="password" style="display: none;">
						<input type="password" name="user_password" id="user_password" value="" autocomplete="off" data-is-domestic="true" placeholder="<?php echo __('Password'); ?>">
						<p class="clue clue-password"></p>
					</div>

					<!--<div class="form-group">
						<small>你会收到一封包含初始密码的电子邮件</small>
					</div>-->

					<input type="hidden" name="redirect_to" value="/wp-login.php"/>
					<div class="form-group">
						<!--<input type="submit" name="register-submit" id="register-submit" value="<?php echo __('Register'); ?>" />-->
						<a class="submit" href="javascript:void(0);"><?php echo __('Register'); ?></a>
					</div>

					<div class="form-group">
						<p class="login">已有帐号？<a class="clicklogin" href="javascript:void(0);">登录</a></p>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(".modal-dialog-woohelps").on("click", ".fa-close", function () {
		$("div.modal").hide();
		$("div.modal").removeClass("in");
		$("div.modal-backdrop").hide();
		$("div.modal-backdrop").removeClass("in");
	});

	$(".modal-dialog-woohelps-register").on("click", "input", function () {
		var me = $(this);
		var clue = me.next("p.clue");

		if (clue) {
			clue.html("");
		}
	});

	$(".modal-dialog-woohelps-register").on("click", "p.login >a", function () {
		$("#registerModal").hide();
		$("#registerModal").removeClass("in");
		$("#lostpasswordModal").hide();
		$("#lostpasswordModal").removeClass("in");
		$("#loginModal").show("slow");
		$("#loginModal").addClass("in");
	});

	$(".modal-dialog-woohelps-register").on("click", "a.submit", function () {
		var me = $(this);
		var root = me.parents(".modal-dialog-woohelps");
		var form = root.find("form");

		var url = form.attr("action");

		var user_login = form.find("input[name=user_login]").val();
		var user_email = form.find("input[name=user_email]").val();
		var user_password = form.find("input[name=user_password]").val();
		var param = {user_login: user_login, user_email: user_email, user_password: user_password};

		$.post(url, param, function (rs) {

			form.find("p.clue").html("");

			if (rs.code == 1000) {
				window.location.href = "/members/" + user_login + "/profile/edit/group/1/";

				return true;
			}

			if (rs.code == 1002) {
				var error = rs.error.errors;

				for (var k in error) {
					if (k.indexOf("username") != '-1') {
						form.find("p.clue-username").html(error[k]);
					}

					if (k.indexOf("email") != '-1') {
						form.find("p.clue-email").html(error[k]);
					}

					if (k.indexOf("password") != '-1') {
						form.find("p.clue-password").html(error[k]);
					}
				}

				return false;
			}

			if (rs.code = 1003) {
				window.location.href = window.location.href;

				return false;
			}

			return false;

		}, "json");

		return false;
	});

	$(".modal-dialog-woohelps-login").on("click", "input", function () {
		var me = $(this);
		var clue = me.next("span.clue");

		if (clue) {
			clue.html("");
		}
	});

	$(".modal-dialog-woohelps-login").on("click", "p.login-remember-lostpassword >a", function () {
		$("#registerModal").hide();
		$("#registerModal").removeClass("in");
		$("#loginModal").hide();
		$("#loginModal").removeClass("in");
		$("#lostpasswordModal").show("slow");
		$("#lostpasswordModal").addClass("in");
	});

	$(".modal-dialog-woohelps-login").on("click", "a.submit", function () {
		var me = $(this);
		var root = me.parents(".modal-dialog-woohelps");
		var form = root.find("form");

		var url = form.attr("action");

		var log = form.find("input[name=log]").val();
		var pwd = form.find("input[name=pwd]").val();
		var remember = form.find("input[name=rememberme]").is(":checked") ? "rememberme" : "";
		var param = {log: log, pwd: pwd, remember: remember};

		$.post(url, param, function (rs) {

			form.find("span.clue").html("");

			if (rs.code == 1000) {
				window.location.href = window.location.href;

				return true;
			}

			if (rs.code == 1001) {
				var error = rs.error.errors;

				for (var k in error) {
					if (k.indexOf("username") != '-1') {
						form.find("span.clue-username").html(error[k]);
					}

					if (k.indexOf("password") != '-1') {
						form.find("span.clue-password").html(error[k]);
					}
				}
			}

			return false;

		}, "json");

		return false;
	});

	$(".modal-dialog-woohelps-lostpassword").on("click", "input", function () {
		var me = $(this);
		var clue = me.next("span.clue");

		if (clue) {
			clue.html("");
		}
	});

	$(".modal-dialog-woohelps-lostpassword").on("click", "a.submit", function () {
		var me = $(this);
		var root = me.parents(".modal-dialog-woohelps");
		var form = root.find("form");

		var url = form.attr("action");

		var user_login = form.find("input[name=user_login]").val();
		var param = {user_login: user_login};

		$.post(url, param, function (rs) {

			form.find("p.clue").html("");

			if (rs.code == 1000) {
				window.location.href = window.location.href;

				return true;
			}

			if (rs.code == 1001) {
				var error = rs.error.errors;

				for (var k in error) {
					form.find("p.clue-login").html(error[k]);
				}

				return false;
			}

			return false;

		}, "json");

		return false;
	});
</script>
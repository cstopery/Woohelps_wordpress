<?php
/**
 * qa.php
 *
 * @author      mogita
 * @created_by  PhpStorm
 * @created_at  8/28/16 00:13
 */
?>
<!-- section divider -->
	<div class="dwqa-home-subtitle-section">
		<div class="container">
			<div class="col-md-4 dwqa-home-subtitle">
				<h3>这里有你感兴趣的加拿大信息</h3>
			</div>
			<div class="col-md-8">
				<div style="padding-top:10px;">
					<a class="btn btn-primary with-radius woo-button pull-right" href="#">我要提问</a>
				</div>
				<div class="clearfix"></div>
				<div class="category-list text-right">
					<ul>
						<li><a class="woohelps-item-tag" href="#">新移民</a></li>
						<li><a class="woohelps-item-tag" href="#">孩子</a></li>
						<li><a class="woohelps-item-tag" href="#">解除条件</a></li>
						<li><a class="woohelps-item-tag" href="#">汽车</a></li>
						<li><a class="woohelps-item-tag" href="#">活动</a></li>
					</ul>
				</div>
			</div>

		</div>
	</div>

	<div class="container">
		<!-- section q&a -->
		<div class="row">

			<div class="dwqa-home">
				<!-- add question list part start -->
				<div class="dwqa-questions-archive">
					<div class="dwqa-questions-list">
					<?php do_action('dwqa_before_questions_list') ?>
						<?php if (dwqa_has_question()) : $count = 0; ?>
							<?php while (dwqa_has_question()) : dwqa_the_question(); ?>
								<?php if ($count >= 8) break; ?>
								<?php if (get_post_status() == 'publish' || (get_post_status() == 'private' && dwqa_current_user_can('edit_question', get_the_ID()))) : ?>
									<?php $count++; ?>
									<?php dwqa_load_template('content', 'question') ?>
								<?php endif; ?>
							<?php endwhile; ?>
						<?php else : ?>
							<p>暂无问答</p>
						<?php endif; ?>
					</div>
					<!-- add question list part end -->
				</div>
			</div>
		</div>
	</div>
	<script>
		var user_logged_in = <?=(is_user_logged_in())? 'true' : 'false' ?>;
	</script>
	<script src="<?=get_stylesheet_directory_uri()?>/js/dwqa-util.js"></script>
<?php
/**
 * localservice-list.php
 *
 * @author      mogita
 * @created_by  PhpStorm
 * @created_at  8/28/16 00:16
 */
?>
<!-- section divider -->
	<div class="section-divider">
		<div class="container">
			<div class="row">
				<div class="title">
					<h2 class="text-center">
						萨斯卡通同城服务
					</h2>
					<a class="btn btn-primary with-radius woo-button pull-right" href="#">我要发布</a>
				</div>
				<div class="clearfix"></div>
				<div class="category-list text-right">
					<ul>
						<li><a href="#">租车</a></li>
						<li><a href="#">家庭旅馆</a></li>
						<li><a href="#">翻译</a></li>
						<li><a href="#">搬家</a></li>
						<li><a href="#">活动</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

<div class="container">
	<div class="row">
		<!-- add city list part start -->
        <?php
        echo do_shortcode('[adverts_categories show_count=0 show="all" columns="4" sub_count="10"]');
        ?>
        <!-- add city list part end -->
	</div>
</div>

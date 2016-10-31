<?php
/**
 * map-google.php
 *
 * @author      mogita
 * @created_by  PhpStorm
 * @created_at  8/28/16 12:52
 */
?>
<!-- section divider -->
<div class="container" style="margin-top: 20px">
    <div class="col-lg-2 col-md-2 sub-title">
         萨斯卡通地图
    </div>
    <div class="col-lg-8 col-md-8 sub-title-line">
    </div>
    <div class="col-lg-2 col-md-2">
        <a class="btn btn-primary with-radius woo-button pull-right" href="#">我要显示在地图上</a>
    </div>
</div>


<div class="container map_container">
    <div class="row">
        <?php echo do_shortcode('[put_wpgm id=1]'); ?>
    </div>
</div>


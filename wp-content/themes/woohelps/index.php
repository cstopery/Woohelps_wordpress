<?php get_header(); ?>
<?php get_header('homebar'); ?>
<?php get_header('banner'); ?>

<?php get_template_part('parts/index/qa', 'list');  ?>
<?php //get_template_part('parts/index/localservice', 'list'); ?>
<?php get_template_part('parts/index/meetup', 'calendar'); ?>
<?php get_template_part('parts/index/map', 'google'); ?>


<?php get_footer(); ?>

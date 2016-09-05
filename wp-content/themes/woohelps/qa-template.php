<?php
/**
 * qa-home.php
 *
 * @author      mogita
 * @created_by  PhpStorm
 * @created_at  9/5/16 22:17
 *
 * Template Name: DW QA Layout
 */
?>
<?php get_header(); ?>
<?php get_header('bar'); ?>

<div class="page-content">
    <div class="container">
        <div class="row">
            <!--    DW QA LIST    -->
            <div class="col-md-8 col-xs-12">
                <?php get_template_part('dwqa-templates/archive', 'question'); ?>
            </div>

            <!-- sidebar -->
            <div class="col-md-4 col-xs-12">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
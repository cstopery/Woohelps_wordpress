<?php

/* Disable WordPress Admin Bar for all users but admins. */

show_admin_bar(false);

// Added footer credits
function woohelps_footer_credits() {
    echo '<span><a rel="nofollow" href="http://www.hardeepasrani.com/portfolio/docpress/">MartinSun</a> - ' . __('Proudly powered by', 'MartinSun') . ' MartinSun</span>';
}

add_action('woohelps_credits', 'woohelps_footer_credits');

add_filter( 'dwqa_prepare_answers', 'dwqa_theme_order_answer_vote' );
function dwqa_theme_order_answer_vote( $args ) {
    $args['orderby'] = 'meta_value_num id';
    $args['meta_key'] = '_dwqa_votes';
    $args['order']	= 'DESC';

    return $args;
}

function bb_cover_image( $settings = array() ) {
    $settings['width']  = 800;
    $settings['height'] = 400;

    return $settings;
}
add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'bb_cover_image', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'bb_cover_image', 10, 1 );

// nav order in profile page
function bbg_change_profile_tab_order() {
    global $bp;
    $bp->bp_nav['profile']['position'] = 10;
    $bp->bp_nav['activity']['position'] = 20;
    $bp->bp_nav['friends']['position'] = 30;
    $bp->bp_nav['groups']['position'] = 40;
    $bp->bp_nav['blogs']['position'] = 50;
    $bp->bp_nav['messages']['position'] = 60;
    $bp->bp_nav['settings']['position'] = 70;
}
add_action('bp_setup_nav', 'bbg_change_profile_tab_order', 999 );

// check or generate default group cover image
function default_cover_image($group_id = 0) {
    if ($group_id == 0 || $group_id < 0 || strlen($group_id) <= 0) return false;

    $image_folder = 'wp-content/uploads/default_group_cover';
    $image_path = ABSPATH . $image_folder;
    if (!file_exists($image_path)) mkdir($image_path, 0757, true);
    $image_name = '/cover_' . $group_id . '.jpg';
    $image_file = $image_path . $image_name;

    if (!file_exists($image_file)) {
        $im = imagecreatetruecolor (720, 480);
        $bg = imagecolorallocate($im, rand(100, 255), rand(100, 255), 114);
        imagefill($im, 0, 0, $bg);
        imagejpeg($im, $image_file, 80);
    }

    return '/' . $image_folder . $image_name;
}
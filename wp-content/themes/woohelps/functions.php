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

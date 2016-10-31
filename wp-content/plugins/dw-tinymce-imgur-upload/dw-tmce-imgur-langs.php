<?php
/**
 *  Author: DesignWall
 *  Author URI: http://www.designwall.com
 *  Version: 1.0.5
 *  Text Domain: dw-tmce-imgur-upload
 *  @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) )
    exit;

if ( ! class_exists( '_WP_Editors' ) )
    require( ABSPATH . WPINC . '/class-wp-editor.php' );

function dw_tmce_imgur_translation() {
    $strings = array(
        'uploadString' => __('Imgur upload image', 'dw-tmce-imgur-upload'),
    );
    $locale = _WP_Editors::$mce_locale;
    $translated = 'tinyMCE.addI18n("' . $locale . '.dw_tmce_imgur_translate", ' . json_encode( $strings ) . ");\n";

     return $translated;
}

$strings = dw_tmce_imgur_translation();

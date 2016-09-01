<?php
/**
 * bp-custom.php
 *
 * @author      mogita
 * @created_by  PhpStorm
 * @created_at  9/1/16 23:41
 */

function bp_remove_signupredirect() {
    remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );
}
add_action('init', 'bp_remove_signupredirect');
<?php
/**
 * BuddyPress Integration Admin Pages
 * 
 * This file contains function to handle BuddyPress Integration config logic in wp-admin 
 * and config form.
 *
 * @package     Adverts
 * @subpackage  BuddyPress
 * @copyright   Copyright (c) 2016, Grzegorz Winiarski
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/**
 * Renders BuddyPress Integration config form.
 * 
 * The page is rendered in wp-admin / Classifieds / Options / BuddyPress panel
 * 
 * @since 1.0
 * @return void
 */
function adext_bp_page_options() {
    
    wp_enqueue_style( 'adverts-admin' );
    $flash = Adverts_Flash::instance();
    $error = array();
    
    $options = get_option ( "wpadverts_bp_config", array() );
    $options=null;
    if( $options === null || empty( $options ) ) {
        $options = adverts_config( "bp.ALL" );
    }
    
    $scheme = Adverts::instance()->get("form_buddypress_config");
    $form = new Adverts_Form( $scheme );
    $form->bind( $options );
    
    $button_text = __("Update Options", "adverts");
    
    if(isset($_POST) && !empty($_POST)) {
        $form->bind( $_POST );
        $valid = $form->validate();

        if($valid) {

            update_option("wpadverts_bp_config", $form->get_values());
            $flash->add_info( __("Settings updated.", "adverts") );
        } else {
            $flash->add_error( __("There are errors in your form.", "adverts") );
        }
    }
    
    include dirname( ADVERTS_PATH ) . '/wpadverts-bp/admin/options.php';
}

// BuddyPress config form
Adverts::instance()->set("form_buddypress_config", array(
    "name" => "",
    "action" => "",
    "field" => array(
        array(
            "name" => "_registration",
            "type" => "adverts_field_header",
            "order" => 10,
            "label" => __( 'Config', 'wpadverts-bp' )
        ),
        array(
            "name" => "use_bp_registration",
            "type" => "adverts_field_checkbox",
            "label" => __("Registration", "wpadverts-bp"),
            "options" => array(
                array(
                    "value" => 1,
                    "text" => __("Use BuddyPress registration system.", "wpadverts-bp")
                )
            ),
            "order" => 10
        ),
        array(
            "name" => "show_bp_priv_button",
            "type" => "adverts_field_checkbox",
            "label" => __("Private Messages", "wpadverts-bp"),
            "options" => array(
                array(
                    "value" => 1,
                    "text" => __("Show 'Send Private Message' button on Ad details page.", "wpadverts-bp")
                )
            ),
            "order" => 10
        ),
        array(
            "name" => "_titles",
            "type" => "adverts_field_header",
            "order" => 10,
            "label" => __( 'BuddyPress Navigation Titles', 'wpadverts-bp' )
        ),
        array(
            "name" => "nav_title_listings",
            "type" => "adverts_field_text",
            "label" => __("Top Title", "wpadverts-bp"),
            "placeholder" => __( "Listings", "wpadverts-bp" ),
            "order" => 10
        ),
        array(
            "name" => "nav_title_browse",
            "type" => "adverts_field_text",
            "label" => __("Subtitle Browse", "wpadverts-bp"),
            "placeholder" => __( "Browse", "wpadverts-bp" ),
            "order" => 10
        ),
        array(
            "name" => "nav_title_manage",
            "type" => "adverts_field_text",
            "label" => __("Subtitle Manage", "wpadverts-bp"),
            "placeholder" => __( "Manage", "wpadverts-bp" ),
            "order" => 10
        ),
        array(
            "name" => "_slugs",
            "type" => "adverts_field_header",
            "order" => 10,
            "label" => __( 'BuddyPress Navigation Slugs', 'wpadverts-bp' )
        ),
        array(
            "name" => "nav_slug_listings",
            "type" => "adverts_field_text",
            "label" => __("Listings / Browse", "wpadverts-bp"),
            "placeholder" => "adverts",
            "order" => 10
        ),
        array(
            "name" => "nav_slug_manage",
            "type" => "adverts_field_text",
            "label" => __("Manage", "wpadverts-bp"),
            "placeholder" => "manage",
            "order" => 10
        ),
        array(
            "name" => "_listings_tab",
            "type" => "adverts_field_header",
            "order" => 10,
            "label" => __( 'Listings Tab', 'wpadverts-bp' )
        ),
        array(
            "name" => "listings_show_search",
            "type" => "adverts_field_checkbox",
            "label" => __("Search", "wpadverts-bp"),
            "options" => array(
                array(
                    "value" => 1,
                    "text" => __("Show search bar.", "wpadverts-bp")
                )
            ),
            "order" => 10
        ),
        array(
            "name" => "listings_columns",
            "type" => "adverts_field_text",
            "label" => __("Columns", "wpadverts-bp"),
            "placeholder" => "2",
            "order" => 10
        ),
        array(
            "name" => "listings_posts_per_page",
            "type" => "adverts_field_text",
            "label" => __("Posts Per Page", "wpadverts-bp"),
            "order" => 10
        ),

    )
));




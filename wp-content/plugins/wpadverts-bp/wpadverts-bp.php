<?php
/*
 * Plugin Name: WP Adverts - BuddyPress Integration
 * Plugin URI: http://wpadverts.com/
 * Description: This module allows to integrate WPAdverts with BuddyPress plugin.
 * Author: Greg Winiarski
 * Text Domain: wpadverts-bp
 * Version: 1.0
 * 
 * WPAdverts is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Adverts is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Adverts. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Adverts
 * @subpackage BuddyPress
 * @author Grzegorz Winiarski
 * @global $adverts_namespace array
 * @version 1.0
 */

// Add BuddyPress Integrationto $adverts_namespace
add_action( 'plugins_loaded', 'adext_bp_namespace' );

/**
 * Adds default addon configuration to $adverts_namespace
 * 
 * @global array $adverts_namespace
 * 
 * @access public
 * @since 1.0
 * @return void
 */
function adext_bp_namespace() {
    global $adverts_namespace;

    // Add BuddyPress to adverts_namespace, in order to store module options and default options
    $adverts_namespace['bp'] = array(
        'option_name' => 'wpadverts_bp_config',
        'default' => array(
            'use_bp_registration' => '0',
            
            'nav_title_listings' => __( "Listings", "wpadverts-bp" ),
            'nav_title_browse' => __( "Browse", "wpadverts-bp" ),
            'nav_title_manage' => __( "Manage", 'wpadverts-bp' ),
            
            'nav_slug_listings' => "adverts",
            'nav_slug_manage' => "manage",
            
            'listings_show_search' => "0",
            'listings_columns' => "2",
            'listings_posts_per_page' => "20",
            
            'show_bp_priv_button' => "0"
            
        )
    );
}

// Init BuddyPress addon
add_action( 'init', 'adext_bp_init' );


if(is_admin() ) {
    // Run Adverts BuddyPress Integration admin only actions
    add_action( 'init', 'adext_bp_init_admin' );
} else {
    // Run Adverts BuddyPress frontend only actions
    add_action( 'init', 'adext_bp_init_frontend' );
    add_action( 'bp_setup_nav', 'adext_bp_tabs', 100 );
    
}

/**
 * Init Adverts - BuddyPress Integration global filters
 * 
 * This function executes actions/filters that need to be run with every request
 * when the integration is enabled.
 * 
 * @access public
 * @since 1.0
 * @return void
 */
function adext_bp_init() {
    
    if( ! defined( "ADVERTS_PATH" ) ) {
        return;
    } 
    
    load_plugin_textdomain("wpadverts-bp", false, dirname( plugin_basename( __FILE__ ) ) . "/languages/");
    
    add_action( 'publish_advert', 'adext_bp_activity_add' );
    add_action( 'bp_core_activated_user', 'adext_bp_core_activated_user', 10, 3 );
}

/**
 * Init Adverts - BuddyPress Integration admin actions and filters
 * 
 * This function adds filters and actions that should be executed when
 * user is browsing wp-admin panel.
 * 
 * @access public
 * @since 1.0
 * @return voic
 */
function adext_bp_init_admin() {
    
    if( ! defined( "ADVERTS_PATH" ) ) {
        return;
    }
    
    include_once ADVERTS_PATH . 'includes/class-updates-manager.php';
    $manager = new Adverts_Updates_Manager(
        "wpadverts-bp/wpadverts-bp.php", 
        "wpadverts-bp", 
        "1.0.0"
    );
    $manager->connect();
    
    add_filter( 'display_post_states', 'adext_bp_display_pending_state', 20 );
    
}

function adext_bp_init_frontend() {

    add_action( 'adverts_tpl_single_posted_by', 'adext_bp_single_posted_by', 10, 2 );
    
    if( adverts_config( 'bp.use_bp_registration' ) ) {
        // register users using BuddyPress regisration system
        add_filter('adverts_create_user_from_post_id', 'adext_bp_create_user_from_post_id', 10, 2 );
    }
    
    
    if( adverts_config( 'bp.show_bp_priv_button' ) ) {
        // add 'send private message' to ad details page
        add_action( "adverts_tpl_single_bottom", "adext_bp_send_private_message_button", 50 );
    }
}

/**
 * Registers new BP tab
 * 
 * This function registers top "Listings" tab and 2 sub tabs ("Browse" and "Manage")
 * 
 * This function is executed by 'bp_setup_nav' action
 * 
 * @see bp_setup_nav action
 * 
 * @uses adext_bp_core_nav_item
 * @uses adext_bp_core_subnav_item_manage
 * @uses adext_bp_core_subnav_item_manage
 * 
 * @since 1.0
 * @access public
 * @return void
 */
function adext_bp_tabs() {

    $main_slug = adverts_config("bp.nav_slug_listings");
    
    bp_core_new_nav_item( apply_filters( "adext_bp_core_nav_item", array(
        'name'                  => adverts_config("bp.nav_title_listings"),
        'slug'                  => $main_slug,
        'screen_function'       => 'adext_bp_screen_listings',			
        'position'              => 200,
        'default_subnav_slug'   => $main_slug
    ) ) );

    bp_core_new_subnav_item( apply_filters( "adext_bp_core_subnav_item_browse", array(
        'name'                  => adverts_config("bp.nav_title_browse"),
        'slug'                  => $main_slug,
        'parent_url'            => trailingslashit( bp_displayed_user_domain() . $main_slug ),
        'parent_slug'           => $main_slug,
        'screen_function'       => 'adext_bp_screen_listings',
        'position'              => 100,
        'user_has_access'       => true
    ) ) );
    
    bp_core_new_subnav_item( apply_filters( "adext_bp_core_subnav_item_manage", array(
        'name'                  => adverts_config("bp.nav_title_manage"),
        'slug'                  => adverts_config("bp.nav_slug_manage"),
        'parent_url'            => trailingslashit( bp_displayed_user_domain() . $main_slug ),
        'parent_slug'           => $main_slug,
        'screen_function'       => 'adext_bp_screen_manage',
        'position'              => 100,
        'user_has_access'       => bp_is_my_profile()
    ) ) );		

}

/**
 * Registers content function for "Listings" (and "Browse") tab.
 * 
 * Registers 'adext_bp_screen_listings_content' action
 * 
 * @since 1.0
 * @access public
 * @return void
 */
function adext_bp_screen_listings() {
    add_action( 'bp_template_content', 'adext_bp_screen_listings_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

/**
 * Displays content for "Listings" and "Browse" tabs.
 * 
 * Basically executes shortcode_adverts_list() function with "author" param.
 * 
 * @since 1.0
 * @access public
 * @return void
 */
function adext_bp_screen_listings_content() {
    
    if( ! adverts_config( "bp.listings_show_search") ) {
        $search_bar = "disabled";
    } else {
        $search_bar = "enabled";
    }
    
    echo shortcode_adverts_list(array(
        "author" => bp_displayed_user_id(),
        "search_bar" => $search_bar,
        "columns" => adverts_config( "bp.listings_columns" ),
        "posts_per_page" => adverts_config( "bp.listings_posts_per_page" )
    ));
}

/**
 * Registers content function for "Manage" tab.
 * 
 * Registers 'adext_bp_screen_manage_content' action
 * 
 * @since 1.0
 * @access public
 * @return void
 */
function adext_bp_screen_manage() {
    add_action( 'bp_template_content', 'adext_bp_screen_manage_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

/**
 * Displays content for "Listings" and "Browse" tabs.
 * 
 * Basically executes shortcode_adverts_manage() function and modifies baseurl
 * using adverts_manage_baseurl filter.
 * 
 * @see shortcode_adverts_manage()
 * @see adverts_manage_baseurl
 * 
 * @since 1.0
 * @access public
 * @return void
 */
function adext_bp_screen_manage_content() {
    add_filter( "adverts_manage_baseurl", "adext_bp_manage_baseurl" );
    echo shortcode_adverts_manage(array());
    remove_action( "adverts_manage_baseurl", "adext_bp_manage_baseurl" );
}

/**
 * Modifies [adverts_manage] baseurl
 * 
 * Baseurl is basically a link to page on which [adverts_manage] shortcode is placed,
 * when using [adverts_manage] inside BP this URL needs to be modified.
 * 
 * @since 1.0
 * @param string $url       Baseurl for [adverts_manage]
 * @return string           Modified baseurl for BP
 */
function adext_bp_manage_baseurl( $url ) {
    
    $slug = adverts_config("bp.nav_slug_listings") . "/";
    $slug.= adverts_config("bp.nav_slug_manage") . "/";
    
    return bp_loggedin_user_domain( ) . apply_filters( 'adext_bp_manage_baseurl', $slug );
}

/**
 * Customizes "by [...]" text on Ad details page.
 * 
 * Function replaces "by [...]" text with link to user BuddyPress profile
 * if the Ad was posted by registered user.
 * 
 * @since 1.0
 * @param string $posted_by     Text to be used as on Ad details page
 * @param int $post_id          ID of currently viewed post
 * @return string               Customized text to display on Ad details
 */
function adext_bp_single_posted_by( $posted_by, $post_id ) {
    
    $post = get_post( $post_id );
    $author = bp_core_get_user_displayname( $post->post_author );
    $url = bp_core_get_user_domain( $post->post_author );
    
    if( $url === null ) {
        return $posted_by;
    }

    if( $author === false ) {
        $author = get_post_meta($post_id, 'adverts_person', true);
    }
    
    $pattern = __( 'by <a href="%1$s">%2$s</a>', "wpadverts-bp" );
    
    return sprintf( $pattern, $url, $author );
}

/**
 * Registers user using BuddyPress registration
 * 
 * User data for registration (email, name, etc.) is derived from posted Advert.
 * The user is registered using bp_core_signup_user() function
 * 
 * @see bp_core_signup_user()
 * 
 * @since 1.0
 * @param int $user_id      Integer if user was already registered or NULL
 * @param int $post_id      ID of a post from which user will be created
 * @return boolean          Newly created user ID
 */
function adext_bp_create_user_from_post_id( $user_id, $post_id ) {
    
    $user_email = get_post_meta( $post_id, "adverts_email", true );
    $user_login = $user_email;
    $user_password = wp_generate_password( 12, false );
    
    $usermeta = array();
    
    $user_id = bp_core_signup_user( $user_login, $user_password, $user_email, $usermeta );
    
    if( is_wp_error( $user_id ) ) {
        // revert to default WP registration
        return null;
    } elseif( is_int( $user_id ) ) {
        // this will happen only if -> define( 'BP_SIGNUPS_SKIP_USER_CREATION', true ); 
        return $user_id;
    } elseif( is_bool( $user_id ) ) {
        // user is in wp_signup table, when he will be verified an Ad will be assigned to him
        add_post_meta( $post_id, "wpadverts_bp_user_pending", $user_login );
        return true;
    }
}

/**
 * Display 'Pending User Registration' state on Classifieds list
 * 
 * This functions shows Expired state in the wp-admin / Classifieds panel
 * 
 * @global WP_Post $post
 * @param array $states
 * @return array
 */
function adext_bp_display_pending_state( $states ) {
    global $post;
     
    $user_login = get_post_meta( $post->ID, "wpadverts_bp_user_pending", true );
    $order_link = null;
    //var_dump($user_login);
    
    if( ! empty( $user_login ) ) {
        $span = new Adverts_Html("span", array(
            "class" => "dashicons dashicons-admin-users",
            "style" => "font-size: 18px"
        ));
        $span->forceLongClosing(true);
        
        $order_link = new Adverts_Html("a", array(
            "href" => admin_url("users.php?page=bp-signups&s=" . $user_login),
            "title" => sprintf( __("Pending User Registration (%s)", "wpadverts-bp"), $user_login )
        ), $span->render());
        
        $states[] = __( 'Pending User', 'wpadverts-wc' ) . $order_link;
    } 
    
   

    return $states;
}

/**
 * Assigns posting to user on user activation.
 * 
 * This function is being called by bp_core_activated_user action.
 * 
 * @see bp_core_activated_user action
 * 
 * @since 1.0
 * @global wpdb $wpdb
 * @param int $user_id  Registered user ID
 * @param string $key   Activation key
 * @param array $user   User data
 */
function adext_bp_core_activated_user( $user_id, $key, $user ) {
    global $wpdb;
    
    $user = get_user_by( "id", $user_id );
    $user_login = $user->user_login;
    
    $select = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='wpadverts_bp_user_pending' AND meta_value = %s";
    $query = $wpdb->prepare( $select, $user_login );
    $rows = $wpdb->get_results( $query);
    
    foreach( $rows as $row ) {
        $post_id = $row->post_id;
        
        delete_post_meta( $post_id, "wpadverts_bp_user_pending" );
        wp_update_post( array(
            "ID" => $post_id,
            "post_author" => $user_id
        ) );

    }
}

/**
 * Adds "Send Private Message" button to Ad details page.
 * 
 * This function is being called by adverts_tpl_single_bottom filter
 * 
 * @see adverts_tpl_single_bottom filter
 * 
 * @since 1.0
 * @param int $post_id      Post ID
 * @return void
 */
function adext_bp_send_private_message_button( $post_id ) {
    
    if( bp_loggedin_user_id() < 1 ) {
        // Only BP users can send messages
        return;
    }

    $post = get_post( $post_id );
    $user = get_user_by( "id", $post->post_author );
    
    if( $user === false ) {
        // Only registered users can receive messages
        return;
    }
    
    $url =  bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . $user->user_login;
    $nonce_url = apply_filters( 'bp_get_send_private_message_link', wp_nonce_url( $url ) );

    include_once ADVERTS_PATH . '/includes/class-html.php';
    
    $icon = new Adverts_Html("span", array(
        "class" => "adverts-icon-mail-alt"
    ));
    $icon->forceLongClosing(true);
    
    $a = new Adverts_Html("a", array(
        "class" => "adverts-button ",
        "href" => $nonce_url
    ), __( "Send Private Message", "wpadverts-bp" ) . " " . $icon );
    
    echo $a;
}

/**
 * Adds entry to user activity stream
 * 
 * This entry is being added when Ad status is being changed to 'publish'.
 * Function is executed by publish_advert action.
 * 
 * @since 1.0
 * @access public
 * @param int $post_id  Post ID
 * @return void
 */
function adext_bp_activity_add( $post_id ) {
    
    $post = get_post( $post_id );
    $activity = get_post_meta( $post_id, "wpadverts_bp_activity_add", true );
    
    if( $activity > 0 ) {
        // Activity already recorded
        return;
    }
    
    $m = __( 'Posted new Ad <a href="%2$s">%1$s</a>.', 'wpadverts-bp' );
    
    $activity_id = bp_activity_add( array(
        "action" => sprintf( $m, $post->post_title, get_the_permalink( $post_id ) ),
        "content" => get_the_excerpt( $post_id ),
        "component" => "wpadverts-bp",
        "type" => __( "New Classified Ad", "wpadverts-bp") ,
        "primary_link" => get_permalink( $post_id ),
        "user_id" => $post->post_author,
        "item_id" => $post_id,
    ) );
    
    add_post_meta( $post_id, "wpadverts_bp_activity_add", $activity_id );
}
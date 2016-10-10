<?php

/* Disable WordPress Admin Bar for all users but admins. */

show_admin_bar(false);

// Added footer credits
function woohelps_footer_credits() {
    echo '<span><a rel="nofollow" href="http://www.hardeepasrani.com/portfolio/docpress/">MartinSun</a> - ' . __('Proudly powered by', 'MartinSun') . ' MartinSun</span>';
}

add_action('woohelps_credits', 'woohelps_footer_credits');

add_filter('dwqa_prepare_answers', 'dwqa_theme_order_answer_vote');
function dwqa_theme_order_answer_vote($args) {
    $args['orderby'] = 'meta_value_num id';
    $args['meta_key'] = '_dwqa_votes';
    $args['order'] = 'DESC';

    return $args;
}

function bb_cover_image($settings = array()) {
    $settings['width'] = 800;
    $settings['height'] = 400;

    return $settings;
}

add_filter('bp_before_xprofile_cover_image_settings_parse_args', 'bb_cover_image', 10, 1);
add_filter('bp_before_groups_cover_image_settings_parse_args', 'bb_cover_image', 10, 1);

// nav menu item order in profile page
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

add_action('bp_setup_nav', 'bbg_change_profile_tab_order', 999);

// check or generate default group cover image
function default_cover_image($group_id = 0) {
    if ($group_id == 0 || $group_id < 0 || strlen($group_id) <= 0) return false;

    $image_folder = 'wp-content/uploads/default_group_cover';
    $image_path = ABSPATH . $image_folder;
    if (!file_exists($image_path)) mkdir($image_path, 0757, true);
    $image_name = '/cover_' . $group_id . '.jpg';
    $image_file = $image_path . $image_name;

    if (!file_exists($image_file)) {
        $im = imagecreatetruecolor(720, 480);
        $bg = imagecolorallocate($im, rand(100, 255), rand(100, 255), 114);
        imagefill($im, 0, 0, $bg);
        imagejpeg($im, $image_file, 80);
    }

    return '/' . $image_folder . $image_name;
}

// featured group function, which in chinese means 置顶群组
//it's important to check if the Groups component is active
if (bp_is_active('groups')) {
    /**
     * This is a quick and dirty class to illustrate "bpgmq"
     * bpgmq stands for BuddyPress Group Meta Query...
     * The goal is to store a groupmeta in order to let the community administrator
     * feature a group.
     * Featured groups will be filterable from the groups directory thanks to a new option
     * and to a filter applied on bp_ajax_query_string()
     *
     * This class is an example, it would be much better to use the group extension API
     */
    class bpgmq_feature_group {

        public function __construct() {
            $this->setup_hooks();
        }

        private function setup_hooks() {
            // in Group Administration screen, you add a new metabox to display a checkbox to featured the displayed group
            add_action('bp_groups_admin_meta_boxes', array($this, 'admin_ui_edit_featured'));
            /* The groups loop uses bp_ajax_querystring( 'groups' ) to filter the groups
   depending on the selected option */
            add_filter('bp_ajax_querystring', array($this, 'filter_ajax_querystring'), 20, 2);
            // Once the group is saved you store a groupmeta in db, the one you will search for in your group meta query
            add_action('bp_group_admin_edit_after', array($this, 'admin_ui_save_featured'), 10, 1);

            /* finally you create your options in the different select boxes */
            // you need to do it for the Groups directory
            add_action('bp_groups_directory_order_options', array($this, 'featured_option'));
            // and for the groups tab of the user's profile
            add_action('bp_member_group_order_options', array($this, 'featured_option'));
        }

        public function featured_option() {
            ?>
            <option value="featured"><?php _e('特色群组'); ?></option>
            <?php
        }

        /**
         * registers a new metabox in Edit Group Administration screen, edit group panel
         */
        public function admin_ui_edit_featured() {
            add_meta_box(
                'bpgmq_feature_group_mb',
                __('特色群组'),
                array(&$this, 'admin_ui_metabox_featured'),
                get_current_screen()->id,
                'side',
                'core'
            );
        }

        /**
         * Displays the meta box
         */
        public function admin_ui_metabox_featured($item = false) {
            if (empty($item)) {
                return;
            }

            // Using groups_get_groupmeta to check if the group is featured
            $is_featured = groups_get_groupmeta($item->id, '_bpgmq_featured_group');
            ?>
            <p>
                <input type="checkbox" id="bpgmq-featured-cb" name="bpgmq-featured-cb" value="1" <?php checked(1, $is_featured); ?>> <?php _e('设为特色群组'); ?>
            </p>
            <?php
            wp_nonce_field('bpgmq_featured_save_' . $item->id, 'bpgmq_featured_admin');
        }

        function admin_ui_save_featured($group_id = 0) {
            if ('POST' !== strtoupper($_SERVER['REQUEST_METHOD']) || empty($group_id)) {
                return false;
            }

            check_admin_referer('bpgmq_featured_save_' . $group_id, 'bpgmq_featured_admin');

            // You need to check if the group was featured so that you can eventually delete the group meta
            $was_featured = groups_get_groupmeta($group_id, '_bpgmq_featured_group');
            $to_feature = !empty($_POST['bpgmq-featured-cb']) ? true : false;

            if (!empty($to_feature) && empty($was_featured)) {
                groups_update_groupmeta($group_id, '_bpgmq_featured_group', 1);
            }
            if (empty($to_feature) && !empty($was_featured)) {
                groups_delete_groupmeta($group_id, '_bpgmq_featured_group');
            }

            return true;
        }

        public function filter_ajax_querystring($querystring = '', $object = '') {

            /* bp_ajax_querystring is also used by other components, so you need
            to check the object is groups, else simply return the querystring and stop the process */
            if ($object != 'groups') {
                return $querystring;
            }

            // Let's rebuild the querystring as an array to ease the job
            $defaults = array(
                'type' => 'active',
                'action' => 'active',
                'scope' => 'all',
                'page' => 1,
                'user_id' => 0,
                'search_terms' => '',
                'exclude' => false
            );

            $bpgmq_querystring = wp_parse_args($querystring, $defaults);

            /* if your featured option has not been requested
            simply return the querystring to stop the process
            */
            if ($bpgmq_querystring['type'] != 'featured') {
                return $querystring;
            }

            /* this is your meta_query */
            $bpgmq_querystring['meta_query'] = array(
                array(
                    'key' => '_bpgmq_featured_group',
                    'value' => 1,
                    'type' => 'numeric',
                    'compare' => '='
                )
            );

            // using a filter will help other plugins to eventually extend this feature
            return apply_filters('bpgmq_filter_ajax_querystring', $bpgmq_querystring, $querystring);
        }

    }

    /**
     * Let's launch !
     *
     * Using bp_is_active() in this case is not needed
     * But i think it's a good practice to use this kind of check
     * just in case <img draggable="false" class="emoji" alt="" src="https://s.w.org/images/core/emoji/2/svg/1f642.svg">
     */
    function bpgmq_feature_group() {
        if (bp_is_active('groups')) {
            return new BPGMQ_Feature_Group();
        }

        return false;
    }

    add_action('bp_init', 'bpgmq_feature_group');

}
// end of featured groups

/*
 * bbPress custom fields
 * should include:
 * 日期，时间，发起人，限制人数，报名截止日，费用，地址，标题，内容，参加人数
 */
add_action('bbp_theme_before_topic_form_content', 'bbp_extra_fields');

function bbp_extra_fields() {
    $value = get_post_meta(bbp_get_topic_id(), 'date_and_time', true);
    echo '<label for="bbp_extra_field1">日期和时间</label><br>';
    echo "<input type='text' name='date_and_time' value='" . $value . "'>";

    $value = get_post_meta(bbp_get_topic_id(), 'organizer', true);
    echo '<label for="bbp_extra_field1">发起人</label><br>';
    echo "<input type='text' name='organizer' value='" . $value . "'>";

    $value = get_post_meta(bbp_get_topic_id(), 'attendee_count_limit', true);
    echo '<label for="bbp_extra_field1">限制人数</label><br>';
    echo "<input type='text' name='attendee_count_limit' value='" . $value . "'>";

    $value = get_post_meta(bbp_get_topic_id(), 'enroll_deadline', true);
    echo '<label for="bbp_extra_field1">报名截止日</label><br>';
    echo "<input type='text' name='enroll_deadline' value='" . $value . "'>";

    $value = get_post_meta(bbp_get_topic_id(), 'fee', true);
    echo '<label for="bbp_extra_field1">费用</label><br>';
    echo "<input type='text' name='fee' value='" . $value . "'>";

    $value = get_post_meta(bbp_get_topic_id(), 'location', true);
    echo '<label for="bbp_extra_field1">地址</label><br>';
    echo "<input type='text' name='location' value='" . $value . "'>";
}

add_action('bbp_new_topic', 'bbp_save_extra_fields', 10, 1);
add_action('bbp_edit_topic', 'bbp_save_extra_fields', 10, 1);

function bbp_save_extra_fields($topic_id = 0) {
    if (isset($_POST) && $_POST['date_and_time'] != '') {
        update_post_meta($topic_id, 'date_and_time', $_POST['date_and_time']);
    }
    if (isset($_POST) && $_POST['organizer'] != '') {
        update_post_meta($topic_id, 'organizer', $_POST['organizer']);
    }
    if (isset($_POST) && $_POST['attendee_count_limit'] != '') {
        update_post_meta($topic_id, 'attendee_count_limit', $_POST['attendee_count_limit']);
    }
    if (isset($_POST) && $_POST['enroll_deadline'] != '') {
        update_post_meta($topic_id, 'enroll_deadline', $_POST['enroll_deadline']);
    }
    if (isset($_POST) && $_POST['fee'] != '') {
        update_post_meta($topic_id, 'fee', $_POST['fee']);
    }
    if (isset($_POST) && $_POST['location'] != '') {
        update_post_meta($topic_id, 'location', $_POST['location']);
    }
}


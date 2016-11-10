<?php

/* Disable WordPress Admin Bar for all users but admins. */

show_admin_bar(false);
date_default_timezone_set(get_option('timezone_string'));

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
    $themeFolder = '/wp-content/themes/woohelps';
    ?>

    <div class="row">
        <div class="col-xs-12" style="padding: 0;">
            <?php $value = get_post_meta(bbp_get_topic_id(), 'date_and_time', true);
            if (strlen($value) == 0) $value = time() * 1000;
            ?>
            <div class="form-group">
                <label for="bbp_extra_field1">日期和时间</label><br>
                <input type='text' id='date_and_time' value=''>
                <input type="hidden" id="fakeTime1" name='date_and_time' value="<?=$value?>">
            </div>

            <?php $value = get_post_meta(bbp_get_topic_id(), 'organizer', true); ?>
            <div class="form-group">
                <label for="bbp_extra_field1">发起人</label><br>
                <input type='text' name='organizer' value='<?=$value ?>'>
            </div>

            <?php $value = get_post_meta(bbp_get_topic_id(), 'attendee_count_limit', true); ?>
            <?php if (strlen($value) == 0) $value = 100; ?>
            <div class="form-group">
                <label for="bbp_extra_field1">限制人数（请填写大于 0 的数字，将用于人数校验）</label><br>
                <input type='text' name='attendee_count_limit' value='<?=$value ?>'>
            </div>

            <?php $value = get_post_meta(bbp_get_topic_id(), 'enroll_deadline', true);
            if (strlen($value) == 0) $value = time() * 1000;
            ?>
            <div class="form-group">
                <label for="bbp_extra_field1">报名截止日</label><br>
                <input type='text' id='enroll_deadline' value=''>
                <input type="hidden" id="fakeTime2" name='enroll_deadline' value="<?=$value ?>">
            </div>

            <?php $value = get_post_meta(bbp_get_topic_id(), 'fee', true); ?>
            <?php if (strlen($value) == 0) $value = 0; ?>
            <div class="form-group">
                <label for="bbp_extra_field1">费用（可填写任意内容）</label><br>
                <input type='text' name='fee' value='<?=$value ?>'>
            </div>

            <?php $value = get_post_meta(bbp_get_topic_id(), 'location', true); ?>
            <div class="form-group">
                <label for="bbp_extra_field1">地址</label><br>
                <input type='text' name='location' value='<?=$value ?>'>
            </div>

            <?php $value = get_post_meta(bbp_get_topic_id(), 'enroll_way', true); ?>
            <div class="form-group">
                <label for="bbp_extra_field1">报名方式</label><br>
                <label class="radio-inline"><input type="radio" name="enroll_way" value='1' <?=($value=='1') ? 'checked="checked"' : ''?>>通过网站:</label>
                <label class="radio-inline"><input type="radio" name="enroll_way" value='2' <?=($value=='2') ? 'checked="checked"' : ''?>>在活动描述中提供报名方式:</label>
                <label class="radio-inline"><input type="radio" name="enroll_way" value='3' <?=($value=='3') ? 'checked="checked"' : ''?>>无需报名:</label>
            </div>

            <?php $value = get_post_meta(bbp_get_topic_id(), 'attendee_count', true); ?>
            <?php if (strlen($value) == 0) $value = 0; ?>
            <input type='hidden' name='attendee_count' value='<?=$value ?>'>
        </div>
    </div>

    <script src="<?=$themeFolder . '/js/moment.min.js'?>"></script>
    <script src="<?=$themeFolder . '/js/locale/zh-cn.js'?>"></script>
    <script src="<?=$themeFolder . '/js/bootstrap-datetimepicker.min.js'?>"></script>
    <script>
        var $ = jQuery;
        var timeInput = $('#date_and_time');
        var endTime = $('#enroll_deadline');
        var fakeTime1 = $('#fakeTime1');
        var fakeTime2 = $('#fakeTime2');
        moment.locale('zh-cn');
        Date.prototype.Format = function (fmt) {
            var o = {
                "M+": this.getMonth() + 1, //月份
                "d+": this.getDate(), //日
                "h+": this.getHours(), //小时
                "m+": this.getMinutes(), //分
                "s+": this.getSeconds(), //秒
                "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                "S": this.getMilliseconds() //毫秒
            };
            if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            for (var k in o)
                if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            return fmt;
        };

        timeInput.datetimepicker({
            format: "YYYY 年 M 月 DD 日 H:m"
        });
        timeInput.data('DateTimePicker').defaultDate(moment(parseInt(fakeTime1.val())).format("YYYY 年 M 月 DD 日 H:m"));

        timeInput.on('blur', function() {
            fakeTime1.val((moment(timeInput.data('DateTimePicker').date()).unix()) * 1000);
        });

        endTime.datetimepicker({
            format: "YYYY 年 M 月 DD 日"
        });
        endTime.data('DateTimePicker').defaultDate(moment(parseInt(fakeTime2.val())).format("YYYY 年 M 月 DD 日"));

        endTime.on('blur', function() {
            fakeTime2.val((moment(endTime.data('DateTimePicker').date()).unix()) * 1000);
        });
    </script>
<?php
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
    if (isset($_POST) && $_POST['enroll_way'] != '') {
        update_post_meta($topic_id, 'enroll_way', $_POST['enroll_way']);
    }
    if (isset($_POST) && $_POST['attendee_count'] != '') {
        update_post_meta($topic_id, 'attendee_count', $_POST['attendee_count']);
    }
}

/*
 * Methods for attendees counting
 */
function bbp_get_attendee_count($topic_id = 0) {
    if ($topic_id === 0) return false;

    $ret = get_post_meta($topic_id, 'attendee_count', true);
    if (strlen($ret) == 0) $ret = 0;
    return $ret;
}

function bbp_add_attendee_count($topic_id = 0, $count = 0, $user_id = 0) {
    if ($topic_id === 0 || $count === 0 || !is_user_logged_in()) return false;

    if ($user_id === 0) {
        global $current_user;
        $user_id = $current_user->ID;
        if (!isset($user_id) || strlen($user_id) <= 0) return false;
    }

    // default to 1 person
    if (!isset($count) || strlen($count) <= 0) $count = 1;

    update_user_meta($user_id, 'subscribe-' . $topic_id, $count);
    update_post_meta($topic_id, 'attendee_count', intval(get_post_meta($topic_id, 'attendee_count', true)) + $count);
}

function bbp_remove_attendee($topic_id = 0, $user_id = 0) {
    if ($topic_id === 0) return false;

    if ($user_id === 0) {
        global $current_user;
        $user_id = $current_user->ID;
        if (!isset($user_id) || strlen($user_id) <= 0) return false;
    }

    $resv = intval(get_user_meta($user_id, 'subscribe-' . $topic_id, true));
    $att_count = intval(get_post_meta($topic_id, 'attendee_count', true));
    $att_left = $att_count - $resv;
    if ($att_left < 0) $att_left = 0;

    update_post_meta($topic_id, 'attendee_count', $att_left);
    delete_user_meta($user_id, 'subscribe-' . $topic_id);
}

class Calendar {

    /**
     * Constructor
     */
    public function __construct($topics = array()) {
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
        $this->topics = $topics;
    }

    /********************* PROPERTY ********************/
    private $dayLabels = array("一", "二", "三", "四", "五", "六", "日");
    private $currentYear = 0;
    private $currentMonth = 0;
    private $currentDay = 0;
    private $currentDate = null;
    private $daysInMonth = 0;
    private $naviHref = null;
    private $topics = null;

    /********************* PUBLIC **********************/

    /**
     * print out the calendar
     */
    public function show() {
        $year == null;
        $month == null;

        if (null == $year && isset($_GET['year'])) {
            $year = $_GET['year'];
        }
        else if (null == $year) {
            $year = date("Y", time());
        }

        if (null == $month && isset($_GET['month'])) {
            $month = $_GET['month'];
        }
        else if (null == $month) {
            $month = date("m", time());
        }

        $this->currentYear = $year;
        $this->currentMonth = $month;
        $this->daysInMonth = $this->_daysInMonth($month, $year);

        $content = '<div id="calendar">' .
            '<div class="box">' .
            $this->_createNavi() .
            '</div>' .
            '<div class="box-content">' .
            '<ul class="label">' . $this->_createLabels() . '</ul>';
        $content .= '<div class="clear"></div>';
        $content .= '<ul class="dates">';

        $weeksInMonth = $this->_weeksInMonth($month, $year);
        // Create weeks in a month
        for ($i = 0; $i < $weeksInMonth; $i++) {
            //Create days in a week
            for ($j = 1; $j <= 7; $j++) {
                $content .= $this->_showDay($i * 7 + $j);
            }
        }

        $content .= '</ul>';
        $content .= '<div class="clear"></div>';
        $content .= '</div>';
        $content .= '</div>';
        return $content;
    }

    /********************* PRIVATE **********************/
    /**
     * create the li element for ul
     */
    private function _showDay($cellNumber) {

        if ($this->currentDay == 0) {
            $firstDayOfTheWeek = date('N', strtotime($this->currentYear . '-' . $this->currentMonth . '-01'));
            if (intval($cellNumber) == intval($firstDayOfTheWeek)) {
                $this->currentDay = 1;
            }
        }

        if (($this->currentDay != 0) && ($this->currentDay <= $this->daysInMonth)) {
            $this->currentDate = date('Y-m-d', strtotime($this->currentYear . '-' . $this->currentMonth . '-' . ($this->currentDay)));
            $cellContent = $this->currentDay;
            $this->currentDay++;
        }
        else {
            $this->currentDate = null;
            $cellContent = null;
        }

        $data_script = '';

        foreach($this->topics as $topic) {
            if (date('Y-m-d', $topic['date'] / 1000) == $this->currentDate) {
                if ($data_script === '') $data_script = '<script type="text/html" id="sc-' . $this->currentDate . '">';
                $data_script .= '<p><a href="' . $topic['url'] . '">' . $topic['title'] . '</a></p>';
            }
        }

        if ($data_script !== '') $data_script .= '</script>';

        $ret = '<li id="li-' . $this->currentDate . '" class="' . ($cellNumber % 7 == 1 ? ' start ' : ($cellNumber % 7 == 0 ? ' end ' : ' ')) . ($cellContent == null ? 'mask' : '') . '">';
        if ($data_script !== '') $ret .= '<button class="btn btn-xs btn-default" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-target="#sc-' . $this->currentDate . '">';
        $ret .= $cellContent;
        if ($data_script !== '') $ret .= '</button>';
        $ret .= '</li>' . $data_script;

        return $ret;
    }

    /**
     * create navigation
     */
    private function _createNavi() {
        $nextMonth = $this->currentMonth == 12 ? 1 : intval($this->currentMonth) + 1;
        $nextYear = $this->currentMonth == 12 ? intval($this->currentYear) + 1 : $this->currentYear;
        $preMonth = $this->currentMonth == 1 ? 12 : intval($this->currentMonth) - 1;
        $preYear = $this->currentMonth == 1 ? intval($this->currentYear) - 1 : $this->currentYear;

        return
            '<div class="header">' .
//            '<a class="prev" href="' . $this->naviHref . '?month=' . sprintf('%02d', $preMonth) . '&year=' . $preYear . '">Prev</a>' .
            '<span class="title">' . date('Y 年 m 月', strtotime($this->currentYear . '-' . $this->currentMonth . '-1')) . '</span>' .
//            '<a class="next" href="' . $this->naviHref . '?month=' . sprintf("%02d", $nextMonth) . '&year=' . $nextYear . '">Next</a>' .
            '</div>';
    }

    /**
     * create calendar week labels
     */
    private function _createLabels() {
        $content = '';
        foreach ($this->dayLabels as $index => $label) {
            $content .= '<li class="' . ($label == 6 ? 'end title' : 'start title') . ' title">' . $label . '</li>';
        }
        return $content;
    }


    /**
     * calculate number of weeks in a particular month
     */
    private function _weeksInMonth($month = null, $year = null) {
        if (null == ($year)) {
            $year = date("Y", time());
        }
        if (null == ($month)) {
            $month = date("m", time());
        }
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month, $year);
        $numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval($daysInMonths / 7);
        $monthEndingDay = date('N', strtotime($year . '-' . $month . '-' . $daysInMonths));
        $monthStartDay = date('N', strtotime($year . '-' . $month . '-01'));
        if ($monthEndingDay < $monthStartDay) {
            $numOfweeks++;
        }

        return $numOfweeks;
    }

    /**
     * calculate number of days in a particular month
     */
    private function _daysInMonth($month = null, $year = null) {
        if (null == ($year)) {
            $year = date("Y", time());
        }
        if (null == ($month)) {
            $month = date("m", time());
        }
        return date('t', strtotime($year . '-' . $month . '-01'));
    }

}

function dd($input) {
    echo '<pre>';
    print_r($input);
    echo '</pre>';
    die;
}


/**
 * 得到个人用户的一句话描述,微信名和手机号。
 */
function getXprofile($user_id) {
    $current_visibility_levels = bp_get_user_meta( $user_id, 'bp_xprofile_visibility_levels', true );
    global $wpdb;
     $querystr = "
     select
        xfield.`id`,
        xfield.`name`,
        xdata.`value`
        from `woo_bp_xprofile_data` as xdata
        inner join `woo_bp_xprofile_fields` as xfield on xfield.`id` = xdata.`field_id`
     where
         xdata.`user_id` = ". $user_id ."
     ";

    $xProfileArr = [];
    $xprofiles = $wpdb->get_results($querystr,ARRAY_A);
    $is_friend = bp_is_friend($user_id);
    $current_user = wp_get_current_user();

    $current_user_id = $current_user->ID;
    $current_user_role = $current_user->roles[0];

    foreach($xprofiles as $xprofile){
        if(isset($current_visibility_levels[$xprofile['id']])){
            $xprofile['meta_value'] = $current_visibility_levels[$xprofile['id']];
        }
        if($xprofile['meta_value'] == 'public' || ($xprofile['meta_value'] == 'friends' && $is_friend) || $current_user_id == $user_id || $current_user_role =="administrator" ) {
            $xProfileArr[$xprofile['name']] = $xprofile ['value'];
        }
    };

    return $xProfileArr;
}


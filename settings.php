<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('block_rss_plus_num_entries', get_string('numentries', 'block_rss_plus'),
                       get_string('clientnumentries', 'block_rss_plus'), 5, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_rss_plus_timeout', get_string('timeout2', 'block_rss_plus'),
                       get_string('timeout', 'block_rss_plus'), 30, PARAM_INT));

    $link ='<a href="'.$CFG->wwwroot.'/blocks/rss_plus/managefeeds.php">'.get_string('feedsaddedit', 'block_rss_plus').'</a>';
    $settings->add(new admin_setting_heading('block_rss_addheading', '', $link));
}
<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing RSS Bar block instances.
 *
 * @package   moodlecore
 * @copyright Based on code from 2009 by Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing RSS Plus block instances.
 *
 * @copyright	2012 Shaun Daubney
 * @sourcecode	2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_rss_plus_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG, $DB, $USER;

        // Fields for editing block contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('uploadlabel'));
        $mform->setType('config_title', PARAM_NOTAGS);        


	    $mform->addElement('text', 'config_shownumentries', get_string('shownumentrieslabel', 'block_rss_plus'), array('size' => 5));
        $mform->setType('config_shownumentries', PARAM_INTEGER);
        $mform->addRule('config_shownumentries', null, 'numeric', null, 'client');
        if (!empty($CFG->block_rss_plus_num_entries)) {
            $mform->setDefault('config_shownumentries', $CFG->block_rss_plus_num_entries);
        } else {
            $mform->setDefault('config_shownumentries', 5);
        }

        $rssfeeds = $DB->get_records_sql_menu('
                SELECT id,
                       CASE WHEN preferredtitle = ? THEN ' . $DB->sql_compare_text('title', 64) .' ELSE preferredtitle END
                FROM {block_rss_plus}
                WHERE userid = ? OR shared = 1
                ORDER BY CASE WHEN preferredtitle = ? THEN ' . $DB->sql_compare_text('title', 64) . ' ELSE preferredtitle END ',
                array($DB->sql_empty(), $USER->id, $DB->sql_empty()));
        if ($rssfeeds) {
            $select = $mform->addElement('select', 'config_rssid', get_string('choosefeedlabel', 'block_rss_plus'), $rssfeeds);
            $select->setMultiple(true);

        } else {
            $mform->addElement('static', 'config_rssid', get_string('choosefeedlabel', 'block_rss_plus'),
                    get_string('nofeeds', 'block_rss_plus'));
        }

        if (has_any_capability(array('block/rss_plus:manageanyfeeds', 'block/rss_plus:manageownfeeds'), $this->block->context)) {
            $mform->addElement('static', 'nofeedmessage', '',
                    '<div class="rssaddfeedlink"><a class="rssaddfeedlink" href="' . $CFG->wwwroot . '/blocks/rss_plus/managefeeds.php?courseid=' . $this->page->course->id . '">' .
                    get_string('feedsaddedit', 'block_rss_plus') . '</a></div>');
        }


    }
}

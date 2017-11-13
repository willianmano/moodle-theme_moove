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
 * Mustache helper to load a theme configuration.
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_moove\util;

use theme_config;
use stdClass;

/**
 * Helper to load a theme configuration.
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_settings {

    /**
     * Get config theme footer itens
     *
     * @return array
     */
    public function footer_items() {
        $theme = theme_config::load('moove');

        $templatecontext = [];

        $templatecontext['facebook'] = '';
        if (!empty($theme->settings->facebook)) {
            $templatecontext['facebook'] = $theme->settings->facebook;
        }

        $templatecontext['twitter'] = '';
        if (!empty($theme->settings->twitter)) {
            $templatecontext['twitter'] = $theme->settings->twitter;
        }

        $templatecontext['googleplus'] = '';
        if (!empty($theme->settings->googleplus)) {
            $templatecontext['googleplus'] = $theme->settings->googleplus;
        }

        $templatecontext['linkedin'] = '';
        if (!empty($theme->settings->linkedin)) {
            $templatecontext['linkedin'] = $theme->settings->linkedin;
        }

        $templatecontext['youtube'] = '';
        if (!empty($theme->settings->youtube)) {
            $templatecontext['youtube'] = $theme->settings->youtube;
        }

        $templatecontext['instagram'] = '';
        if (!empty($theme->settings->instagram)) {
            $templatecontext['instagram'] = $theme->settings->instagram;
        }

        $templatecontext['getintouchcontent'] = '';
        if (!empty($theme->settings->getintouchcontent)) {
            $templatecontext['getintouchcontent'] = $theme->settings->getintouchcontent;
        }

        $templatecontext['website'] = '';
        if (!empty($theme->settings->website)) {
            $templatecontext['website'] = $theme->settings->website;
        }

        $templatecontext['mobile'] = '';
        if (!empty($theme->settings->mobile)) {
            $templatecontext['mobile'] = $theme->settings->mobile;
        }

        $templatecontext['mail'] = '';
        if (!empty($theme->settings->mail)) {
            $templatecontext['mail'] = $theme->settings->mail;
        }

        $templatecontext['disablebottomfooter'] = false;
        if (!empty($theme->settings->disablebottomfooter)) {
            $templatecontext['disablebottomfooter'] = true;
        }

        return $templatecontext;
    }

    /**
     * Get config theme marketing itens
     *
     * @return array
     */
    public function marketing_items() {
        global $OUTPUT;

        $theme = theme_config::load('moove');

        $templatecontext = [];

        // Marketing 1.
        if (!empty($theme->settings->marketing1icon)) {
            $templatecontext['marketing1icon'] = $theme->setting_file_url('marketing1icon', 'marketing1icon');
        } else {
            $templatecontext['marketing1icon'] = $OUTPUT->image_url('icon_default', 'theme');
        }

        $templatecontext['marketing1heading'] = '';
        if (!empty($theme->settings->marketing1heading)) {
            $templatecontext['marketing1heading'] = theme_moove_get_setting('marketing1heading', true);
        }

        $templatecontext['marketing1subheading'] = '';
        if (!empty($theme->settings->marketing1subheading)) {
            $templatecontext['marketing1subheading'] = theme_moove_get_setting('marketing1subheading', true);
        }

        $templatecontext['marketing1content'] = '';
        if (!empty($theme->settings->marketing1content)) {
            $templatecontext['marketing1content'] = theme_moove_get_setting('marketing1content', true);
        }

        $templatecontext['marketing1url'] = '';
        if (!empty($theme->settings->marketing1url)) {
            $templatecontext['marketing1url'] = $theme->settings->marketing1url;
        }

        // Marketing 2.
        if (!empty($theme->settings->marketing2icon)) {
            $templatecontext['marketing2icon'] = $theme->setting_file_url('marketing2icon', 'marketing2icon');
        } else {
            $templatecontext['marketing2icon'] = $OUTPUT->image_url('icon_default', 'theme');
        }

        $templatecontext['marketing2heading'] = '';
        if (!empty($theme->settings->marketing2heading)) {
            $templatecontext['marketing2heading'] = theme_moove_get_setting('marketing2heading', true);
        }

        $templatecontext['marketing2subheading'] = '';
        if (!empty($theme->settings->marketing2subheading)) {
            $templatecontext['marketing2subheading'] = theme_moove_get_setting('marketing2subheading', true);
        }

        $templatecontext['marketing2content'] = '';
        if (!empty($theme->settings->marketing2content)) {
            $templatecontext['marketing2content'] = theme_moove_get_setting('marketing2content', true);
        }

        $templatecontext['marketing2url'] = '';
        if (!empty($theme->settings->marketing2url)) {
            $templatecontext['marketing2url'] = $theme->settings->marketing2url;
        }

        // Marketing 3.
        if (!empty($theme->settings->marketing3icon)) {
            $templatecontext['marketing3icon'] = $theme->setting_file_url('marketing3icon', 'marketing3icon');
        } else {
            $templatecontext['marketing3icon'] = $OUTPUT->image_url('icon_default', 'theme');
        }

        $templatecontext['marketing3heading'] = '';
        if (!empty($theme->settings->marketing3heading)) {
            $templatecontext['marketing3heading'] = theme_moove_get_setting('marketing3heading', true);
        }

        $templatecontext['marketing3subheading'] = '';
        if (!empty($theme->settings->marketing3subheading)) {
            $templatecontext['marketing3subheading'] = theme_moove_get_setting('marketing3subheading', true);
        }

        $templatecontext['marketing3content'] = '';
        if (!empty($theme->settings->marketing3content)) {
            $templatecontext['marketing3content'] = theme_moove_get_setting('marketing3content', true);
        }

        $templatecontext['marketing3url'] = '';
        if (!empty($theme->settings->marketing3url)) {
            $templatecontext['marketing3url'] = $theme->settings->marketing3url;
        }

        // Marketing 4.
        if (!empty($theme->settings->marketing4icon)) {
            $templatecontext['marketing4icon'] = $theme->setting_file_url('marketing4icon', 'marketing4icon');
        } else {
            $templatecontext['marketing4icon'] = $OUTPUT->image_url('icon_default', 'theme');
        }

        $templatecontext['marketing4heading'] = '';
        if (!empty($theme->settings->marketing4heading)) {
            $templatecontext['marketing4heading'] = theme_moove_get_setting('marketing4heading', true);
        }

        $templatecontext['marketing4subheading'] = '';
        if (!empty($theme->settings->marketing4subheading)) {
            $templatecontext['marketing4subheading'] = theme_moove_get_setting('marketing4subheading', true);
        }

        $templatecontext['marketing4content'] = '';
        if (!empty($theme->settings->marketing4content)) {
            $templatecontext['marketing4content'] = theme_moove_get_setting('marketing4content', true);
        }

        $templatecontext['marketing4url'] = '';
        if (!empty($theme->settings->marketing4url)) {
            $templatecontext['marketing4url'] = $PAGE->theme->settings->marketing4url;
        }

        return $templatecontext;
    }
}

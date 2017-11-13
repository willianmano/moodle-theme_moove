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

defined('MOODLE_INTERNAL') || die();

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

        $footersettings = [
            'facebook', 'twitter', 'googleplus', 'linkedin', 'youtube', 'instagram', 'getintouchcontent',
            'website', 'mobile', 'mail'
        ];

        foreach ($footersettings as $setting) {
            if (!empty($theme->settings->$setting)) {
                $templatecontext[$setting] = $theme->settings->$setting;
            }
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

        for ($i = 1; $i < 5; $i++) {
            $marketingicon = 'marketing' . $i . 'icon';
            $marketingheading = 'marketing' . $i . 'heading';
            $marketingsubheading = 'marketing' . $i . 'subheading';
            $marketingcontent = 'marketing' . $i . 'content';
            $marketingurl = 'marketing' . $i . 'url';

            $templatecontext[$marketingicon] = $OUTPUT->image_url('icon_default', 'theme');
            if (!empty($theme->settings->$marketingicon)) {
                $templatecontext[$marketingicon] = $theme->setting_file_url($marketingicon, $marketingicon);
            }

            $templatecontext[$marketingheading] = '';
            if (!empty($theme->settings->$marketingheading)) {
                $templatecontext[$marketingheading] = theme_moove_get_setting($marketingheading, true);
            }

            $templatecontext[$marketingsubheading] = '';
            if (!empty($theme->settings->$marketingsubheading)) {
                $templatecontext[$marketingsubheading] = theme_moove_get_setting($marketingsubheading, true);
            }

            $templatecontext[$marketingcontent] = '';
            if (!empty($theme->settings->$marketingcontent)) {
                $templatecontext[$marketingcontent] = theme_moove_get_setting($marketingcontent, true);
            }

            $templatecontext[$marketingurl] = '';
            if (!empty($theme->settings->$marketingurl)) {
                $templatecontext[$marketingurl] = $theme->settings->$marketingurl;
            }
        }

        return $templatecontext;
    }
}

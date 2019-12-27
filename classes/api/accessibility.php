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
 * Accessibility API endpoints
 *
 * @package    theme_moove
 * @copyright  2019 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_moove\api;

defined('MOODLE_INTERNAL') || die;

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

class accessibility extends external_api {
    public static function fontsize_parameters() {
        return new external_function_parameters(
            [
                'action' => new external_value(PARAM_RAW, 'The action value'),
            ]
        );
    }

    public static function fontsize($action) {
        $params = self::validate_parameters(self::fontsize_parameters(), ['action' => $action]);

//        user_preference_allow_ajax_update('accessibilitystyles_fontsize', PARAM_INT);
        $currentfontsizeclass = get_user_preferences('accessibilitystyles_fontsizeclass', '');
        $newfontsizeclass = null;

        $currentfontsize = [];
        if ($currentfontsizeclass) {
            $currentfontsize = explode('-', $currentfontsizeclass);
        }

        if ($params['action'] == 'increase') {
            if ($currentfontsizeclass == '') {
                $newfontsizeclass = 'fontsize-inc-1';
            }

            if (isset($currentfontsize[1]) && $currentfontsize[1]  == 'inc' && $currentfontsize[2] < 6) {
                $newfontsizeclass = 'fontsize-inc-' . ($currentfontsize[2] + 1);
            }

            if (isset($currentfontsize[1]) && $currentfontsize[1]  == 'dec' && $currentfontsize[2] > 1) {
                $newfontsizeclass = 'fontsize-dec-' . ($currentfontsize[2] - 1);
            }

            if (isset($currentfontsize[1]) && $currentfontsize[1]  == 'dec' && $currentfontsize[2] == 1) {
                $newfontsizeclass = null;
            }
        }

        if ($params['action'] == 'decrease') {
            if ($currentfontsizeclass == '') {
                $newfontsizeclass = 'fontsize-dec-1';
            }

            if (isset($currentfontsize[1]) && $currentfontsize[1]  == 'dec' && $currentfontsize[2] < 6) {
                $newfontsizeclass = 'fontsize-dec-' . ($currentfontsize[2] + 1);
            }

            if (isset($currentfontsize[1]) && $currentfontsize[1]  == 'inc' && $currentfontsize[2] > 1) {
                $newfontsizeclass = 'fontsize-inc-' . ($currentfontsize[2] - 1);
            }

            if (isset($currentfontsize[1]) && $currentfontsize[1]  == 'inc' && $currentfontsize[2] == 1) {
                $newfontsizeclass = null;
            }
        }

        if ($params['action'] == 'reset') {
            $newfontsizeclass = null;
        }

        set_user_preference('accessibilitystyles_fontsizeclass', $newfontsizeclass);

        return ['newfontsizeclass' => $newfontsizeclass];
    }

    public static function fontsize_returns() {
        return new external_single_structure([
            'newfontsizeclass' => new external_value(PARAM_RAW, 'The new fontsize class')
        ]);
    }

    public static function sitecolor_parameters() {
        return new external_function_parameters(
            [
                'colorscheme' => new external_value(PARAM_RAW, 'The colorscheme value'),
            ]
        );
    }

    public static function sitecolor($formdata) {
        $params = self::validate_parameters(self::sitecolor_parameters(), ['formdata' => $formdata]);

        $data = [];
        parse_str($params['formdata'], $data);

        if ($data['action'] == 'increase') {

        }

        if ($data['action'] == 'decrease') {

        }

        if ($data['action'] == 'reset') {

        }

        return ['success' => true];
    }

    public static function sitecolor_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Operation response')
        ]);
    }
}
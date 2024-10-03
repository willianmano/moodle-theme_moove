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

defined('MOODLE_INTERNAL') || die;

global $CFG;
$h5prenderer = $CFG->dirroot.'/mod/hvp/renderer.php';
if (file_exists($h5prenderer)) {
    // Be sure to include the H5P renderer so it can be extended.
    require_once($h5prenderer);

    /**
     * Class theme_moove_mod_hvp_renderer
     */
    class theme_moove_mod_hvp_renderer extends mod_hvp_renderer {
        public function hvp_alter_styles(&$styles, $libraries, $embedtype) {
            global $CFG;
            $theme = theme_config::load('moove');
            $h5pcss = $theme->setting_file_url('h5pcss', 'h5pcss');
            if (empty($h5pcss)) {
                return;
            }

            // Convert url as h5p expects full https at start instead of "//" returned by setting_file_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $h5pcss = str_replace($relativebaseurl, '', $h5pcss);

            $styles[] = (object) array(
                'path' => new \moodle_url($h5pcss),
                'version' => '?ver=0.0.1',
            );
        }
    }
}

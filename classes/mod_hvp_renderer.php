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
 * Class theme_nexus_mod_hvp_renderer.
 *
 * See: https://tracker.moodle.org/browse/MDL-69087 and
 *      https://github.com/sarjona/h5pmods-moodle-plugin.
 *
 * @package     theme_nexus

 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_nexus_mod_hvp_renderer extends \mod_hvp_renderer {
    /**
     * Add styles when an HVP is displayed.
     *
     * @param object $styles List of stylesheets that will be loaded
     * @param array $libraries Array of libraries indexed by the library's machineName
     * @param string $embedtype Possible values: div, iframe, external, editor
     */
    public function hvp_alter_styles(&$styles, $libraries, $embedtype) {
        $theme = \theme_config::load('moove');

        $content = $theme->settings->hvpcss;

        if (!empty($content)) {
            $styles[] = (object) [
                'path' => $this->get_style_url($content),
                'version' => '',
            ];
        }
    }

    /**
     * Get style URL when an H5P is displayed.
     *
     * @param string $content Content.
     *
     * @return moodle_url the URL.
     */
    protected function get_style_url($content) {
        global $CFG;

        $syscontext = \core\context\system::instance();

        $itemid = md5($content);

        return \moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php",
            "/$syscontext->id/theme_nexus/hvp/$itemid/themehvp.css");
    }
}

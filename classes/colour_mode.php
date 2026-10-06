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

namespace theme_moove;

/**
 * Helper for the Boost light and dark colour modes.
 *
 * Boost renders the colour mode using the Bootstrap 5.3 colour modes API. The resolved mode is written to the
 * data-bs-theme attribute of the html tag, and the mode chosen by the user is written to data-colourmode so that
 * the "auto" mode can be resolved in the browser.
 *
 * The mode is stored as a user preference, and mirrored into a cookie of the same name so that a page which nobody
 * is logged in to can be rendered in it. The preference is authoritative whenever it can be read.
 *
 * @package    theme_moove
 * @copyright  2026 Willian Mano <willianmanoaraujo@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class colour_mode extends \theme_boost\colour_mode {
    /** @var array<string, string> The Font Awesome icon representing each colour mode. */
    private const ICONS = [
        self::LIGHT => 'fa-sun',
        self::DARK => 'fa-moon',
        self::AUTO => 'fa-circle-half-stroke',
    ];

    /**
     * Whether users are allowed to switch between colour modes on this site.
     *
     * Colour modes are an experimental feature which a site opts in to, so they are off until an admin turns them on.
     * A Behat run started with --colourmode turns them on for the run, as the option exists in order to exercise the
     * whole suite in one mode.
     *
     * @return bool
     */
    public static function is_enabled(): bool {
        // The output hooks that this method gates run on the installer's own pages, where there is no configuration to read yet:
        // get_config() throws until the database has been installed, and that exception is how core detects that it
        // still needs to be. Every other entry point to this class goes through here.
        if (during_initial_install()) {
            return false;
        }

        if (
            defined('BEHAT_SITE_RUNNING')
            && function_exists('behat_get_colour_mode')
            && self::is_valid_mode(behat_get_colour_mode())
        ) {
            return true;
        }

        return (bool) get_config('theme_moove', 'enablecolourmodes');
    }

    /**
     * The colour mode used for users who have not chosen one.
     *
     * A Behat run started with --colourmode takes precedence over the site setting, so that the whole suite can be
     * exercised in one mode without every feature having to set a user preference. A preference set by a scenario
     * still wins, so a feature can pin itself to the mode it is about.
     *
     * The setting defaults to light while colour modes are experimental, rather than following the device, so that
     * turning them on does not move anybody into dark mode without them choosing it. See MDL-89379.
     *
     * @return string One of the self::LIGHT, self::DARK or self::AUTO constants.
     */
    public static function get_site_default(): string {
        // Reached through is_enabled() in every path there is today, so this only guards against a future caller
        // reaching it directly on an installer page, where get_config() would throw.
        if (during_initial_install()) {
            return self::LIGHT;
        }

        if (defined('BEHAT_SITE_RUNNING')) {
            $behatmode = behat_get_colour_mode();
            if (self::is_valid_mode($behatmode)) {
                return $behatmode;
            }
        }

        // Falls back to the same mode the setting defaults to, so that a site which has never saved the setting and a
        // site which has saved something unusable are rendered the same way.
        $default = get_config('theme_moove', 'defaultcolourmode');
        return self::is_valid_mode($default) ? $default : self::LIGHT;
    }

    /**
     * Whether the colour mode features apply to the theme currently in use.
     *
     * The hook callbacks in this plugin are called whatever the current theme is, so they must check that Boost is
     * actually involved in rendering the page.
     *
     * @return bool
     */
    public static function is_moove_theme(): bool {
        global $PAGE;

        $theme = $PAGE->theme;
        return $theme->name === 'moove' || in_array('moove', $theme->parents, true);
    }

    /**
     * Render the navbar menu for switching between the colour modes.
     *
     * Nothing is rendered when colour modes are not turned on for the site, or for people who cannot store a user
     * preference (guests and users who are not logged in), as they always get the site default.
     *
     * @param \renderer_base $output The renderer to render the menu with.
     * @return string HTML for the colour mode menu, or an empty string.
     */
    public static function render_menu(\renderer_base $output): string {
        if (!self::can_choose_mode()) {
            return '';
        }

        $current = self::get_current_mode();
        $modes = [];
        foreach (self::get_modes() as $mode) {
            $label = get_string('colourmode:' . $mode, 'theme_moove');
            $modes[] = [
                'mode' => $mode,
                'label' => $label,
                'icon' => self::ICONS[$mode],
                'togglelabel' => get_string('colourmodeselected', 'theme_moove', $label),
                'isactive' => $mode === $current,
            ];
        }

        return $output->render_from_template('theme_moove/colour_mode_menu', [
            'cookieattributes' => self::get_cookie_attributes(),
            'currenticon' => self::ICONS[$current],
            'currenttogglelabel' => get_string(
                'colourmodeselected',
                'theme_moove',
                get_string('colourmode:' . $current, 'theme_moove'),
            ),
            'modes' => $modes,
        ]);
    }
}

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
 * Theme functions.
 *
 * @package    theme_moove
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Post process the CSS tree.
 *
 * @param string $tree The CSS tree.
 * @param theme_config $theme The theme config object.
 */
function theme_moove_css_tree_post_processor($tree, $theme) {
    $prefixer = new theme_moove\autoprefixer($tree);
    $prefixer->prefix();
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_moove_get_extra_scss($theme) {
    $scss = $theme->settings->scss;

    $scss .= theme_moove_set_headerimg($theme);

    return $scss;
}

/**
 * Adds the cover to CSS.
 *
 * @param string $css The CSS.
 * @param string $cover The URL of the logo.
 * @return string The parsed CSS
 */
function theme_moove_set_headerimg($theme) {
    global $OUTPUT;

    $headerimg = $theme->setting_file_url('headerimg', 'headerimg');

    if (is_null($headerimg)) {
        $headerimg = $OUTPUT->pix_url('headerimg', 'theme');
    }

    $headercss = "#page-site-index.notloggedin #page-header {background-image: url('$headerimg');}";

    return $headercss;
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_moove_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/moove/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/moove/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_moove', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/moove/scss/preset/default.scss');
    }

    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return array
 */
function theme_moove_get_pre_scss($theme) {
    global $CFG;

    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['brand-primary'],
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_moove_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'logo') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'headerimg') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('headerimg', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing1icon') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('marketing1icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing2icon') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('marketing2icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing3icon') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('marketing3icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing4icon') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('marketing4icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'mainbox1icon') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('mainbox1icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'mainbox2icon') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('mainbox2icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'mainbox3icon') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('mainbox3icon', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'mainbox4icon') {
        $theme = theme_config::load('moove');
        return $theme->setting_file_serve('mainbox4icon', $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Get theme setting
 *
 * @param string $setting
 * @param bool $format
 * @return string
 */
function theme_moove_get_setting($setting, $format = false) {
    $theme = theme_config::load('moove');

    if (empty($theme->settings->$setting)) {
        return false;
    } else if (!$format) {
        return $theme->settings->$setting;
    } else if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    } else if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    } else {
        return format_string($theme->settings->$setting);
    }
}

/**
 * Get config theme marketing itens
 *
 * @return array
 */
function theme_moove_get_marketing_items() {
    global $PAGE, $OUTPUT;

    $templatecontext = [];

    // Marketing 1.
    if (!empty($PAGE->theme->settings->marketing1icon)) {
        $templatecontext['marketing1icon'] = $PAGE->theme->setting_file_url('marketing1icon', 'marketing1icon');
    } else {
        $templatecontext['marketing1icon'] = $OUTPUT->pix_url('icon_default', 'theme');
    }

    $templatecontext['marketing1heading'] = '';
    if (!empty($PAGE->theme->settings->marketing1heading)) {
        $templatecontext['marketing1heading'] = theme_moove_get_setting('marketing1heading', true);
    }

    $templatecontext['marketing1subheading'] = '';
    if (!empty($PAGE->theme->settings->marketing1subheading)) {
        $templatecontext['marketing1subheading'] = theme_moove_get_setting('marketing1subheading', true);
    }

    $templatecontext['marketing1content'] = '';
    if (!empty($PAGE->theme->settings->marketing1content)) {
        $templatecontext['marketing1content'] = theme_moove_get_setting('marketing1content', true);
    }

    $templatecontext['marketing1url'] = '#';
    if (!empty($PAGE->theme->settings->marketing1url)) {
        $templatecontext['marketing1url'] = $PAGE->theme->settings->marketing1url;
    }

    // Marketing 2.
    if (!empty($PAGE->theme->settings->marketing2icon)) {
        $templatecontext['marketing2icon'] = $PAGE->theme->setting_file_url('marketing2icon', 'marketing2icon');
    } else {
        $templatecontext['marketing2icon'] = $OUTPUT->pix_url('icon_default', 'theme');
    }

    $templatecontext['marketing2heading'] = '';
    if (!empty($PAGE->theme->settings->marketing2heading)) {
        $templatecontext['marketing2heading'] = theme_moove_get_setting('marketing2heading', true);
    }

    $templatecontext['marketing2subheading'] = '';
    if (!empty($PAGE->theme->settings->marketing2subheading)) {
        $templatecontext['marketing2subheading'] = theme_moove_get_setting('marketing2subheading', true);
    }

    $templatecontext['marketing2content'] = '';
    if (!empty($PAGE->theme->settings->marketing2content)) {
        $templatecontext['marketing2content'] = theme_moove_get_setting('marketing2content', true);
    }

    $templatecontext['marketing2url'] = '#';
    if (!empty($PAGE->theme->settings->marketing2url)) {
        $templatecontext['marketing2url'] = $PAGE->theme->settings->marketing2url;
    }

    // Marketing 3.
    if (!empty($PAGE->theme->settings->marketing3icon)) {
        $templatecontext['marketing3icon'] = $PAGE->theme->setting_file_url('marketing3icon', 'marketing3icon');
    } else {
        $templatecontext['marketing3icon'] = $OUTPUT->pix_url('icon_default', 'theme');
    }

    $templatecontext['marketing3heading'] = '';
    if (!empty($PAGE->theme->settings->marketing3heading)) {
        $templatecontext['marketing3heading'] = theme_moove_get_setting('marketing3heading', true);
    }

    $templatecontext['marketing3subheading'] = '';
    if (!empty($PAGE->theme->settings->marketing3subheading)) {
        $templatecontext['marketing3subheading'] = theme_moove_get_setting('marketing3subheading', true);
    }

    $templatecontext['marketing3content'] = '';
    if (!empty($PAGE->theme->settings->marketing3content)) {
        $templatecontext['marketing3content'] = theme_moove_get_setting('marketing3content', true);
    }

    $templatecontext['marketing3url'] = '#';
    if (!empty($PAGE->theme->settings->marketing3url)) {
        $templatecontext['marketing3url'] = $PAGE->theme->settings->marketing3url;
    }

    // Marketing 4.
    if (!empty($PAGE->theme->settings->marketing4icon)) {
        $templatecontext['marketing4icon'] = $PAGE->theme->setting_file_url('marketing4icon', 'marketing4icon');
    } else {
        $templatecontext['marketing4icon'] = $OUTPUT->pix_url('icon_default', 'theme');
    }

    $templatecontext['marketing4heading'] = '';
    if (!empty($PAGE->theme->settings->marketing4heading)) {
        $templatecontext['marketing4heading'] = theme_moove_get_setting('marketing4heading', true);
    }

    $templatecontext['marketing4subheading'] = '';
    if (!empty($PAGE->theme->settings->marketing4subheading)) {
        $templatecontext['marketing4subheading'] = theme_moove_get_setting('marketing4subheading', true);
    }

    $templatecontext['marketing4content'] = '';
    if (!empty($PAGE->theme->settings->marketing4content)) {
        $templatecontext['marketing4content'] = theme_moove_get_setting('marketing4content', true);
    }

    $templatecontext['marketing4url'] = '#';
    if (!empty($PAGE->theme->settings->marketing4url)) {
        $templatecontext['marketing4url'] = $PAGE->theme->settings->marketing4url;
    }

    return $templatecontext;
}

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
 * Web manifest for including a native app banner.
 *
 * The banner is only displayed if the user has visited the site twice over two
 * separate days during the course of two weeks. There is an experimental chrome
 * flag to allow testing.
 * More information here: https://developer.android.com/distribute/users/banners.html
 *
 * @package    tool_mobile
 * @copyright  2017 Juan Leyva
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('NO_DEBUG_DISPLAY', true);
define('NO_MOODLE_COOKIES', true);

require_once(__DIR__ . '/../../config.php');

header('Content-Type: application/json; charset: utf-8');

$mobilesettings = get_config('tool_mobile');

if (empty($mobilesettings->enablesmartappbanners)) {
    die;
}

$manifest = new StdClass;
$manifest->name = format_string($SITE->fullname);
$manifest->short_name = format_string($SITE->shortname);
$manifest->icons = [(object)[
    'sizes' => '144x144',
    'type' => 'image/png',
    'src' => "$CFG->wwwroot/$CFG->admin/tool/mobile/pix/icon_144.png"
]];
$manifest->related_applications = [(object)[
    'platform' => 'play',
    'id' => $mobilesettings->androidappid,
]];

// Manifest for mobile app.
if (!empty($CFG->enablemobilewebservice) && !empty($mobilesettings->androidappid)) {
    $manifest->prefer_related_applications = true;
} else {
    // Manifest for PWA.
    $brandcolor = get_config('theme_moove', 'brandcolor');

    $manifest->start_url = $CFG->wwwroot;
    $manifest->display = 'minimal-ui';
    $manifest->background_color = $brandcolor ?: '#0f47ad';
}

echo json_encode($manifest);

die;

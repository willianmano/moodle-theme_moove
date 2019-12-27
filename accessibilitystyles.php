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
 * Prints the accessibility css content
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

// 1rem Assumes the browser default, typically `16px`.
$fontsizebase = get_user_preferences('accessibilitystyles_fontsize', 0.9375);

if ($fontsizebase > 1) {
    $fontsizebase = 1 + (($fontsizebase - 1) * 0.1);
}

$h1fontsize = $fontsizebase * 2.5;
$h2fontsize = $fontsizebase * 2;
$h3fontsize = $fontsizebase * 1.75;
$h4fontsize = $fontsizebase * 1.5;
$h5fontsize = $fontsizebase * 1.25;
$h6fontsize = $fontsizebase;

// Including config.php overwrites header content-type in moodle 2.8.
header('Content-Type: text/css', true);
header("X-Content-Type-Options: nosniff"); // For IE.
header('Cache-Control: no-cache');

$output = sprintf("
    body,.dropdown-menu{font-size: %srem !important;}
    #accessibilitybar .bars{font-size: .9375rem !important;}
    #nav-drawer, #page, #top-footer{font-size: %srem !important;}
	#page *{font-size: inherit;}
    h1,#page #page-header h1,#page #region-main h1{font-size: %srem !important;}
    h2{font-size: %srem !important;}
    h3{font-size: %srem !important;}
    h4{font-size: %srem !important;}
    h5{font-size: %srem !important;}
    h6{font-size: %srem !important;}", $fontsizebase, $fontsizebase, $h1fontsize, $h2fontsize, $h3fontsize, $h4fontsize, $h5fontsize, $h6fontsize);

//echo minify($output);

function minify($content) {
//    $content = str_replace(" ", "", $content);
    $content = str_replace("\n", "", $content);
    $content = trim($content);

    return $content;
}
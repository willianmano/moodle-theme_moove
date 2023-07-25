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
 * React based dashboard.
 *
 * @package    theme_moove
 * @copyright  2023 Diego Monroy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
// For this type of page this is the course id.

require_login();
global $PAGE, $OUTPUT;
$PAGE->set_url('/theme/moove/react_dashboard.php');
$PAGE->set_pagelayout('react_dashboard');

// Print the header.
echo $OUTPUT->header();

echo '<div id="root" data-test="otrodato"></div>';

$PAGE->requires->js('/theme/moove/react_components/dashboard/dist/bundle.js');
// Get the assign to render the page.
echo $OUTPUT->footer();


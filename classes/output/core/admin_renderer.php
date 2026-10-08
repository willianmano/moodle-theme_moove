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
 * Overriden theme boost core renderer.
 *
 * @package    theme_moove
 * @copyright  2024 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_moove\output\core;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/admin/renderer.php');

/**
 * Standard HTML output renderer for core_admin subsystem.
 *
 * @package    core
 * @subpackage admin
 * @copyright  2011 David Mudrak <david@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_renderer extends \core_admin_renderer {
    /**
     * Display the admin notifications page.
     * @param int $maturity
     * @param bool $insecuredataroot warn dataroot is invalid
     * @param bool $errorsdisplayed warn invalid dispaly error setting
     * @param bool $cronoverdue warn cron not running
     * @param bool $dbproblems warn db has problems
     * @param bool $maintenancemode warn in maintenance mode
     * @param bool $buggyiconvnomb warn iconv problems
     * @param array|null $availableupdates array of \core\update\info objects or null
     * @param int|null $availableupdatesfetch timestamp of the most recent updates fetch or null (unknown)
     * @param string[] $cachewarnings An array containing warnings from the Cache API.
     * @param array $eventshandlers Events 1 API handlers.
     * @param bool $themedesignermode Warn about the theme designer mode.
     * @param bool $devlibdir Warn about development libs directory presence.
     * @param bool $mobileconfigured Whether the mobile web services have been enabled
     * @param bool $overridetossl Whether or not ssl is being forced.
     * @param bool $invalidforgottenpasswordurl Whether the forgotten password URL does not link to a valid URL.
     * @param bool $croninfrequent If true, warn that cron hasn't run in the past few minutes
     * @param string $xmlrpcwarning XML-RPC deprecation warning message.
     *
     * @return string HTML to output.
     */
    public function notifications_page(
        $maturity,
        $insecuredataroot,
        $errorsdisplayed,
        $cronoverdue,
        $dbproblems,
        $maintenancemode,
        $availableupdates,
        $availableupdatesfetch,
        $buggyiconvnomb,
        $registered,
        array $cachewarnings = [],
        $eventshandlers = 0,
        $themedesignermode = false,
        $devlibdir = false,
        $mobileconfigured = false,
        $overridetossl = false,
        $invalidforgottenpasswordurl = false,
        $croninfrequent = false,
        $xmlrpcwarning = ''
    ) {

        global $CFG;
        $output = '';

        $notifications = [];

        $add = function (string $html, string $severity) use (&$notifications) {
            if ($html !== '') {
                $notifications[] = ['html' => $html, 'severity' => $severity];
            }
        };
        $add($this->maturity_info($maturity), $maturity == MATURITY_ALPHA ? 'danger' : 'warning');
        if (empty($CFG->disableupdatenotifications)) {
            $add($this->available_updates($availableupdates, $availableupdatesfetch), 'notice');
        }
        $add(
            $this->insecure_dataroot_warning($insecuredataroot),
            $insecuredataroot == INSECURE_DATAROOT_ERROR ? 'danger' : 'warning'
        );
        $add($this->development_libs_directories_warning($devlibdir), 'danger');
        $add($this->themedesignermode_warning($themedesignermode), 'warning');
        $add($this->display_errors_warning($errorsdisplayed), 'warning');
        $add($this->buggy_iconv_warning($buggyiconvnomb), 'warning');
        $add($this->cron_overdue_warning($cronoverdue), 'warning');
        $add($this->cron_infrequent_warning($croninfrequent), 'warning');
        $add($this->db_problems($dbproblems), 'warning');
        $add($this->maintenance_mode_warning($maintenancemode), 'warning');
        $add($this->overridetossl_warning($overridetossl), 'warning');
        $add($this->cache_warnings($cachewarnings), 'warning');
        $add($this->events_handlers($eventshandlers), 'warning');
        $add($this->registration_warning($registered), $this->registration_warning_severity($registered));
        $add($this->mobile_configuration_warning($mobileconfigured), 'warning');
        $add($this->forgotten_password_url_warning($invalidforgottenpasswordurl), 'danger');
        $add($this->mnet_deprecation_warning($xmlrpcwarning), 'warning');
        $add($this->moodlenet_removal_warning(), 'warning');
        // Group and order by severity (danger/critical first, then warning, then notice), preserving the
        // original relative order of notifications within each severity group.
        $severityorder = ['danger' => 0, 'warning' => 1, 'notice' => 2];
        usort($notifications, fn($a, $b) => $severityorder[$a['severity']] <=> $severityorder[$b['severity']]);

        $counts = ['danger' => 0, 'warning' => 0, 'notice' => 0];
        foreach ($notifications as $notification) {
            $counts[$notification['severity']]++;
        }

        $output .= $this->header();
        $output .= $this->notifications_heading($counts);
        foreach ($notifications as $notification) {
            $output .= $notification['html'];
        }

        $output .= $this->conectime_services_and_support_content();

        //////////////////////////////////////////////////////////////////////////////////////////////////
        ////  IT IS ILLEGAL AND A VIOLATION OF THE GPL TO HIDE, REMOVE OR MODIFY THIS COPYRIGHT NOTICE ///
        $output .= $this->moodle_copyright();
        //////////////////////////////////////////////////////////////////////////////////////////////////

        $output .= $this->footer();

        return $output;
    }

    /**
     * Display services and support content.
     *
     * @return string the campaign content raw html.
     */
    private function conectime_services_and_support_content(): string {
        return $this->render_from_template('theme_moove/moove/conectime_services_and_support_content_banner', []);
    }
}

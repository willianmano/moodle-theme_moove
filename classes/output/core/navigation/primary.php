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
 * File containing navigation renderable.
 *
 * @package    theme_moove
 * @copyright  2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_moove\output\core\navigation;

use renderer_base;

/**
 * The class activity navigation renderable.
 *
 * @package    theme_moove
 * @copyright  2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class primary extends \core\navigation\output\primary {
    /** @var \moodle_page $page the moodle page that the navigation belongs to */
    private $page = null;

    public function __construct($page) {
        parent::__construct($page);

        $this->page = $page;
    }

    /**
     * Get/Generate the user menu.
     *
     * This is leveraging the data from user_get_user_navigation_info and the logic in $OUTPUT->user_menu()
     *
     * @param renderer_base $output
     * @return array
     */
    public function get_user_menu(renderer_base $output): array {
        global $CFG, $USER, $PAGE;
        require_once($CFG->dirroot . '/user/lib.php');

        $usermenudata = [];
        $submenusdata = [];
        $info = user_get_user_navigation_info($USER, $PAGE);
        if (isset($info->unauthenticateduser)) {
            $info->unauthenticateduser['content'] = get_string($info->unauthenticateduser['content']);
            $info->unauthenticateduser['url'] = get_login_url();
            return (array) $info;
        }

        // Custom user avatar.
        $userimage = theme_moove_get_user_avatar_or_image($USER);
        $info->metadata['useravatar'] = \html_writer::img($userimage, fullname($USER), ['class' => 'userpicture', 'width' => 35, 'height' => 35]);

        // Gather all the avatar data to be displayed in the user menu.
        $usermenudata['avatardata'][] = [
            'content' => $info->metadata['useravatar'],
            'classes' => 'current'
        ];
        $usermenudata['userfullname'] = $info->metadata['realuserfullname'] ?? $info->metadata['userfullname'];

        // Logged in as someone else.
        if ($info->metadata['asotheruser']) {
            $usermenudata['avatardata'][] = [
                'content' => $info->metadata['realuseravatar'],
                'classes' => 'realuser'
            ];
            $usermenudata['metadata'][] = [
                'content' => get_string('loggedinas', 'moodle', $info->metadata['userfullname']),
                'classes' => 'viewingas'
            ];
        }

        // Gather all the meta data to be displayed in the user menu.
        $metadata = [
            'asotherrole' => [
                'value' => 'rolename',
                'class' => 'role role-##GENERATEDCLASS##',
            ],
            'userloginfail' => [
                'value' => 'userloginfail',
                'class' => 'loginfailures',
            ],
            'asmnetuser' => [
                'value' => 'mnetidprovidername',
                'class' => 'mnet mnet-##GENERATEDCLASS##',
            ],
        ];
        foreach ($metadata as $key => $value) {
            if (!empty($info->metadata[$key])) {
                $content = $info->metadata[$value['value']] ?? '';
                $generatedclass = strtolower(preg_replace('#[ ]+#', '-', trim($content)));
                $customclass = str_replace('##GENERATEDCLASS##', $generatedclass, ($value['class'] ?? ''));
                $usermenudata['metadata'][] = [
                    'content' => $content,
                    'classes' => $customclass
                ];
            }
        }

        $modifiedarray = array_map(function($value) {
            $value->divider = $value->itemtype == 'divider';
            $value->link = $value->itemtype == 'link';
            if (isset($value->pix) && !empty($value->pix)) {
                $value->pixicon = $value->pix;
                unset($value->pix);
            }
            return $value;
        }, $info->navitems);

        // Include the language menu as a submenu within the user menu.
        $languagemenu = new \core\output\language_menu($this->page);
        $langmenu = $languagemenu->export_for_template($output);
        if (!empty($langmenu)) {
            $languageitems = $langmenu['items'];
            // If there are available languages, generate the data for the the language selector submenu.
            if (!empty($languageitems)) {
                $langsubmenuid = uniqid();
                // Generate the data for the link to language selector submenu.
                $language = (object) [
                    'itemtype' => 'submenu-link',
                    'submenuid' => $langsubmenuid,
                    'title' => get_string('language'),
                    'divider' => false,
                    'submenulink' => true,
                ];

                // Place the link before the 'Log out' menu item which is either the last item in the menu or
                // second to last when 'Switch roles' is available.
                $menuposition = count($modifiedarray) - 1;
                if (has_capability('moodle/role:switchroles', $PAGE->context)) {
                    $menuposition = count($modifiedarray) - 2;
                }
                array_splice($modifiedarray, $menuposition, 0, [$language]);

                // Generate the data for the language selector submenu.
                $submenusdata[] = (object)[
                    'id' => $langsubmenuid,
                    'title' => get_string('languageselector'),
                    'items' => $languageitems,
                ];
            }
        }

        // Add divider before the last item.
        $modifiedarray[count($modifiedarray) - 2]->divider = true;
        $usermenudata['items'] = $modifiedarray;
        $usermenudata['submenus'] = array_values($submenusdata);

        return $usermenudata;
    }
}

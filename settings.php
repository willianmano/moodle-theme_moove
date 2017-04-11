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
 * @package   theme_moove
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    /*
    * ---------------------
    * General settings page
    * ---------------------
    */
    $settings = new theme_moove_admin_settingspage_tabs('themesettingmoove', get_string('configtitle', 'theme_moove'));
    $page = new admin_settingpage('theme_moove_general', get_string('generalsettings', 'theme_moove'));

    // Logo file setting.
    $name = 'theme_moove/logo';
    $title = get_string('logo', 'theme_moove');
    $description = get_string('logodesc', 'theme_moove');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset.
    $name = 'theme_moove/preset';
    $title = get_string('preset', 'theme_moove');
    $description = get_string('preset_desc', 'theme_moove');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_moove', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files setting.
    $name = 'theme_moove/presetfiles';
    $title = get_string('presetfiles', 'theme_moove');
    $description = get_string('presetfiles_desc', 'theme_moove');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 20, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Variable $body-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_moove/brandcolor';
    $title = get_string('brandcolor', 'theme_moove');
    $description = get_string('brandcolor_desc', 'theme_moove');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    /*
    * ----------------------
    * Advanced settings page
    * ----------------------
    */
    $page = new admin_settingpage('theme_moove_advanced', get_string('advancedsettings', 'theme_moove'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_moove/scsspre',
        get_string('rawscsspre', 'theme_moove'), get_string('rawscsspre_desc', 'theme_moove'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_moove/scss', get_string('rawscss', 'theme_moove'),
        get_string('rawscss_desc', 'theme_moove'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    /*
    * -----------------------
    * Frontpage settings page
    * -----------------------
    */
    $page = new admin_settingpage('theme_moove_frontpage', get_string('frontpagesettings', 'theme_moove'));

    // Headerimg file setting.
    $name = 'theme_moove/headerimg';
    $title = get_string('headerimg', 'theme_moove');
    $description = get_string('headerimgdesc', 'theme_moove');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bannerheading.
    $name = 'theme_moove/bannerheading';
    $title = get_string('bannerheading', 'theme_moove');
    $description = get_string('bannerheadingdesc', 'theme_moove');
    $default = 'Perfect Learning System';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bannercontent.
    $name = 'theme_moove/bannercontent';
    $title = get_string('bannercontent', 'theme_moove');
    $description = get_string('bannercontentdesc', 'theme_moove');
    $default = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_moove/displaymarketingbox';
    $title = get_string('displaymarketingbox', 'theme_moove');
    $description = get_string('displaymarketingboxdesc', 'theme_moove');
    $default = 1;
    $choices = array(0 => 'No', 1 => 'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Marketing1icon.
    $name = 'theme_moove/marketing1icon';
    $title = get_string('marketing1icon', 'theme_moove');
    $description = get_string('marketing1icondesc', 'theme_moove');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'marketing1icon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing1heading.
    $name = 'theme_moove/marketing1heading';
    $title = get_string('marketing1heading', 'theme_moove');
    $description = get_string('marketing1headingdesc', 'theme_moove');
    $default = 'We host';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing1subheading.
    $name = 'theme_moove/marketing1subheading';
    $title = get_string('marketing1subheading', 'theme_moove');
    $description = get_string('marketing1subheadingdesc', 'theme_moove');
    $default = 'your MOODLE';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing1content.
    $name = 'theme_moove/marketing1content';
    $title = get_string('marketing1content', 'theme_moove');
    $description = get_string('marketing1contentdesc', 'theme_moove');
    $default = 'Moodle hosting in a powerful cloud infrastructure';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing1url.
    $name = 'theme_moove/marketing1url';
    $title = get_string('marketing1url', 'theme_moove');
    $description = get_string('marketing1urldesc', 'theme_moove');
    $default = '#';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing2icon.
    $name = 'theme_moove/marketing2icon';
    $title = get_string('marketing2icon', 'theme_moove');
    $description = get_string('marketing2icondesc', 'theme_moove');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'marketing2icon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing2heading.
    $name = 'theme_moove/marketing2heading';
    $title = get_string('marketing2heading', 'theme_moove');
    $description = get_string('marketing2headingdesc', 'theme_moove');
    $default = 'Consulting';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing2subheading.
    $name = 'theme_moove/marketing2subheading';
    $title = get_string('marketing2subheading', 'theme_moove');
    $description = get_string('marketing2subheadingdesc', 'theme_moove');
    $default = 'for your company';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing2content.
    $name = 'theme_moove/marketing2content';
    $title = get_string('marketing2content', 'theme_moove');
    $description = get_string('marketing2contentdesc', 'theme_moove');
    $default = 'Moodle consulting and training for you';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing2url.
    $name = 'theme_moove/marketing2url';
    $title = get_string('marketing2url', 'theme_moove');
    $description = get_string('marketing2urldesc', 'theme_moove');
    $default = '#';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing3icon.
    $name = 'theme_moove/marketing3icon';
    $title = get_string('marketing3icon', 'theme_moove');
    $description = get_string('marketing3icondesc', 'theme_moove');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'marketing3icon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing3heading.
    $name = 'theme_moove/marketing3heading';
    $title = get_string('marketing3heading', 'theme_moove');
    $description = get_string('marketing3headingdesc', 'theme_moove');
    $default = 'Development';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing3subheading.
    $name = 'theme_moove/marketing3subheading';
    $title = get_string('marketing3subheading', 'theme_moove');
    $description = get_string('marketing3subheadingdesc', 'theme_moove');
    $default = 'themes and plugins';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing3content.
    $name = 'theme_moove/marketing3content';
    $title = get_string('marketing3content', 'theme_moove');
    $description = get_string('marketing3contentdesc', 'theme_moove');
    $default = 'We develop themes and plugins as your desires';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing3url.
    $name = 'theme_moove/marketing3url';
    $title = get_string('marketing3url', 'theme_moove');
    $description = get_string('marketing3urldesc', 'theme_moove');
    $default = '#';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing4icon.
    $name = 'theme_moove/marketing4icon';
    $title = get_string('marketing4icon', 'theme_moove');
    $description = get_string('marketing4icondesc', 'theme_moove');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'marketing4icon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing4heading.
    $name = 'theme_moove/marketing4heading';
    $title = get_string('marketing4heading', 'theme_moove');
    $description = get_string('marketing4headingdesc', 'theme_moove');
    $default = 'Support';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing4subheading.
    $name = 'theme_moove/marketing4subheading';
    $title = get_string('marketing4subheading', 'theme_moove');
    $description = get_string('marketing4subheadingdesc', 'theme_moove');
    $default = 'we give you answers';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing4content.
    $name = 'theme_moove/marketing4content';
    $title = get_string('marketing4content', 'theme_moove');
    $description = get_string('marketing4contentdesc', 'theme_moove');
    $default = 'MOODLE specialized support';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Marketing4url.
    $name = 'theme_moove/marketing4url';
    $title = get_string('marketing4url', 'theme_moove');
    $description = get_string('marketing4urldesc', 'theme_moove');
    $default = '#';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    /*
    * --------------------
    * Footer settings page
    * --------------------
    */
    $page = new admin_settingpage('theme_moove_footer', get_string('footersettings', 'theme_moove'));

    $name = 'theme_moove/getintouchcontent';
    $title = get_string('getintouchcontent', 'theme_moove');
    $description = get_string('getintouchcontentdesc', 'theme_moove');
    $default = 'Conecti.me';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Website.
    $name = 'theme_moove/website';
    $title = get_string('website', 'theme_moove');
    $description = get_string('websitedesc', 'theme_moove');
    $default = 'http://conecti.me';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mobile.
    $name = 'theme_moove/mobile';
    $title = get_string('mobile', 'theme_moove');
    $description = get_string('mobiledesc', 'theme_moove');
    $default = 'Mobile : +55 (98) 00123-45678';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mail.
    $name = 'theme_moove/mail';
    $title = get_string('mail', 'theme_moove');
    $description = get_string('maildesc', 'theme_moove');
    $default = 'willianmano@conectime.com';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Facebook url setting.
    $name = 'theme_moove/facebook';
    $title = get_string('facebook', 'theme_moove');
    $description = get_string('facebookdesc', 'theme_moove');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Twitter url setting.
    $name = 'theme_moove/twitter';
    $title = get_string('twitter', 'theme_moove');
    $description = get_string('twitterdesc', 'theme_moove');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Googleplus url setting.
    $name = 'theme_moove/googleplus';
    $title = get_string('googleplus', 'theme_moove');
    $description = get_string('googleplusdesc', 'theme_moove');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Linkdin url setting.
    $name = 'theme_moove/linkedin';
    $title = get_string('linkedin', 'theme_moove');
    $description = get_string('linkedindesc', 'theme_moove');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}

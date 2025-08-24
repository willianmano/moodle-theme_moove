<?php
// This file is part of Ranking block for Moodle - http://moodle.org/
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
 * Theme Moove block settings file
 *
 * @package    theme_nexus
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingmoove', get_string('configtitle', 'theme_nexus'));

    /*
    * ----------------------
    * General settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_nexus_general', get_string('generalsettings', 'theme_nexus'));

    // Logo file setting.
    $name = 'theme_nexus/logo';
    $title = get_string('logo', 'theme_nexus');
    $description = get_string('logodesc', 'theme_nexus');
    $opts = ['accepted_types' => ['.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'], 'maxfiles' => 1];
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
    $page->add($setting);

    // Favicon setting.
    $name = 'theme_nexus/favicon';
    $title = get_string('favicon', 'theme_nexus');
    $description = get_string('favicondesc', 'theme_nexus');
    $opts = ['accepted_types' => ['.ico'], 'maxfiles' => 1];
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
    $page->add($setting);

    // Preset.
    $name = 'theme_nexus/preset';
    $title = get_string('preset', 'theme_nexus');
    $description = get_string('preset_desc', 'theme_nexus');
    $default = 'default.scss';

    $context = \core\context\system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_nexus', 'preset', 0, 'itemid, filepath, filename', false);

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
    $name = 'theme_nexus/presetfiles';
    $title = get_string('presetfiles', 'theme_nexus');
    $description = get_string('presetfiles_desc', 'theme_nexus');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        ['maxfiles' => 10, 'accepted_types' => ['.scss']]);
    $page->add($setting);

    // Login page background image.
    $name = 'theme_nexus/loginbgimg';
    $title = get_string('loginbgimg', 'theme_nexus');
    $description = get_string('loginbgimg_desc', 'theme_nexus');
    $opts = ['accepted_types' => ['.png', '.jpg', '.svg']];
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbgimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $brand-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_nexus/brandcolor';
    $title = get_string('brandcolor', 'theme_nexus');
    $description = get_string('brandcolor_desc', 'theme_nexus');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#0f47ad');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $navbar-header-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_nexus/secondarymenucolor';
    $title = get_string('secondarymenucolor', 'theme_nexus');
    $description = get_string('secondarymenucolor_desc', 'theme_nexus');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#0f47ad');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $fontsarr = [
        'Moodle' => 'Moodle Font',
        'Roboto' => 'Roboto',
        'Poppins' => 'Poppins',
        'Montserrat' => 'Montserrat',
        'Open Sans' => 'Open Sans',
        'Lato' => 'Lato',
        'Raleway' => 'Raleway',
        'Inter' => 'Inter',
        'Nunito' => 'Nunito',
        'Encode Sans' => 'Encode Sans',
        'Work Sans' => 'Work Sans',
        'Oxygen' => 'Oxygen',
        'Manrope' => 'Manrope',
        'Sora' => 'Sora',
        'Epilogue' => 'Epilogue',
    ];

    $name = 'theme_nexus/fontsite';
    $title = get_string('fontsite', 'theme_nexus');
    $description = get_string('fontsite_desc', 'theme_nexus');
    $setting = new admin_setting_configselect($name, $title, $description, 'Roboto', $fontsarr);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_nexus/enablecourseindex';
    $title = get_string('enablecourseindex', 'theme_nexus');
    $description = get_string('enablecourseindex_desc', 'theme_nexus');
    $default = 1;
    $choices = [0 => get_string('no'), 1 => get_string('yes')];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    $name = 'theme_nexus/enableclassicbreadcrumb';
    $title = get_string('enableclassicbreadcrumb', 'theme_nexus');
    $description = get_string('enableclassicbreadcrumb_desc', 'theme_nexus');
    $default = 0;
    $choices = [0 => get_string('no'), 1 => get_string('yes')];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    /*
    * ----------------------
    * Advanced settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_nexus_advanced', get_string('advancedsettings', 'theme_nexus'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_nexus/scsspre',
        get_string('rawscsspre', 'theme_nexus'), get_string('rawscsspre_desc', 'theme_nexus'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_nexus/scss', get_string('rawscss', 'theme_nexus'),
        get_string('rawscss_desc', 'theme_nexus'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Google analytics block.
    $name = 'theme_nexus/googleanalytics';
    $title = get_string('googleanalytics', 'theme_nexus');
    $description = get_string('googleanalyticsdesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // H5P custom CSS.
    $setting = new admin_setting_configtextarea(
        'theme_nexus/hvpcss',
        get_string('hvpcss', 'theme_nexus'),
        get_string('hvpcss_desc', 'theme_nexus'),
        '');
    $page->add($setting);

    $settings->add($page);

    /*
    * -----------------------
    * Frontpage settings tab
    * -----------------------
    */
    $page = new admin_settingpage('theme_nexus_frontpage', get_string('frontpagesettings', 'theme_nexus'));

    // Disable teachers from cards.
    $name = 'theme_nexus/disableteacherspic';
    $title = get_string('disableteacherspic', 'theme_nexus');
    $description = get_string('disableteacherspicdesc', 'theme_nexus');
    $default = 1;
    $choices = [0 => get_string('no'), 1 => get_string('yes')];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Slideshow.
    $name = 'theme_nexus/slidercount';
    $title = get_string('slidercount', 'theme_nexus');
    $description = get_string('slidercountdesc', 'theme_nexus');
    $default = 0;
    $options = [];
    for ($i = 0; $i < 13; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // If we don't have an slide yet, default to the preset.
    $slidercount = get_config('theme_nexus', 'slidercount');

    if (!$slidercount) {
        $slidercount = $default;
    }

    if ($slidercount) {
        for ($sliderindex = 1; $sliderindex <= $slidercount; $sliderindex++) {
            $fileid = 'sliderimage' . $sliderindex;
            $name = 'theme_nexus/sliderimage' . $sliderindex;
            $title = get_string('sliderimage', 'theme_nexus');
            $description = get_string('sliderimagedesc', 'theme_nexus');
            $opts = ['accepted_types' => ['.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'], 'maxfiles' => 1];
            $setting = new admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
            $page->add($setting);

            $name = 'theme_nexus/slidertitle' . $sliderindex;
            $title = get_string('slidertitle', 'theme_nexus');
            $description = get_string('slidertitledesc', 'theme_nexus');
            $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
            $page->add($setting);

            $name = 'theme_nexus/slidercap' . $sliderindex;
            $title = get_string('slidercaption', 'theme_nexus');
            $description = get_string('slidercaptiondesc', 'theme_nexus');
            $default = '';
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $page->add($setting);
        }
    }

    $setting = new admin_setting_heading('slidercountseparator', '', '<hr>');
    $page->add($setting);

    $name = 'theme_nexus/displaymarketingbox';
    $title = get_string('displaymarketingboxes', 'theme_nexus');
    $description = get_string('displaymarketingboxesdesc', 'theme_nexus');
    $default = 1;
    $choices = [0 => get_string('no'), 1 => get_string('yes')];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    $displaymarketingbox = get_config('theme_nexus', 'displaymarketingbox');

    if ($displaymarketingbox) {
        // Marketingheading.
        $name = 'theme_nexus/marketingheading';
        $title = get_string('marketingsectionheading', 'theme_nexus');
        $default = 'Awesome App Features';
        $setting = new admin_setting_configtext($name, $title, '', $default);
        $page->add($setting);

        // Marketingcontent.
        $name = 'theme_nexus/marketingcontent';
        $title = get_string('marketingsectioncontent', 'theme_nexus');
        $default = 'Moove is a Moodle template based on Boost with modern and creative design.';
        $setting = new admin_setting_confightmleditor($name, $title, '', $default);
        $page->add($setting);

        for ($i = 1; $i < 5; $i++) {
            $filearea = "marketing{$i}icon";
            $name = "theme_nexus/$filearea";
            $title = get_string('marketingicon', 'theme_nexus', $i . '');
            $opts = ['accepted_types' => ['.png', '.jpg', '.gif', '.webp', '.tiff', '.svg']];
            $setting = new admin_setting_configstoredfile($name, $title, '', $filearea, 0, $opts);
            $page->add($setting);

            $name = "theme_nexus/marketing{$i}heading";
            $title = get_string('marketingheading', 'theme_nexus', $i . '');
            $default = 'Lorem';
            $setting = new admin_setting_configtext($name, $title, '', $default);
            $page->add($setting);

            $name = "theme_nexus/marketing{$i}content";
            $title = get_string('marketingcontent', 'theme_nexus', $i . '');
            $default = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod.';
            $setting = new admin_setting_confightmleditor($name, $title, '', $default);
            $page->add($setting);
        }

        $setting = new admin_setting_heading('displaymarketingboxseparator', '', '<hr>');
        $page->add($setting);
    }

    // Enable or disable Numbers sections settings.
    $name = 'theme_nexus/numbersfrontpage';
    $title = get_string('numbersfrontpage', 'theme_nexus');
    $description = get_string('numbersfrontpagedesc', 'theme_nexus');
    $default = 1;
    $choices = [0 => get_string('no'), 1 => get_string('yes')];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    $numbersfrontpage = get_config('theme_nexus', 'numbersfrontpage');

    if ($numbersfrontpage) {
        $name = 'theme_nexus/numbersfrontpagecontent';
        $title = get_string('numbersfrontpagecontent', 'theme_nexus');
        $description = get_string('numbersfrontpagecontentdesc', 'theme_nexus');
        $default = get_string('numbersfrontpagecontentdefault', 'theme_nexus');
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);
    }

    // Enable FAQ.
    $name = 'theme_nexus/faqcount';
    $title = get_string('faqcount', 'theme_nexus');
    $description = get_string('faqcountdesc', 'theme_nexus');
    $default = 0;
    $options = [];
    for ($i = 0; $i < 11; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    $faqcount = get_config('theme_nexus', 'faqcount');

    if ($faqcount > 0) {
        for ($i = 1; $i <= $faqcount; $i++) {
            $name = "theme_nexus/faqquestion{$i}";
            $title = get_string('faqquestion', 'theme_nexus', $i . '');
            $setting = new admin_setting_configtext($name, $title, '', '');
            $page->add($setting);

            $name = "theme_nexus/faqanswer{$i}";
            $title = get_string('faqanswer', 'theme_nexus', $i . '');
            $setting = new admin_setting_confightmleditor($name, $title, '', '');
            $page->add($setting);
        }

        $setting = new admin_setting_heading('faqseparator', '', '<hr>');
        $page->add($setting);
    }

    $settings->add($page);

    /*
    * --------------------
    * Footer settings tab
    * --------------------
    */
    $page = new admin_settingpage('theme_nexus_footer', get_string('footersettings', 'theme_nexus'));

    // Website.
    $name = 'theme_nexus/website';
    $title = get_string('website', 'theme_nexus');
    $description = get_string('websitedesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Mobile.
    $name = 'theme_nexus/mobile';
    $title = get_string('mobile', 'theme_nexus');
    $description = get_string('mobiledesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Mail.
    $name = 'theme_nexus/mail';
    $title = get_string('mail', 'theme_nexus');
    $description = get_string('maildesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // TikTok url setting.
    $name = 'theme_nexus/tiktok';
    $title = get_string('tiktok', 'theme_nexus');
    $description = get_string('tiktokdesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Facebook url setting.
    $name = 'theme_nexus/facebook';
    $title = get_string('facebook', 'theme_nexus');
    $description = get_string('facebookdesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Twitter url setting.
    $name = 'theme_nexus/twitter';
    $title = get_string('twitter', 'theme_nexus');
    $description = get_string('twitterdesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Linkdin url setting.
    $name = 'theme_nexus/linkedin';
    $title = get_string('linkedin', 'theme_nexus');
    $description = get_string('linkedindesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Youtube url setting.
    $name = 'theme_nexus/youtube';
    $title = get_string('youtube', 'theme_nexus');
    $description = get_string('youtubedesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Instagram url setting.
    $name = 'theme_nexus/instagram';
    $title = get_string('instagram', 'theme_nexus');
    $description = get_string('instagramdesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Pinterest url setting.
    $name = 'theme_nexus/pinterest';
    $title = get_string('pinterest', 'theme_nexus');
    $description = get_string('pinterestdesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Whatsapp url setting.
    $name = 'theme_nexus/whatsapp';
    $title = get_string('whatsapp', 'theme_nexus');
    $description = get_string('whatsappdesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Telegram url setting.
    $name = 'theme_nexus/telegram';
    $title = get_string('telegram', 'theme_nexus');
    $description = get_string('telegramdesc', 'theme_nexus');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    $settings->add($page);
}

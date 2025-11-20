<?php

namespace theme_moove\api;

use core_external\external_api;
use core_external\external_value;
use core_external\external_single_structure;
use core_external\external_function_parameters;

/**
 * Badge criteria external api class.
 *
 * @package     theme_moove
 * @copyright   2022 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class mylearning extends external_api {
    /**
     * Get my learning parameters
     *
     * @return external_function_parameters
     */
    public static function get_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * Get my learning method
     *
     * @param integer $contextid
     *
     * @return array
     *
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    public static function get() {
        global $PAGE;

        $context = \core\context\system::instance();
        $PAGE->set_context($context);

        $mylearning = new \theme_moove\util\mylearning();

        $courses = $mylearning->get_last_accessed_courses(3);

        return [
            'courses' => json_encode($courses)
        ];
    }

    /**
     * Get my learning return fields
     *
     * @return external_single_structure
     */
    public static function get_returns() {
        return new external_single_structure([
            'courses' => new external_value(PARAM_TEXT, 'Return courses')
        ]);
    }
}

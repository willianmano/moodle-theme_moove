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
 * Custom moove extras functions
 *
 * @package    theme_moove
 * @copyright  2018 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_moove\util;

use core_competency\api as competency_api;

class extras {
    /**
     * Returns all user enrolled courses with progress
     * @return array
     */
    public static function users_courses_with_progress() {
        global $USER, $CFG;

        require_once($CFG->dirroot.'/course/renderer.php');

        $chelper = new \coursecat_helper();

        $courses = enrol_get_users_courses($USER->id, true, '*', 'visible DESC, fullname ASC, sortorder ASC');

        foreach ($courses as $course) {
            $course->fullname = strip_tags($chelper->get_course_formatted_name($course));

            $courseobj = new \core_course_list_element($course);
            $completion = new \completion_info($course);

            // First, let's make sure completion is enabled.
            if ($completion->is_enabled()) {
                $percentage = \core_completion\progress::get_course_progress_percentage($course, $USER->id);

                if (!is_null($percentage)) {
                    $percentage = floor($percentage);
                }

                if (is_null($percentage)) {
                    $percentage = 0;
                }

                // add completion data in course object
                $course->completed = $completion->is_course_complete($USER->id);
                $course->progress  = $percentage;
            }

            $course->link = $CFG->wwwroot."/course/view.php?id=".$course->id;

            // summary
            $course->summary = strip_tags($chelper->get_course_formatted_summary(
                $courseobj,
                array('overflowdiv' => false, 'noclean' => false, 'para' => false)
            ));

            $course->courseimage = self::get_course_summary_image($courseobj, $course->link);
        }

        return $courses;
    }

    /**
     * Returns the first course's summary issue
     *
     * @param stdClass $course the course object
     * @return string
     */
    public static function get_course_summary_image($course, $courselink) {
        global $CFG;

        $contentimage = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {
                $contentimage = \html_writer::link($courselink, \html_writer::empty_tag('img', array(
                    'src' => $url,
                    'alt' => $course->fullname,
                    'class' => 'card-img-top w-100')));
                break;
            }
        }

        if (empty($contentimage)) {
            $url = $CFG->wwwroot . "/theme/moove/pix/default_course.jpg";

            $contentimage = \html_writer::link($courselink, \html_writer::empty_tag('img', array(
                'src' => $url,
                'alt' => $course->fullname,
                'class' => 'card-img-top w-100')));
        }

        return $contentimage;
    }

    /**
     * Returns the user picture
     *
     * @param null $userobject
     * @param int $imgsize
     * @return \moodle_url
     * @throws \coding_exception
     */
    public static function get_user_picture($userobject = null, $imgsize = 100)
    {
        global $USER, $PAGE;

        if (!$userobject) {
            $userobject = $USER;
        }

        $userimg = new \user_picture($userobject);

        $userimg->size = $imgsize;

        return $userimg->get_url($PAGE);
    }

    /**
     * Returns an array of all user competency plans
     *
     * @return array|bool
     * @throws \coding_exception
     * @throws \required_capability_exception
     */
    public static function get_user_competency_plans() {
        global $USER;

        $plans = array_values(competency_api::list_user_plans($USER->id));

        if (empty($plans)) {
            return false;
        }

        $retorno = [];
        foreach ($plans as $plan) {
            $pclist = competency_api::list_plan_competencies($plan);

            $ucproperty = 'usercompetency';
            if ($plan->get('status') != 1) {
                $ucproperty = 'usercompetencyplan';
            }

            $proficientcount = 0;
            foreach ($pclist as $pc) {
                $usercomp = $pc->$ucproperty;

                if ($usercomp->get('proficiency')) {
                    $proficientcount++;
                }
            }

            $competencycount = count($pclist);
            $proficientcompetencypercentage = ((float) $proficientcount / (float) $competencycount) * 100.0;

            $progressclass = '';
            if ($proficientcompetencypercentage == 100) {
                $progressclass = 'bg-success';
            }

            $retorno[] = [
                'id' => $plan->get('id'),
                'name' => $plan->get('name'),
                'competencycount' => $competencycount,
                'proficientcount' => $proficientcount,
                'proficientcompetencypercentage' => $proficientcompetencypercentage,
                'progressclass' => $progressclass
            ];
        }

        return $retorno;
    }
}
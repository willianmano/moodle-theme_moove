<?php

namespace theme_moove\task;

class totalusagereadable extends \core\task\scheduled_task {
    public function get_name() {
        // Shown in admin screens
        return get_string('totalusagereadable', 'theme_moove');
    }

    public function execute() {
        global $CFG;

        $cache = \cache::make('theme_moove', 'admininfos');
        $cache->delete('totalusagereadable');

        $totalusage = get_directory_size($CFG->dataroot);
        $totalusagereadable = number_format(ceil($totalusage / 1048576));

        $cache->set('totalusagereadable', $totalusagereadable);
    }
}

<?php
namespace local_nexus\local;

use moodle_url;

class course_service {
    public static function get_recent_courses(int $limit = 4): array {
        global $CFG;

        require_once($CFG->libdir . '/enrollib.php');

        $courses = enrol_get_my_courses(
            'id, fullname, shortname, summary, summaryformat, visible',
            'lastaccess DESC, fullname ASC',
            0,
            [],
            false
        );

        $items = [];

        $courses = array_slice($courses, 0, $limit, true);

        foreach ($courses as $course) {
            $items[] = [
                'fullname' => format_string($course->fullname),
                'shortname' => format_string($course->shortname),
                'summary' => format_text($course->summary, $course->summaryformat),
                'url' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
                'hidden' => empty($course->visible),
            ];
        }

        return $items;
    }
}

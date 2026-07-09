<?php
namespace local_nexus\local;

use moodle_url;

class course_service {
    public static function get_recent_courses(int $limit = 4): array {
        global $CFG, $DB, $USER;

        require_once($CFG->libdir . '/enrollib.php');

        $courses = enrol_get_my_courses(
            'id, fullname, shortname, summary, summaryformat, visible',
            'sortorder ASC, fullname ASC',
            0,
            [],
            false
        );

        if (!$courses) {
            return [];
        }

        $courseids = array_keys($courses);
        [$insql, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'courseid');
        $params['userid'] = $USER->id;

        $lastaccessrecords = $DB->get_records_select(
            'user_lastaccess',
            "userid = :userid AND courseid $insql",
            $params,
            '',
            'courseid, timeaccess'
        );

        foreach ($courses as $course) {
            $course->nexuslastaccess = $lastaccessrecords[$course->id]->timeaccess ?? 0;
        }

        uasort($courses, static function($first, $second): int {
            if ($first->nexuslastaccess === $second->nexuslastaccess) {
                return strcasecmp($first->fullname, $second->fullname);
            }

            return $second->nexuslastaccess <=> $first->nexuslastaccess;
        });

        $items = [];
        $courses = array_slice($courses, 0, $limit, true);

        foreach ($courses as $course) {
            $items[] = [
                'fullname' => format_string($course->fullname),
                'shortname' => format_string($course->shortname),
                'summary' => format_text($course->summary, $course->summaryformat),
                'url' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
                'hidden' => empty($course->visible),
                'haslastaccess' => !empty($course->nexuslastaccess),
                'lastaccess' => !empty($course->nexuslastaccess) ? userdate($course->nexuslastaccess, get_string('strftimedate', 'langconfig')) : '',
            ];
        }

        return $items;
    }
}

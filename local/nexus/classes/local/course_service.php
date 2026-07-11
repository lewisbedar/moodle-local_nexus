<?php
namespace local_nexus\local;

use context_course;
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
            $imageurl = self::get_course_image_url((int) $course->id);
            $summary = trim(strip_tags(format_text($course->summary, $course->summaryformat)));

            $items[] = [
                'fullname' => format_string($course->fullname),
                'shortname' => format_string($course->shortname),
                'summary' => shorten_text($summary, 150),
                'hassummary' => $summary !== '',
                'url' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
                'hidden' => empty($course->visible),
                'hasimage' => $imageurl !== '',
                'imageurl' => $imageurl,
                'haslastaccess' => !empty($course->nexuslastaccess),
                'lastaccess' => !empty($course->nexuslastaccess) ? userdate($course->nexuslastaccess, get_string('strftimedate', 'langconfig')) : '',
            ];
        }

        return $items;
    }

    private static function get_course_image_url(int $courseid): string {
        $context = context_course::instance($courseid, IGNORE_MISSING);

        if (!$context) {
            return '';
        }

        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', 0, 'filename', false);

        foreach ($files as $file) {
            if (!$file->is_valid_image()) {
                continue;
            }

            return moodle_url::make_pluginfile_url(
                $context->id,
                'course',
                'overviewfiles',
                null,
                $file->get_filepath(),
                $file->get_filename(),
                false
            )->out(false);
        }

        return '';
    }
}

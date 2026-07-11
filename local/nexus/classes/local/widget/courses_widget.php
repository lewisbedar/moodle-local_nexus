<?php
namespace local_nexus\local\widget;

defined('MOODLE_INTERNAL') || die();

use local_nexus\local\course_service;
use renderer_base;

class courses_widget implements widget_interface {
    public function get_id(): string { return 'courses'; }
    public function get_name(): string { return get_string('widget_courses', 'local_nexus'); }
    public function is_available(): bool { return isloggedin() && !isguestuser(); }
    public function get_template(): string { return 'local_nexus/widgets/courses'; }

    public function export_for_template(renderer_base $output): array {
        $courses = course_service::get_recent_courses();
        return [
            'courses' => $courses,
            'hascourses' => !empty($courses),
        ];
    }
}

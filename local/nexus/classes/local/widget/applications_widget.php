<?php
namespace local_nexus\local\widget;

defined('MOODLE_INTERNAL') || die();

use local_nexus\local\application_access_service;
use local_nexus\local\application_service;
use renderer_base;

class applications_widget implements widget_interface {
    public function get_id(): string { return 'applications'; }
    public function get_name(): string { return get_string('widget_applications', 'local_nexus'); }
    public function is_available(): bool { return true; }
    public function get_template(): string { return 'local_nexus/widgets/applications'; }

    public function export_for_template(renderer_base $output): array {
        global $DB;

        $records = $DB->get_records(
            'local_nexus_applications',
            ['enabled' => 1, 'showhomepage' => 1],
            'sortorder ASC, name ASC',
            '*',
            0,
            6
        );
        $applications = [];

        foreach ($records as $app) {
            if (!application_access_service::can_view_card($app)) {
                continue;
            }

            $canopen = application_access_service::can_open($app);
            $categorylabel = application_service::get_category_label($app->category ?? '');

            $applications[] = [
                'name' => format_string($app->name),
                'slug' => $app->slug,
                'description' => format_text($app->description),
                'icon' => application_service::get_icon_url($app),
                'version' => s($app->version ?? ''),
                'category' => s($categorylabel),
                'hasversion' => !empty($app->version),
                'hascategory' => !empty($app->category),
                'locked' => !$canopen,
                'canopen' => $canopen,
            ];
        }

        return [
            'applications' => $applications,
            'hasapplications' => !empty($applications),
            'catalogurl' => '/applications',
        ];
    }
}

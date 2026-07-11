<?php
namespace local_nexus\local\widget;

defined('MOODLE_INTERNAL') || die();

use local_nexus\manager;
use renderer_base;

class dock_widget implements widget_interface {
    public function get_id(): string { return 'dock'; }
    public function get_name(): string { return get_string('widget_dock', 'local_nexus'); }
    public function is_available(): bool { return true; }
    public function get_template(): string { return 'local_nexus/widgets/dock'; }

    public function export_for_template(renderer_base $output): array {
        $applications = [];
        foreach (manager::get_applications() as $app) {
            $applications[] = [
                'name' => $app->name,
                'icon' => $app->icon,
                'url' => $app->url,
                'description' => $app->description,
                'locked' => $app->locked,
            ];
        }

        return [
            'applications' => $applications,
            'hasapplications' => !empty($applications),
            'hasdockcontrols' => count($applications) > 5,
        ];
    }
}

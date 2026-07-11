<?php
namespace local_nexus\output;

defined('MOODLE_INTERNAL') || die();

use local_nexus\local\widget\widget_manager;
use renderable;
use templatable;
use renderer_base;

class homepage implements renderable, templatable {
    public function export_for_template(renderer_base $output): array {
        $widgets = [];

        foreach (widget_manager::get_active_widgets() as $item) {
            $widget = $item['widget'];
            $data = $widget->export_for_template($output);
            $data['customtitle'] = $item['title'];

            $content = $output->render_from_template($widget->get_template(), $data);
            $widgets[] = (new widget($widget->get_id(), $item['title'], $content))->export_for_template($output);
        }

        return [
            'widgets' => $widgets,
            'haswidgets' => !empty($widgets),
        ];
    }
}

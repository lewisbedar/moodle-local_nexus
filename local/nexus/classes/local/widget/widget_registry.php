<?php
namespace local_nexus\local\widget;

defined('MOODLE_INTERNAL') || die();

class widget_registry {
    public static function get_widgets(): array {
        return [
            'hero' => new hero_widget(),
            'dock' => new dock_widget(),
            'applications' => new applications_widget(),
            'courses' => new courses_widget(),
            'news' => new news_widget(),
        ];
    }

    public static function get_widget(string $id): ?widget_interface {
        $widgets = self::get_widgets();
        return $widgets[$id] ?? null;
    }

    public static function get_ids(): array {
        return array_keys(self::get_widgets());
    }
}

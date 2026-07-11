<?php
namespace local_nexus\local\widget;

defined('MOODLE_INTERNAL') || die();

class widget_manager {
    public const CONFIG_KEY = 'homepage_widgets';

    public static function default_config(): array {
        return [
            ['id' => 'hero', 'enabled' => 1, 'sortorder' => 10, 'title' => ''],
            ['id' => 'dock', 'enabled' => 1, 'sortorder' => 20, 'title' => 'Applications'],
            ['id' => 'applications', 'enabled' => 1, 'sortorder' => 30, 'title' => 'Découvrir'],
            ['id' => 'courses', 'enabled' => 1, 'sortorder' => 40, 'title' => 'Continuer à apprendre'],
            ['id' => 'news', 'enabled' => 1, 'sortorder' => 50, 'title' => 'Quoi de neuf ?'],
        ];
    }

    public static function get_config(): array {
        $raw = get_config('local_nexus', self::CONFIG_KEY);
        $config = $raw ? json_decode($raw, true) : null;

        if (!is_array($config)) {
            return self::default_config();
        }

        return self::normalise_config($config);
    }

    public static function save_config(array $config): void {
        set_config(self::CONFIG_KEY, json_encode(self::normalise_config($config)), 'local_nexus');
    }

    public static function normalise_config(array $config): array {
        $known = widget_registry::get_ids();
        $normalised = [];

        foreach ($config as $item) {
            $id = clean_param($item['id'] ?? '', PARAM_ALPHANUMEXT);

            if (!in_array($id, $known, true)) {
                throw new \invalid_parameter_exception('Unknown Nexus homepage widget: ' . $id);
            }

            $normalised[$id] = [
                'id' => $id,
                'enabled' => !empty($item['enabled']) ? 1 : 0,
                'sortorder' => (int) ($item['sortorder'] ?? 0),
                'title' => clean_param($item['title'] ?? '', PARAM_TEXT),
            ];
        }

        foreach (self::default_config() as $default) {
            if (!isset($normalised[$default['id']])) {
                $normalised[$default['id']] = $default;
            }
        }

        uasort($normalised, static function(array $first, array $second): int {
            if ($first['sortorder'] === $second['sortorder']) {
                return strcmp($first['id'], $second['id']);
            }
            return $first['sortorder'] <=> $second['sortorder'];
        });

        $order = 10;
        foreach ($normalised as &$item) {
            $item['sortorder'] = $order;
            $order += 10;
        }
        unset($item);

        return array_values($normalised);
    }

    public static function get_active_widgets(): array {
        $widgets = widget_registry::get_widgets();
        $active = [];

        foreach (self::get_config() as $item) {
            if (empty($item['enabled']) || empty($widgets[$item['id']])) {
                continue;
            }

            $widget = $widgets[$item['id']];
            if (!$widget->is_available()) {
                continue;
            }

            $active[] = [
                'widget' => $widget,
                'title' => $item['title'],
                'sortorder' => $item['sortorder'],
            ];
        }

        return $active;
    }
}

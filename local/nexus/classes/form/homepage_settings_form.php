<?php
namespace local_nexus\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use local_nexus\local\widget\widget_manager;
use local_nexus\local\widget\widget_registry;

class homepage_settings_form extends \moodleform {
    public function definition(): void {
        $mform = $this->_form;
        $config = [];

        foreach (widget_manager::get_config() as $item) {
            $config[$item['id']] = $item;
        }

        foreach (widget_registry::get_widgets() as $id => $widget) {
            $defaults = $config[$id] ?? ['enabled' => 0, 'sortorder' => 0, 'title' => ''];
            $group = [];
            $group[] = $mform->createElement('advcheckbox', 'widget_' . $id . '_enabled', '', get_string('enabled', 'local_nexus'));
            $group[] = $mform->createElement('text', 'widget_' . $id . '_sortorder', get_string('sortorder', 'local_nexus'), ['size' => 4]);
            $group[] = $mform->createElement('text', 'widget_' . $id . '_title', get_string('customtitle', 'local_nexus'), ['size' => 32]);

            $mform->addGroup($group, 'widget_' . $id, $widget->get_name(), [' '], false);
            $mform->setType('widget_' . $id . '_sortorder', PARAM_INT);
            $mform->setType('widget_' . $id . '_title', PARAM_TEXT);
            $mform->setDefault('widget_' . $id . '_enabled', !empty($defaults['enabled']) ? 1 : 0);
            $mform->setDefault('widget_' . $id . '_sortorder', (int) $defaults['sortorder']);
            $mform->setDefault('widget_' . $id . '_title', $defaults['title']);
        }

        $this->add_action_buttons(false, get_string('savechanges'));
    }

    public function get_widget_config_from_data(\stdClass $data): array {
        $config = [];

        foreach (widget_registry::get_ids() as $id) {
            $config[] = [
                'id' => $id,
                'enabled' => !empty($data->{'widget_' . $id . '_enabled'}) ? 1 : 0,
                'sortorder' => (int) ($data->{'widget_' . $id . '_sortorder'} ?? 0),
                'title' => (string) ($data->{'widget_' . $id . '_title'} ?? ''),
            ];
        }

        return widget_manager::normalise_config($config);
    }
}

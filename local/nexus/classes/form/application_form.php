<?php
namespace local_nexus\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class application_form extends \moodleform {
    public function definition(): void {
        $mform = $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', 'Nom');
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required');

        $mform->addElement('text', 'slug', 'Slug');
        $mform->setType('slug', PARAM_ALPHANUMEXT);
        $mform->addRule('slug', null, 'required');

        $mform->addElement('textarea', 'description', 'Description', [
            'rows' => 3,
            'cols' => 60
        ]);
        $mform->setType('description', PARAM_TEXT);

        $mform->addElement('text', 'icon', 'URL de l’icône');
        $mform->setType('icon', PARAM_RAW);

        $mform->addElement('text', 'url', 'URL de l’application');
        $mform->setType('url', PARAM_URL);
        $mform->addRule('url', null, 'required');

        $mform->addElement('select', 'visibility', 'Visibilité', [
            'public' => 'Public',
            'members' => 'Adhérents',
            'admin' => 'Administration'
        ]);
        $mform->setDefault('visibility', 'public');

        $mform->addElement('text', 'sortorder', 'Ordre');
        $mform->setType('sortorder', PARAM_INT);
        $mform->setDefault('sortorder', 10);

        $mform->addElement('advcheckbox', 'enabled', 'Activée');
        $mform->setDefault('enabled', 1);

        $this->add_action_buttons();
    }
}
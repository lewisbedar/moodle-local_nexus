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

        $mform->addElement('text', 'icon', get_string('iconurl', 'local_nexus'));
        $mform->setType('icon', PARAM_RAW);

        $mform->addElement(
            'filemanager',
            'iconfile',
            get_string('iconfile', 'local_nexus'),
            null,
            \local_nexus\local\application_service::filemanager_options()
        );

        $mform->addElement('text', 'url', 'URL de l’application');
        $mform->setType('url', PARAM_URL);
        $mform->addRule('url', null, 'required');

        $mform->addElement('select', 'visibility', get_string('visibility', 'local_nexus'), [
            'public' => get_string('visibility_public', 'local_nexus'),
            'members' => get_string('visibility_members', 'local_nexus'),
            'admin' => get_string('visibility_admin', 'local_nexus')
        ]);
        $mform->setDefault('visibility', 'public');

        $mform->addElement('text', 'version', get_string('version', 'local_nexus'));
        $mform->setType('version', PARAM_TEXT);

        $mform->addElement('select', 'status', get_string('status', 'local_nexus'), [
            'stable' => get_string('status_stable', 'local_nexus'),
            'beta' => get_string('status_beta', 'local_nexus'),
            'alpha' => get_string('status_alpha', 'local_nexus')
        ]);
        $mform->setDefault('status', 'stable');

        $mform->addElement('text', 'category', get_string('category', 'local_nexus'));
        $mform->setType('category', PARAM_TEXT);

        $mform->addElement('text', 'color', get_string('color', 'local_nexus'));
        $mform->setType('color', PARAM_TEXT);

        $mform->addElement('advcheckbox', 'showdock', get_string('showdock', 'local_nexus'));
        $mform->setDefault('showdock', 1);

        $mform->addElement('advcheckbox', 'showhomepage', get_string('showhomepage', 'local_nexus'));
        $mform->setDefault('showhomepage', 1);

        $mform->addElement('text', 'sortorder', 'Ordre');
        $mform->setType('sortorder', PARAM_INT);
        $mform->setDefault('sortorder', 10);

        $mform->addElement('advcheckbox', 'enabled', 'Activée');
        $mform->setDefault('enabled', 1);

        $this->add_action_buttons();
    }
}

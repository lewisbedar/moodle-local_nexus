<?php
namespace local_nexus\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class news_form extends \moodleform {
    public function definition(): void {
        $mform = $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'title', get_string('newstitle', 'local_nexus'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required');

        $mform->addElement('editor', 'summary_editor', get_string('newssummary', 'local_nexus'), null, [
            'maxfiles' => 0,
            'noclean' => false,
            'trusttext' => false,
        ]);
        $mform->setType('summary_editor', PARAM_RAW);

        $mform->addElement('text', 'url', get_string('newsurl', 'local_nexus'));
        $mform->setType('url', PARAM_URL);

        $mform->addElement('text', 'sortorder', get_string('sortorder', 'local_nexus'));
        $mform->setType('sortorder', PARAM_INT);
        $mform->setDefault('sortorder', 10);

        $mform->addElement('advcheckbox', 'published', get_string('published', 'local_nexus'));
        $mform->setDefault('published', 1);

        $this->add_action_buttons();
    }
}

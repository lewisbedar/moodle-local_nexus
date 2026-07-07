<?php
namespace local_nexus\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class hero_settings_form extends \moodleform {
    public function definition(): void {
        $mform = $this->_form;

        $mform->addElement('text', 'hero_title', get_string('herotitle', 'local_nexus'));
        $mform->setType('hero_title', PARAM_TEXT);
        $mform->addRule('hero_title', null, 'required');

        $mform->addElement('textarea', 'hero_subtitle', get_string('herosubtitle', 'local_nexus'), ['rows' => 3, 'cols' => 60]);
        $mform->setType('hero_subtitle', PARAM_TEXT);
        $mform->addRule('hero_subtitle', null, 'required');

        $mform->addElement('text', 'hero_primary_label', get_string('heroprimarylabel', 'local_nexus'));
        $mform->setType('hero_primary_label', PARAM_TEXT);
        $mform->addRule('hero_primary_label', null, 'required');

        $mform->addElement('text', 'hero_primary_url', get_string('heroprimaryurl', 'local_nexus'));
        $mform->setType('hero_primary_url', PARAM_URL);
        $mform->addRule('hero_primary_url', null, 'required');

        $mform->addElement('text', 'hero_secondary_label', get_string('herosecondarylabel', 'local_nexus'));
        $mform->setType('hero_secondary_label', PARAM_TEXT);
        $mform->addRule('hero_secondary_label', null, 'required');

        $mform->addElement('text', 'hero_secondary_url', get_string('herosecondaryurl', 'local_nexus'));
        $mform->setType('hero_secondary_url', PARAM_URL);
        $mform->addRule('hero_secondary_url', null, 'required');

        $this->add_action_buttons(false, get_string('savechanges'));
    }
}

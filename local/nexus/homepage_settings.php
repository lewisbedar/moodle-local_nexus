<?php
require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/nexus:managehomepage', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/homepage_settings.php'));
$PAGE->set_title(get_string('homepagesettings', 'local_nexus'));
$PAGE->set_heading(get_string('homepagesettings', 'local_nexus'));
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

$mform = new \local_nexus\form\homepage_settings_form();

if ($data = $mform->get_data()) {
    \local_nexus\local\widget\widget_manager::save_config($mform->get_widget_config_from_data($data));
    redirect(new moodle_url('/local/nexus/homepage_settings.php'), get_string('settingssaved', 'local_nexus'));
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('homepagesettings', 'local_nexus'));
$mform->display();
echo $OUTPUT->footer();

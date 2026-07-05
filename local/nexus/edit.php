<?php
require_once(__DIR__ . '/../../config.php');

require_login();
require_capability('moodle/site:config', context_system::instance());

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/nexus/edit.php', ['id' => $id]));
$PAGE->set_title('Nexus - Modifier une application');
$PAGE->set_heading('Nexus - Modifier une application');

$mform = new \local_nexus\form\application_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/nexus/applications.php'));
}

if ($data = $mform->get_data()) {
    $record = new stdClass();

    $record->name = $data->name;
    $record->slug = $data->slug;
    $record->description = $data->description ?? '';
    $record->icon = $data->icon ?? '';
    $record->url = $data->url;
    $record->visibility = $data->visibility;
    $record->sortorder = (int) $data->sortorder;
    $record->enabled = !empty($data->enabled) ? 1 : 0;
    $record->timemodified = time();

    if (!empty($data->id)) {
        $record->id = $data->id;
        $DB->update_record('local_nexus_applications', $record);
        redirect(new moodle_url('/local/nexus/applications.php'), 'Application mise à jour.');
    } else {
        $record->timecreated = time();
        $DB->insert_record('local_nexus_applications', $record);
        redirect(new moodle_url('/local/nexus/applications.php'), 'Application ajoutée.');
    }
}

if ($id) {
    $app = $DB->get_record('local_nexus_applications', ['id' => $id], '*', MUST_EXIST);
    $mform->set_data($app);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
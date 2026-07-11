<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');

require_login();
$context = context_system::instance();
require_capability('local/nexus:manageapps', $context);

$id = optional_param('id', 0, PARAM_INT);
$filemanageroptions = \local_nexus\local\application_service::filemanager_options();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/edit.php', ['id' => $id]));
$PAGE->set_title(get_string('editapplication', 'local_nexus'));
$PAGE->set_heading(get_string('editapplication', 'local_nexus'));
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

$mform = new \local_nexus\form\application_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/nexus/manage_apps.php'));
}

if ($data = $mform->get_data()) {
    $record = new stdClass();

    $record->name = $data->name;
    $record->slug = $data->slug;
    $record->description = $data->description ?? '';
    $record->icon = $data->icon ?? '';
    $record->url = $data->url;
    $record->visibility = $data->visibility;
    $record->version = $data->version ?? '';
    $record->status = $data->status ?? 'stable';
    $record->category = $data->category ?? '';
    $record->color = $data->color ?? '';
    $record->showdock = !empty($data->showdock) ? 1 : 0;
    $record->showhomepage = !empty($data->showhomepage) ? 1 : 0;
    $record->sortorder = (int) $data->sortorder;
    $record->enabled = !empty($data->enabled) ? 1 : 0;
    $record->timemodified = time();

    if (!empty($data->id)) {
        $record->id = $data->id;
        $DB->update_record('local_nexus_applications', $record);
        file_save_draft_area_files(
            $data->iconfile,
            $context->id,
            'local_nexus',
            'appicon',
            $record->id,
            $filemanageroptions
        );
        redirect(new moodle_url('/local/nexus/manage_apps.php'), get_string('applicationupdated', 'local_nexus'));
    } else {
        $record->timecreated = time();
        $record->id = $DB->insert_record('local_nexus_applications', $record);
        file_save_draft_area_files(
            $data->iconfile,
            $context->id,
            'local_nexus',
            'appicon',
            $record->id,
            $filemanageroptions
        );
        redirect(new moodle_url('/local/nexus/manage_apps.php'), get_string('applicationadded', 'local_nexus'));
    }
}

if ($id) {
    $app = $DB->get_record('local_nexus_applications', ['id' => $id], '*', MUST_EXIST);
    $draftitemid = file_get_submitted_draft_itemid('iconfile');
    file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'local_nexus',
        'appicon',
        $app->id,
        $filemanageroptions
    );
    $app->iconfile = $draftitemid;
    $mform->set_data($app);
} else {
    $draftitemid = file_get_submitted_draft_itemid('iconfile');
    file_prepare_draft_area($draftitemid, $context->id, 'local_nexus', 'appicon', 0, $filemanageroptions);
    $mform->set_data(['iconfile' => $draftitemid]);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();

<?php
require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$manageurl = new moodle_url('/local/nexus/manage_apps.php');

$PAGE->set_context($context);
$PAGE->set_url($manageurl);
$PAGE->set_title(get_string('manageapplications', 'local_nexus'));
$PAGE->set_heading(get_string('manageapplications', 'local_nexus'));
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

$delete = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if ($delete && confirm_sesskey()) {
    if ($confirm) {
        $DB->delete_records('local_nexus_applications', ['id' => $delete]);
        redirect($manageurl, get_string('applicationdeleted', 'local_nexus'));
    }

    echo $OUTPUT->header();
    echo $OUTPUT->confirm(
        get_string('confirmdeleteapplication', 'local_nexus'),
        new moodle_url('/local/nexus/manage_apps.php', [
            'delete' => $delete,
            'confirm' => 1,
            'sesskey' => sesskey(),
        ]),
        $manageurl
    );
    echo $OUTPUT->footer();
    exit;
}

$apps = $DB->get_records('local_nexus_applications', null, 'sortorder ASC, name ASC');
$applications = [];

foreach ($apps as $app) {
    $applications[] = [
        'name' => format_string($app->name),
        'version' => s($app->version ?? ''),
        'status' => get_string('status_' . ($app->status ?? 'stable'), 'local_nexus'),
        'category' => s($app->category ?? ''),
        'visibility' => s($app->visibility),
        'sortorder' => (int) $app->sortorder,
        'enabledlabel' => $app->enabled ? get_string('enabled', 'local_nexus') : get_string('disabled', 'local_nexus'),
        'showdocklabel' => !empty($app->showdock) ? get_string('yes') : get_string('no'),
        'showhomepagelabel' => !empty($app->showhomepage) ? get_string('yes') : get_string('no'),
        'editurl' => (new moodle_url('/local/nexus/edit.php', ['id' => $app->id]))->out(false),
        'deleteurl' => (new moodle_url('/local/nexus/manage_apps.php', [
            'delete' => $app->id,
            'sesskey' => sesskey(),
        ]))->out(false),
    ];
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_nexus/manage_apps', [
    'addurl' => (new moodle_url('/local/nexus/edit.php'))->out(false),
    'applications' => $applications,
]);
echo $OUTPUT->footer();

<?php
require_once(__DIR__ . '/../../config.php');

require_login();
require_capability('moodle/site:config', context_system::instance());

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/nexus/applications.php'));
$PAGE->set_title('Nexus - Applications');
$PAGE->set_heading('Nexus - Applications');

$delete = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if ($delete && confirm_sesskey()) {
    if ($confirm) {
        $DB->delete_records('local_nexus_applications', ['id' => $delete]);
        redirect(new moodle_url('/local/nexus/applications.php'), 'Application supprimée.');
    }

    echo $OUTPUT->header();
    echo $OUTPUT->confirm(
        'Supprimer cette application ?',
        new moodle_url('/local/nexus/applications.php', [
            'delete' => $delete,
            'confirm' => 1,
            'sesskey' => sesskey()
        ]),
        new moodle_url('/local/nexus/applications.php')
    );
    echo $OUTPUT->footer();
    exit;
}

$apps = $DB->get_records('local_nexus_applications', null, 'sortorder ASC, name ASC');

echo $OUTPUT->header();

echo html_writer::link(
    new moodle_url('/local/nexus/edit.php'),
    'Ajouter une application',
    ['class' => 'btn btn-primary mb-3']
);

$table = new html_table();
$table->head = ['Nom', 'Visibilité', 'Ordre', 'État', 'Actions'];

foreach ($apps as $app) {
    $editurl = new moodle_url('/local/nexus/edit.php', ['id' => $app->id]);
    $deleteurl = new moodle_url('/local/nexus/applications.php', [
        'delete' => $app->id,
        'sesskey' => sesskey()
    ]);

    $table->data[] = [
        format_string($app->name),
        s($app->visibility),
        (int) $app->sortorder,
        $app->enabled ? 'Activée' : 'Désactivée',
        html_writer::link($editurl, 'Modifier') . ' | ' .
        html_writer::link($deleteurl, 'Supprimer')
    ];
}

echo html_writer::table($table);

echo $OUTPUT->footer();
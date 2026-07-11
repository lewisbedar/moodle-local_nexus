<?php
require_once(__DIR__ . '/../../config.php');

require_login();

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/index.php'));
$PAGE->set_title(get_string('dashboard', 'local_nexus'));
$PAGE->set_heading(get_string('dashboard', 'local_nexus'));
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));
$PAGE->requires->js(new moodle_url('/local/nexus/dock.js'));

echo $OUTPUT->header();

echo $OUTPUT->render_from_template(
    'local_nexus/homepage',
    (new \local_nexus\output\homepage())->export_for_template($OUTPUT)
);

echo $OUTPUT->footer();

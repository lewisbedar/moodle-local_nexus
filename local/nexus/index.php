<?php
require_once(__DIR__ . '/../../config.php');

require_login();

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/index.php'));
$PAGE->set_title('Nexus');
$PAGE->set_heading('Nexus');
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

echo $OUTPUT->header();

echo $OUTPUT->render_from_template(
    'local_nexus/dashboard',
    (new \local_nexus\output\dashboard())->export_for_template($OUTPUT)
);

echo $OUTPUT->footer();

<?php
require_once(__DIR__ . '/../../config.php');

require_login();

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/catalog.php'));
$PAGE->set_title('Applications');
$PAGE->set_heading('Applications');

$apps = $DB->get_records(
    'local_nexus_applications',
    ['enabled' => 1],
    'sortorder ASC, name ASC'
);

$applications = [];

foreach ($apps as $app) {
    $applications[] = [
        'name' => format_string($app->name),
		'slug' => $app->slug,
        'description' => format_text($app->description),
        'icon' => $app->icon,
        'url' => $app->url,
        'visibility' => $app->visibility,
        'locked' => $app->visibility !== 'public',
    ];
}

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_nexus/catalog', [
    'applications' => $applications,
]);

echo $OUTPUT->footer();
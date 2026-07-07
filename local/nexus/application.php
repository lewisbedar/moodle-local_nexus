<?php
require_once(__DIR__ . '/../../config.php');

require_login();

$slug = required_param('slug', PARAM_ALPHANUMEXT);

$context = context_system::instance();

$app = $DB->get_record('local_nexus_applications', [
    'slug' => $slug,
    'enabled' => 1
], '*', MUST_EXIST);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/application.php', ['slug' => $slug]));
$PAGE->set_title(format_string($app->name));
$PAGE->set_heading(format_string($app->name));
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

$data = [
    'name' => format_string($app->name),
    'description' => format_text($app->description),
    'icon' => $app->icon,
    'url' => $app->url,
    'visibility' => $app->visibility,
    'version' => s($app->version ?? ''),
    'status' => get_string('status_' . ($app->status ?? 'stable'), 'local_nexus'),
    'category' => s($app->category ?? ''),
    'color' => s($app->color ?? ''),
    'hasversion' => !empty($app->version),
    'hascategory' => !empty($app->category),
    'hascolor' => !empty($app->color),
    'locked' => $app->visibility !== 'public',
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_nexus/application', $data);
echo $OUTPUT->footer();

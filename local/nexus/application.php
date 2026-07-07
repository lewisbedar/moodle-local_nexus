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

$accesslabel = $app->visibility !== 'public'
    ? get_string('restrictedaccess', 'local_nexus')
    : get_string('publicaccess', 'local_nexus');

$metaitems = [
    [
        'label' => get_string('access', 'local_nexus'),
        'value' => $accesslabel,
    ],
    [
        'label' => get_string('status', 'local_nexus'),
        'value' => get_string('status_' . ($app->status ?? 'stable'), 'local_nexus'),
    ],
];

if (!empty($app->version)) {
    $metaitems[] = [
        'label' => get_string('version', 'local_nexus'),
        'value' => s($app->version),
    ];
}

if (!empty($app->category)) {
    $metaitems[] = [
        'label' => get_string('category', 'local_nexus'),
        'value' => s($app->category),
    ];
}

$data = [
    'name' => format_string($app->name),
    'description' => format_text($app->description),
    'icon' => \local_nexus\local\application_service::get_icon_url($app),
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
    'accesslabel' => $accesslabel,
    'metaitems' => $metaitems,
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_nexus/application', $data);
echo $OUTPUT->footer();

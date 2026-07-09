<?php
require_once(__DIR__ . '/../../config.php');

require_login();

$context = context_system::instance();
$selectedcategory = optional_param('category', '', PARAM_ALPHANUMEXT);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/catalog.php', ['category' => $selectedcategory ?: null]));
$PAGE->set_title('Applications');
$PAGE->set_heading('Applications');
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

$apps = $DB->get_records(
    'local_nexus_applications',
    ['enabled' => 1],
    'sortorder ASC, name ASC'
);

$categorygroups = [];

foreach ($apps as $app) {
    $categorylabel = \local_nexus\local\application_service::get_category_label($app->category ?? '');
    $categoryslug = \local_nexus\local\application_service::get_category_slug($app->category ?? '');

    if (!isset($categorygroups[$categoryslug])) {
        $categorygroups[$categoryslug] = [
            'slug' => $categoryslug,
            'label' => s($categorylabel),
            'url' => '/applications?category=' . rawurlencode($categoryslug),
            'active' => $selectedcategory === $categoryslug,
            'applications' => [],
        ];
    }

    $categorygroups[$categoryslug]['applications'][] = [
        'name' => format_string($app->name),
        'slug' => $app->slug,
        'description' => format_text($app->description),
        'icon' => \local_nexus\local\application_service::get_icon_url($app),
        'url' => $app->url,
        'visibility' => $app->visibility,
        'version' => s($app->version ?? ''),
        'status' => get_string('status_' . ($app->status ?? 'stable'), 'local_nexus'),
        'category' => s($categorylabel),
        'color' => s($app->color ?? ''),
        'hasversion' => !empty($app->version),
        'hascategory' => !empty($app->category),
        'hascolor' => !empty($app->color),
        'locked' => $app->visibility !== 'public',
    ];
}

$categories = array_values($categorygroups);
$sections = [];

foreach ($categories as $category) {
    if ($selectedcategory && $category['slug'] !== $selectedcategory) {
        continue;
    }

    $sections[] = $category;
}

$heroiconurl = \local_nexus\local\hero_service::get_file_url(\local_nexus\local\hero_service::FILEAREA_ICON);
$herologourl = \local_nexus\local\hero_service::get_file_url(\local_nexus\local\hero_service::FILEAREA_LOGO);

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_nexus/catalog', [
    'hero' => [
        'iconurl' => $heroiconurl,
        'logourl' => $herologourl,
        'hasicon' => $heroiconurl !== '',
        'haslogo' => $herologourl !== '',
    ],
    'allcategoriesurl' => '/applications',
    'allcategoriesactive' => $selectedcategory === '',
    'categories' => $categories,
    'hascategories' => !empty($categories),
    'sections' => $sections,
    'hassections' => !empty($sections),
]);

echo $OUTPUT->footer();

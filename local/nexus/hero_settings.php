<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/hero_settings.php'));
$PAGE->set_title(get_string('herosettings', 'local_nexus'));
$PAGE->set_heading(get_string('herosettings', 'local_nexus'));
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

$fileoptions = \local_nexus\local\hero_service::filemanager_options();
$mform = new \local_nexus\form\hero_settings_form();

if ($data = $mform->get_data()) {
    foreach (['hero_title', 'hero_subtitle', 'hero_primary_label', 'hero_primary_url', 'hero_secondary_label', 'hero_secondary_url'] as $name) {
        set_config($name, $data->{$name}, 'local_nexus');
    }

    file_save_draft_area_files(
        $data->hero_icon,
        $context->id,
        'local_nexus',
        \local_nexus\local\hero_service::FILEAREA_ICON,
        0,
        $fileoptions
    );
    file_save_draft_area_files(
        $data->hero_logo,
        $context->id,
        'local_nexus',
        \local_nexus\local\hero_service::FILEAREA_LOGO,
        0,
        $fileoptions
    );

    set_config('nexus_as_home', !empty($data->nexus_as_home) ? 1 : 0, 'local_nexus');

    redirect(new moodle_url('/local/nexus/hero_settings.php'), get_string('settingssaved', 'local_nexus'));
}

$config = get_config('local_nexus');
$heroicondraftitemid = file_get_submitted_draft_itemid('hero_icon');
file_prepare_draft_area(
    $heroicondraftitemid,
    $context->id,
    'local_nexus',
    \local_nexus\local\hero_service::FILEAREA_ICON,
    0,
    $fileoptions
);
$herologodraftitemid = file_get_submitted_draft_itemid('hero_logo');
file_prepare_draft_area(
    $herologodraftitemid,
    $context->id,
    'local_nexus',
    \local_nexus\local\hero_service::FILEAREA_LOGO,
    0,
    $fileoptions
);

$mform->set_data([
    'hero_title' => $config->hero_title ?? 'Nexus',
    'hero_subtitle' => $config->hero_subtitle ?? 'Le portail des outils, ressources et formations de Flux Croisés.',
    'hero_primary_label' => $config->hero_primary_label ?? 'Mes formations',
    'hero_primary_url' => $config->hero_primary_url ?? '/my/courses.php',
    'hero_secondary_label' => $config->hero_secondary_label ?? 'Documentation',
    'hero_secondary_url' => $config->hero_secondary_url ?? 'https://www.docs.flux-croises.fr',
    'hero_icon' => $heroicondraftitemid,
    'hero_logo' => $herologodraftitemid,
    'nexus_as_home' => !empty($config->nexus_as_home),
]);

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();

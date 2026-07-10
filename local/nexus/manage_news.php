<?php
require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$manageurl = new moodle_url('/local/nexus/manage_news.php');

$PAGE->set_context($context);
$PAGE->set_url($manageurl);
$PAGE->set_title(get_string('managenews', 'local_nexus'));
$PAGE->set_heading(get_string('managenews', 'local_nexus'));
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

$delete = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if ($delete && confirm_sesskey()) {
    if ($confirm) {
        $DB->delete_records('local_nexus_news', ['id' => $delete]);
        get_file_storage()->delete_area_files(
            $context->id,
            'local_nexus',
            \local_nexus\local\news_service::FILEAREA_IMAGE,
            $delete
        );
        redirect($manageurl, get_string('newsdeleted', 'local_nexus'));
    }

    echo $OUTPUT->header();
    echo $OUTPUT->confirm(
        get_string('confirmdeletenews', 'local_nexus'),
        new moodle_url('/local/nexus/manage_news.php', ['delete' => $delete, 'confirm' => 1, 'sesskey' => sesskey()]),
        $manageurl
    );
    echo $OUTPUT->footer();
    exit;
}

$records = $DB->get_records('local_nexus_news', null, 'sortorder ASC, timemodified DESC');
$news = [];

foreach ($records as $record) {
    $imageurl = \local_nexus\local\news_service::get_image_url((int) $record->id);

    $news[] = [
        'title' => format_string($record->title),
        'imageurl' => $imageurl,
        'hasimage' => $imageurl !== '',
        'summary' => format_text($record->summary, FORMAT_HTML),
        'sortorder' => (int) $record->sortorder,
        'publishedlabel' => $record->published ? get_string('yes') : get_string('no'),
        'editurl' => (new moodle_url('/local/nexus/edit_news.php', ['id' => $record->id]))->out(false),
        'deleteurl' => (new moodle_url('/local/nexus/manage_news.php', ['delete' => $record->id, 'sesskey' => sesskey()]))->out(false),
    ];
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_nexus/manage_news', [
    'addurl' => (new moodle_url('/local/nexus/edit_news.php'))->out(false),
    'news' => $news,
]);
echo $OUTPUT->footer();

<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/nexus/edit_news.php', ['id' => $id]));
$PAGE->set_title(get_string('editnews', 'local_nexus'));
$PAGE->set_heading(get_string('editnews', 'local_nexus'));
$PAGE->requires->css(new moodle_url('/local/nexus/styles.css'));

$fileoptions = \local_nexus\local\news_service::image_filemanager_options();
$mform = new \local_nexus\form\news_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/nexus/manage_news.php'));
}

if ($data = $mform->get_data()) {
    $record = new stdClass();
    $record->title = $data->title;
    $record->summary = $data->summary_editor['text'] ?? '';
    $record->url = $data->url ?? '';
    $record->sortorder = (int) $data->sortorder;
    $record->published = !empty($data->published) ? 1 : 0;
    $record->timemodified = time();

    if (!empty($data->id)) {
        $record->id = $data->id;
        $DB->update_record('local_nexus_news', $record);
        file_save_draft_area_files(
            $data->image,
            $context->id,
            'local_nexus',
            \local_nexus\local\news_service::FILEAREA_IMAGE,
            $record->id,
            $fileoptions
        );
        redirect(new moodle_url('/local/nexus/manage_news.php'), get_string('newsupdated', 'local_nexus'));
    }

    $record->timecreated = time();
    $record->id = $DB->insert_record('local_nexus_news', $record);
    file_save_draft_area_files(
        $data->image,
        $context->id,
        'local_nexus',
        \local_nexus\local\news_service::FILEAREA_IMAGE,
        $record->id,
        $fileoptions
    );
    redirect(new moodle_url('/local/nexus/manage_news.php'), get_string('newsadded', 'local_nexus'));
}

if ($id) {
    $news = $DB->get_record('local_nexus_news', ['id' => $id], '*', MUST_EXIST);
    $draftitemid = file_get_submitted_draft_itemid('image');
    file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'local_nexus',
        \local_nexus\local\news_service::FILEAREA_IMAGE,
        $id,
        $fileoptions
    );
    $news->summary_editor = [
        'text' => $news->summary ?? '',
        'format' => FORMAT_HTML,
    ];
    $news->image = $draftitemid;
    $mform->set_data($news);
} else {
    $draftitemid = file_get_submitted_draft_itemid('image');
    file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'local_nexus',
        \local_nexus\local\news_service::FILEAREA_IMAGE,
        0,
        $fileoptions
    );
    $mform->set_data(['image' => $draftitemid]);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();

<?php
defined('MOODLE_INTERNAL') || die();

function local_nexus_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel !== CONTEXT_SYSTEM || $filearea !== 'appicon') {
        return false;
    }

    require_login();

    $itemid = array_shift($args);
    $filename = array_pop($args);

    if (!$itemid || !$filename) {
        return false;
    }

    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_nexus', $filearea, (int) $itemid, $filepath, $filename);

    if (!$file || $file->is_directory()) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
    return true;
}

function local_nexus_before_http_headers() {
    global $CFG;

    if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
        return;
    }

    $config = get_config('local_nexus');

    if (empty($config->nexus_as_home) || headers_sent()) {
        return;
    }

    $scriptfile = $_SERVER['SCRIPT_FILENAME'] ?? '';
    $frontpage = $CFG->dirroot . '/index.php';
    $dashboard = $CFG->dirroot . '/my/index.php';

    if ($scriptfile === $frontpage || $scriptfile === $dashboard) {
        redirect(new moodle_url('/local/nexus/index.php'));
    }
}

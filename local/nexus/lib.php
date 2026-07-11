<?php
defined('MOODLE_INTERNAL') || die();

function local_nexus_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    $allowedareas = ['appicon', 'heroicon', 'heroiconhome', 'heroiconcatalog', 'heroiconapplication', 'herologo', 'newsimage'];

    if ($context->contextlevel !== CONTEXT_SYSTEM || !in_array($filearea, $allowedareas, true)) {
        return false;
    }

    require_login();

    $itemid = array_shift($args);
    $filename = array_pop($args);

    if ($itemid === null || $itemid === '' || !$filename) {
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
    local_nexus_redirect_home_requests();
}

function local_nexus_after_config() {
    local_nexus_redirect_home_requests();
}

function local_nexus_redirect_home_requests(): void {
    global $CFG;

    if ((defined('CLI_SCRIPT') && CLI_SCRIPT) || (defined('AJAX_SCRIPT') && AJAX_SCRIPT)) {
        return;
    }

    if (headers_sent()) {
        return;
    }

    $config = get_config('local_nexus');

    if (empty($config->nexus_as_home)) {
        return;
    }

    $scriptfile = $_SERVER['SCRIPT_FILENAME'] ?? '';
    $frontpage = $CFG->dirroot . '/index.php';
    $dashboard = $CFG->dirroot . '/my/index.php';

    if ($scriptfile === $frontpage || $scriptfile === $dashboard) {
        redirect(new moodle_url('/local/nexus/index.php'));
    }
}

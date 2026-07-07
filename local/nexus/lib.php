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

<?php
namespace local_nexus\local;

defined('MOODLE_INTERNAL') || die();

use context_system;
use moodle_url;

class hero_service {
    public const FILEAREA_ICON = 'heroicon';
    public const FILEAREA_LOGO = 'herologo';

    public static function filemanager_options(): array {
        return [
            'subdirs' => 0,
            'maxbytes' => 2 * 1024 * 1024,
            'maxfiles' => 1,
            'accepted_types' => ['web_image'],
            'return_types' => FILE_INTERNAL,
        ];
    }

    public static function get_file_url(string $filearea): string {
        if (!in_array($filearea, [self::FILEAREA_ICON, self::FILEAREA_LOGO], true)) {
            return '';
        }

        $context = context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'local_nexus', $filearea, 0, 'filename', false);

        foreach ($files as $file) {
            if (!$file->is_valid_image()) {
                continue;
            }

            return moodle_url::make_pluginfile_url(
                $context->id,
                'local_nexus',
                $filearea,
                0,
                $file->get_filepath(),
                $file->get_filename(),
                false
            )->out(false);
        }

        return '';
    }
}

<?php
namespace local_nexus\local;

use context_system;
use moodle_url;

class news_service {
    public const FILEAREA_IMAGE = 'newsimage';

    public static function image_filemanager_options(): array {
        return [
            'subdirs' => 0,
            'maxbytes' => 4 * 1024 * 1024,
            'maxfiles' => 1,
            'accepted_types' => ['web_image'],
        ];
    }

    public static function get_latest(int $limit = 3): array {
        global $DB;

        return $DB->get_records(
            'local_nexus_news',
            ['published' => 1],
            'sortorder ASC, timemodified DESC',
            '*',
            0,
            $limit
        );
    }

    public static function get_image_url(int $newsid): string {
        $context = context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'local_nexus', self::FILEAREA_IMAGE, $newsid, 'filename', false);

        foreach ($files as $file) {
            if (!$file->is_valid_image()) {
                continue;
            }

            return moodle_url::make_pluginfile_url(
                $context->id,
                'local_nexus',
                self::FILEAREA_IMAGE,
                $newsid,
                $file->get_filepath(),
                $file->get_filename(),
                false
            )->out(false);
        }

        return '';
    }
}

<?php
namespace local_nexus\local;

defined('MOODLE_INTERNAL') || die();

use context_system;
use moodle_url;

class hero_service {
    public const FILEAREA_ICON = 'heroicon';
    public const FILEAREA_HOME_ICON = 'heroiconhome';
    public const FILEAREA_CATALOG_ICON = 'heroiconcatalog';
    public const FILEAREA_APPLICATION_ICON = 'heroiconapplication';
    public const FILEAREA_LOGO = 'herologo';

    public static function filemanager_options(): array {
        return [
            'subdirs' => 0,
            'maxbytes' => 2 * 1024 * 1024,
            'maxfiles' => 1,
            'accepted_types' => ['web_image'],
        ];
    }

    public static function get_icon_filearea(string $page): string {
        return match ($page) {
            'catalog' => self::FILEAREA_CATALOG_ICON,
            'application' => self::FILEAREA_APPLICATION_ICON,
            default => self::FILEAREA_HOME_ICON,
        };
    }

    public static function get_icon_url(string $page): string {
        $url = self::get_file_url(self::get_icon_filearea($page));

        if ($url === '' && $page === 'home') {
            return self::get_file_url(self::FILEAREA_ICON);
        }

        return $url;
    }

    public static function get_file_url(string $filearea): string {
        $allowedfileareas = [
            self::FILEAREA_ICON,
            self::FILEAREA_HOME_ICON,
            self::FILEAREA_CATALOG_ICON,
            self::FILEAREA_APPLICATION_ICON,
            self::FILEAREA_LOGO,
        ];

        if (!in_array($filearea, $allowedfileareas, true)) {
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

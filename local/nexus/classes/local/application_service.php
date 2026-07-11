<?php
namespace local_nexus\local;

defined('MOODLE_INTERNAL') || die();

use context_system;
use local_nexus\persistent\application;
use moodle_url;

class application_service {

    public static function get_all(): array {

        return application::get_records([
            'enabled' => 1
        ]);

    }


    public static function get_category_label(?string $category): string {
        $category = trim((string) $category);

        return $category !== '' ? $category : get_string('uncategorized', 'local_nexus');
    }

    public static function get_category_slug(?string $category): string {
        $category = self::get_category_label($category);
        $slug = strtolower(trim($category));
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug) ?: $slug;
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : 'sans-categorie';
    }

    public static function filemanager_options(): array {
        return [
            'subdirs' => 0,
            'maxbytes' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.webp'],
        ];
    }

    public static function get_icon_url(object $application): string {
        $context = context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $context->id,
            'local_nexus',
            'appicon',
            (int) $application->id,
            'filename',
            false
        );

        $file = reset($files);

        if ($file) {
            return moodle_url::make_pluginfile_url(
                $context->id,
                'local_nexus',
                'appicon',
                (int) $application->id,
                '/',
                $file->get_filename()
            )->out(false);
        }

        return $application->icon ?? '';
    }
}

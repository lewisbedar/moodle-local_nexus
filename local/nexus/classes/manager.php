<?php
namespace local_nexus;

defined('MOODLE_INTERNAL') || die();

use local_nexus\local\application_service;
use local_nexus\output\application;

class manager {
    public static function get_applications(): array {
        global $DB;

        $records = $DB->get_records(
            'local_nexus_applications',
            ['enabled' => 1, 'showdock' => 1],
            'sortorder ASC, name ASC'
        );

        $applications = [];

        foreach ($records as $record) {
            $applications[] = new application(
                $record->name,
                application_service::get_icon_url($record),
                $record->url,
                $record->description ?? '',
                $record->visibility !== 'public',
                $record->version ?? '',
                $record->status ?? 'stable',
                $record->category ?? '',
                $record->color ?? ''
            );
        }

        return $applications;
    }
}

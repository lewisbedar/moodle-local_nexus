<?php
namespace local_nexus\local;

class news_service {
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
}

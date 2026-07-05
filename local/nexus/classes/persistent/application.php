<?php
namespace local_nexus\persistent;

defined('MOODLE_INTERNAL') || die();

class application extends \core\persistent {

    /** Nom de la table */
    const TABLE = 'local_nexus_applications';

    protected static function define_properties(): array {

        return [

            'name' => [
                'type' => PARAM_TEXT
            ],

            'slug' => [
                'type' => PARAM_ALPHANUMEXT
            ],

            'description' => [
                'type' => PARAM_TEXT,
                'default' => ''
            ],

            'icon' => [
                'type' => PARAM_RAW,
                'default' => ''
            ],

            'url' => [
                'type' => PARAM_URL
            ],

            'visibility' => [
                'type' => PARAM_ALPHA,
                'default' => 'public'
            ],

            'sortorder' => [
                'type' => PARAM_INT,
                'default' => 0
            ],

            'enabled' => [
                'type' => PARAM_BOOL,
                'default' => true
            ],

            'timecreated' => [
                'type' => PARAM_INT
            ],

            'timemodified' => [
                'type' => PARAM_INT
            ],

        ];

    }

}
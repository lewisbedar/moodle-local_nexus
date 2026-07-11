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


            'version' => [
                'type' => PARAM_TEXT,
                'default' => ''
            ],

            'status' => [
                'type' => PARAM_ALPHA,
                'default' => 'stable',
                'choices' => ['stable', 'beta', 'alpha']
            ],

            'category' => [
                'type' => PARAM_TEXT,
                'default' => ''
            ],

            'color' => [
                'type' => PARAM_TEXT,
                'default' => ''
            ],

            'showdock' => [
                'type' => PARAM_BOOL,
                'default' => true
            ],

            'showhomepage' => [
                'type' => PARAM_BOOL,
                'default' => true
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

<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_nexus_upgrade(int $oldversion): bool {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026070700) {
        $table = new xmldb_table('local_nexus_applications');

        $fields = [
            new xmldb_field('version', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, '', 'visibility'),
            new xmldb_field('status', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, 'stable', 'version'),
            new xmldb_field('category', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, '', 'status'),
            new xmldb_field('color', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, '', 'category'),
            new xmldb_field('showdock', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'color'),
            new xmldb_field('showhomepage', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'showdock'),
        ];

        foreach ($fields as $field) {
            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
        }

        upgrade_plugin_savepoint(true, 2026070700, 'local', 'nexus');
    }

    if ($oldversion < 2026070701) {
        $table = new xmldb_table('local_nexus_news');

        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
            $table->add_field('summary', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('url', XMLDB_TYPE_CHAR, '255', null, null, null, null);
            $table->add_field('published', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
            $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2026070701, 'local', 'nexus');
    }

    return true;
}

<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add(
        'localplugins',
        new admin_externalpage(
            'local_nexus_applications',
            get_string('manageapplications', 'local_nexus'),
            new moodle_url('/local/nexus/manage_apps.php')
        )
    );

    $ADMIN->add(
        'localplugins',
        new admin_externalpage(
            'local_nexus_news',
            get_string('managenews', 'local_nexus'),
            new moodle_url('/local/nexus/manage_news.php')
        )
    );

    $ADMIN->add(
        'localplugins',
        new admin_externalpage(
            'local_nexus_hero_settings',
            get_string('herosettings', 'local_nexus'),
            new moodle_url('/local/nexus/hero_settings.php')
        )
    );
}

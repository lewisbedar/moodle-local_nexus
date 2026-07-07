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
}

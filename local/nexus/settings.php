<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add(
        'localplugins',
        new admin_externalpage(
            'local_nexus_applications',
            'Nexus - Applications',
            new moodle_url('/local/nexus/applications.php')
        )
    );
}
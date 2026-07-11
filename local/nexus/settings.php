<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    global $DB;

    $cohortoptions = [0 => get_string('none')];
    $cohorts = $DB->get_records('cohort', null, 'name ASC', 'id, name, idnumber');

    foreach ($cohorts as $cohort) {
        $label = format_string($cohort->name);
        if ($cohort->idnumber !== '') {
            $label .= ' (' . s($cohort->idnumber) . ')';
        }
        $cohortoptions[$cohort->id] = $label;
    }

    $settings = new admin_settingpage(
        'local_nexus_access',
        get_string('accesssettings', 'local_nexus'),
        'local/nexus:managehero'
    );

    $settings->add(new admin_setting_configselect(
        'local_nexus/nexus_member_cohortid',
        get_string('membercohort', 'local_nexus'),
        get_string('membercohort_desc', 'local_nexus'),
        0,
        $cohortoptions
    ));

    $settings->add(new admin_setting_configselect(
        'local_nexus/nexus_staff_cohortid',
        get_string('staffcohort', 'local_nexus'),
        get_string('staffcohort_desc', 'local_nexus'),
        0,
        $cohortoptions
    ));

    $ADMIN->add('localplugins', $settings);

    $ADMIN->add(
        'localplugins',
        new admin_externalpage(
            'local_nexus_applications',
            get_string('manageapplications', 'local_nexus'),
            new moodle_url('/local/nexus/manage_apps.php'),
            'local/nexus:manageapps'
        )
    );

    $ADMIN->add(
        'localplugins',
        new admin_externalpage(
            'local_nexus_news',
            get_string('managenews', 'local_nexus'),
            new moodle_url('/local/nexus/manage_news.php'),
            'local/nexus:managenews'
        )
    );

    $ADMIN->add(
        'localplugins',
        new admin_externalpage(
            'local_nexus_hero_settings',
            get_string('herosettings', 'local_nexus'),
            new moodle_url('/local/nexus/hero_settings.php'),
            'local/nexus:managehero'
        )
    );
}

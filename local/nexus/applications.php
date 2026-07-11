<?php
require_once(__DIR__ . '/../../config.php');

require_login();
require_capability('local/nexus:manageapps', context_system::instance());

redirect(new moodle_url('/local/nexus/manage_apps.php'));

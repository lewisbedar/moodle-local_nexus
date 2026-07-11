<?php
namespace local_nexus;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/cohort/lib.php');

use advanced_testcase;
use context_system;
use local_nexus\local\application_access_service;

class application_access_service_test extends advanced_testcase {
    public function test_public_application_can_be_opened_without_login(): void {
        $this->resetAfterTest();
        $this->setUser(null);

        $application = $this->application('public');

        $this->assertTrue(application_access_service::can_view_card($application));
        $this->assertTrue(application_access_service::can_open($application));
        $this->assertFalse(application_access_service::should_show_locked_card($application));
    }

    public function test_authenticated_application_is_locked_for_guests(): void {
        $this->resetAfterTest();
        $this->setUser(null);

        $application = $this->application('authenticated');

        $this->assertTrue(application_access_service::can_view_card($application));
        $this->assertFalse(application_access_service::can_open($application));
        $this->assertTrue(application_access_service::should_show_locked_card($application));
    }

    public function test_member_cohort_can_open_member_application(): void {
        $this->resetAfterTest();

        $user = $this->getDataGenerator()->create_user();
        $cohort = $this->getDataGenerator()->create_cohort();
        cohort_add_member($cohort->id, $user->id);
        set_config('nexus_member_cohortid', $cohort->id, 'local_nexus');
        $this->setUser($user);

        $this->assertTrue(application_access_service::can_open($this->application('members')));
    }

    public function test_staff_capability_can_open_staff_application(): void {
        $this->resetAfterTest();

        $user = $this->getDataGenerator()->create_user();
        $roleid = $this->getDataGenerator()->create_role();
        assign_capability('local/nexus:viewstaffapps', CAP_ALLOW, $roleid, context_system::instance()->id);
        role_assign($roleid, $user->id, context_system::instance()->id);
        $this->setUser($user);

        $this->assertTrue(application_access_service::can_open($this->application('staff')));
    }

    public function test_unprivileged_user_cannot_open_admin_application(): void {
        $this->resetAfterTest();

        $this->setUser($this->getDataGenerator()->create_user());

        $this->assertFalse(application_access_service::can_open($this->application('admin')));
    }

    private function application(string $visibility): \stdClass {
        return (object) [
            'id' => 1,
            'enabled' => 1,
            'visibility' => $visibility,
        ];
    }
}

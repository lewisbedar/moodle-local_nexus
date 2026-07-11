<?php
namespace local_nexus;

defined('MOODLE_INTERNAL') || die();

use advanced_testcase;
use local_nexus\local\widget\widget_manager;

class widget_manager_test extends advanced_testcase {
    public function test_default_config_preserves_homepage_order(): void {
        $this->resetAfterTest();

        $this->assertSame(
            ['hero', 'dock', 'applications', 'courses', 'news'],
            array_column(widget_manager::default_config(), 'id')
        );
    }

    public function test_normalise_config_orders_widgets(): void {
        $this->resetAfterTest();

        $config = widget_manager::normalise_config([
            ['id' => 'news', 'enabled' => 1, 'sortorder' => 50, 'title' => 'News'],
            ['id' => 'hero', 'enabled' => 1, 'sortorder' => 10, 'title' => 'Hero'],
            ['id' => 'dock', 'enabled' => 1, 'sortorder' => 20, 'title' => 'Dock'],
        ]);

        $this->assertSame('hero', $config[0]['id']);
        $this->assertSame('dock', $config[1]['id']);
        $this->assertSame(10, $config[0]['sortorder']);
        $this->assertSame(20, $config[1]['sortorder']);
    }

    public function test_disabled_widgets_are_excluded(): void {
        $this->resetAfterTest();

        widget_manager::save_config([
            ['id' => 'hero', 'enabled' => 1, 'sortorder' => 10, 'title' => ''],
            ['id' => 'dock', 'enabled' => 0, 'sortorder' => 20, 'title' => ''],
            ['id' => 'applications', 'enabled' => 0, 'sortorder' => 30, 'title' => ''],
            ['id' => 'courses', 'enabled' => 0, 'sortorder' => 40, 'title' => ''],
            ['id' => 'news', 'enabled' => 0, 'sortorder' => 50, 'title' => ''],
        ]);

        $this->assertSame(['hero'], array_map(static fn(array $item): string => $item['widget']->get_id(), widget_manager::get_active_widgets()));
    }

    public function test_unavailable_course_widget_is_excluded_for_guest(): void {
        $this->resetAfterTest();
        $this->setUser(null);

        widget_manager::save_config([
            ['id' => 'hero', 'enabled' => 0, 'sortorder' => 10, 'title' => ''],
            ['id' => 'dock', 'enabled' => 0, 'sortorder' => 20, 'title' => ''],
            ['id' => 'applications', 'enabled' => 0, 'sortorder' => 30, 'title' => ''],
            ['id' => 'courses', 'enabled' => 1, 'sortorder' => 10, 'title' => ''],
            ['id' => 'news', 'enabled' => 0, 'sortorder' => 50, 'title' => ''],
        ]);

        $this->assertSame([], widget_manager::get_active_widgets());
    }

    public function test_unknown_widget_identifier_is_rejected(): void {
        $this->expectException(\invalid_parameter_exception::class);

        widget_manager::normalise_config([
            ['id' => 'unknown', 'enabled' => 1, 'sortorder' => 10, 'title' => ''],
        ]);
    }
}

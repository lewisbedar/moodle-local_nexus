<?php
namespace local_nexus\local\widget;

defined('MOODLE_INTERNAL') || die();

use renderer_base;

interface widget_interface {
    public function get_id(): string;
    public function get_name(): string;
    public function is_available(): bool;
    public function export_for_template(renderer_base $output): array;
    public function get_template(): string;
}

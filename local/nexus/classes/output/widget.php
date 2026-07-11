<?php
namespace local_nexus\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;

class widget implements renderable, templatable {
    public function __construct(
        private string $id,
        private string $title,
        private string $content
    ) {}

    public function export_for_template(renderer_base $output): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'hastitle' => $this->title !== '',
            'content' => $this->content,
        ];
    }
}

<?php
namespace local_nexus\output;

defined('MOODLE_INTERNAL') || die();

class application {
    public function __construct(
        public string $name,
        public string $icon,
        public string $url,
        public string $description = '',
        public bool $locked = false,
        public string $version = '',
        public string $status = 'stable',
        public string $category = '',
        public string $color = ''
    ) {}
}

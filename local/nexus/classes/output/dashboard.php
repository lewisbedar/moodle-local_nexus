<?php
namespace local_nexus\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;
use local_nexus\manager;

class dashboard implements renderable, templatable {
    public function export_for_template(renderer_base $output): array {
        $apps = [];

        foreach (manager::get_applications() as $app) {
            $apps[] = [
                'name' => $app->name,
                'icon' => $app->icon,
                'url' => $app->url,
                'description' => $app->description,
                'locked' => $app->locked,
            ];
        }

        return [
            'title' => 'Nexus',
            'subtitle' => 'Le portail des outils, ressources et formations de Flux Croisés.',
            'applications' => $apps,
        ];
    }
}
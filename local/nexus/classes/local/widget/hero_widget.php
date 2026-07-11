<?php
namespace local_nexus\local\widget;

defined('MOODLE_INTERNAL') || die();

use local_nexus\local\hero_service;
use renderer_base;

class hero_widget implements widget_interface {
    public function get_id(): string { return 'hero'; }
    public function get_name(): string { return get_string('widget_hero', 'local_nexus'); }
    public function is_available(): bool { return true; }
    public function get_template(): string { return 'local_nexus/widgets/hero'; }

    public function export_for_template(renderer_base $output): array {
        global $PAGE, $USER;

        $config = get_config('local_nexus');
        $user = ['fullname' => '', 'pictureurl' => '', 'hasuser' => false];

        if (isloggedin() && !isguestuser()) {
            $userpicture = new \user_picture($USER);
            $userpicture->size = 96;
            $user = [
                'fullname' => fullname($USER),
                'pictureurl' => $userpicture->get_url($PAGE)->out(false),
                'hasuser' => true,
            ];
        }

        $iconurl = hero_service::get_icon_url('home');
        $logourl = hero_service::get_file_url(hero_service::FILEAREA_LOGO);

        return [
            'title' => $config->hero_title ?? 'Nexus',
            'subtitle' => $config->hero_subtitle ?? 'Le portail des outils, ressources et formations de Flux Croisés.',
            'primarylabel' => $config->hero_primary_label ?? 'Mes formations',
            'primaryurl' => $config->hero_primary_url ?? '/my/courses.php',
            'secondarylabel' => $config->hero_secondary_label ?? 'Documentation',
            'secondaryurl' => $config->hero_secondary_url ?? 'https://www.docs.flux-croises.fr',
            'iconurl' => $iconurl,
            'logourl' => $logourl,
            'hasicon' => $iconurl !== '',
            'haslogo' => $logourl !== '',
            'user' => $user,
        ];
    }
}

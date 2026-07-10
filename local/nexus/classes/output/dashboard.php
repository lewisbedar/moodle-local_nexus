<?php
namespace local_nexus\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;
use local_nexus\local\course_service;
use local_nexus\local\hero_service;
use local_nexus\local\news_service;
use local_nexus\manager;

class dashboard implements renderable, templatable {
    public function export_for_template(renderer_base $output): array {
        global $PAGE, $USER;

        $apps = [];

        foreach (manager::get_applications() as $app) {
            $apps[] = [
                'name' => $app->name,
                'icon' => $app->icon,
                'url' => $app->url,
                'description' => $app->description,
                'locked' => $app->locked,
                'version' => $app->version,
                'status' => $app->status,
                'category' => $app->category,
                'color' => $app->color,
            ];
        }

        $config = get_config('local_nexus');

        $hero = [
            'title' => $config->hero_title ?? 'Nexus',
            'subtitle' => $config->hero_subtitle ?? 'Le portail des outils, ressources et formations de Flux Croisés.',
            'primarylabel' => $config->hero_primary_label ?? 'Mes formations',
            'primaryurl' => $config->hero_primary_url ?? '/my/courses.php',
            'secondarylabel' => $config->hero_secondary_label ?? 'Documentation',
            'secondaryurl' => $config->hero_secondary_url ?? 'https://www.docs.flux-croises.fr',
            'iconurl' => hero_service::get_icon_url('home'),
            'logourl' => hero_service::get_file_url(hero_service::FILEAREA_LOGO),
        ];

        $hero['hasicon'] = $hero['iconurl'] !== '';
        $hero['haslogo'] = $hero['logourl'] !== '';

        $userpicture = new \user_picture($USER);
        $userpicture->size = 96;
        $user = [
            'fullname' => fullname($USER),
            'pictureurl' => $userpicture->get_url($PAGE)->out(false),
        ];

        $courses = course_service::get_recent_courses();

        $newsitems = [];

        foreach (news_service::get_latest() as $news) {
            $newsitems[] = [
                'title' => format_string($news->title),
                'summary' => format_text($news->summary, FORMAT_HTML),
                'url' => $news->url ?? '',
                'hasurl' => !empty($news->url),
            ];
        }

        return [
            'title' => $hero['title'],
            'subtitle' => $hero['subtitle'],
            'hero' => $hero,
            'user' => $user,
            'applications' => $apps,
            'courses' => $courses,
            'hascourses' => !empty($courses),
            'news' => $newsitems,
            'hasnews' => !empty($newsitems),
        ];
    }
}

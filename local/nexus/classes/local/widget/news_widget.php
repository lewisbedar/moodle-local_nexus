<?php
namespace local_nexus\local\widget;

defined('MOODLE_INTERNAL') || die();

use local_nexus\local\news_service;
use renderer_base;

class news_widget implements widget_interface {
    public function get_id(): string { return 'news'; }
    public function get_name(): string { return get_string('widget_news', 'local_nexus'); }
    public function is_available(): bool { return true; }
    public function get_template(): string { return 'local_nexus/widgets/news'; }

    public function export_for_template(renderer_base $output): array {
        $items = [];
        foreach (news_service::get_latest() as $news) {
            $imageurl = news_service::get_image_url((int) $news->id);
            $items[] = [
                'title' => format_string($news->title),
                'summary' => format_text($news->summary, FORMAT_HTML),
                'url' => $news->url ?? '',
                'hasurl' => !empty($news->url),
                'imageurl' => $imageurl,
                'hasimage' => $imageurl !== '',
            ];
        }
        return ['news' => $items, 'hasnews' => !empty($items)];
    }
}

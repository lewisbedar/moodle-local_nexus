namespace local_nexus\local;

use local_nexus\persistent\application;

class application_service {

    public static function get_all(): array {

        return application::get_records([
            'enabled' => 1
        ]);

    }

}
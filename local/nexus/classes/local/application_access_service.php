<?php
namespace local_nexus\local;

defined('MOODLE_INTERNAL') || die();

use context_system;

class application_access_service {
    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_AUTHENTICATED = 'authenticated';
    public const VISIBILITY_MEMBERS = 'members';
    public const VISIBILITY_STAFF = 'staff';
    public const VISIBILITY_ADMIN = 'admin';

    public static function visibility_options(): array {
        return [
            self::VISIBILITY_PUBLIC => get_string('visibility_public', 'local_nexus'),
            self::VISIBILITY_AUTHENTICATED => get_string('visibility_authenticated', 'local_nexus'),
            self::VISIBILITY_MEMBERS => get_string('visibility_members', 'local_nexus'),
            self::VISIBILITY_STAFF => get_string('visibility_staff', 'local_nexus'),
            self::VISIBILITY_ADMIN => get_string('visibility_admin', 'local_nexus'),
        ];
    }

    public static function normalise_visibility(?string $visibility): string {
        $visibility = trim((string) $visibility);

        return array_key_exists($visibility, self::visibility_options()) ? $visibility : self::VISIBILITY_PUBLIC;
    }

    public static function is_authenticated_user(?\stdClass $user = null): bool {
        global $USER;

        $user = $user ?? $USER;

        return !empty($user->id) && isloggedin() && !isguestuser($user);
    }

    public static function can_view_card(object $application, ?\stdClass $user = null): bool {
        return !empty($application->enabled);
    }

    public static function can_view_details(object $application, ?\stdClass $user = null): bool {
        return self::can_open($application, $user);
    }

    public static function can_open(object $application, ?\stdClass $user = null): bool {
        global $USER;

        $user = $user ?? $USER;
        $visibility = self::normalise_visibility($application->visibility ?? self::VISIBILITY_PUBLIC);

        if ($visibility === self::VISIBILITY_PUBLIC) {
            return true;
        }

        if (!self::is_authenticated_user($user)) {
            return false;
        }

        $context = context_system::instance();

        if (has_capability('local/nexus:manageapps', $context, $user)) {
            return true;
        }

        return match ($visibility) {
            self::VISIBILITY_AUTHENTICATED => true,
            self::VISIBILITY_MEMBERS => has_capability('local/nexus:viewmemberapps', $context, $user)
                || self::is_user_in_configured_cohort($user->id, 'nexus_member_cohortid'),
            self::VISIBILITY_STAFF => has_capability('local/nexus:viewstaffapps', $context, $user)
                || self::is_user_in_configured_cohort($user->id, 'nexus_staff_cohortid'),
            self::VISIBILITY_ADMIN => false,
            default => false,
        };
    }

    public static function should_show_locked_card(object $application, ?\stdClass $user = null): bool {
        return self::can_view_card($application, $user) && !self::can_open($application, $user);
    }

    public static function require_can_view_details(object $application, ?\stdClass $user = null): void {
        if (self::can_view_details($application, $user)) {
            return;
        }

        if (!self::is_authenticated_user($user)) {
            require_login();
        }

        throw new \required_capability_exception(
            context_system::instance(),
            'local/nexus:viewmemberapps',
            'nopermissions',
            ''
        );
    }

    private static function is_user_in_configured_cohort(int $userid, string $configkey): bool {
        global $DB;

        $cohortid = (int) get_config('local_nexus', $configkey);

        if ($cohortid <= 0) {
            return false;
        }

        return $DB->record_exists('cohort_members', [
            'cohortid' => $cohortid,
            'userid' => $userid,
        ]);
    }
}

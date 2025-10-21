<?php
namespace App\Enums;

enum TypeStatus: string {
    case ATTENDED       = 'Attended';
    case QUOTE_SENT     = 'Quote Sent';
    case UNQUALIFIED    = 'Unqualified';
    case STALLED        = 'Stalled';
    case QUALIFIED      = 'Qualified';
    case CONTACTED      = 'Contacted';
    case DELETED        = 'Deleted';
    case PENDING        = 'Pending';

    public function meta(?string $key = null): mixed {
        $data = match ($this) {
            self::ATTENDED      => [
                'label'         => 'Attended',
                'badge_class'   => '',
                'style'         => '',
                'keyValue'      => 'Attended',
            ],
            self::QUOTE_SENT    => [
                'label'         => 'Quote Sent',
                'badge_class'   => 'badge-light-success',
                'style'         => 'color: #1D813A; background-color: #E9F6ED',
                'keyValue'      => 'Quote Sent',
            ],
            self::UNQUALIFIED   => [
                'label'         => 'Unqualified',
                'badge_class'   => 'badge-light-unqualified',
                'style'         => 'color: #686868; background-color: #E8E8E8',
                'keyValue'      => 'Unqualified',
            ],
            self::STALLED       => [
                'label'         => 'Stalled',
                'badge_class'   => 'badge-light-stalled',
                'style'         => 'color: #68C0FF; background-color: #EEF8FF',
                'keyValue'      => 'Stalled',
            ],
            self::QUALIFIED     => [
                'label'         => 'Processing',
                'badge_class'   => 'badge-light-info',
                'style'         => 'color: #0A6AB7; background-color: #D3EAFD',
                'keyValue'      => 'Qualified',
            ],
            self::CONTACTED     => [
                'label'         => 'Contacted',
                'badge_class'   => 'badge-light-warning',
                'style'         => 'color: #B28600; background-color: #FCF4D6',
                'keyValue'      => 'Contacted',
            ],
            self::DELETED       => [
                'label'         => 'Deleted',
                'badge_class'   => 'badge-light-danger',
                'style'         => '',
                'keyValue'      => 'Deleted',
            ],
            self::PENDING       => [
                'label'         => 'Pending',
                'badge_class'   => 'badge-light-pending',
                'style'         => 'color: #EB6200; background-color: #FFF2E8',
                'keyValue'      => 'Pending',
            ],
        };

        return $key ? ($data[$key] ?? null) : $data;
    }

    public static function options() : array {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->meta('label');
        }
        return $options;
    }

    public static function deals_change_status_list() : array {
        return [
            self::PENDING->value => self::PENDING->meta(),
            self::CONTACTED->value => self::CONTACTED->meta(),
            self::STALLED->value => self::STALLED->meta(),
            self::QUALIFIED->value => self::QUALIFIED->meta(),
            self::QUOTE_SENT->value => self::QUOTE_SENT->meta(),
            self::UNQUALIFIED->value => self::UNQUALIFIED->meta(),
        ];
    }
}

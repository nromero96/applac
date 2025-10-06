<?php
namespace App\Enums;

enum TypeInquiry: string {
    // SEO/HOUSE Dept.
    case INTERNAL           = 'internal';
    case INTERNAL_OTHER     = 'internal_other'; // new
    case EXTERNAL_1         = 'external 1';
    case EXTERNAL_2         = 'external 2';

    // Agents Dept.
    case INTERNAL_VIP           = 'internal_vip'; // new
    case INTERNAL_LEGACY        = 'internal_legacy'; // new
    case INTERNAL_OTHER_AGT     = 'internal_other_agt'; // new
    case EXTERNAL_SEO_RFQ       = 'external_seo_rfq'; // new

    // System
    case EXT_AUTO           = 'ext-auto';

    public function label(): string {
        return match ($this) {
            // SEO/HOUSE Dept.
            self::INTERNAL          => 'House',
            self::INTERNAL_OTHER    => 'Other',
            self::EXTERNAL_1        => 'SEO RFQ Personal',
            self::EXTERNAL_2        => 'SEO RFQ Business',

            // Agents Dept.
            self::INTERNAL_VIP          => 'VIP',
            self::INTERNAL_LEGACY       => 'Legacy',
            self::INTERNAL_OTHER_AGT    => 'Other',
            self::EXTERNAL_SEO_RFQ      => 'SEO RFQ Agent',

            // System
            self::EXT_AUTO      => 'Auto',
        };
    }

    public function list_icon(): string {
        return match ($this) {
            // SEO/HOUSE Dept.
            self::INTERNAL          => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><g clip-path="url(#clip0_8656_5074)" fill="none" stroke-width="1.5px"><path d="M8.00065 14.6667C11.6825 14.6667 14.6673 11.6819 14.6673 8.00004C14.6673 4.31814 11.6825 1.33337 8.00065 1.33337C4.31875 1.33337 1.33398 4.31814 1.33398 8.00004C1.33398 11.6819 4.31875 14.6667 8.00065 14.6667Z" stroke="#0A6AB7" stroke-width="1.5px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path><path d="M8 10C9.10457 10 10 9.10457 10 8C10 6.89543 9.10457 6 8 6C6.89543 6 6 6.89543 6 8C6 9.10457 6.89543 10 8 10Z" stroke="#0A6AB7" stroke-width="1.5px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path></g><defs><clipPath id="clip0_8656_5074"><rect width="16" height="16" fill="white"></rect></clipPath></defs></svg>',
            self::INTERNAL_OTHER    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><g clip-path="url(#clip0_8656_5074)" fill="none" stroke-width="1.5px"><path d="M8.00065 14.6667C11.6825 14.6667 14.6673 11.6819 14.6673 8.00004C14.6673 4.31814 11.6825 1.33337 8.00065 1.33337C4.31875 1.33337 1.33398 4.31814 1.33398 8.00004C1.33398 11.6819 4.31875 14.6667 8.00065 14.6667Z" stroke="#0A6AB7" stroke-width="1.5px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path><path d="M8 10C9.10457 10 10 9.10457 10 8C10 6.89543 9.10457 6 8 6C6.89543 6 6 6.89543 6 8C6 9.10457 6.89543 10 8 10Z" stroke="#0A6AB7" stroke-width="1.5px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path></g><defs><clipPath id="clip0_8656_5074"><rect width="16" height="16" fill="white"></rect></clipPath></defs></svg>',
            self::EXTERNAL_1        => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4.66602 4.66663L11.3327 11.3333" stroke="#B28600" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.3327 4.66663V11.3333H4.66602" stroke="#B28600" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            self::EXTERNAL_2        => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4.66602 4.66663L11.3327 11.3333" stroke="#EB6200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.3327 4.66663V11.3333H4.66602" stroke="#EB6200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            // System
            self::EXT_AUTO          => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4.66602 4.66663L11.3327 11.3333" stroke="#EB6200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.3327 4.66663V11.3333H4.66602" stroke="#EB6200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        };
    }

    public function list_class(): string {
        return match ($this) {
            // SEO/HOUSE Dept.
            self::INTERNAL          => 'internal',
            self::INTERNAL_OTHER    => 'internal',
            self::EXTERNAL_1        => 'external-1',
            self::EXTERNAL_2        => 'external-2',

            // System
            self::EXT_AUTO          => 'ext-auto',
        };
    }

    public static function labels(): array {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}

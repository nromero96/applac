<?php
namespace App\Enums;

enum TypeModeTransport: string {
    case GROUND_FTL             = 'FTL (Full Truckload)';
    case GROUND_LTL             = 'LTL (Less-than-Truckload)';
    case OCEAN_FCL              = 'FCL (Full Container Load)';
    case OCEAN_LCL              = 'LCL (Less-than-Container Load)';
    case OCEAN_RORO             = 'RORO (Roll-On/Roll-Off)';
    case OCEAN_BREAKBULK        = 'Breakbulk';
    case AIR_FREIGHT            = 'Standard Air Freight';
    case OTHER_PROJECT_CARGO    = 'Project Cargo';

    public function label(): string {
        return match ($this) {
            self::GROUND_FTL            => 'Ground FTL',
            self::GROUND_LTL            => 'Ground LTL',
            self::OCEAN_FCL             => 'Ocean FCL',
            self::OCEAN_LCL             => 'Ocean LCL',
            self::OCEAN_RORO            => 'Ocean RORO',
            self::OCEAN_BREAKBULK       => 'Ocean Breakbulk/LOLO',
            self::AIR_FREIGHT           => 'Air Freight',
            self::OTHER_PROJECT_CARGO   => 'Project Cargo',
        };
    }

    public static function options() : array {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }

    public static function options_internal() : array {
        return [
            self::GROUND_FTL->value => self::GROUND_FTL->label(),
            self::GROUND_LTL->value => self::GROUND_LTL->label(),
            self::OCEAN_FCL->value => self::OCEAN_FCL->label(),
            self::OCEAN_LCL->value => self::OCEAN_LCL->label(),
            self::OCEAN_RORO->value => self::OCEAN_RORO->label(),
            self::OCEAN_BREAKBULK->value => self::OCEAN_BREAKBULK->label(),
            self::AIR_FREIGHT->value => self::AIR_FREIGHT->label(),
            // self::OTHER_PROJECT_CARGO->value => self::OTHER_PROJECT_CARGO->label(),
        ];
    }
}

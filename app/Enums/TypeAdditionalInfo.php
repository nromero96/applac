<?php
namespace App\Enums;

enum TypeAdditionalInfo: string {
    case CARGO_VALUE = 'cargo_value';
    case SHIPPING_VOLUME = 'shipping_volume';
    case AGENTS_EXISTING_CLIENT = 'agents_existing_client';

    public function meta(?string $key = null): mixed {
        $data = match ($this) {
            self::CARGO_VALUE => ['label' => 'Cargo value'],
            self::SHIPPING_VOLUME => ['label' => 'Shipping volume'],
            self::AGENTS_EXISTING_CLIENT => ['label' => "Agent's existing client"],
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
}

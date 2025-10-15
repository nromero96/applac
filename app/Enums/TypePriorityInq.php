<?php
namespace App\Enums;

enum TypePriorityInq: string {
    case HIGH       = 'high';
    case MEDIUM     = 'medium';
    case LOW        = 'low';

    public function meta(?string $key = null): mixed {
        $data = match ($this) {
            self::HIGH => [
                'label' => 'High',
                'color' => '#B80000',
                'bg' => '#FAE6E6',
            ],
            self::MEDIUM => [
                'label' => 'Medium',
                'color' => '#B28600',
                'bg' => '#FCF4D6',
            ],
            self::LOW => [
                'label' => 'Low',
                'color' => '#686868',
                'bg' => '#E8E8E8',
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
}

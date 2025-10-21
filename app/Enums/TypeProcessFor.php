<?php
namespace App\Enums;

enum TypeProcessFor: string {
    case FULL_QUOTE = 'Full Quote';
    case ESTIMATE = 'Estimate';

    public function meta(?string $key = null): mixed {
        $data = match ($this) {
            self::FULL_QUOTE => [
                'label' => 'Full Quote',
                'class' => 'process_full_quote',
            ],
            self::ESTIMATE => [
                'label' => 'Estimate',
                'class' => 'process_estimate',
            ],
        };

        return $key ? ($data[$key] ?? null) : $data;
    }
}

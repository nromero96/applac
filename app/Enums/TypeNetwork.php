<?php
namespace App\Enums;

enum TypeNetwork: string {
    case TWIG       = 'twig';
    case WCA        = 'wca';
    case JC_TRANS   = 'jc_trans';
    case GKF        = 'gkf';
    case X2         = 'x2';
    case PANGEA     = 'Pangea';
    case GFA        = 'gfa';
    case DFA        = 'dfa';
    case NONE       = 'none';

    public function meta(?string $key = null): mixed {
        $data = match ($this) {
            self::TWIG => [
                'label' => 'TWIG',
                'short_label' => 'twig',
                'color' => '#6200EE',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            ],
            self::WCA => [
                'label' => 'WCA',
                'short_label' => 'wca',
                'color' => '#1877F2',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            ],
            self::JC_TRANS => [
                'label' => 'JC Trans',
                'short_label' => 'other',
                'color' => '#B28600',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            ],
            self::GKF => [
                'label' => 'GKF',
                'short_label' => 'other',
                'color' => '#B28600',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            ],
            self::X2 => [
                'label' => 'X2',
                'short_label' => 'other',
                'color' => '#B28600',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            ],
            self::PANGEA => [
                'label' => 'PANGEA',
                'short_label' => 'other',
                'color' => '#B28600',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            ],
            self::GFA => [
                'label' => 'GFA',
                'short_label' => 'other',
                'color' => '#B28600',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            ],
            self::DFA => [
                'label' => 'DFA',
                'short_label' => 'other',
                'color' => '#B28600',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            ],
            self::NONE => [
                'label' => 'Not part of any network',
                'short_label' => 'none',
                'color' => '#999999',
                'icon' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 4C9.82843 4 10.5 3.32843 10.5 2.5C10.5 1.67157 9.82843 1 9 1C8.17157 1 7.5 1.67157 7.5 2.5C7.5 3.32843 8.17157 4 9 4Z" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 7.5C3.82843 7.5 4.5 6.82843 4.5 6C4.5 5.17157 3.82843 4.5 3 4.5C2.17157 4.5 1.5 5.17157 1.5 6C1.5 6.82843 2.17157 7.5 3 7.5Z" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11C9.82843 11 10.5 10.3284 10.5 9.5C10.5 8.67157 9.82843 8 9 8C8.17157 8 7.5 8.67157 7.5 9.5C7.5 10.3284 8.17157 11 9 11Z" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.29492 6.755L7.70992 8.745" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.70492 3.255L4.29492 5.245" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/></svg>'
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

<?php

namespace App\Models;

use App\Enums\TypeAdditionalInfo;
use App\Enums\TypeInquiry;
use App\Enums\TypeModeTransport;
use App\Enums\TypePriorityInq;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type_inquiry',
        'department_id',
        'featured',
        'customer_user_id',
        'guest_user_id',
        'mode_of_transport',
        'cargo_type',
        'service_type',
        'origin_country_id',
        'origin_address',
        'origin_city',
        'origin_state_id',
        'origin_zip_code',
        'origin_airportorport',
        'destination_country_id',
        'destination_address',
        'destination_city',
        'destination_state_id',
        'destination_zip_code',
        'destination_airportorport',
        'total_qty',
        'total_actualweight',
        'total_volum_weight',
        'tota_chargeable_weight',
        'shipping_date',
        'no_shipping_date',
        'declared_value',
        'insurance_required',
        'currency',
        'rating',
        'rating_modified',
        'status',
        'result',
        'assigned_user_id',
        'created_at',
        'updated_at',
        'recovered_account',
        'shipment_ready_date',
        'is_internal_inquiry',
        'cargo_description',
        'cargo_details',
        'additional_info',
        'priority',
        'points',
        'process_for',
        'processed_by_type',
        'processed_by_user_id',
    ];

    protected $casts = [
        'type_inquiry'      => TypeInquiry::class,
        'priority'          => TypePriorityInq::class,
        'cargo_details'     => 'array',
        // 'additional_info'   => TypeAdditionalInfo::class,
        'additional_info'   => 'array',
    ];

    public function modeOfTransportLabel(): string {
        try {
            return TypeModeTransport::from($this->mode_of_transport)->label();
        } catch (\ValueError $e) {
            return ucfirst($this->mode_of_transport ?? '');
        }
    }

    public function getShipmentReadyInfo(): array {
        // $now = Carbon::now(); // en base a fecha actual
        $now = Carbon::parse($this->created_at); // en base a fecha de creaction del inquiry

        // Valor por defecto
        $result = [
            'label' => 'Not yet ready, just exploring options/budgeting',
            'rank'  => 1,
        ];

        if (empty($this->shipping_date)) {
            return $result;
        }

        // Detectar rango
        $dates = explode(' to ', $this->shipping_date);

        if (count($dates) === 2) {
            $shippingDate = Carbon::parse(trim($dates[1])); // [0]fecha inicial | [1]fecha final
        } else {
            $shippingDate = Carbon::parse(trim($this->shipping_date));
        }

        $daysDiff = $now->diffInDays($shippingDate, false);

        if ($daysDiff <= 14) {
            return [
                'label' => 'Ready to ship now',
                'rank'  => 3,
            ];
        } elseif ($daysDiff <= 90) {
            return [
                'label' => 'Ready within 1-3 months',
                'rank'  => 2,
            ];
        }

        return $result;
    }

    // Accessor: cada vez que accedas a $quotation->declared_value
    // se devolverá como float limpio.
    public function getDeclaredValueAttribute($value) {
        // Quitar comas y convertir a float
        return (float) str_replace(',', '', $value);
    }

    // Mutator: cada vez que guardes declared_value
    // se grabará limpio en la BD (sin comas).
    public function setDeclaredValueAttribute($value) {
        $this->attributes['declared_value'] = (float) str_replace(',', '', $value);
    }
}

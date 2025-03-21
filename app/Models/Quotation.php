<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type_inquiry',
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
    ];
}

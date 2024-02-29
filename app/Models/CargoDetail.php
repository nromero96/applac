<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'package_type',
        'temperature',
        'temperature_type',
        'qty',
        'details_shipment',
        'length',
        'width',
        'height',
        'dimensions_unit',
        'per_piece',
        'item_total_weight',
        'weight_unit',
        'item_total_volume_weight_cubic_meter',
        'cargo_description',
        'electric_vehicle',
        'dangerous_cargo',
        'dc_imoclassification_1',
        'dc_unnumber_1',
        'dc_imoclassification_2',
        'dc_unnumber_2',
        'dc_imoclassification_3',
        'dc_unnumber_3',
        'dc_imoclassification_4',
        'dc_unnumber_4',
        'dc_imoclassification_5',
        'dc_unnumber_5',
    ];
}

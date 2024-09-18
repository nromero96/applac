<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'type',
        'action',
        'reason',
        'note',
        'user_id',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotePendingEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'type',
        'customer_name',
        'email',
        'status',
        'error_message',
        'sent_at',
    ];
}

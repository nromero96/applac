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
        'contacted_via',
        'note',
        'user_id',
        'followup_channel',
        'followup_feedback',
        'followup_comment',
        'lost_reason',
        'update_type',
    ];

}

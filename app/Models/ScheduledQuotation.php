<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quotation_id',
        'date',
        'notes',
        'priority',
    ];

    // Relación con el modelo User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relación con el modelo Quotation
    public function quotation() {
        return $this->belongsTo(Quotation::class);
    }
}

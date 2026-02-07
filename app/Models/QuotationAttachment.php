<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'user_id',
        'description',
        'is_important',
        'file_paths',
    ];

    protected $casts = [
        'file_paths' => 'array',
        'is_important' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

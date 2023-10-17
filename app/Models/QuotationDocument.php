<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'document_path',
    ];

}

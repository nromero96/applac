<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_type',
        'name',
        'lastname',
        'company_name',
        'company_website',
        'email',
        'location',
        'phone_code',
        'phone',
        'job_title',
        'business_role',
        'ea_shipments',
        'source',
        'tier',
        'score',
        'network',
        'recovered_account',
        'referred_by',
        'subscribed_to_newsletter',
    ];

    protected $casts = [
        'network' => 'array',
        'recovered_account' => 'boolean',
        'referred_by' => 'boolean',
    ];

    public function getScoreAttribute($value) {
        return rtrim(rtrim($value, '0'), '.');
    }
}

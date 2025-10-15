<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'addresses',
        'tier',
        'score',
        'country_id',
        'network',
        'recovered_account',
        'referred_by',
    ];

    protected $casts = [
        'addresses' => 'array',
        'network' => 'array',
        'recovered_account' => 'boolean',
        'referred_by' => 'boolean',
    ];

    public function getScoreAttribute($value) {
        return rtrim(rtrim($value, '0'), '.');
    }

    public function contacts() {
        return $this->hasMany(OrganizationContact::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }
}

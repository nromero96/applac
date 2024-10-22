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
    ];

    protected $casts = [
        'addresses' => 'array',
    ];

    public function contacts() {
        return $this->hasMany(OrganizationContact::class);
    }
}

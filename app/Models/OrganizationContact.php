<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'job_title',
        'email',
        'phone',
        'fax',
        'organization_id',
    ];

    public function organization(){
        return $this->belongsTo(Organization::class);
    }
}

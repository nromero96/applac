<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        'password',
        'status',
        'photo',
        'subscribed_to_newsletter',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // Relación con FeaturedQuotation
    public function featuredQuotations()
    {
        return $this->belongsToMany(Quotation::class, 'featured_quotations')
                    ->withTimestamps();
    }

}

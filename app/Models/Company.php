<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_id';

    protected $guarded = ['company_id'];

    protected $fillable = [
        'company_name',
        'company_foundation_date',
        'company_registration_number',
        'country',
        'zip_code',
        'city',
        'street_address',
        'latitude',
        'longitude',
        'company_owner',
        'employees',
        'activity',
        'active',
        'email',
        'password',
    ];
}

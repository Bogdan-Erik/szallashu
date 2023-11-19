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

    public function scopeFilterByIds($query, ?string $ids)
    {
        $idsArray = $ids ? explode(',', $ids) : [];

        return empty($idsArray) ? $query : $query->whereIn('company_id', $idsArray);
    }

    public function scopeJsonAgg($query, $columnName, $alias)
    {
        return $query->selectRaw("JSON_ARRAYAGG($columnName) as $alias");
    }
}

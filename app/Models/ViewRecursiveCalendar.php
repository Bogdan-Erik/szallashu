<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewRecursiveCalendar extends Model
{

    public $table = "view_recursive_calendar";

    public function scopeJsonAgg($query, $columnName, $alias)
    {
        return $query->selectRaw("JSON_ARRAYAGG($columnName) as $alias");
    }
}

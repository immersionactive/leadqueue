<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppendInput extends Model
{
    
    /**
     * @todo Cache this
     */
    public static function getList()
    {
        return self::pluck('name', 'property')->toArray();
    }

}

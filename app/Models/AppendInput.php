<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppendInput extends Model
{
    
    // This model uses a string primary key, not an int
    protected $primaryKey = 'property';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * @todo Cache this
     */
    public static function getList()
    {
        return self::pluck('name', 'property')->toArray();
    }

}

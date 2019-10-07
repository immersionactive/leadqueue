<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppendProperty extends Model
{

    /**
     * @todo Cache this
     */
    static public function getList()
    {
        return self::pluck('label', 'slug')->toArray();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppendOutput extends Model
{

    // This model uses a string primary key, not an int
    protected $primaryKey = 'slug';
    public $incrementing = false;
    protected $keyType = 'string';

    public function destination_appends()
    {
        return $this->hasMany('App\Models\DestinationAppend');
    }

    /**
     * @todo Cache this
     */
    static public function getList()
    {
        return self::orderBy('name')->pluck('name', 'slug')->toArray();
    }

}

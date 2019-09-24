<?php

namespace App;

class LeadSourceTypeRegistry
{

    protected $types = [];

    public function register($something)
    {
        echo '%%%' . $something . '%%%';
    }

}

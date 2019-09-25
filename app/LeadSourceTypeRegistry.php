<?php

namespace App;

use App\LeadSourceType;

class LeadSourceTypeRegistry
{

    protected $type_classnames = [];

    public function register(string $type_classname)
    {
        $this->type_classnames[] = $type_classname;
    }

    public function getRegisteredTypes(): array
    {
        return $this->type_classnames;
    }

}

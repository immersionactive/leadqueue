<?php

namespace App;

class DestinationConfigTypeRegistry
{

    protected $type_classnames = [];

    public function register(string $type_classname)
    {
        $this->type_classnames[] = $type_classname;
    }

}

<?php

namespace App;

class SourceConfigTypeRegistry
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

    public function getByModelClassname(string $model_classname)
    {
        $rv = false;
        foreach ($this->type_classnames as $type_classname) {
            if ($model_classname === $type_classname::getModelClassname()) {
                $rv = $type_classname;
                break;
            }
        }
        return $rv;
    }

    public function getBySlug(string $slug)
    {
        $rv = false;
        foreach ($this->type_classnames as $type_classname) {
            if ($slug === $type_classname::getSlug()) {
                $rv = $type_classname;
                break;
            }
        }
        return $rv;
    }

}

<?php

namespace SengentoBV\PhpRulesEngine;

use JsonSerializable;

abstract class RuleObject implements JsonSerializable
{
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $array = get_object_vars($this);

        foreach ($array as $key => $value) {
            if ($value instanceof RuleObject) {
                $array[$key] = $value->toArray();
            }
        }

        return $array;
    }
}
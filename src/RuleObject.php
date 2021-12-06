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
        return get_object_vars($this);
    }
}
<?php

namespace SengentoBV\PhpRulesEngine;

use JsonSerializable;

class RuleOperation implements JsonSerializable
{
    public string $operator;
    public string $column;

    /**
     * @var mixed
     */
    public $value;

    public function __construct(string $operator, string $column, $value)
    {
        $this->operator = $operator;
        $this->column = $column;
        $this->value = $value;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
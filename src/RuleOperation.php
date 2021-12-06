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

    public static function fromArray(array $array): RuleOperation
    {
        if (count($array) < 3) {
            throw new \Exception('Invalid input array. Must contain an operator, column and value key.');
        }

        if (!isset($array['operator']) || !is_string($array['operator']) || $array['operator'] === '') {
            throw new \Exception('Invalid input array. Must contain a non-empty string operator.');
        }
        if (!isset($array['column']) || !is_string($array['column']) || $array['column'] === '') {
            throw new \Exception('Invalid input array. Must contain a non-empty column.');
        }
        if (!array_key_exists('value', $array)) {
            throw new \Exception('Invalid input array. Must contain a value field.');
        }

        if (is_array($array['value']) && isset($array['value']['$type'])) {
            $array['value'] = new RuleValue($array['value']['value'] ?? null, $array['value']['$type']);
        } // TODO: Always create a RuleValue? also for static? Or also: always unwrap static values?

        return new RuleOperation($array['operator'], $array['column'], $array['value']);
    }
}
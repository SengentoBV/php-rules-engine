<?php /** @noinspection PhpDocMissingThrowsInspection */

namespace SengentoBV\PhpRulesEngine;

use JsonSerializable;

class RuleOperation extends RuleObject
{
    public string $operator;
    public string $column;

    /**
     * @var RuleValue
     */
    public RuleValue $value;


    /**
     * @param string $operator
     * @param string $column
     * @param $value
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function __construct(string $operator, string $column, $value = null)
    {
        $this->operator = $operator;
        $this->column = $column;
        $this->value = $value instanceof RuleValue ? $value : new RuleValue($value, RuleValue::TYPE_STATIC);
    }

    public function isLogical(): bool
    {
        return false;
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
        } else {
            $array['value'] = new RuleValue($array['value']['value'] ?? null, RuleValue::TYPE_STATIC);
        }

        return new RuleOperation($array['operator'], $array['column'], $array['value']);
    }

    public function getColumnValue(array $row)
    {
        return $row[$this->column] ?? null;
    }
}
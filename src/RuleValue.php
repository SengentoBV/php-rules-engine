<?php

namespace SengentoBV\PhpRulesEngine;

use JsonSerializable;

class RuleValue extends RuleObject
{
    public const TYPE_STATIC = 'static';
    public const TYPE_FIELD = 'field';

    public string $type;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @throws \Exception
     */
    public function __construct($value, string $type = self::TYPE_STATIC)
    {
        if (!in_array($type, [self::TYPE_STATIC, self::TYPE_FIELD])) {
            throw new \Exception('Invalid value type.');
        }

        if ($type === self::TYPE_FIELD && (!is_string($value) || $value === '')) {
            throw new \Exception('Field value must be a column name.');
        }

        $this->value = $value;
        $this->type = $type;
    }

    public function toArray(): array
    {
        /*if ($this->type === self::TYPE_STATIC) {
            return [$this->value];
        }*/

        return [
          '$type' => $this->type,
          'value' => $this->value instanceof JsonSerializable ? $this->value->jsonSerialize() : $this->value,
        ];
    }

    public function jsonSerialize()
    {
        if ($this->type === self::TYPE_STATIC) {
            return $this->value;
        }

        return parent::jsonSerialize();
    }

    public function getValue(array $row)
    {
        if ($this->type === self::TYPE_FIELD) {
            return $row[$this->value] ?? null;
        }

        return $this->value;
    }
}
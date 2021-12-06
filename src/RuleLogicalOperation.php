<?php

namespace SengentoBV\PhpRulesEngine;

use Exception;

class RuleLogicalOperation extends RuleOperation
{
    public const OP_AND = 'AND';
    public const OP_OR = 'OR';

    /**
     * @var RuleObject[]
     */
    public array $children = [];

    /**
     * @param string $operator
     * @param RuleOperation[] $children
     * @throws Exception
     */
    public function __construct(string $operator, array $children)
    {
        // Uppercase the operator
        $operator = strtoupper($operator);

        if (!in_array($operator, [self::OP_AND, self::OP_OR], true))
            throw new Exception('Invalid operator.');

        // Validate the 'logical operator' children, as the logical operator should be the inverse of this' instances operation

        foreach ($children as $child) {
            if ($child instanceof RuleLogicalOperation && $child->operator === $operator)
                throw new Exception('Logical child operations cannot have the same logical operator as their parent.');
        }

        parent::__construct($operator, '', null);

        $this->children = $children;
    }

    public function toArray(): array
    {
        if ($this->operator === RuleLogicalOperation::OP_AND) {
            return $this->children;
        }

        return [$this->operator => $this->children];
    }
}
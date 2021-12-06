<?php /** @noinspection PhpDocMissingThrowsInspection */

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
        return [$this->operator => $this->children];
    }

    /**
     * @param array $array
     * @return RuleLogicalOperation
     * @noinspection PhpUnhandledExceptionInspection
     */
    public static function fromArray(array $array): RuleLogicalOperation
    {
        $operator = 'AND';
        $children = $array;

        if (array_key_exists(self::OP_AND, $array)) {
            $operator = self::OP_AND;
            $children = $array[self::OP_AND];
            //  return new RuleLogicalOperation(self::OP_OR, $array[self::OP_AND]);
        }
        if (array_key_exists(self::OP_OR, $array)) {
            $operator = self::OP_OR;
            $children = $array[self::OP_OR];
        }

        $children = array_map(fn($child) => (isset($child[self::OP_AND]) || isset($child[self::OP_OR])) ? RuleLogicalOperation::fromArray($child) : RuleOperation::fromArray($child), $children);

        // Otherwise AND is assumed
        return new RuleLogicalOperation($operator, $children);
    }
}
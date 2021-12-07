<?php /** @noinspection PhpDocMissingThrowsInspection */

namespace SengentoBV\PhpRulesEngine;

class RuleBuilder
{
    private array $context = [];

    public function operator(string $operator, string $column, $value): RuleOperation
    {
        return new RuleOperation($operator, $column, $value);
    }

    /**
     * @param RuleOperation ...$operations
     * @return RuleLogicalOperation
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function logicalAnd(...$operations): RuleLogicalOperation
    {
        return new RuleLogicalOperation(RuleLogicalOperation::OP_AND, $operations);
    }

    /**
     * @param RuleOperation ...$operations
     * @return RuleLogicalOperation
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function logicalOr(...$operations): RuleLogicalOperation
    {
        return new RuleLogicalOperation(RuleLogicalOperation::OP_OR, $operations);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function field(string $column): RuleValue
    {
        return new RuleValue($column, RuleValue::TYPE_FIELD);
    }

    public function build(): ?Rule
    {

    }
}

//$x = new RuleBuilder();

//Rule::build(fn(RuleBuilder $rb) => $rb->operator('x', 'a')->operator())

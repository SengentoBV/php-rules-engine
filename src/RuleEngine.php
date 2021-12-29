<?php

namespace SengentoBV\PhpRulesEngine;

class RuleEngine
{
    private array $operators = [];

    public function registerCommonOperators()
    {
        $this->operators['==='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) === $operation->value->get($row);
        $this->operators['=='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) == $operation->value->get($row);
        $this->operators['!='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) != $operation->value->get($row);
        $this->operators['<>'] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) <> $operation->value->get($row);
        $this->operators['!=='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) !== $operation->value->get($row);
        $this->operators['<'] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) < $operation->value->get($row);
        $this->operators['>'] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) > $operation->value->get($row);
        $this->operators['>='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) >=$operation->value->get($row);
        $this->operators['<='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) <= $operation->value->get($row);
        $this->operators['true'] = fn(array $row, RuleOperation $operation) => true;
        $this->operators['false'] = fn(array $row, RuleOperation $operation) => false;
    }

    public function __construct()
    {

    }

    /**
     * @param string $operator
     * @param RuleOperationCallableInterface|callable $callback
     * @return void
     */
    public function registerOperator(string $operator, callable $callback)
    {
        $this->operators[$operator] = $callback;
    }

    public function test(Rule $rule, array $row): bool
    {
        return $this->testLogicalOperation($rule->entryPoint, $row);
    }

    /**
     * @param string $operator
     * @return RuleOperationCallableInterface|callable
     */
    protected function getOperatorCallback(string $operator): callable
    {
        return $this->operators[$operator];
    }

    protected function testLogicalOperation(RuleLogicalOperation $logicalOperation, array $row): bool
    {
        /** @var RuleLogicalOperation $subLogical */
        $subLogical = $logicalOperation->children[RuleLogicalOperation::OP_AND] ?? $logicalOperation->children[RuleLogicalOperation::OP_OR] ?? null;

        if ($subLogical !== null && $subLogical->isLogicalAnd()) {
            if (!$this->testLogicalOperation($subLogical, $row)) {
                return false; // Short-circuit
            }
        }

        $testedValid = false;

        foreach ($logicalOperation->children as $childKey => $child) {
            if ($child instanceof RuleLogicalOperation && is_string($childKey)) {
                continue;
            }

            try {
                $testedValid = $child instanceof RuleLogicalOperation ? $this->testLogicalOperation($child, $row) : $this->getOperatorCallback($child->operator)($row, $child);
            } catch (RuleColumnMissingException $e) {
                // When a column is missing, we assume it's false
                $testedValid = false;
            }

            if ($logicalOperation->isLogicalAnd() && !$testedValid) {
                return false; // Short-circuit
            } else if (!$logicalOperation->isLogicalAnd() && $testedValid) {
                return true; // Short-circuit
            }
        }

        // Still ok at this point (or no other rules)
        // This could still be valid IF there are any OR sub-logical operation that matches
        if ($subLogical !== null && !$subLogical->isLogicalAnd()) {
            return $this->testLogicalOperation($subLogical, $row);
        }

        return $testedValid;
    }
}
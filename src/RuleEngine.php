<?php

namespace SengentoBV\PhpRulesEngine;

class RuleEngine
{
    private array $operators = [];

    public function registerCommonOperators()
    {
        $this->operators['==='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) === $operation->value->getValue($row);
        $this->operators['=='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) == $operation->value->getValue($row);
        $this->operators['!='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) != $operation->value->getValue($row);
        $this->operators['<>'] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) <> $operation->value->getValue($row);
        $this->operators['!=='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) !== $operation->value->getValue($row);
        $this->operators['<'] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) < $operation->value->getValue($row);
        $this->operators['>'] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) > $operation->value->getValue($row);
        $this->operators['>='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) >=$operation->value->getValue($row);
        $this->operators['<='] = fn(array $row, RuleOperation $operation) => $operation->getColumnValue($row) <= $operation->value->getValue($row);
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

    public function test(Rule $rule): bool
    {
        $logicalOperation = $rule->entryPoint->operator;
    }
}
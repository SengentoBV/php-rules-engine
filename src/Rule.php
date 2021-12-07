<?php

namespace SengentoBV\PhpRulesEngine;

class Rule extends RuleObject
{
    public RuleLogicalOperation $entryPoint;

    public function __construct(RuleLogicalOperation $entryPoint)
    {
        $this->entryPoint = $entryPoint;
    }

    /**
     * @param RuleOperationCallableInterface|callable $rb
     * @return Rule
     */
    public static function build(callable $rb): Rule
    {
        $ruleBuilder = new RuleBuilder();

        return new Rule($rb($ruleBuilder));
    }

    public function toArray(): array
    {
        if ($this->entryPoint->operator === RuleLogicalOperation::OP_AND) {
            return $this->entryPoint->children;
        }

        return $this->entryPoint->toArray();
    }

    public static function fromArray(array $array): Rule
    {
        return new Rule(RuleLogicalOperation::fromArray($array));
    }
}
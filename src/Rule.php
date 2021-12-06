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
     * @param RuleBuilderCallableInterface|callable $rb
     * @return Rule
     */
    public static function build(callable $rb): Rule
    {
        $ruleBuilder = new RuleBuilder();

        return new Rule($rb($ruleBuilder));
    }

    public function toArray(): array
    {
        return $this->entryPoint->toArray();
    }
}
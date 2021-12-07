<?php

namespace SengentoBV\PhpRulesEngine;

interface RuleBuilderCallableInterface
{
    /**
     * @param RuleBuilder $rb
     *
     * @return RuleLogicalOperation
     */
    public function __invoke(RuleBuilder $rb): RuleLogicalOperation;
}
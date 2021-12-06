<?php

namespace SengentoBV\PhpRulesEngine;

interface RuleOperationCallableInterface
{
    public function __invoke(array $row, RuleOperation $operation): bool;
}
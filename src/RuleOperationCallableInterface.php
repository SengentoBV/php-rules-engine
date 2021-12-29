<?php

namespace SengentoBV\PhpRulesEngine;

interface RuleOperationCallableInterface
{
    /**
     * @param array $row
     * @param RuleOperation $operation
     * @return bool
     * @throws RuleColumnMissingException When the column is missing.
     */
    public function __invoke(array $row, RuleOperation $operation): bool;
}
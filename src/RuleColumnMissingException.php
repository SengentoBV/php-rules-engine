<?php

namespace SengentoBV\PhpRulesEngine;

use Throwable;

class RuleColumnMissingException extends \Exception
{
    private string $columnName;

    public function columnName() : string
    {
        return $this->columnName;
    }

    public function __construct(string $columnName, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = ($message === null || $message === '') ? "A column is missing." : $message;

        parent::__construct($message, $code, $previous);

        $this->columnName = $columnName;
    }
}
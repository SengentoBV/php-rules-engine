<?php

require_once __DIR__ . '/vendor/autoload.php';

use SengentoBV\PhpRulesEngine\Rule;
use SengentoBV\PhpRulesEngine\RuleBuilder;
use SengentoBV\PhpRulesEngine\RuleEngine;
use SengentoBV\PhpRulesEngine\RuleOperation;

$rule = Rule::build(fn(RuleBuilder $rb) => $rb->logicalAnd(
  $rb->operator('str_contains', 'column1', 'a'),
  $rb->operator('str_contains', 'column2', 'b'),
  $rb->logicalOr(
    $rb->operator('str_contains', 'column3', 'c'),
    $rb->operator('str_contains', 'column3', 'd'),
    $rb->logicalAnd(
      $rb->logicalOr(
        $rb->operator('str_contains', 'column3', 'bleh'),
        $rb->operator('str_contains', 'column3', $rb->field('column1')),
      )
    )
  )
));

if (!function_exists('str_contains2') && function_exists('mb_strpos')) {
    function str_contains2($haystack, $needle) {
        echo $haystack . ' => ' . $needle . PHP_EOL;
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
} else if (!function_exists('str_contains2') && !function_exists('mb_strpos')) {
    function str_contains2($haystack, $needle) {
        return $needle !== '' && strpos($haystack, $needle) !== false;
    }
}

// var_dump($rule);

$json = json_encode($rule);
$jsonArray = json_decode($json, true);

echo $json . PHP_EOL;
$rule2 = Rule::fromArray($jsonArray);
$json2 = json_encode($rule2);

echo $json2 . PHP_EOL;

$row = [
  'column1' => 'aaa',
  'column2' => 'b',
  'column3' => 'aaa',
];
$ruleEngine = new RuleEngine();
$ruleEngine->registerCommonOperators();
$ruleEngine->registerOperator('str_contains', fn(array $row, RuleOperation $operation) => str_contains2($operation->getColumnValue($row), $operation->value->get($row)));
echo $ruleEngine->test($rule, $row) ? 'Matches!' : 'Does not match!';

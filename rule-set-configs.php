<?php

require __DIR__ . '/utils/utils.php';

use PhpCsFixer\RuleSet\RuleSet;

$ruleSetNames = array_slice($argv, 1);

if (!$ruleSetNames) {
    die("Please provide at least one Rule Set Name.");
}

$delimiter = "\t";
$output = "";
$fixers = getAvailableFixers();

$rulesInRuleSets = [];

foreach ($ruleSetNames as $ruleSetName) {
    $rulesInRuleSets[] = (new RuleSet(["@$ruleSetName" => true]))->getRules();
    $output .= $ruleSetName . $delimiter;
}

$output = rtrim($output, $delimiter) . PHP_EOL;

foreach ($fixers as $fixer) {
    foreach ($rulesInRuleSets as $rulesInRuleSet) {
        $rule = $rulesInRuleSet[$fixer->getName()] ?? null;

        if ($rule === true) {
            $output .= "✅";
        }
        elseif ($rule === false) {
            $output .= "❌";
        }
        elseif ($rule !== null) {
            $output .= var_export_modern($rule, true);
        }
        // else: fixer not in rule set, output nothing

        $output .= $delimiter;
    }

    $output = rtrim($output, $delimiter) . PHP_EOL;
}

file_put_contents(__DIR__ . '/output/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.tsv', $output);
echo $output;

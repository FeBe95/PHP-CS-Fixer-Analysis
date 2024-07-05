<?php

require __DIR__ . '/utils/utils.php';

$output = "";
$fixers = getAvailableFixers();

foreach ($fixers as $fixer) {
    $riskyDesc = $fixer->getDefinition()->getRiskyDescription();

    if ($riskyDesc) {
        $output .= $riskyDesc;
    }

    $output .= PHP_EOL;
}

file_put_contents(__DIR__ . '/output/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.tsv', $output);
echo $output;

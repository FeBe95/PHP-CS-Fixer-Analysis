<?php

require __DIR__ . '/utils/utils.php';

use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerFactory;

$output = "";
$fixers = getAvailableFixers();

foreach ($fixers as $fixer) {
    if ($fixer instanceof ConfigurableFixerInterface) {
        $configOptions = $fixer->getConfigurationDefinition()->getOptions();

        $configDefaults = [];

        foreach ($configOptions as $configOption) {
            if ($configOption->hasDefault()) {
                $configDefaults[$configOption->getName()] = $configOption->getDefault();
            }
        }
        $output .= var_export_modern($configDefaults, true);
    }

    $output .= PHP_EOL;
}

file_put_contents(__DIR__ . '/output/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.tsv', $output);
echo $output;

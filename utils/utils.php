<?php

require __DIR__ . '/../../vendor/autoload.php';

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\ToolInfo;

/**
 * @return FixerInterface[]
 */
function getAvailableFixers(): array
{
    $fixerFactory = new FixerFactory();
    $fixerFactory->registerBuiltInFixers();

    $fixers = $fixerFactory->getFixers();
    usort($fixers, fn (FixerInterface $a, FixerInterface $b) => $a->getName() <=> $b->getName());

    return $fixers;
}

function getBaseConfig(): ConfigInterface {
    $configResolver = new ConfigurationResolver(new Config(), [], getcwd(), new ToolInfo());
    $baseConfig = $configResolver->getConfig();
    $baseConfig->setRules([]);

    return $baseConfig;
}

function var_export_modern($var, $return = false): ?string
{
    if (!is_array($var)) {
        if ($var === null) {
            return 'null'; // instead of 'NULL' by var_export()
        }
        return var_export($var, $return);
    }

    $arrayItems = [];

    if (is_array_sequential($var)) {
        foreach ($var as $value) {
            $arrayItems[] = var_export_modern($value, true);
        }
    }
    else {
        foreach ($var as $key => $value) {
            $arrayItems[] = var_export($key, true) . ' => ' . var_export_modern($value, true);
        }
    }

    $code = '[' . implode(', ', $arrayItems) . ']';

    if ($return) {
        return $code;
    }

    echo $code;
    return null;
}

function is_array_sequential($arr): bool
{
    return array_keys($arr) === range(0, count($arr) - 1);
}

function clearLine(): void
{
    echo "\033[2K\r";
}

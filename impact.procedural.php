<?php

require __DIR__ . '/utils/utils.php';

use PhpCsFixer\Config;
use PhpCsFixer\Console\Command\WorkerCommand;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixer\Runner\Runner;
use PhpCsFixer\ToolInfo;
use Symfony\Component\Console\Input\ArgvInput;

set_time_limit(60 * 60);
header('Content-Type: text/plain');

$config = getBaseConfig();
$toolInfo = new ToolInfo();

$resolver = new ConfigurationResolver(
    $config,
    [
        'allow-risky' => 'yes',
        'using-cache' => 'no', // is actually quicker somehow (25.0 vs 25.3 seconds for first 10 fixers)
        'dry-run' => true,
        'stop-on-violation' => false,
    ],
    getcwd(),
    $toolInfo,
);

$finder = new ArrayIterator(iterator_to_array($resolver->getFinder()));
$totalFiles = count($finder);

$fixers = getAvailableFixers();

$workerCommand = new WorkerCommand($toolInfo);

file_put_contents(__DIR__ . '/output/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.tsv', '');

foreach ($fixers as $fixer) {
    $ruleName = $fixer->getName();

    $input = new ArgvInput(
        [
            $argv[0],
            '--allow-risky=yes',
            "--rules=$ruleName",
        ],
        $workerCommand->getNativeDefinition(),
    );

    $runner = new Runner(
        $finder,
        $resolver->getFixers(),
        $resolver->getDiffer(),
        null,
        new ErrorsManager(),
        $resolver->getLinter(),
        $resolver->isDryRun(),
        $resolver->getCacheManager(),
        $resolver->getDirectory(),
        $resolver->shouldStopOnViolation(),
        $resolver->getParallelConfig(),
        $input,
    );

    $changed = count($runner->fix());

    $output = "$ruleName\t$changed\t$totalFiles" . PHP_EOL;

    file_put_contents(__DIR__ . '/output/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.tsv', $output, FILE_APPEND);
    echo $output;
}

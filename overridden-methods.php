<?php

require __DIR__ . '/utils/utils.php';

$verbosityOption = $argv[1] ?? null;

$verbosityLevel = match ($verbosityOption) {
    '-v', '--verbose' => 1,
    '-vv', '--very-verbose' => 2,
    default => 0,
};

$outputMatchedFiles = $verbosityLevel >= 1;
$outputMatches      = $verbosityLevel >= 2;

header("Content-type: text/plain");

/*
$listOfFunctionsAndMethods = file_get_contents('https://www.php.net/manual/en/indexes.functions.php');

// find global functions that are not scoped (without '::' or '\')
preg_match_all('@<li><a href=".*?" class="index">(\w+?)</a> - .*?</li>@s', $listOfFunctionsAndMethods, $matches);

$globalFunctions = $matches[1];
*/

// after initial check, speed up output with extracted list:
$globalFunctions = [
    'closelog',  // (1)
    'curl_exec', // (1)
    'delete',    // (4)
    'gettype',   // (1)
    'list',      // (1)
    'rename',    // (1)
    'round',     // (1)
    'sort',      // (1)
];

$gitRoot = exec('git rev-parse --show-toplevel');

$current = 0;
$total = count($globalFunctions);
$pad = 0;

foreach ($globalFunctions as $functionName) {
    $current++;

    echo "Checking function $current of $total\r";

    $countOpt = $outputMatches ? '--line-number' : '--count'; // or '--name-only'
    $command = [
        'git grep',
        $countOpt,
        // '--only-matching', // don't output whole line, just matching string
        '--full-name', // always print out whole path relative to git root (even if run from a subdirectory)
        '--ignore-case',
        '--word-regexp',
        "\"function $functionName\"",
        '--',
        '"*.php"',
        '2>&1',
    ];

    $res = shell_exec(implode(' ', $command));

    clearLine();

    if ($res) {
        $files = explode("\n", trim($res));
        $fileCount = count($files);

        echo "$functionName ($fileCount)" . PHP_EOL;

        if ($outputMatchedFiles) {
            foreach ($files as $file) {
                echo "  - $gitRoot/$file" . PHP_EOL;
            }
            echo PHP_EOL;
        }
    }
}

echo  PHP_EOL . "Checked $current/$total function names";

// Analysis result:
// - function needs to be global in a namespace, not in a class
// - `curl_exec()` is the only function that uses global namespace override

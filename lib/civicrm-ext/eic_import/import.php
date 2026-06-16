<?php
/**
 * CiviCRM CSV Importer — cv php:script entry point
 *
 * CiviCRM is already bootstrapped by cv before this script runs.
 * Do NOT call CRM_Core_Config::singleton() or require civicrm.settings.php here.
 *
 * Usage:
 *   cv php:script import.php organisations --file=orgs.csv
 *   cv php:script import.php organisations --file=orgs.csv --dry-run
 *   cv php:script import.php organisations --file=orgs.csv --update-existing
 *   cv php:script import.php organisations --file=orgs.csv --delimiter=","
 *   cv php:script import.php organisations --file=orgs.csv --skip-rows=1
 *   cv php:script import.php organisations --file=orgs.csv --log=import.log
 *   cv php:script import.php --help
 *
 * Available commands:
 *   organisations   Import Organisation contacts from CSV
 *
 * Options:
 *   --file=PATH          Path to the CSV file (required)
 *   --delimiter=CHAR     Column delimiter (default: ;)
 *   --skip-rows=N        Skip N rows before the header (default: 0)
 *   --dry-run            Parse and validate without writing to CiviCRM
 *   --update-existing    Update matched organisations instead of skipping
 *   --log=PATH           Write log output to file in addition to stdout
 *   --help               Show this help text
 *
 * Notes on cv php:script argument passing:
 *   cv passes everything after the script filename verbatim in $argv.
 *   $argv[0] is the script path; our command starts at $argv[1].
 *   The cv binary itself and any cv flags (e.g. --user) are NOT in $argv.
 */

declare(strict_types=1);

// ---------------------------------------------------------------------------
// Load application classes
// CiviCRM core classes (\Civi\Api4\*, CRM_*) are already available.
// ---------------------------------------------------------------------------

$srcDir = __DIR__ . '/src';

require_once $srcDir . '/Logger/ImportLogger.php';
require_once $srcDir . '/Logger/ImportStats.php';
require_once $srcDir . '/Parser/CsvParser.php';
require_once $srcDir . '/Config/FieldMap.php';
require_once $srcDir . '/Importer/BaseImporter.php';
require_once $srcDir . '/Importer/OrganisationImporter.php';
require_once $srcDir . '/Importer/PersonImporter.php';
require_once $srcDir . '/Importer/ProjectImporter.php';
require_once $srcDir . '/Importer/ContactImporter.php';
require_once $srcDir . '/Importer/ConsortiumImporter.php';
require_once $srcDir . '/Importer/SectorImporter.php';

// ---------------------------------------------------------------------------
// Parse arguments
// ---------------------------------------------------------------------------
// $argv layout when called via cv php:script:
//   $argv[0]  = /path/to/import.php   (script path — set by cv)
//   $argv[1]  = command               (e.g. "organisations")
//   $argv[2+] = --flag / --key=value options

$args    = $argv;
$script  = array_shift($args);  // consume script path
$command = array_shift($args) ?? '';

$opts = [];
foreach ($args as $arg) {
    if (preg_match('/^--([a-zA-Z][a-zA-Z0-9\-]*)(?:=(.+))?$/', $arg, $m)) {
        $opts[$m[1]] = $m[2] ?? true;
    }
}

// ---------------------------------------------------------------------------
// Help
// ---------------------------------------------------------------------------

if (isset($opts['help']) || $command === '' || $command === '--help') {
    // Print the doc-block at the top of this file as help text
    $src   = file_get_contents(__FILE__);
    $start = strpos($src, '/*');
    $end   = strpos($src, '*/') + 2;
    echo substr($src, $start, $end - $start) . PHP_EOL;
    exit(0);
}

// ---------------------------------------------------------------------------
// Validate command
// ---------------------------------------------------------------------------

$validCommands = ['organisations', 'persons', 'projects', 'contacts', 'consortiums', 'sectors'];

if (!in_array($command, $validCommands, true)) {
    fwrite(STDERR, "Unknown command: '{$command}'\n");
    fwrite(STDERR, "Valid commands: " . implode(', ', $validCommands) . "\n");
    fwrite(STDERR, "Run: cv php:script import.php --help\n");
    exit(1);
}

if (empty($opts['file'])) {
    fwrite(STDERR, "Error: --file is required.\n");
    fwrite(STDERR, "Example: cv php:script import.php {$command} --file=data.csv\n");
    exit(1);
}

// Resolve relative paths against the working directory cv was invoked from,
// which is available in $_SERVER['PWD'] (more reliable than __DIR__ here).
$filePath = $opts['file'];
if (!file_exists($filePath)) {
    $fromPwd = ($_SERVER['PWD'] ?? getcwd()) . DIRECTORY_SEPARATOR . $filePath;
    if (file_exists($fromPwd)) {
        $filePath = $fromPwd;
    }
}

$config = [
    'file'            => $filePath,
    'delimiter'       => $opts['delimiter']       ?? ';',
    'skip_rows'       => (int) ($opts['skip-rows'] ?? 0),
    'dry_run'         => isset($opts['dry-run']),
    'update_existing' => isset($opts['update-existing']),
    'log_file'        => $opts['log']             ?? null,
];

// ---------------------------------------------------------------------------
// Run
// ---------------------------------------------------------------------------

try {
    $logger = new ImportLogger($config['log_file']);
    $stats  = new ImportStats();

    match ($command) {
        'organisations' => (new OrganisationImporter($config, $logger, $stats))->run(),
        'persons' => (new PersonImporter($config, $logger, $stats))->run(),
        'projects' => (new ProjectImporter($config, $logger, $stats))->run(),
        'contacts' => (new ContactImporter($config, $logger, $stats))->run(),
        'consortiums' => (new ConsortiumImporter($config, $logger, $stats))->run(),
        'sectors' => (new SectorImporter($config, $logger, $stats))->run(),
    };

    // Non-zero exit if any rows failed — useful for cron/CI alerting.
    // cv propagates the exit code to the shell.
    exit($stats->failed > 0 ? 1 : 0);

} catch (Throwable $e) {
    fwrite(STDERR, "\nFatal: " . $e->getMessage() . "\n");
    fwrite(STDERR, $e->getTraceAsString() . "\n");
    exit(2);
}
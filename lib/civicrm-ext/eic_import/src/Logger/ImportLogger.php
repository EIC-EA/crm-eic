<?php
/**
 * src/Logger/ImportLogger.php
 *
 * Writes timestamped log lines to STDOUT (and optionally a file).
 * Errors also go to STDERR.
 *
 * Log levels: INFO, WARN, ERROR, DEBUG
 */

declare(strict_types=1);

class ImportLogger
{
    private ?string $logFilePath;

    /** @var resource|null */
    private $logFileHandle = null;

    public function __construct(?string $logFilePath = null)
    {
        $this->logFilePath = $logFilePath;

        if ($logFilePath) {
            $this->logFileHandle = fopen($logFilePath, 'a');
            if ($this->logFileHandle === false) {
                fwrite(STDERR, "WARNING: Cannot open log file: {$logFilePath}\n");
                $this->logFileHandle = null;
            }
        }
    }

    public function __destruct()
    {
        if ($this->logFileHandle) {
            fclose($this->logFileHandle);
        }
    }

    public function info(string $message): void
    {
        $this->write('INFO ', $message);
    }

    public function warn(string $message): void
    {
        $this->write('WARN ', $message);
    }

    public function error(string $message): void
    {
        $this->write('ERROR', $message, true);
    }

    public function debug(string $message): void
    {
        // Only output in debug mode — check env var
        if (getenv('IMPORT_DEBUG')) {
            $this->write('DEBUG', $message);
        }
    }

    public function blank(): void
    {
        echo PHP_EOL;
    }

    public function section(string $title): void
    {
        $line = str_repeat('─', 60);
        $this->info($line);
        $this->info($title);
        $this->info($line);
    }

    // -------------------------------------------------------------------------

    private function write(string $level, string $message, bool $stderr = false): void
    {
        $line = sprintf(
            '[%s] [%s] %s',
            date('Y-m-d H:i:s'),
            $level,
            $message
        );

        if ($stderr) {
            fwrite(STDERR, $line . PHP_EOL);
        } else {
            echo $line . PHP_EOL;
        }

        if ($this->logFileHandle) {
            fwrite($this->logFileHandle, $line . PHP_EOL);
        }
    }
}

<?php
/**
 * src/Logger/ImportStats.php
 *
 * Tracks counts and collects per-row error details across an import run.
 */

declare(strict_types=1);

class ImportStats
{
    public int $total   = 0;
    public int $created = 0;
    public int $updated = 0;
    public int $skipped = 0;
    public int $failed  = 0;

    /** @var array<int, array{line: int, pic: string, error: string}> */
    public array $errors = [];

    public function recordError(int $lineNumber, string $pkVal, string $message): void
    {
        $this->failed++;
        $this->errors[] = [
            'line'  => $lineNumber,
            'pkVal'   => $pkVal,
            'error' => $message,
        ];
    }

    public function processed(): int
    {
        return $this->created + $this->updated + $this->skipped + $this->failed;
    }

    public function printSummary(ImportLogger $logger): void
    {
        $logger->blank();
        $logger->section('Import Summary');
        $logger->info(sprintf('  Total rows processed : %d', $this->total));
        $logger->info(sprintf('  Created              : %d', $this->created));
        $logger->info(sprintf('  Updated              : %d', $this->updated));
        $logger->info(sprintf('  Skipped (duplicate)  : %d', $this->skipped));
        $logger->info(sprintf('  Failed               : %d', $this->failed));

        if (!empty($this->errors)) {
            $logger->blank();
            $logger->error('Rows that failed:');
            foreach ($this->errors as $err) {
                $logger->error(sprintf(
                    '  Line %-5d | PKVAL: %-20s | %s',
                    $err['line'],
                    ($err['pkVal'] ?: '(none)'),
                    $err['error']
                ));
            }
        }
    }
}

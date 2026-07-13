<?php
/**
 * src/Parser/CsvParser.php
 *
 * Reads a semicolon-delimited (or custom delimiter) CSV file and yields
 * one associative row at a time, keyed by the header row.
 *
 * Handles:
 *  - UTF-8 BOM stripping
 *  - Configurable delimiter
 *  - Optional leading rows to skip before the header
 *  - Quoted fields containing the delimiter
 *  - Empty trailing rows
 */

declare(strict_types=1);

class CsvParser
{
    private string $filePath;
    private string $delimiter;
    private int    $skipRows;

    /** @var string[] */
    private array $headers = [];

    public function __construct(string $filePath, string $delimiter = ';', int $skipRows = 0)
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("CSV file not found: {$filePath}");
        }

        $this->filePath  = $filePath;
        $this->delimiter = $delimiter;
        $this->skipRows  = $skipRows;
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Returns the header column names.
     * Opens the file to read them if not already loaded.
     */
    public function headers(): array
    {
        if (empty($this->headers)) {
            $fh = $this->openFile();
            $this->headers = $this->readHeaders($fh);
            fclose($fh);
        }
        return $this->headers;
    }

    /**
     * Yields each data row as an associative array keyed by header name.
     *
     * @return Generator<int, array<string,string>>
     */
    public function rows(): Generator
    {
        $fh = $this->openFile();

        // Read (and cache) headers
        $this->headers = $this->readHeaders($fh);

        $lineNumber = $this->skipRows + 2; // account for skipped rows + header

        while (!feof($fh)) {
            $raw = fgetcsv($fh, 0, $this->delimiter, '"', '');

            if ($raw === false) {
                break;
            }

            // Skip completely blank lines
            if (count($raw) === 1 && ($raw[0] === null || trim($raw[0]) === '')) {
                $lineNumber++;
                continue;
            }

            // Pad or trim to match header count
            $raw = array_slice(
                array_pad($raw, count($this->headers), ''),
                0,
                count($this->headers)
            );

            $row = array_combine($this->headers, $raw);

            // Trim all values
            $row = array_map('trim', $row);

            yield $lineNumber => $row;
            $lineNumber++;
        }

        fclose($fh);
    }

    /**
     * Count data rows (excluding header and skipped rows).
     * Reads the file once — use for progress reporting before streaming.
     */
    public function countRows(): int
    {
        $count = 0;
        foreach ($this->rows() as $_) {
            $count++;
        }
        return $count;
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /** @return resource */
    private function openFile()
    {
        $fh = fopen($this->filePath, 'r');
        if ($fh === false) {
            throw new RuntimeException("Cannot open file: {$this->filePath}");
        }

        // Strip UTF-8 BOM if present
        $bom = fread($fh, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($fh); // Not a BOM — rewind
        }

        // Skip leading non-header rows
        for ($i = 0; $i < $this->skipRows; $i++) {
            fgetcsv($fh, 0, $this->delimiter, '"', '');
        }

        return $fh;
    }

    /** @param resource $fh */
    private function readHeaders($fh): array
    {
        $raw = fgetcsv($fh, 0, $this->delimiter, '"', '');

        if ($raw === false || empty($raw)) {
            throw new RuntimeException("CSV file is empty or header row could not be read.");
        }

        // Trim each header and strip BOM from the very first cell (belt-and-suspenders)
        return array_map(
            fn(string $h) => trim(ltrim($h, "\xEF\xBB\xBF")),
            $raw
        );
    }
}

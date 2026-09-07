<?php
// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

declare(strict_types = 1);

use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

/**
 * @phpstan-type TJsonData = array{
 *     name: NULL|string,
 * }
 *
 *
 * @phpstan-type TImportResult = array{
 *     import: array{
 *       original_id: NULL|int,
 *       new_id: int,
 *     },
 *     is_error: NULL|int,
 *     error_message: NULL|string,
 *  }
 *
 * @phpstan-type THasFPResult = array{
 *     count: int,
 * }
 */
class CRM_EicEuSurveyFormProcessor_Importer_FormProcessorJsonImporter {

  public const IMPORT_MODE_SETTING_KEY = 'eic_eu_survey_form_processor_import_mode';

  public static function create(): CRM_EicEuSurveyFormProcessor_Importer_FormProcessorJsonImporter {
    return new CRM_EicEuSurveyFormProcessor_Importer_FormProcessorJsonImporter();
  }

  /**
   * @throws \RuntimeException
   *
   */
  public function import(string $jsonFilePath): bool {
    $jsonData = $this->getJsonFromConfigFile($jsonFilePath);

    $fpName = $this->getFormProcessorName($jsonData);

    $formProcessorExistsInCiviCrm = $this->doesFormProcessorExists($fpName);

    if (!$this->isImportAllowed($formProcessorExistsInCiviCrm)) {
      return FALSE;
    }

    $this->importFormProcessor($jsonFilePath);
    return TRUE;
  }

  protected function isImportAllowed(bool $formProcessorExistsInCiviCrm): bool {
    return (!$formProcessorExistsInCiviCrm || $this->importFormProcessorIfExists());
  }

  /**
   *
   * @throws \RuntimeException
   */
  protected function importFormProcessor(string $jsonFilePath): void {
    /** @phpstan-var TImportResult $result*/
    $result = civicrm_api3('FormProcessorInstance', 'import', [
      'file' => $jsonFilePath,
    ]);

    if (array_key_exists('is_error', $result)) {
      if (NULL !== $result['is_error'] && 1 === $result['is_error']) {
        throw new \RuntimeException('Form-Processor could not be imported: ' . $jsonFilePath);
      }
    }
  }

  protected function importFormProcessorIfExists(): bool {
    /** @phpstan-ignore return.type */
    return \Civi::settings()->get(self::IMPORT_MODE_SETTING_KEY) ?? FALSE;
  }

  /**
   *
   */
  protected function doesFormProcessorExists(string $fpName): bool {
    /** @phpstan-var THasFPResult $result */
    $result = civicrm_api3('FormProcessorInstance', 'get', [
      'return' => ['id', 'name'],
      'sequential' => 1,
      'name' => $fpName,
    ]);

    return (1 === $result['count']);
  }

  /**
   * @throws \RuntimeException
   * @param TJsonData $jsonData
   */
  protected function getFormProcessorName($jsonData): string {
    $fpName = $jsonData['name'];
    if (NULL === $fpName || '' === $fpName) {
      throw new \RuntimeException('Form-Processor config data has not name field: ' . json_encode($jsonData));
    }
    return $fpName;
  }

  /**
   * @throws \RuntimeException
   * @return TJsonData
   */
  protected function getJsonFromConfigFile(string $jsonFilePath) {
    if (!is_readable($jsonFilePath)) {
      throw new \RuntimeException(
        'Form-Processor config file has no read permissions or does not exist: ' . $jsonFilePath
      );
    }

    $fileContent = file_get_contents($jsonFilePath);
    if (FALSE === $fileContent) {
      throw new \RuntimeException(
        'Could not read content from Form-Processor config file: ' . $jsonFilePath
      );
    }
    /** @var NULL|TJsonData $jsonData */
    $jsonData = json_decode($fileContent, TRUE);
    if (NULL === $jsonData) {
      throw new \RuntimeException(
        'Could not decode content from Form-Processor config file to json: ' . $fileContent
      );
    }

    return $jsonData;
  }

}

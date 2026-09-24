<?php
// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

declare(strict_types = 1);

use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

/**
 * @phpstan-type TJsonData = array{
 *     name: NULL|string,
 *     value: NULL|string,
 * }
 *
 * @phpstan-type TJsonDataList = array<TJsonData>
 *
 */
class CRM_EicEuSurveyFormProcessor_Importer_CiviSettingsJsonImporter {

  public const IMPORT_MODE_SETTINGS_KEY = 'eic_eu_survey_settings_import_mode';

  public static function create(): CRM_EicEuSurveyFormProcessor_Importer_CiviSettingsJsonImporter {
    return new CRM_EicEuSurveyFormProcessor_Importer_CiviSettingsJsonImporter();
  }

  /**
   * @throws \RuntimeException
   *
   */
  public function import(string $jsonFilePath): bool {
    $jsonData = $this->getJsonFromConfigFile($jsonFilePath);

    $name = $this->getSettingsName($jsonData);
    $value = $this->getSettingsValue($jsonData);

    if (!$this->isImportAllowed($name)) {
      return FALSE;
    }

    $this->importSetting($name, $value);
    return TRUE;
  }

  /**
   * @param TJsonData $value
   */
  protected function importSetting(string $name, $value): void {
    \Civi::settings()->set($name, $value);
  }

  protected function isImportAllowed(string $name): bool {
    return (!$this->doesSettingEntryExists($name) || $this->importSettingsIfExists());
  }

  protected function importSettingsIfExists(): bool {
    if (!$this->doesSettingEntryExists(self::IMPORT_MODE_SETTINGS_KEY)) {
      return FALSE;
    }
    return (bool) \Civi::settings()->get(self::IMPORT_MODE_SETTINGS_KEY);
  }

  protected function doesSettingEntryExists(string $name): bool {
    return \Civi::settings()->hasExplicit($name);
  }

  /**
   * @throws \RuntimeException
   * @param TJsonData $jsonData
   */
  protected function getSettingsName($jsonData): string {
    $name = $jsonData['name'];
    if (NULL === $name || '' === $name) {
      throw new \RuntimeException('config json-data has no name field: ' . json_encode($jsonData));
    }
    return $name;
  }

  /**
   * @throws \RuntimeException
   * @param TJsonData $jsonData
   * @return TJsonData
   */
  protected function getSettingsValue($jsonData) {
    $value = $jsonData['value'];
    if (NULL === $value || '' === $value) {
      throw new \RuntimeException('config json-data has no value field: ' . json_encode($jsonData));
    }
    return $value;
  }

  /**
   * @throws \RuntimeException
   * @return TJsonData
   */
  protected function getJsonFromConfigFile(string $jsonFilePath) {
    if (!is_readable($jsonFilePath)) {
      throw new \RuntimeException(
        'Config file has no read permissions or does not exist: ' . $jsonFilePath
      );
    }

    $fileContent = file_get_contents($jsonFilePath);
    if (FALSE === $fileContent) {
      throw new \RuntimeException(
        'Could not read content from config file: ' . $jsonFilePath
      );
    }
    /** @var NULL|TJsonDataList $jsonData */
    $jsonDataList = json_decode($fileContent, TRUE);
    if (NULL === $jsonDataList) {
      throw new \RuntimeException(
        'Could not decode content from config file to json: ' . $fileContent
      );
    }

    return $jsonDataList[0];
  }

}

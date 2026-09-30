<?php
// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

declare(strict_types = 1);

/**
 * XcmProfileRemapper
 */
class CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper {

  public const MAP_API3_TO_API4 = 'mapApi3toApi4';
  public const MAP_API4_TO_API3 = 'mapApi4toApi3';

  private string $strategy;
  private array $mappingData;

  /**
   * @var callable
   *   The currently active remap function.
   */
  private $remapper;

  public static function create(string $strategy = CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper::MAP_API3_TO_API4): CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper {
    return new CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper($strategy);
  }

  public function __construct(
    string $strategy = CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper::MAP_API3_TO_API4) {
    $this->strategy = $strategy;

    // Set the mapping data.
    $this->mappingData = [
      'custom_' => $this->getCustomFieldMapping(),
      'DEDUPE_' => $this->getDeduplicationGroupMapping(),
    ];

    // Set the
    $this->remapper = match ($strategy) {
      CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper::MAP_API3_TO_API4,
      CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper::MAP_API4_TO_API3 => $this->remapValue(...),
      default => throw new \InvalidArgumentException("Unknown strategy '{$strategy}'"),
    };
  }

  /**
   * Helper function retrieving a map of all custom fields.
   *
   * The map is in form of an array keyed by the custom field id. The value contains an array of the following:
   * - id
   * - field name in the form customGroup.customFieldName
   *
   * @return array
   *   The map of custom fields. Key is the custom field id.
   */
  public function getCustomFieldMapping(): array {
    // Get all custom fields using API v4.
    $returnedData = \Civi\Api4\CustomField::get(FALSE)
      ->addSelect('id', 'name', 'custom_group_id:name')
      ->execute();

    // Convert result to array keyed by the field id.
    $returnedData = (array) $returnedData->indexBy('id');

    // Concatenate custom_group_id:name and name (as custom_group_id:name.name)
    $returnedData = array_map(function($item) {
      return sprintf("%s.%s", $item['custom_group_id:name'], $item['name']);
    }, $returnedData);

    // Sort array by key values.
    ksort($returnedData);

    // Return field mappings.
    return match($this->strategy) {
      CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper::MAP_API4_TO_API3 => array_flip($returnedData),
      default => $returnedData,
    };
  }

  /**
   * Helper function retrieving a map of all Deduplication Group Rules.
   *
   * The map is in form of an array keyed by the custom field id. The value contains an array of the following:
   * - id
   * - name
   *
   * @return array
   *   The map of custom fields. Key is the custom field id.
   */
  public function getDeduplicationGroupMapping(): array {
    // Get all custom fields using API v4.
    $returnedData = \Civi\Api4\DedupeRuleGroup::get(FALSE)
      ->addSelect('id', 'name')
      ->execute();

    // Convert result to array keyed by the field id.
    $returnedData = (array) $returnedData->indexBy('id');

    // Concatenate custom_group_id:name and name (as custom_group_id:name.name)
    $returnedData = array_map(function($item) {
      return $item['name'];
    }, $returnedData);

    // Sort array by key values.
    ksort($returnedData);

    // Return field mappings.
    return match($this->strategy) {
      CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper::MAP_API4_TO_API3 => array_flip($returnedData),
      default => $returnedData,
    };
  }

  /**
   * Mapping Api3 to Api4 fields and rules.
   *
   * This is called automatically to remap APIv4 to APIv3 in \CRM_EicEuSurveyFormProcessor_Importer_CiviSettingsJsonImporter::import
   * - Prerequisite to this is that the override and fill names in the XCM are already in APIv4 format.
   *
   * This function can also be used to update the xcm_config_profiles.json (change APIv3 to APIv4 names):
   * - docker compose exec php cv php:eval 'CRM_EicEuSurveyFormProcessor_Importer_XcmProfileRemapper::create()->processXcmSettings();'
   *
   * The command above will convert fill and override fields from custom_1 to custom_group_name.field_name
   *
   * @return void
   *   Nothing is returned.
   */
  public function processXcmSettings(?array &$data = NULL): void {
    $writeToFile = empty($data['value']);
    if ($data == NULL) {
      // In the case data is not passed as parameter, fetch the settings from the file.
      $data = CRM_EicEuSurveyFormProcessor_Importer_CiviSettingsJsonImporter::create()->getJsonFromConfigFile($this->getXcmSettingsPath());;
    }

    if (empty($data['value']) || !is_array($data['value'])) {
      return;
    }

    // Update the JSON file.
    foreach ($data['value'] as &$profile) {
      if (!empty($profile['rules'])) {
        $profile['rules'] = $this->remapArray($profile['rules']);
      }

      foreach (['fill_fields', 'override_fields'] as $key) {
        if (!empty($profile['options'][$key])) {
          $profile['options'][$key] = $this->remapArray($profile['options'][$key]);
        }
      }
    }

    if ($writeToFile) {
      $newJson = json_encode([$data], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      if (file_put_contents($this->getXcmSettingsPath(), $newJson) === false) {
        throw new \RuntimeException("Could not write file: {$this->getXcmSettingsPath()}");
      }
    }
  }

  /**
   * Remaps values form the array.
   *
   * @param array $values
   *   The array values.
   *
   * @return array
   *   The array with remopped values.
   */
  private function remapArray(array $values): array {
    return array_map(
      fn($value) => ($this->remapper)((string) $value),
      $values
    );
  }

  /**
   *
   */
  /**
   * Remap value.
   *
   * Replaces values of the following form to their corresponding grouo.field names.
   *  - Strategy: matches "custom_51" / "dedupe_8" style values.
   *
   * @param string $value
   *   The value to be mapped.
   *
   * @return string
   *   The mapped value.
   */
  private function remapValue(string $value): string {
    if (preg_match('/^(custom_|DEDUPE_)(.+)$/i', $value, $matches)) {
      [$fullMatch, $prefix, $id] = $matches;
      if (isset($this->mappingData[$prefix][$id])) {
        return sprintf("%s%s", $prefix, $this->mappingData[$prefix][$id]);
      }
      else {
        if (intval($id) > 0) {
          $message = sprintf("Id: %d, is not found in the '%s' mapping table.", $id, $prefix);
          // @ignoreException
          \Civi::log()->error($message);
          echo $message, PHP_EOL;
        }
      }
    }
    return $value;
  }

  /**
   * Helper function returning the XCM Settings JSON absolute path.
   *
   * @return string
   *   XCM Settings JSON absolute path.
   */
  private function getXcmSettingsPath(): string {
    // Array holding the XCM JSON files.
    $settingsPath = [];
    try {
      $settingsPath = CRM_EicEuSurveyFormProcessor_Assets_CiviSettingsAssets::getConfigFilesFullPath();
      $settingsPath = reset($settingsPath);
    }
    catch (\Exception $ex) {
      // @ignoreException
      \Civi::log()->error($ex->getMessage());
    }

    // Return the settings path.
    return $settingsPath;
  }

}

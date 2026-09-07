<?php
// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

declare(strict_types = 1);

use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

class CRM_EicEuSurveyFormProcessor_Assets_CiviSettingsAssets {

  public const ASSETS_DIR = '/assets/settings';
  public const EXTENSION_SHORT_NAME = 'eic_eu_survey_form_processor';

  /**
   * @throws \RuntimeException
   * @return array<string>
   */
  public static function getConfigFilesFullPath() {
    $fpAssetsDir = E::path(CRM_EicEuSurveyFormProcessor_Assets_CiviSettingsAssets::ASSETS_DIR);
    if (FALSE === $fpAssetsDir) {
      throw new \RuntimeException(
        'Could not retrieve CiviCRM-Settings config files from assets folder: ' . CRM_EicEuSurveyFormProcessor_Assets_CiviSettingsAssets::ASSETS_DIR
      );
    }

    // full paths
    /** @phpstan-ignore binaryOp.invalid */
    $fpJsonFiles = glob($fpAssetsDir . '/*.json');
    if (FALSE === $fpJsonFiles) {
      throw new \RuntimeException(
        /** @phpstan-ignore binaryOp.invalid */
        'Could not read CiviCRM-Settings config files from assets folder: ' . $fpAssetsDir
      );
    }
    return $fpJsonFiles;
  }

}

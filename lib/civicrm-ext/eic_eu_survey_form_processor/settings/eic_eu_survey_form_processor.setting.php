<?php
// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

declare(strict_types = 1);

use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
    'eic_eu_survey_form_processor_import_mode' => [
        'group_name' => 'eic_eu_survey_form_processor',
        'group' => 'eic_eu_survey_form_processor',
        'name' => 'eic_eu_survey_form_processor_import_mode',
        'title' => E::ts('Import Form-Processors if already existing?'),
        'description' => E::ts('If this option is enabled, then the Form-Processor configuration that is shipped with this extension will be imported if a form-processor with the same name exists already. If this option is turned off, then the avaiable form-processor configuration will only be imported if a form-processor with the same name does not exists. This will overwrite all changes made to that form-processor.'),
        'type' => 'Boolean',
        'default' => FALSE,
        'required' => FALSE,
        'html_type' => 'checkbox',
        'is_domain' => 1,
        'is_contact' => 0,
    ],
    'eic_eu_survey_settings_import_mode' => [
        'group_name' => 'eic_eu_survey_form_processor',
        'group' => 'eic_eu_survey_form_processor',
        'name' => 'eic_eu_survey_settings_import_mode',
        'title' => E::ts('Import CiviCRM-Settings if already existing?'),
        'description' => E::ts('If this option is enabled, then the CiviCRM Settings configuration that is shipped with this extension will be imported if a setting with the same name exists already. If this option is turned off, then the avaiable CiviCRM Settings configuration will only be imported if a setting with the same name does not exists. This will overwrite all changes made to that setting value.'),
        'type' => 'Boolean',
        'default' => FALSE,
        'required' => FALSE,
        'html_type' => 'checkbox',
        'is_domain' => 1,
        'is_contact' => 0,
    ],
];

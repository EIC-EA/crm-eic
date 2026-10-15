<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_eic_vm_priorities',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_vm_priorities',
        'title' => E::ts('VM Fundraising Priorities'),
        'description' => E::ts('Priorities offered by the EIC VM Fundraising Assessment EU-Survey (respondent selects one or two).'),
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_Product_Development_Technical_M',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('Product Development / Technical Milestones'),
        'value' => 'Product Development / Technical Milestones',
        'name' => 'Product_Development_Technical_M',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_Customer_Acquisition_Sales_Grow',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('Customer Acquisition / Sales Growth'),
        'value' => 'Customer Acquisition / Sales Growth',
        'name' => 'Customer_Acquisition_Sales_Grow',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_Team_Hiring_Talent_Acquisition',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('Team Hiring / Talent Acquisition'),
        'value' => 'Team Hiring / Talent Acquisition',
        'name' => 'Team_Hiring_Talent_Acquisition',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_Securing_Key_Partnerships',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('Securing Key Partnerships'),
        'value' => 'Securing Key Partnerships',
        'name' => 'Securing_Key_Partnerships',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_Extending_the_Cash_Runway',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('Extending the Cash Runway'),
        'value' => 'Extending the Cash Runway',
        'name' => 'Extending_the_Cash_Runway',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_Optimizing_Corporate_Model_Unit',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('Optimizing Corporate Model / Unit Economics'),
        'value' => 'Optimizing Corporate Model / Unit Economics',
        'name' => 'Optimizing_Corporate_Model_Unit',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_IP_Protection_Filing',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('IP Protection / Filing'),
        'value' => 'IP Protection / Filing',
        'name' => 'IP_Protection_Filing',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_Regulatory_Compliance_Certifica',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('Regulatory Compliance / Certification'),
        'value' => 'Regulatory Compliance / Certification',
        'name' => 'Regulatory_Compliance_Certifica',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities_OptionValue_Financial_Planning_Forecasting',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts('Financial Planning / Forecasting'),
        'value' => 'Financial Planning / Forecasting',
        'name' => 'Financial_Planning_Forecasting',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];

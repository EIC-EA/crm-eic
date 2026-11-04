<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CustomLayout_VM_Investor_Organisation',
    'entity' => 'ContactLayout',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'VentureMatch - Investor Organisation',
        'contact_type' => 'Organization',
        'contact_sub_type' => [
          'Investor',
        ],
        'groups:name' => ['EIC_VentureMatch'],
        'weight' => 2,
        'blocks' => [
          [
            [
              [
                'name' => 'core.ContactInfo',
                'title' => 'Employer, Nickname, Source',
              ],
            ],
            [
              [
                'name' => 'core.Basic',
                'title' => 'ID, Type, Tags',
              ],
            ],
          ],
          [
            [
              [
                'name' => 'core.Email',
                'title' => 'Email',
              ],
              [
                'name' => 'core.Website',
                'title' => 'Website',
              ],
            ],
            [
              [
                'name' => 'core.Phone',
                'title' => 'Phone',
              ],
              [
                'name' => 'core.IM',
                'title' => 'Instant Messenger',
              ],
            ],
          ],
          [
            [
              [
                'name' => 'core.Address',
                'title' => 'Address',
              ],
            ],
            [],
          ],
          [
            [
              [
                'name' => 'core.CommunicationPreferences',
                'title' => 'Communication Preferences',
              ],
            ],
            [],
          ],
          [
            [
              [
                'name' => 'custom.Activity_related',
                'title' => 'Activity-related',
                'collapsible' => TRUE,
                'collapsed' => TRUE,
              ],
              [
                'name' => 'custom.EIC_Awardee_Additionnal_Information',
                'title' => 'EIC Awardee Additionnal Information',
                'collapsible' => TRUE,
                'collapsed' => FALSE,
              ],
              [
                'name' => 'custom.EIC_Fundraising_Assessment',
                'title' => 'EIC VentureMatch Fundraising Assessment',
                'collapsible' => TRUE,
                'collapsed' => FALSE,
              ],
            ],
            [
              [
                'name' => 'custom.EU_Survey_Company_Data',
                'title' => 'Self-Assessed information',
                'collapsible' => TRUE,
                'collapsed' => FALSE,
              ],
              [
                'name' => 'custom.General_Company_Info',
                'title' => 'General Company Information',
                'collapsible' => TRUE,
                'collapsed' => TRUE,
              ],
              [
                'name' => 'custom.EIC_Organisation_identifiers',
                'title' => 'Organisation identifiers',
                'collapsible' => TRUE,
                'collapsed' => FALSE,
              ],
              [
                'name' => 'custom.Financial_information',
                'title' => 'Financial Information',
                'collapsible' => TRUE,
                'collapsed' => TRUE,
              ],
              [
                'name' => 'custom.VentureMatch_Fields',
                'title' => 'VentureMatch-Fields',
                'collapsible' => TRUE,
                'collapsed' => FALSE,
              ],
            ],
          ],
        ],
        'tabs' => [
          [
            'id' => 'summary',
            'is_active' => TRUE,
          ],
          [
            'id' => 'note',
            'is_active' => TRUE,
          ],
          [
            'id' => 'engagement',
            'is_active' => TRUE,
          ],
          [
            'id' => 'e_i_c_details',
            'is_active' => FALSE,
          ],
          [
            'id' => 'venture_match_mail',
            'is_active' => TRUE,
          ],
          [
            'id' => 'calendar',
            'is_active' => TRUE,
          ],
          [
            'id' => 'mailing',
            'is_active' => TRUE,
          ],
          [
            'id' => 'case',
            'is_active' => TRUE,
          ],
          [
            'id' => 'activity',
            'is_active' => TRUE,
          ],
          [
            'id' => 'rel',
            'is_active' => TRUE,
          ],
          [
            'id' => 'group',
            'is_active' => TRUE,
          ],
          [
            'id' => 'tag',
            'is_active' => TRUE,
          ],
          [
            'id' => 'e_i_c_venture_match_engagement',
            'is_active' => TRUE,
          ],
          [
            'id' => 'log',
            'is_active' => TRUE,
          ],
        ],
        'settings' => [
          'sub_type_operator' => 'OR',
        ],
      ],
    ],
  ],
];
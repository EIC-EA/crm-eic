<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CustomLayout_VM_EIC_Awardee',
    'entity' => 'ContactLayout',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'VentureMatch - EIC Awardee Organisation-',
        'contact_type' => 'Organization',
        'contact_sub_type' => [
          'EIC_Awardee',
          'EIC_Organisation',
        ],
        'groups:name' => ['EIC_VentureMatch'],
        'weight' => 1,
        'blocks' => [
          [
            [
              [
                'name' => 'profile.VentureMatch_EIC_Awardee_information',
                'title' => 'VentureMatch EIC Awardee information',
                'collapsible' => FALSE,
                'collapsed' => FALSE,
                'showTitle' => FALSE,
              ],
            ],
            [
              [
                'name' => 'profile.Custom_EIC_Awardee_Organisation_information_right',
                'title' => 'VentureMatch EIC Awardee information right pannel',
                'collapsible' => FALSE,
                'collapsed' => FALSE,
                'showTitle' => FALSE,
              ],
            ],
          ],
          [
            [
              [
                'name' => 'afform_search.afsearchVentureMatchServiceRequestSummary',
                'title' => 'VentureMatch Service request summary',
                'collapsible' => TRUE,
                'collapsed' => FALSE,
              ],
              [
                'name' => 'afform_search.afsearchActivities',
                'title' => 'Activities',
                'collapsible' => TRUE,
                'collapsed' => TRUE,
                'showTitle' => FALSE,
              ],
            ],
          ],
          [
            [],
            [],
          ],
          [
            [],
          ],
          [
            [
              [
                'name' => 'profile.EIC_VentureMatch_Main_information_Left',
                'title' => 'EIC VentureMatch Main information',
                'collapsible' => FALSE,
                'collapsed' => FALSE,
                'showTitle' => TRUE,
                'related_rel' => '',
              ],
            ],
            [
              [
                'name' => 'custom.VentureMatch_Fields',
                'title' => 'VentureMatch Analysis',
                'collapsible' => FALSE,
                'collapsed' => FALSE,
                'showTitle' => TRUE,
              ],
            ],
          ],
          [
            [],
            [],
          ],
          [
            [
              [
                'name' => 'profile.EIC_VentureMatch_Fundraising_Snapshot',
                'title' => 'EIC VentureMatch Fundraising Snapshot',
                'collapsible' => TRUE,
                'collapsed' => TRUE,
                'showTitle' => FALSE,
              ],
            ],
            [
              [
                'name' => 'profile.EIC_VentureMatch_Fundraising_Documents',
                'title' => 'EIC VentureMatch Fundraising Documents',
                'collapsible' => TRUE,
                'collapsed' => TRUE,
                'showTitle' => FALSE,
              ],
            ],
          ],
          [
            [
              [
                'name' => 'custom.EU_Survey_Company_Data',
                'title' => 'KAM Onboarding Assessment',
                'collapsible' => TRUE,
                'collapsed' => TRUE,
                'showTitle' => FALSE,
              ],
            ],
            [
              [
                'name' => 'custom.EIC_Awardee_Additionnal_Information',
                'title' => 'EIC Awardee Additional Information',
                'collapsible' => TRUE,
                'collapsed' => TRUE,
                'showTitle' => FALSE,
              ],
            ],
          ],
          [
            [],
            [],
          ],
          [
            [
              [
                'name' => 'afform_search.afsearchEICVentureMatchEngagement',
                'title' => 'EIC VentureMatch Engagement',
                'collapsible' => FALSE,
                'collapsed' => FALSE,
                'showTitle' => FALSE,
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
            'id' => 'e_i_c_venture_match_engagement',
            'is_active' => TRUE,
          ],
          [
            'id' => 'e_i_c_details',
            'is_active' => TRUE,
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
            'id' => 'engagement',
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
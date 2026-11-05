<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_Service_Request_VentureMatch_NUMBERS',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Service_Request_VentureMatch_NUMBERS',
        'label' => E::ts('EIC Service Request - VentureMatch - NUMBERS'),
        'api_entity' => 'Case',
        'api_params' => [
          'version' => 4,
          'select' => [
            'COUNT(id) AS COUNT_id',
            'subject',
            'Case_CaseContact_Contact_01.sort_name',
            'status_id:label',
            'start_date',
            'end_date',
            'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.near_contact_id.display_name',
            'case_type_id:label',
          ],
          'orderBy' => [],
          'where' => [
            [
              'case_type_id:name',
              'CONTAINS ONE OF',
              [
                'eic_sr_venturematch',
              ],
            ],
          ],
          'groupBy' => [],
          'join' => [
            [
              'Contact AS Case_CaseContact_Contact_01',
              'LEFT',
              'CaseContact',
              [
                'id',
                '=',
                'Case_CaseContact_Contact_01.case_id',
              ],
            ],
            [
              'Case AS Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01',
              'LEFT',
              'RelationshipCache',
              [
                'Case_CaseContact_Contact_01.id',
                '=',
                'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.far_contact_id',
              ],
              [
                'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.near_contact_id',
                '=',
                'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.case_manager_id',
              ],
            ],
          ],
          'having' => [],
        ],
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'SavedSearch_EIC_Service_Request_VentureMatch_NUMBERS_SearchDisplay_EIC_Service_Request_VentureMatch_NUMBERS_Grid_1',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Service_Request_VentureMatch_NUMBERS_Grid_1',
        'label' => E::ts('EIC Service Request - VentureMatch - NUMBERS Grid 1'),
        'saved_search_id.name' => 'EIC_Service_Request_VentureMatch_NUMBERS',
        'type' => 'grid',
        'settings' => [
          'colno' => '3',
          'limit' => 50,
          'sort' => [],
          'pager' => [
            'expose_limit' => TRUE,
            'hide_single' => TRUE,
          ],
          'columns' => [
            [
              'type' => 'html',
              'key' => 'COUNT_id',
              'rewrite' => '<div class="card text-center bg-danger text-white p-3">
  <h3>Dossiers en cours</h3> 
  <h1 class="display-1">[COUNT_id]</h1>
</div>',
              'label' => NULL,
              'title' => NULL,
              'cssRules' => [],
            ],
          ],
          'placeholder' => 5,
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
  [
    'name' => 'SavedSearch_EIC_Service_Request_VentureMatch_NUMBERS_SearchDisplay_EIC_Service_Request_VentureMatch_NUMBERS_Grid_2',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Service_Request_VentureMatch_NUMBERS_Grid_2',
        'label' => E::ts('EIC Service Request - VentureMatch - NUMBERS Grid 2'),
        'saved_search_id.name' => 'EIC_Service_Request_VentureMatch_NUMBERS',
        'type' => 'grid',
        'settings' => [
          'colno' => '3',
          'limit' => 50,
          'sort' => [],
          'pager' => [],
          'columns' => [
            [
              'type' => 'field',
              'key' => 'status_id:label',
              'colors' => [
                [
                  'field' => 'status_id:color',
                ],
              ],
            ],
            [
              'type' => 'html',
              'key' => 'COUNT_id',
              'rewrite' => '
{* 1. Définition de la couleur selon la valeur du statut *}
{if $status_id_label == "Urgent" or $status_id_label == "Need Attention"}
  {assign var="status_color" value="#f87171"} {* Rouge *}
{elseif $status_id_label == "This Week" or $status_id_label == "In Progress"}
  {assign var="status_color" value="#fb923c"} {* Orange *}
{elseif $status_id_label == "Open" or $status_id_label == "Completed"}
  {assign var="status_color" value="#4ade80"} {* Vert *}
{else}
  {assign var="status_color" value="#e2e8f0"} {* Blanc/Gris par défaut *}
{/if}

{* 2. Génération de la carte HTML utilisant la variable de couleur *}
<div class="card text-center p-3 text-white" style="background-color: #1e1e24; border: 1px solid #2d2d35; border-radius: 8px; min-width: 150px;">
  <div class="display-4 font-weight-bold" style="color: {$status_color}; font-size: 2.5rem; line-height: 1.2;">
    [COUNT_id] {* Ou votre champ de comptage/valeur *}
  </div>
  <div class="small text-muted text-uppercase" style="letter-spacing: 0.5px; margin-top: 5px;">
    [status_id:label]
  </div>
</div>',
            ],
          ],
          'placeholder' => 5,
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];

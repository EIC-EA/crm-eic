<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
    [
        'name'    => 'OptionValue_activity_type_VentureMatch_Email',
        'entity'  => 'OptionValue',
        'cleanup' => 'always',
        'update'  => 'always',
        'params'  => [
            'version' => 4,
            'match'   => ['option_group_id.name', 'name'],
            'values'  => [
                'option_group_id.name' => 'activity_type',
                'name'                 => 'VentureMatch Email',
                'label'                => 'VentureMatch Email',
                'icon'                 => 'fa-circle-arrow-down',
                'is_active'            => true,
                'is_reserved'          => false,
                'description'          => '<p>VentureMatch inbound email</p>'
            ],
        ],
    ],
];
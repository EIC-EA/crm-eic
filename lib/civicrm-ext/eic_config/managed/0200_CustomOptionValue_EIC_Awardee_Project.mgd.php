<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
    [
        'name'    => 'OptionValue_activity_type_EIC_Awardee_Project',
        'entity'  => 'OptionValue',
        'cleanup' => 'always',
        'update'  => 'always',
        'params'  => [
            'version' => 4,
            'match'   => ['option_group_id.name', 'name'],
            'values'  => [
                'option_group_id.name' => 'activity_type',
                'name'                 => 'EIC_Awardee_Project',
                'label'                => 'EIC Awardee Project',
                'icon'                 => 'fa-rocket',
                'is_active'            => true,
                'is_reserved'          => false,
                'description'          => '<p>Activity used to import the information about companies elligible to BAS:</p>
<ul>
	<li><strong>EIC Awardees &amp; Grantees:</strong> All beneficiaries of EIC Pathfinder, Transition, Accelerator, and STEP Scale-Up programs (including legacy schemes like FET and SME Instruments).</li>
	<li><strong>Seal of Excellence Holders:</strong> Entities holding a Horizon Europe Seal of Excellence or STEP Seal.</li>
</ul>
'
            ],
        ],
    ],
];
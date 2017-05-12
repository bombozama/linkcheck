<?php
return [
    'plugin' => [
        'tab'      => 'LinkCheck Plugin',
        'manage'   => 'Manage Link check configuration',
        'view'     => 'Grant access to the link report',
        'category' => 'Link Check'
    ],
    'details'     => [
        'name'           => 'Link Check',
        'description'    => 'Checks database daily for broken links...'
    ],
    'strings'     => [
        'refresh_list'      => 'Refresh Link List',
        'list_title'        => 'Broken Link List',
        'no_records'        => 'No broken links have been detected at this time or the system is currently checking for broken links.',
        'total_links'       => 'A total of :number broken links were found!',
        'status'            => 'Status',
        'url'               => 'URL',
        'plugin'            => 'Plugin',
        'model'             => 'Model/Filepath',
        'model_id'          => 'ID',
        'field'             => 'Field',
        'created_at'        => 'Last check',
        'time'              => 'Time mask',
        'time_comment'      => 'The plugin will check for broken links as scheduled here. Check http://crontab.org/ for more info.',
        'codes'             => 'Which HTTP response codes should be reported?',
        'codes_opt_200'     => 'Successful responses (200 - 206) [not recomended]',
        'codes_opt_300'     => 'Redirection responses (300 - 308)',
        'codes_opt_400'     => 'Client error responses (400 - 431)',
        'codes_opt_500'     => 'Server error responses (500 - 511)',
        'check_cms'         => 'Check links within CMS files?',
        'modelators_prompt' => 'Select a database field',
        'modelator'         => 'Plugin/Model',
        'modelator_comment' => 'Select the Plugin/Model/Fields that you wish to scan.',
        'modelator_empty'   => 'Select at least one field to check for broken links [optional]',
    ],
    'menu' => [
        'settings'    => [
            'label'       => 'Link Check Settings',
            'description' => 'Checks database daily for broken links.',
        ],
        'brokenlinks' => [
            'label'       => 'Link report',
            'description' => 'Shows list of broken links',
        ]
    ]
];
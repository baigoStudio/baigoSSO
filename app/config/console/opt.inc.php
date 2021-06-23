<?php
return array(
    'base' => array(
        'title' => 'Base settings',
        'lists' => array(
            'site_name' => array(
                'title'      => 'Site name',
                'type'       => 'str',
                'format'     => 'text',
                'require'    => 'true',
            ),
            'site_perpage' => array(
                'title'      => 'Count of per page',
                'type'       => 'str',
                'format'     => 'int',
                'require'    => 'true',
            ),
            'site_date' => array(
                'title'      => 'Date format',
                'type'       => 'select_input',
                'note'       => 'Select or type the format parameter of the <code>date</code> function',
                'require'    => 'true',
                'date_param' => 'true',
                'option'     => array(
                    'Y-m-d'     => '{:Y-m-d}',
                    'y-m-d'     => '{:y-m-d}',
                    'M. d, Y'   => '{:M. d, Y}',
                ),
            ),
            'site_date_short' => array(
                'title'      => 'Short date format',
                'type'       => 'select_input',
                'note'       => 'Select or type the format parameter of the <code>date</code> function',
                'require'    => 'true',
                'date_param' => 'true',
                'option'     => array(
                    'm-d'    => '{:m-d}',
                    'M. d'   => '{:M. d}',
                ),
            ),
            'site_time' => array(
                'title'      => 'Time format',
                'type'       => 'select_input',
                'note'       => 'Select or type the format parameter of the <code>date</code> function',
                'require'    => 'true',
                'date_param' => 'true',
                'option'     => array(
                    'H:i:s'     => '{:H:i:s}',
                    'h:i:s A'   => '{:h:i:s A}',
                ),
            ),
            'site_time_short' => array(
                'title'      => 'Short time format',
                'type'       => 'select_input',
                'note'       => 'Select or type the format parameter of the <code>date</code> function',
                'require'    => 'true',
                'date_param' => 'true',
                'option'     => array(
                    'H:i'    => '{:H:i}',
                    'h:i A'  => '{:h:i A}',
                ),
            ),
            'access_expire' => array(
                'title'     => 'Access token expiration time',
                'type'      => 'select_input',
                'note'      => 'Select or type minutes',
                'require'   => 'true',
                'option' => array(
                    10    => '{:value} Minutes',
                    20    => '{:value} Minutes',
                    30    => '{:value} Minutes',
                    40    => '{:value} Minutes',
                    50    => '{:value} Minutes',
                    60    => '{:value} Minutes',
                    70    => '{:value} Minutes',
                    80    => '{:value} Minutes',
                    90    => '{:value} Minutes',
                ),
            ),
            'refresh_expire' => array(
                'title'     => 'Refresh token expiration time',
                'type'      => 'select_input',
                'note'      => 'Select or type days',
                'require'   => 'true',
                'option' => array(
                    10    => '{:value} Days',
                    20    => '{:value} Days',
                    30    => '{:value} Days',
                    40    => '{:value} Days',
                    50    => '{:value} Days',
                    60    => '{:value} Days',
                ),
            ),
            'verify_expire' => array(
                'title'     => 'Validation token expiration time',
                'type'      => 'select_input',
                'note'      => 'Select or type minutes',
                'require'   => 'true',
                'option' => array(
                    10    => '{:value} Minutes',
                    20    => '{:value} Minutes',
                    30    => '{:value} Minutes',
                    40    => '{:value} Minutes',
                    50    => '{:value} Minutes',
                    60    => '{:value} Minutes',
                    70    => '{:value} Minutes',
                    80    => '{:value} Minutes',
                    90    => '{:value} Minutes',
                ),
            ),
        ),
    ),
    'reg' => array(
        'title' => 'Register settings',
        'lists' => array(
            'reg_acc' => array(
                'title'     => 'Allow registration',
                'type'      => 'switch',
                'require'   => 'false',
            ),
            'reg_needmail' => array(
                'title'     => 'Require email',
                'type'      => 'switch',
                'require'   => 'false',
            ),
            'reg_confirm' => array(
                'title'     => 'Need to verify the email',
                'type'      => 'switch',
                'require'   => 'false',
                'note'      => 'Turning on this, you need to verify the email when you register or replace it',
            ),
            'acc_mail' => array(
                'title'      => 'Allowed emails',
                'type'       => 'textarea',
                'format'     => 'text',
                'require'    => 'false',
                'note'       => 'For multiple domain, please use <kbd>|</kbd> to separate, fill in the domain, such as: <code>@hotmail.com</code>',
            ),
            'bad_mail' => array(
                'title'      => 'Banned emails',
                'type'       => 'textarea',
                'format'     => 'text',
                'require'    => 'false',
                'note'       => 'For multiple domain, please use <kbd>|</kbd> to separate, fill in the domain, such as: <code>@hotmail.com</code>',
            ),
            'bad_name' => array(
                'title'      => 'Banned names',
                'type'       => 'textarea',
                'format'     => 'text',
                'require'    => 'false',
                'note'       => 'For multiple username, please use <kbd>|</kbd> to separate, use wildcard <kbd>*</kbd> such as: <code>*master*</code>',
            ),
        ),
    ),
    'smtp'      => array(
        'title' => 'Email sending settings',
    ),
    'mailtpl'   => array(
        'title' => 'Email template settings',
    ),
    'dbconfig'  => array(
        'title' => 'Database settings',
    ),
    'chkver'    => array(
        'title' => 'Check for updates',
    ),
);
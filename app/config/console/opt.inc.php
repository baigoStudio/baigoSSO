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
                'title'     => 'Date format',
                'type'      => 'select_input',
                'note'      => 'Select or fill in the format parameter of the <code>date</code> function',
                'require'   => 'true',
                'option'    => array(
                    'Y-m-d'     => date('Y-m-d'),
                    'y-m-d'     => date('y-m-d'),
                    'M. d, Y'   => date('M. d, Y'),
                ),
            ),
            'site_date_short' => array(
                'title'     => 'Short date format',
                'type'      => 'select_input',
                'note'      => 'Select or fill in the format parameter of the <code>date</code> function',
                'require'   => 'true',
                'option'    => array(
                    'm-d'   => date('m-d'),
                    'M. d'  => date('M. d'),
                ),
            ),
            'site_time' => array(
                'title'     => 'Time format',
                'type'      => 'select_input',
                'note'      => 'Select or fill in the format parameter of the <code>date</code> function',
                'require'   => 'true',
                'option'    => array(
                    'H:i:s'     => date('H:i:s'),
                    'h:i:s A'   => date('h:i:s A'),
                ),
            ),
            'site_time_short' => array(
                'title'     => 'Short time format',
                'type'      => 'select_input',
                'note'      => 'Select or fill in the format parameter of the <code>date</code> function',
                'require'   => 'true',
                'option'    => array(
                    'H:i'   => date('H:i'),
                    'h:i A' => date('h:i A'),
                ),
            ),
            'access_expire' => array(
                'title'     => 'Access token expiration time',
                'type'      => 'select_input',
                'note'      => 'Select or fill in minutes',
                'require'   => 'true',
                'option' => array(
                    10    => '{:option} Minutes',
                    20    => '{:option} Minutes',
                    30    => '{:option} Minutes',
                    40    => '{:option} Minutes',
                    50    => '{:option} Minutes',
                    60    => '{:option} Minutes',
                    70    => '{:option} Minutes',
                    80    => '{:option} Minutes',
                    90    => '{:option} Minutes',
                ),
            ),
            'refresh_expire' => array(
                'title'     => 'Refresh token expiration time',
                'type'      => 'select_input',
                'note'      => 'Select or fill in days',
                'require'   => 'true',
                'option' => array(
                    10    => '{:option} Days',
                    20    => '{:option} Days',
                    30    => '{:option} Days',
                    40    => '{:option} Days',
                    50    => '{:option} Days',
                    60    => '{:option} Days',
                ),
            ),
            'verify_expire' => array(
                'title'     => 'Validation token expiration time',
                'type'      => 'select_input',
                'note'      => 'Select or fill in minutes',
                'require'   => 'true',
                'option' => array(
                    10    => '{:option} Minutes',
                    20    => '{:option} Minutes',
                    30    => '{:option} Minutes',
                    40    => '{:option} Minutes',
                    50    => '{:option} Minutes',
                    60    => '{:option} Minutes',
                    70    => '{:option} Minutes',
                    80    => '{:option} Minutes',
                    90    => '{:option} Minutes',
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
            'login_mail' => array(
                'title'     => 'Allow login by email',
                'type'      => 'switch',
                'require'   => 'false',
                'note'      => 'Turning on this, the email will be required and cannot be duplicated',
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
                'note'       => 'Fill in the domain, one domain per line, such as: <code>@hotmail.com</code>',
            ),
            'bad_mail' => array(
                'title'      => 'Banned emails',
                'type'       => 'textarea',
                'format'     => 'text',
                'require'    => 'false',
                'note'       => 'Fill in the domain, one domain per line, such as: <code>@hotmail.com</code>',
            ),
            'bad_name' => array(
                'title'      => 'Banned names',
                'type'       => 'textarea',
                'format'     => 'text',
                'require'    => 'false',
                'note'       => 'One username per line, use wildcard <kbd>*</kbd> such as: <code>*master*</code>',
            ),
        ),
    ),
    'smtp'      => array(
        'title' => 'SMTP Settings'
    ),
    'mailtpl'   => array(
        'title' => 'Mail template settings'
    ),
    'dbconfig'  => array(
        'title' => 'Database settings'
    ),
    'chkver'    => array(
        'title' => 'Check for updates'
    ),
);
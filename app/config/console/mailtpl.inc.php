<?php
return array(
    /*------ 注册------*/
    'reg' => array(
        'title' => 'Confirm registration',
        'list' => array(
            'subject'   => array(
                'title'      => 'Subject',
                'type'       => 'str',
                'format'     => 'text',
                'require'    => 'true',
            ),
            'content'   => array(
                'title'      => 'Content',
                'type'       => 'textarea',
                'format'     => 'text',
                'require'    => 'true',
            ),
        ),
    ),

    /*------ 更换邮箱------*/
    'mailbox' => array(
        'title' => 'Update mailbox',
        'list' => array(
            'subject'   => array(
                'title'      => 'Subject',
                'type'       => 'str',
                'format'     => 'text',
                'require'    => 'true',
            ),
            'content'   => array(
                'title'      => 'Content',
                'type'       => 'textarea',
                'format'     => 'text',
                'require'    => 'true',
            ),
        ),
    ),

    /*------ 找回密码------*/
    'forgot' => array(
        'title' => 'Forgot password',
        'list' => array(
            'subject'   => array(
                'title'      => 'Subject',
                'type'       => 'str',
                'format'     => 'text',
                'require'    => 'true',
            ),
            'content'   => array(
                'title'      => 'Content',
                'type'       => 'textarea',
                'format'     => 'text',
                'require'    => 'true',
            ),
        ),
    ),
);
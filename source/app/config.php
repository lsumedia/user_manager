<?php

$config = [
    
    /* Database configuration */
    'database' => [
        /* Prefix appended to all table names */
        'db_prefix' => 'mu',
        /* Database name */
        'db_name' => 'grovestr_media_user_manager',
        /* Database server */
        'db_host' => 'luna.servers.prgn.misp.co.uk',
        /* Database user name */
        'db_user' => 'grovestr_media',
        /* Database user password */
        'db_pass' => 'i<3media16'
    ],
    
    
    'registration' => [
        /* Allow anyone to register */
        'open_registration' => false,
        /* Require users to confirm their email before logging in */
        'require_email_confirmation' => false,
    ],
    
    /* Dashboard to redirect to if no login redirect provided */
    'dashboard_address' => '',
    
];


/* Array of possible permissions a user can have */

$permissions = [
    
    /* Permission to manage users. Do not remove this permission! */
    'manage_users' => 'Manage user permissions',
    
    /* Media manager */
    
    'upload_media' => 'Media: Upload media',
    'edit_media' => 'Media: Edit media',
    
    /* LCR */
    
    'lcr_submit_content' => 'LCR: Submit content',
    'lcr_edit_content' => 'LCR: Approve and edit content',
    'lcr_manage_channels' => 'LCR: Manage channels',
    
    /* LSUTV */
    
    'lsutv_submit_content' => 'LSUTV: Submit content',
    'lsutv_edit_content' => 'LSUTV: Edit content',
    'lsutv_manage_channels' => 'LSUTV: Broadcast Control Centre',
    
    /* Lens */
    
    'lens_submit_content' => 'Lens: Submit content',
    'lens_edit_content' => 'Lens: Edit content',
    
    /* Label (WP) */
    
    'label_submit_content' => 'Label: Submit content',
    'label_edit_content' => 'Label: Edit content',
    'label_site_admin' => 'Label: Site administration'
];


/* Default permissions a user has on creation */
$default_permissions = [
    'upload_media',
    'lcr_submit_content',
    'lsutv_submit_content',
    'lens_submit_content',
    'label_submit_content'
];

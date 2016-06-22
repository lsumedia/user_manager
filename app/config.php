<?php

$config = [
    
    /* Database configuration */
    'database' => [
        'db_name' => '',
        'db_server' => '',
        'db_user' => '',
        'db_pass' => ''
    ],
    
    
];


/* Array of possible permissions a user can have */

$permissions = [
    
    'manage_users' => 'Manage user permissions',
    
    /* Media manager */
    
    'upload_media' => 'Media: Upload media',
    'edit_media' => 'Media: Upload media',
    
    /* LCR */
    
    'lcr_submit_content' => 'LCR: Submit content',
    'lcr_edit_content' => 'LCR: Approve and edit content',
    'lcr_manage_channels' => 'LCR: Manage channels',
    
    /* LSUTV */
    
    'lsutv_submit_content' => 'LSUTV: Submit content',
    'lsutv_edit_content' => 'LSUTV: Edit content',
    'lsutv_manage_channels' => 'LSUTV: Manage channels',
    
    /* Lens */
    
    'lens_submit_content' => 'Lens: Submit content',
    'lens_edit_content' => 'Lens: Edit content',
    
    /* Label (WP) */
    
    'label_submit_content' => 'Label: Submit content',
    'label_edit_content' => 'Label: Edit content',
    'label_site_admin' => 'Label: Site administration'
];


/* Default permissions a user has on creation */
$default_pemissions = [
    'upload_media',
    'lcr_submit_content',
    'lsutv_submit_content',
    'lens_submit_content',
    'label_submit_content'
];

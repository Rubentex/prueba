<?php
// Array of the files with an unique ID
$files = array(
    'UID12345' => array(
        'content_type' => 'application/zip', 
        'suggested_name' => 'codex.zip', 
        'file_path' => 'tempfile.zip',
        'type' => 'local_file'
    ),
    'UID67890' => array(
        'content_type' => 'application/zip', 
        'suggested_name' => 'codex2.zip', 
        'file_path' => 'tempfile2.zip',
        'type' => 'remote_file'
    ),
);
// Base URL of the application
define('BASE_URL','http://'. $_SERVER['HTTP_HOST'].'/generate_temporary_download_link_php/');
// Path of the download-link.php file
define('DOWNLOAD_PATH', BASE_URL.'download.php');
// Path of the token directory to store keys
define('TOKEN_DIR', 'tokens');
// Authentication password to generate download links
define('OAUTH_PASSWORD','Tutorialswebsite');
// Expiration time of the link (examples: +1 year, +1 month, +5 days, +10 hours)
define('EXPIRATION_TIME', '+5 minutes');
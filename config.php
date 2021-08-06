<?php
// Array of the files with an unique ID
$files = array(
    'UID12345' => array(
        'content_type' => 'application/zip', 
        'suggested_name' => 'codex.zip', 
        'file_path' => 'files/tempfile.zip',
        'type' => 'local_file'
    ),
    'UID67890' => array(
        'content_type' => 'application/zip', 
        'suggested_name' => 'codex2.zip', 
        'file_path' => 'files/tempfile.zip',
        'type' => 'local_file'
    ),
);
// Base URL of the application
define('BASE_URL','http://'. $_SERVER['HTTP_HOST'].'/');
// Path of the download-link.php file
define('DOWNLOAD_PATH', BASE_URL.'download.php');
// Path of the token directory to store keys
define('TOKEN_DIR', 'tokens');
// Authentication password to generate download links
define('OAUTH_PASSWORD','CODEXWORLD');
// Expiration time of the link (examples: +1 year, +1 month, +5 days, +10 hours)
define('EXPIRATION_TIME', '+5 minutes');
// Expiration time of the link (examples: +5)
define('CLICK_NUMBER_EXPIRATION','+5');

    // Don't worry about this
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: ".date('U', strtotime(EXPIRATION_TIME)));
?>


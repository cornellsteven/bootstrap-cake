<?php
    
    // Turn debug on or off: 0 = off, 1 = debug, 2 = debug & sql log output
    Configure::write('debug', 2);
    
    if (isset($_GET['debug']) || (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '64.207.227.14') || Configure::read('localhost')) {
        Configure::write('debug', 2);
    }
    
    // Settings
    Configure::write('Company.name', 'Company Name, Inc.');
    
    // Directory where config.php and other config files are stored
    define('CONFIG', ROOT . DS . 'app' . DS . 'Config' . DS);
    
    // Name of the admin "folder" (ie, "admin" for /admin/login, or "cms" for /cms/login)
    define('ADMIN', 'admin');
    
    // Where should users be directed to upon successful login?
    define('LOGIN_REDIRECT', DS . ADMIN . DS);
    
    // Directory for file uploads
    define('UPLOADS', WWW_ROOT . 'uploads' . DS);
    
?>
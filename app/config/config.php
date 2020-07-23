<?php

// Paramètres DB
define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');
define('DB_CHARSET', 'utf8');

// App root
define('APPROOT', dirname(dirname(__FILE__))); // Dirname x 2 permettant d'être à la source du dossier app 

// URL root
define('URLROOT', 'http://localhost/TFEAPP');

// Site info
define('SITENAME', 'TFEAPP');
define('MAINMAIL', 'contact@mail.be');

// App SHORTCUT ICON
define('APPICON', URLROOT . '/public/images/favicon.png');

// reCaptcha keys
define('SITE_KEY', '');
define('SECRET_KEY', '');

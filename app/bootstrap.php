<?php
// Loead config
require_once 'config/config.php';

// Load helpers
require_once 'helpers/session.php';
require_once 'helpers/tools.php';
require_once 'helpers/hyperlink.php';

// Load vendor : API externe
require_once __DIR__.'/vendor/autoload.php';

// Autoloader Core Libraries
spl_autoload_register(function($classname)
{
    require_once 'libraries/' . $classname . '.php';
    // require_once 'controllers/validations/' . $classname . '.php';
});

// Load controllers errors
require_once 'controllers/validations/UsersValidator.php';
require_once 'controllers/validations/CategoriesValidator.php';
require_once 'controllers/validations/SuppliersValidator.php';
require_once 'controllers/validations/ConstructionsValidator.php';
require_once 'controllers/validations/purchasesValidator.php';
require_once 'controllers/validations/workersValidator.php';
require_once 'controllers/validations/customersValidator.php';
require_once 'controllers/validations/invoicesValidator.php';
require_once 'controllers/validations/galleriesValidator.php';

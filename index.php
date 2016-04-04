<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}



require 'vendor/autoload.php';
require 'config/constants.php';
require 'config/database.php';

require 'helpers/guid.php';
require 'helpers/functions.php';
require 'helpers/misc.php';

require 'controllers/API.php';

require 'routes.php';

?>
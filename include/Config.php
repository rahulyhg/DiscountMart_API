<?php
/**
 * Database configuration
 */
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_NAME', 'discountmart');

define('USER_CREATED_SUCCESSFULLY', 0);
define('USER_CREATE_FAILED', 1);
define('USER_ALREADY_EXISTED', 2);

define('ADDRESS_ADDED_SUCCESSFULLY', 3);
define('ADDRESS_ADDITION_FAILED', 4);

define('ORDER_PLACED_SUCCESSFULLY', 5);

define('ADSLIDER_ADDED_SUCCESSFULLY', 6);
define('ADSLIDER_ADDITION_FAILED', 7);


define('ADSLIDER', 'ADSLIDER');
define('BASE_IMAGE_DIR', '../admin_web/images/');
//define('DOMAIN', '192.168.1.100');

define('DOMAIN', 'http://192.168.1.100/');
//define('DOMAIN', 'http://aws-bitnami-server.bitnamiapp.com/');


define('BASE_URL_IMAGES', DOMAIN.'/discountmart_web/admin_web/images/');

?>

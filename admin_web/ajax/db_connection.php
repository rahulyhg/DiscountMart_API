<?php

// Connection variables 
$host = "localhost"; // MySQL host name eg. localhost
$user = "root"; // MySQL user. eg. root ( if your on localserver)
$password = ""; // MySQL user password  (if password is not set for your root user then keep it empty )
$database = "discountmart"; // MySQL Database name

define('ADSLIDER', 'ADSLIDER');
define('BASE_IMAGE_DIR', '../images/');
//define('DOMAIN', '192.168.1.100');

define('DOMAIN', 'http://localhost/');
//define('DOMAIN', 'http://aws-bitnami-server.bitnamiapp.com/');


define('BASE_URL_IMAGES', DOMAIN.'/discountmart_web/admin_web/images/');


   $mysqli=mysqli_connect($host,$user,$password,$database);
  

?>
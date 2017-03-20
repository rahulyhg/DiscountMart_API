<?php

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require_once '../include/Responce.php';
require '.././libs/Slim/Slim.php';


\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(); 
 
// User id from db - Global Variable
$user_id = NULL;

/**
 * Adding Middle Layer to authe   nticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
 
 
 
 function apache_request_headers() {
  $arh = array();
  $rx_http = '/\AHTTP_/';
  foreach($_SERVER as $key => $val) {
    if( preg_match($rx_http, $key) ) {
      $arh_key = preg_replace($rx_http, '', $key);
      $rx_matches = array();
      // do some nasty string manipulations to restore the original letter case
      // this should work in most cases
      $rx_matches = explode('_', $arh_key);
      if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
        foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
        $arh_key = implode('-', $rx_matches);
      }
      $arh[$arh_key] = $val;
    }
  }
  return( $arh );
}


function authenticate(\Slim\Route $route) {
    // Getting request headers
	
	//print_r( $_SERVER);
    $headers = apache_request_headers();

	//print_r($headers);
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['AUTHORIZATION'])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $headers['AUTHORIZATION'];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * ----------- METHODS WITHOUT AUTHENTICATION ---------------------------------
 */
/**
 * User Registration
 * url - /register
 * method - POST
 * params - name, email, password
 */
$app->post('/register', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('fname', 'lname','email', 'password', 'phone','gcm_regid','imei'));

            $Responce = new Responce();

            // reading post params
            $fname = $app->request->post('fname');
            $lname = $app->request->post('lname');
            $email = $app->request->post('email');
            $password = $app->request->post('password');
            $phone = $app->request->post('phone');
            $gcm_regid = $app->request->post('gcm_regid');
            $imei = $app->request->post('imei');
            
            // validating email address
            validateEmail($email);

            $db = new DbHandler();
            $user = $db->createUser($fname,$lname, $email, $password,$phone,$gcm_regid,$imei);

            if ($user == USER_CREATED_SUCCESSFULLY) {
                
                $Responce->setError(false);
                $Responce->setMessage("You are successfully registered");
                
                $user = $db->getUserByPhone($phone);
                $userinfo = array();
                $userinfo = array ("id" => $user['id'],
                                    "fname" => $user['fname'],
                                  );

                    
                    
                $Responce->setData('user',$user);

            } else if ($user == USER_CREATE_FAILED) {
                
                $Responce->setError(true);
                $Responce->setMessage("Oops! An error occurred while registereing");
                
                
            } else if ($user == USER_ALREADY_EXISTED) {
                $Responce->setError(true);
                $Responce->setMessage("Sorry, this phone number already existed");
                
            }
            
                        echoRespnse(201, $Responce->setArray());

            // echo json response
        });

/**
 * User Login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/login', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('phone', 'password'));

            // reading post params
            $phone = $app->request()->post('phone');
            $password = $app->request()->post('password');
            $response = array();

            $db = new DbHandler();
            $Responce = new Responce();
            // check for correct email and password
            if ($db->checkLogin($phone, $password)) {
                // get the user by email
                $user = $db->getUserByPhone($phone);

                if ($user != NULL) {
                
                    $Responce->setError(false);
                    $Responce->setMessage("false");
                    $Responce->setData('user',$user);
                } else {
                    // unknown error occurred
                    $Responce->setError(true);
                    $Responce->setMessage("An error occurred. Please try again");
                    
                }
            } else {
                // user credenti   als are wrong
                 $Responce->setError(true);
                 $Responce->setMessage("Login failed. Incorrect credentials");

            }

            echoRespnse(201, $Responce->setArray());
        });

/*
 * ------------------------ METHODS WITH AUTHENTICATION ------------------------
 */

/**
 * Listing all tasks of particual user
 * method GET
 * url /tasks          
 */
        
$app->post('/place_order','authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('products','address_id'));

            $Responce = new Responce();
			global $user_id;

            // reading post params
            $products = $app->request->post('products');
            $address_id = $app->request->post('address_id');
            $amount = $app->request->post('amount');
            $discount = $app->request->post('discount');




           // print_r($products_arr);
      
            $db = new DbHandler();
            $new_order_id = $db->placeOrder($user_id,$address_id,$products);

            $result = $db->placeOrderDetails($new_order_id,$products);

            if ($result == ORDER_PLACED_SUCCESSFULLY) {
                
                $Responce->setError(false);
                $Responce->setMessage("Order Placed");
             

            } else{
                
                $Responce->setError(true);
                $Responce->setMessage("Oops! An error occurred while placing an order");
                
                
            } 
            
               echoRespnse(201, $Responce->setArray());

            // echo json response
        });
                
        
$app->post('/add_address','authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('fname', 'lname','address', 'phone', 'pincode'));

            $Responce = new Responce();
			global $user_id;

            // reading post params
            $fname = $app->request->post('fname');
            $lname = $app->request->post('lname');
            $address = $app->request->post('address');
            $phone = $app->request->post('phone');
            $pincode = $app->request->post('pincode');

            
      
            $db = new DbHandler();
            $user = $db->AddAddress($user_id,$fname,$lname, $address, $phone,$pincode);

            if ($user == ADDRESS_ADDED_SUCCESSFULLY) {
                
                $Responce->setError(false);
                $Responce->setMessage("Address Added");
             

            } else if ($user == ADDRESS_ADDITION_FAILED) {
                
                $Responce->setError(true);
                $Responce->setMessage("Oops! An error occurred while adding address");
                
                
            } 
            
               echoRespnse(201, $Responce->setArray());

            // echo json response
        });
        
        
        
$app->get('/get_adsliders/','authenticate',

		function()
		{


			global $user_id;
			


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getAdSliders();

                        $resultRetailerCategories = $db->getRetailerCategories();

			if ($result != NULL && $resultRetailerCategories !=NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('ads',$result);
				$Responce->setData('retailer_categories',$resultRetailerCategories);


			} else {
				$Responce->setError(true);
				$Responce->setMessage("true");
		
			}

			echoRespnse(201, $Responce->setArray());
});

$app->get('/get_addresses/:id_user','authenticate',

		function($id_user)
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getAddresses($id_user);

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('addresses',$result);


			} else {
				$Responce->setError(true);
				$Responce->setMessage("Address book is emply, please add new address.");
		
			}

			echoRespnse(201, $Responce->setArray());
});


$app->get('/get_service_providers/:city_id/:category_id','authenticate',

		function($city_id,$category_id)
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getServiceProviders($city_id,$category_id);

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('service_providers',$result);


			} else {
				$Responce->setError(false);
				$Responce->setMessage("No Data Available");
		
			}

			echoRespnse(201, $Responce->setArray());
});

$app->get('/get_service_categories/','authenticate',

		function()
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getServiceCategories();

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('categories',$result);


			} else {
				$Responce->setError(true);
				$Responce->setMessage("true");
		
			}

			echoRespnse(201, $Responce->setArray());
});


$app->get('/get_shopping_categories/','authenticate',

		function()
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getShoppingCategories();

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('categories',$result);


			} else {
				$Responce->setError(true);
				$Responce->setMessage("true");
		
			}

			echoRespnse(201, $Responce->setArray());
});


$app->get('/get_offer_categories/','authenticate',

		function()
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getOfferCategories();

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('categories',$result);


			} else {
				$Responce->setError(true);
				$Responce->setMessage("true");
		
			}

			echoRespnse(201, $Responce->setArray());
});


$app->get('/get_retailers/:city_id/:offer_category_id','authenticate',

		function($city_id,$offer_category_id)
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getRetailers($city_id,$offer_category_id);

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('retailers',$result);


			} else {
				$Responce->setError(false);
				$Responce->setMessage("No Data Available");
		
			}

			echoRespnse(201, $Responce->setArray());
});


$app->get('/get_retailers/:city_id/:offer_category_id','authenticate',

		function($city_id,$offer_category_id)
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getRetailers($city_id,$offer_category_id);

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('retailers',$result);


			} else {
				$Responce->setError(false);
				$Responce->setMessage("No Data Available");
		
			}

			echoRespnse(201, $Responce->setArray());
});


$app->get('/get_products/:category_id/:retailer_id','authenticate',

		function($category_id,$retailer_id)
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getProducts($category_id,$retailer_id);

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('products',$result);


			} else {
				$Responce->setError(false);
				$Responce->setMessage("No Data Available");
		
			}

			echoRespnse(201, $Responce->setArray());
});



$app->get('/get_quotes/:category_id/:auther_id/:quote_id','authenticate',
		
		function($category_id,$auther_id,$quote_id) 
		{
            global $user_id;
            
            
            $response = array();
            $db = new DbHandler();
            
              $Responce = new Responce();

            // fetch task
            $result = $db->getQuotes($category_id,$auther_id,$quote_id);

            if ($result != NULL) {
                $Responce->setError(false);
                $Responce->setMessage("false");
                $Responce->setData('quotes',$result);

//                $result["error"] = false;
//                $response["id"] = $result["id"];
//                $response["task"] = $result["task"];
//                $response["status"] = $result["status"];
//                $response["createdAt"] = $result["created_at"];
//                echoRespnse(200, $Responce);
            } else {
                $Responce->setError(false);
                $Responce->setMessage("false");
               // $response["error"] = true;
               // $response["message"] = "The requested resource doesn't exists";
//                echoRespnse(404, $response);
            }
            
            echoRespnse(201, $Responce->setArray());
        });


$app->get('/get_categories/','authenticate',

		function()
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getCategories();

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('categories',$result);


			} else {
				$Responce->setError(true);
				$Responce->setMessage("true");
		
			}

			echoRespnse(201, $Responce->setArray());
});
        
$app->get('/get_authors/:sort','authenticate',

		function($sort)
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getAuthors($sort);

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('authors',$result);


			} else {
				$Responce->setError(false);
				$Responce->setMessage("false");

			}

			echoRespnse(201, $Responce->setArray());
});
        
	//	CREATE EVENT hourly_inactive_cleanup
  //ON SCHEDULE EVERY '5:00' MINUTE_SECOND
 // DO
  //  DELETE FROM coupon_requests
  //    WHERE created_on <= DATE_SUB(NOW(), INTERVAL '5:00' MINUTE_SECOND)
   //     AND status = 0;
		
$app->post('/create_coupon_request', 'authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('id_offer','id_retailer','id_user'));

            $response = array();
            $id_offer = $app->request->post('id_offer');
            $id_retailer = $app->request->post('id_retailer');
            $id_user = $app->request->post('id_user');

            global $user_id;
            $db = new DbHandler();
			$Responce = new Responce();

            // creating new task
            $coupon_id = $db->createCouponRequest($id_offer, $id_retailer,$id_user);
		            
			$coupon = $db->getCouponById($coupon_id);

			
			
            if ($coupon_id != NULL) {
               $Responce->setError(false);
				$Responce->setMessage("Requested");
				$Responce->setData("coupon",$coupon);

            } else {
       			$Responce->setError(true);
				$Responce->setMessage("Request failed");
            }        
			echoRespnse(201, $Responce->setArray());
			
        });
		
$app->put('/validate_coupon/:id', 'authenticate', function($coupon_id) use($app) {
            // check for required params

            global $user_id;            

            $db = new DbHandler();
			$Responce = new Responce();

            $response = array();

            // updating task
            $result = $db->updateValidateCoupon($coupon_id);
            if ($result) {
				$Responce->setError(false);
				$Responce->setMessage("Coupon validated");
            } else {
                // task failed to update
                $Responce->setError(true);
               $Responce->setMessage("Coupon validation failed");
            }
			echoRespnse(201, $Responce->setArray());
        });

        
$app->get('/tasks', 'authenticate', function() {
            global $user_id;
            $response = array();
            $db = new DbHandler();

            // fetching all user tasks
            $result = $db->getAllUserTasks($user_id);

            $response["error"] = false;
            $response["tasks"] = array();

            // looping through result and preparing tasks array
            while ($task = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["id"] = $task["id"];
                $tmp["task"] = $task["task"];
                $tmp["status"] = $task["status"];
                $tmp["createdAt"] = $task["created_at"];
                array_push($response["tasks"], $tmp);
            }

            echoRespnse(200, $response);
        });

/**
 * Listing single task of particual user
 * method GET
 * url /tasks/:id
 * Will return 404 if the task doesn't belongs to user
 */
$app->get('/tasks/:id', 'authenticate', function($task_id) {
            global $user_id;
            $response = array();
            $db = new DbHandler();

            // fetch task
            $result = $db->getTask($task_id, $user_id);

            if ($result != NULL) {
                $response["error"] = false;
                $response["id"] = $result["id"];
                $response["task"] = $result["task"];
                $response["status"] = $result["status"];
                $response["createdAt"] = $result["created_at"];
                echoRespnse(200, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "The requested resource doesn't exists";
                echoRespnse(404, $response);
            }
        });

/**
 * Creating new task in db
 * method POST
 * params - name
 * url - /tasks/
 */
$app->post('/tasks', 'authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('task'));

            $response = array();
            $task = $app->request->post('task');

            global $user_id;
            $db = new DbHandler();

            // creating new task
            $task_id = $db->createTask($user_id, $task);

            if ($task_id != NULL) {
                $response["error"] = false;
                $response["message"] = "Task created successfully";
                $response["task_id"] = $task_id;
                echoRespnse(201, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "Failed to create task. Please try again";
                echoRespnse(200, $response);
            }            
        });

/**
 * Updating existing task
 * method PUT
 * params task, status
 * url - /tasks/:id
 */
$app->put('/tasks/:id', 'authenticate', function($task_id) use($app) {
            // check for required params
            verifyRequiredParams(array('task', 'status'));

            global $user_id;            
            $task = $app->request->put('task');
            $status = $app->request->put('status');

            $db = new DbHandler();
            $response = array();

            // updating task
            $result = $db->updateTask($user_id, $task_id, $task, $status);
            if ($result) {
                // task updated successfully
                $response["error"] = false;
                $response["message"] = "Task updated successfully";
            } else {
                // task failed to update
                $response["error"] = true;
                $response["message"] = "Task failed to update. Please try again!";
            }
            echoRespnse(200, $response);
        });

/**
 * Deleting task. Users can delete only their tasks
 * method DELETE
 * url /tasks
 */
$app->delete('/tasks/:id', 'authenticate', function($task_id) use($app) {
            global $user_id;

            $db = new DbHandler();
            $response = array();
            $result = $db->deleteTask($user_id, $task_id);
            if ($result) {
                // task deleted successfully
                $response["error"] = false;
                $response["message"] = "Task deleted succesfully";
            } else {
                // task failed to delete
                $response["error"] = true;
                $response["message"] = "Task failed to delete. Please try again!";
            }
            echoRespnse(200, $response);
        });

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>
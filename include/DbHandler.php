<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class DbHandler {

    public $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    public function readRecords($page) {



        if ($page == 'adslider') {


            // Design initial table header 
            $data = '<table class="table table-bordered table-striped">
						<tr>
							<th>ID.</th>
							<th>Name</th>
							<th>Image</th>
							<th>Status</th>
							<th>Action</th>

						</tr>';


            $stmt = $this->conn->prepare("SELECT * FROM adsliders");

            $stmt->execute();

            $result = $stmt->get_result();


            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $data .= '<tr>
				<td>' . $row['id'] . '</td>
				<td>' . $row['name'] . '</td>
                                <td><img src=' . BASE_URL_IMAGES . '' . $row['image'] . ' alt="" style="width:200px; height:auto;"></td>
				<td>' . $row['status'] . '</td>
				<td>
					<button onclick="GetRecordDetails(`' . $page . '`,' . $row['id'] . ')" class="btn btn-warning">Update</button>
					<button onclick="DeleteRecord(`adslider`,' . $row['id'] . ')" class="btn btn-danger">Delete</button>
				

</td>
				
				
                                

    		</tr>';
                }
                $stmt->close();
            } else {
                $data .= '<tr><td colspan="6">Records not found!</td></tr>';
            }

            $data .= '</table>';

            return $data;
        }
    }

    public function deleteRecords($page, $id) {


        if ($page == 'adslider') {


            $stmt = $this->conn->prepare("DELETE  FROM adsliders WHERE id = $id");
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $num_affected_rows > 0;
        }
    }

    public function getRecords($page, $id) {


        if ($page == 'adslider') {


            $stmt = $this->conn->prepare("SELECT * FROM adsliders WHERE id = $id");
            $stmt->execute();

            $result = $stmt->get_result();

            $response = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $response = $row;
                }
            } else {
                $response['status'] = 200;
                $response['message'] = "Data not found!";
            }
            // display JSON data
            return json_encode($response);
        }
    }

    public function addRecords($page, $data, $files) {


        if ($page == 'adslider') {



            $name = $data['name'];



            $imgFile = $files['image']['name'];
            $tmp_dir = $files['image']['tmp_name'];
            $imgSize = $files['image']['size'];

            $errMSG = "";

            if (empty($name)) {
                $errMSG = "Please Enter Username.";
            } else {

                $folder_name = "imagesliders/";

                $upload_dir = BASE_IMAGE_DIR . $folder_name; // upload directory

                $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
                // valid image extensions
                $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
                // rename uploading image
                $userpic = rand(1000, 1000000) . "." . $imgExt;

                $imagename = $folder_name . $userpic;
                // allow valid image file formats
                if (in_array($imgExt, $valid_extensions)) {
                    // Check file size '5MB'
                    if ($imgSize < 5000000) {
                        move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                    } else {
                        $errMSG = "Sorry, your file is too large.";
                    }
                } else {
                    $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            }



            $stmt = $this->conn->prepare("INSERT INTO adsliders(name, image)"
                    . " VALUES('$name', '$imagename' )");


            $result = $stmt->execute();

            $stmt->close();

            if ($result) {
                return "1 Record Added!";
            } else {
                return "No Record Added!";
            }
        }
    }

    public function updateRecords($page, $data, $files) {


        if ($page == 'adslider') {



            $name = $data['name'];
            $id = $data['id'];



            $imgFile = $files['image']['name'];
            $tmp_dir = $files['image']['tmp_name'];
            $imgSize = $files['image']['size'];

            $errMSG = "";

            if (empty($name)) {
                $errMSG = "Please Enter Username.";
            } else {

                $folder_name = "imagesliders/";

                $upload_dir = BASE_IMAGE_DIR . $folder_name; // upload directory

                $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
                // valid image extensions
                $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
                // rename uploading image
                $userpic = rand(1000, 1000000) . "." . $imgExt;

                $imagename = $folder_name . $userpic;
                // allow valid image file formats
                if (in_array($imgExt, $valid_extensions)) {
                    // Check file size '5MB'
                    if ($imgSize < 5000000) {
                        move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                    } else {
                        $errMSG = "Sorry, your file is too large.";
                    }
                } else {
                    $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            }



            $stmt = $this->conn->prepare("UPDATE adsliders SET(name, image) "
                    . " VALUES('$name', '$imagename' ) WHERE id = $id");


            $result = $stmt->execute();

            $stmt->close();

            if ($result) {
                return "1 Record Added!";
            } else {
                return "No Record Added!";
            }
        }
    }

    /* ------------- `users` table method ------------------ */

    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function createUser($fname, $lname, $email, $password, $phone, $gcm_regid, $imei) {
        require_once 'PassHash.php';
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExists($phone)) {
            // Generating password hash
            $password_hash = PassHash::hash($password);

            // Generating API key
            $api_key = $this->generateApiKey();
            // insert query
            $stmt = $this->conn->prepare("INSERT INTO users"
                    . "(`fname`, `lname`, `phone`, `email`, `password_hash`, `api_key`, `gcm_regid`,`imei`, `status`,`membership`)"
                    . " values('$fname','$lname',$phone, '$email', '$password_hash','$api_key','$gcm_regid','$imei', 1,0)");
//            $stmt->bind_param("ssssssss", $fname,$lname,$phone, $email, $password_hash,$api_key,$gcm_regid,$imei);
//            echo "INSERT INTO users"
//                    . "(`fname`, `lname`, `phone`, `email`, `password_hash`, `api_key`, `gcm_regid`,`imei`, `status`,`membership`)"
//                    . " values('$fname','$lname',$phone, '$email', '$password_hash','$api_key','$gcm_regid','$imei', 1,0)";
//            

            $result = $stmt->execute();

            $stmt->close();



            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            return USER_ALREADY_EXISTED;
        }

        return $response;
    }

    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkLogin($phone, $password) {
        // fetching user by email
        $stmt = $this->conn->prepare("SELECT password_hash FROM users WHERE phone = ?");

        $stmt->bind_param("s", $phone);

        $stmt->execute();

        $stmt->bind_result($password_hash);

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Found user with the email
            // Now verify the password

            $stmt->fetch();

            $stmt->close();

            if (PassHash::check_password($password_hash, $password)) {
                // User password is correct
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();

            // user not existed with the email
            return FALSE;
        }
    }
    
    public function updatePassword($user_id, $old_pass, $new_pass) {
		
		$stmt = $this->conn->prepare("select password_hash from users where id=?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->bind_result($password_hash);
		while($stmt->fetch())
		{
			$password_hash1=$password_hash;
		}
		
			$stmt->close();
			
			$password_hash_old = PassHash::hash($old_pass);
			$password_hash_new = PassHash::hash($new_pass);
			//die("".$password_hash_old);
			
					
			//echo "exist".$password_hash_existing."<br>".$password_hash_old;
			//die();
			//echo "exist".$password_hash1."<br>".$password_hash_old;
		
		if(PassHash::check_password($password_hash1,$old_pass))
		{		
		//die("".$password_hash_new);
		
        $stmt1 = $this->conn->prepare("UPDATE users set password_hash = ? where id = ?");
        $stmt1->bind_param("si",$password_hash_new, $user_id);
        $stmt1->execute();
        $num_affected_rows = $stmt1->affected_rows;
        $stmt1->close();
        return $num_affected_rows > 0;
		}
		else
		{
			return null;
		}
    }
    
    
    
  public function getOtp($email) {
		 $random_num=0;
		 $contents="";
		 $email_from="registration@squibit.in";
		 
			
								       $stmt = $this->conn->prepare("SELECT id from users where email= ?");
									   //die("SELECT id  from category where name= $name");
									   $stmt->bind_param("s",$email);
									   $stmt->bind_result($id); 
									   
        $post = array();
        
        $stmt->execute();
		if ($stmt->fetch()) 
		{
				$random_num=rand(100000,999999);
				  $contents = "Hi,\n" .
                        "\n" .
                        "Greetings!\n" .
                        "\n" .
                        "You are just a step away from accessing your Squibit account\n" .
                        "\n" .
                        "We have shared a verification code to access your account. \n" .
                        "\n" .
                        "Once you have verified the code, you'll be prompted to set a new password immediately. This is to ensure that only you have access to your account.\n" .
                        "\n" .
						
						 "This Verification code (OTP) is valid for 10 minutes only \n" .
						 
						  "\n" .
                        "Your OTP : " . $random_num . "\n" .
						
                        "\n" .
						
						"This is an automatically generated email – please do not reply to it. If you have any queries regarding your service please contact our customer service through support@squibit.in.\n".
						"\n" .
						
                        "Best Regards, \n" .
                        "Team Squibit";
				
				
				if(mail($email, "Squibit Account - One Time Password ", $contents,'From: Squibit<'.$email_from.'>'))
				{
					//die("hii");
					$post[] = array("otp" => $random_num);
				}
				else
				{
					return null;
				}
   		 }
   		 
      //  $tasks = $stmt->get_result();
      
        $stmt->close();
		//die(print_r($post));
        return $post ;
    }
    
    
    
    public function AddAddress($user_id, $fname, $lname, $address, $phone, $pincode) {

        $response = array();


        $stmt = $this->conn->prepare("INSERT INTO addresses"
                . "(`user_id`,`fname`, `lname`, `address`, `phone`, `pincode`)"
                . " values($user_id,'$fname','$lname','$address', '$phone', '$pincode')");


        $result = $stmt->execute();

        $stmt->close();



        // Check for successful insertion
        if ($result) {
            // User successfully inserted
            return ADDRESS_ADDED_SUCCESSFULLY;
        } else {
            // Failed to create user
            return ADDRESS_ADDITION_FAILED;
        }


        return $response;
    }

    public function createRetailerCategory($parent_id, $name) {

        $stmt = $this->conn->prepare("INSERT INTO retailer_categories(parent_id, name) values($parent_id, '$name')");
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }

    public function createCity($parent_id, $name) {


        $stmt = $this->conn->prepare("INSERT INTO cities(parent_id, city_name) values($parent_id, '$name')");
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }

    public function createServiceCategory($parent_id, $name) {

        $stmt = $this->conn->prepare("INSERT INTO service_categories(parent_id, name) values($parent_id, '$name')");
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }
    public function createRating($user_id, $product_varient_id,$ratings) {

        $stmt = $this->conn->prepare("INSERT INTO ratings(user_id, product_varient_id,ratings)  "
                . "values($user_id, $product_varient_id, $ratings)"
                . "ON DUPLICATE KEY UPDATE ratings = $ratings");
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }
    public function deleteRetailerCategory($id) {

        $stmt = $this->conn->prepare("DELETE FROM retailer_categories WHERE id = $id");
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }

    public function deleteCity($id) {

        $stmt = $this->conn->prepare("DELETE FROM cities WHERE id = $id");
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }

    public function deleteServiceCategory($id) {

        $stmt = $this->conn->prepare("DELETE FROM service_categories WHERE id = $id");
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }

    public function updateRetailerCategory($id, $name) {


        $stmt = $this->conn->prepare("UPDATE retailer_categories SET name = '$name' WHERE id = $id");
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function updateCity($id, $name) {

        $stmt = $this->conn->prepare("UPDATE cities SET city_name = '$name' WHERE id = $id");
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function updateServiceCategory($id, $name) {


        $stmt = $this->conn->prepare("UPDATE service_categories SET name = '$name' WHERE id = $id");
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function addAdSlider($name, $image) {





        // print_r($image);


        $imgFile = $image['name'];
        $tmp_dir = $image['tmp_name'];
        $imgSize = $image['size'];

        $errMSG = "";

        if (empty($name)) {
            $errMSG = "Please Enter Username.";
        } else {

            $folder_name = "imagesliders/";

            $upload_dir = BASE_IMAGE_DIR . $folder_name; // upload directory

            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
            // valid image extensions
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
            // rename uploading image
            $userpic = rand(1000, 1000000) . "." . $imgExt;

            $imagename = $folder_name . $userpic;
            // allow valid image file formats
            if (in_array($imgExt, $valid_extensions)) {
                // Check file size '5MB'
                if ($imgSize < 5000000) {
                    move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                } else {
                    $errMSG = "Sorry, your file is too large.";
                }
            } else {
                $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }



        $stmt = $this->conn->prepare("INSERT INTO adsliders(name, image)"
                . " VALUES('$name', '$imagename' )");


        $result = $stmt->execute();

        $stmt->close();

        if ($result) {
            return ADSLIDER_ADDED_SUCCESSFULLY;
        } else {
            return ADSLIDER_ADDITION_FAILED;
        }
    }

    public function createRetailer($category_id, $city_id, $name, $address, $description, $phone, $images) {





        // print_r($image);

        $foldername = "retailers/";
        $banner1 = NULL;
        $banner2 = NULL;
        $banner3 = NULL;
        $imagename = NULL;

        if (array_key_exists('image', $images)) {

            $imagename = self::uploadImage($images['image'], $foldername);
        }
        if (array_key_exists('banner1', $images)) {

            $banner1 = self::uploadImage($images['banner1'], $foldername);
        }
        if (array_key_exists('banner2', $images)) {

            $banner2 = self::uploadImage($images['banner2'], $foldername);
        }
        if (array_key_exists('banner3', $images)) {

            $banner3 = self::uploadImage($images['banner3'], $foldername);
        }


//            echo "INSERT INTO `retailers`"
//                    . "(`category_id`, `city_id`, `name`, `address`, "
//                    . "`description`, `phone`, `image`, `banner1`, `banner2`, `banner3`) "
//                    . "VALUES ($category_id,$city_id,'$name','$address','$description','$phone','$imagename','$banner1','$banner2','$banner3')";



        $stmt = $this->conn->prepare("INSERT INTO `retailers`"
                . "(`category_id`, `city_id`, `name`, `address`, "
                . "`description`, `phone`, `image`, `banner1`, `banner2`, `banner3`) "
                . "VALUES ($category_id,$city_id,'$name','$address','$description','$phone','$imagename','$banner1','$banner2','$banner3')");


        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }

    public static function uploadImage($image, $folder_name) {


        $uploadSuccess = false;
        $imgFile = $image['name'];
        $tmp_dir = $image['tmp_name'];
        $imgSize = $image['size'];

        $errMSG = "";



        $upload_dir = BASE_IMAGE_DIR . $folder_name; // upload directory

        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
        // rename uploading image
        $userpic = rand(1000, 1000000) . "." . $imgExt;

        $imagename = $folder_name . $userpic;
        // allow valid image file formats
        if (in_array($imgExt, $valid_extensions)) {
            // Check file size '5MB'
            if ($imgSize < 5000000) {
                $uploadSuccess = move_uploaded_file($tmp_dir, $upload_dir . $userpic);
            } else {
                $errMSG = "Sorry, your file is too large.";
            }
        } else {
            $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }


        if ($uploadSuccess) {
            return $imagename;
        } else {
            return $errMSG;
        }
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($phone) {
        $stmt = $this->conn->prepare("SELECT id from users WHERE phone = $phone");
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByPhone($phone) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        while ($row_users = $result->fetch_assoc()) {
            foreach ($row_users as $key => $value) {
                $users_array[$key] = $value;
            }
        }

        return $users_array;




//        if ($stmt->execute()) {
//            // $user = $stmt->get_result()->fetch_assoc();
//            $stmt->bind_result($name, $email, $api_key, $status, $created_at);
//            $stmt->fetch();
//            
//            
//            
//            
//            $user = array();
//            $user["name"] = $name;
//            $user["email"] = $email;
//            $user["api_key"] = $api_key;
//            $user["status"] = $status;
//            $user["created_at"] = $created_at;
//            $stmt->close();
//            return $user;
//        } else {
//            return NULL;
//        }
    }

    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */
    public function getApiKeyById($user_id) {
        $stmt = $this->conn->prepare("SELECT api_key FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // $api_key = $stmt->get_result()->fetch_assoc();
            // TODO
            $stmt->bind_result($api_key);
            $stmt->close();
            return $api_key;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getUserId($api_key) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        if ($stmt->execute()) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            // TODO
            // $user_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user_id;
        } else {
            return NULL;
        }
    }

    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidApiKey($api_key) {
        $stmt = $this->conn->prepare("SELECT id from users WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }

    /* ------------- `tasks` table method ------------------ */

    /**
     * Creating new task
     * @param String $user_id user id to whom task belongs to
     * @param String $task task text
     */
    public function createTask($user_id, $task) {
        $stmt = $this->conn->prepare("INSERT INTO tasks(task) VALUES(?)");
        $stmt->bind_param("s", $task);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // task row created
            // now assign the task to user
            $new_task_id = $this->conn->insert_id;
            $res = $this->createUserTask($user_id, $new_task_id);
            if ($res) {
                // task created successfully
                return $new_task_id;
            } else {
                // task failed to create
                return NULL;
            }
        } else {
            // task failed to create
            return NULL;
        }
    }

    /**
     * Fetching single task
     * @param String $task_id id of the task
     */
    public function getTask($task_id, $user_id) {
        $stmt = $this->conn->prepare("SELECT t.id, t.task, t.status, t.created_at from tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        if ($stmt->execute()) {
            $res = array();
            $stmt->bind_result($id, $task, $status, $created_at);
            // TODO
            // $task = $stmt->get_result()->fetch_assoc();
            $stmt->fetch();
            $res["id"] = $id;
            $res["task"] = $task;
            $res["status"] = $status;
            $res["created_at"] = $created_at;
            $stmt->close();
            return $res;
        } else {
            return NULL;
        }
    }

    public function getQuotes($category_id, $author_id, $quote_id) {


        $authorList_array = array();
        $author_array = array();
        $author_quotes_array = array();

        if ($category_id == 'all' && $author_id == 'all' && $quote_id == 'null') {

            // All authors All Categories

            $stmtAuthor = $this->conn->prepare("SELECT * from authors 
            		ORDER BY author_name  ASC");

            $stmtAuthor->execute();

            $resultAuthor = $stmtAuthor->get_result();
            $stmtAuthor->close();


            while ($row_authors = $resultAuthor->fetch_assoc()) {
                foreach ($row_authors as $key => $value) {
                    $author_array[$key] = $value;
                }

                $author_array['quotes'] = array();
                $id_author = $row_authors['id_author'];


                $stmtQuote = $this->conn->prepare(
                        "SELECT q.* ,c.category_name 
                			FROM quotes q ,categories c 
                			WHERE q.id_category = c.id_category 
                			AND id_author = " . $id_author . "
                			ORDER BY q.quote_likes_count  DESC");

                $stmtQuote->execute();
                $resultQuote = $stmtQuote->get_result();
                $stmtQuote->close();


                while ($row_quotes = $resultQuote->fetch_assoc()) {
                    foreach ($row_quotes as $key => $value) {
                        $quotes_array[$key] = $value;
                    }

                    array_push($author_array['quotes'], $quotes_array);
                }

                array_push($authorList_array, $author_array);
            }




            return $authorList_array;
        } else if ($category_id == 'all' && $author_id != 'all' && $quote_id == 'null') {
            // Single author All Categories

            $stmtauthor = $this->conn->prepare("SELECT * from authors 
            			WHERE id_author = " . $author_id . "");
            $stmtauthor->execute();

            $resultauthor = $stmtauthor->get_result();
            $stmtauthor->close();


            while ($row_authors = $resultauthor->fetch_assoc()) {
                foreach ($row_authors as $key => $value) {
                    $author_array[$key] = $value;
                }

                $author_array['quotes'] = array();
                $id_author = $row_authors['id_author'];


                $stmtQuote = $this->conn->prepare(
                        "SELECT q.* ,c.category_name
                			FROM quotes q ,categories c
                			WHERE q.id_category = c.id_category
                			AND id_author = " . $id_author . " ");

                $stmtQuote->execute();
                $resultQuote = $stmtQuote->get_result();
                $stmtQuote->close();


                while ($row_quotes = $resultQuote->fetch_assoc()) {
                    foreach ($row_quotes as $key => $value) {
                        $quotes_array[$key] = $value;
                    }

                    array_push($author_array['quotes'], $quotes_array);
                }

                array_push($authorList_array, $author_array);
            }


            return $authorList_array;
        } else if ($category_id != 'all' && $author_id != 'all' && $quote_id == 'null') {
            // Single author Single Category

            $stmtauthor = $this->conn->prepare("SELECT * from author
            			WHERE id_author = " . $author_id . "");
            $stmtauthor->execute();

            $resultauthor = $stmtauthor->get_result();
            $stmtauthor->close();


            while ($row_authors = $resultauthor->fetch_assoc()) {
                foreach ($row_authors as $key => $value) {
                    $author_array[$key] = $value;
                }

                $author_array['quotes'] = array();
                $id_author = $row_authors['id_author'];






                $stmtQuote = $this->conn->prepare(
                        "SELECT q.* ,c.category_name
                			FROM quotes q ,categories c
                			WHERE q.id_category = c.id_category
                			AND id_author = " . $id_author . " 
            				AND q.id_category = " . $category_id . "");

                $stmtQuote->execute();
                $resultQuote = $stmtQuote->get_result();
                $stmtQuote->close();


                while ($row_quotes = $resultQuote->fetch_assoc()) {
                    foreach ($row_quotes as $key => $value) {
                        $quotes_array[$key] = $value;
                    }

                    array_push($author_array['quotes'], $quotes_array);
                }

                array_push($authorList_array, $author_array);
            }


            return $authorList_array;
        } else if ($category_id != 'all' && $author_id == 'all' && $quote_id == 'null') {
            // Single Category All author

            $stmtauthor = $this->conn->prepare("SELECT * from authors");
            $stmtauthor->execute();

            $resultauthor = $stmtauthor->get_result();
            $stmtauthor->close();


            while ($row_authors = $resultauthor->fetch_assoc()) {
                foreach ($row_authors as $key => $value) {
                    $author_array[$key] = $value;
                }

                $author_array['quotes'] = array();
                $id_author = $row_authors['id_author'];






                $stmtQuote = $this->conn->prepare(
                        "SELECT q.* ,c.category_name
                			FROM quotes q ,categories c
                			WHERE q.id_category = c.id_category
                			AND id_author = " . $id_author . "
            				AND q.id_category = " . $category_id . "");

                $stmtQuote->execute();
                $resultQuote = $stmtQuote->get_result();
                $stmtQuote->close();


                while ($row_quotes = $resultQuote->fetch_assoc()) {
                    foreach ($row_quotes as $key => $value) {
                        $quotes_array[$key] = $value;
                    }

                    array_push($author_array['quotes'], $quotes_array);
                }

                array_push($authorList_array, $author_array);
            }


            return $authorList_array;
        } else if ($category_id == 'null' && $author_id == 'null' && $quote_id != 'null') {
            // Single Category All author



            $stmtauthor = $this->conn->prepare("SELECT a.* 
													FROM author a, quotes q
													WHERE a.id_author = q.id_author
													AND id_quote = " . $quote_id . "");
            $stmtauthor->execute();

            $resultauthor = $stmtauthor->get_result();
            $stmtauthor->close();


            while ($row_authors = $resultauthor->fetch_assoc()) {
                foreach ($row_authors as $key => $value) {
                    $author_array[$key] = $value;
                }

                $author_array['quotes'] = array();
                $id_author = $row_authors['id_author'];






                $stmtQuote = $this->conn->prepare(
                        "SELECT q.* ,c.category_name
                			FROM quotes q ,categories c
                			WHERE q.id_category = c.id_category
                			AND id_author = " . $id_author . "
            				AND id_quote = " . $quote_id . "");

                $stmtQuote->execute();
                $resultQuote = $stmtQuote->get_result();
                $stmtQuote->close();


                while ($row_quotes = $resultQuote->fetch_assoc()) {
                    foreach ($row_quotes as $key => $value) {
                        $quotes_array[$key] = $value;
                    }

                    array_push($author_array['quotes'], $quotes_array);
                }

                array_push($authorList_array, $author_array);
            }


            return $authorList_array;
        }
    }

    public function createCouponRequest($id_offer, $id_retailer, $id_user) {

        $digits = 4;
        $coupon_code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $stmt = $this->conn->prepare("INSERT INTO coupon_requests(id_offer,id_retailer,id_user,coupon_code) VALUES($id_offer, $id_retailer,$id_user,$coupon_code)");
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // task row created
            // now assign the task to user
            // now assign the task to user
            $new_coupon_id = $this->conn->insert_id;

            if ($new_coupon_id) {
                // task created successfully
                return $new_coupon_id;
            } else {
                // task failed to create
                return NULL;
            }
        } else {
            // task failed to create
            return NULL;
        }
    }

    public function getCouponById($coupon_id) {
        $stmt = $this->conn->prepare("SELECT * FROM coupon_requests WHERE id = $coupon_id");

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        while ($rows = $result->fetch_assoc()) {
            foreach ($rows as $key => $value) {
                $coupon_array[$key] = $value;
            }
        }

        return $coupon_array;
    }

    public function getAddresses($user_id) {




        $stmt = $this->conn->prepare("SELECT a.*
 FROM addresses a, users u
WHERE a.user_id = u.id AND a.user_id=$user_id AND a.status = 1 
 ORDER BY a.id  ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $item_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }

    public function placeOrder($user_id, $address_id, $products) {

        $products_arr = json_decode($products, TRUE);

        $total_amount = 0;
        $discount = 0;
        foreach ($products_arr as $product) {
            $total_amount = $total_amount + (($product['price']) * $product['quantity']);
            $discount = $discount + (($product['mrp'] - $product['price']) * $product['quantity']);
        }




        $stmt = $this->conn->prepare("INSERT INTO `orders`(`user_id`, `address_id`, `amount`, `discount`)"
                . " VALUES ($user_id,$address_id,$total_amount,$discount)");



        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // task row created
            // now assign the task to user
            // now assign the task to user
            $new_order_id = $this->conn->insert_id;

            if ($new_order_id) {
                // task created successfully
                return $new_order_id;
            } else {
                // task failed to create
                return NULL;
            }
        } else {
            // task failed to create
            return NULL;
        }
    }

    public function placeOrderDetails($order_id, $products) {


        $products_arr = json_decode($products, TRUE);

//        print_r($products_arr);



        foreach ($products_arr as $product) {
            $product_id = $product['id'];
            $price = $product['price'];
            $quantity = $product['quantity'];

            $stmt = $this->conn->prepare("INSERT INTO `order_details`(`order_id`, `product_id`, `price`, `quantity`) "
                    . "VALUES ($order_id,$product_id,$price,$quantity)");

            $result = $stmt->execute();
        }



        $stmt->close();

        if ($result) {

            return ORDER_PLACED_SUCCESSFULLY;
        } else {
            // task failed to create
            return NULL;
        }
    }

    
        public function getUserCouponHistory($user_id) {




        $stmt = $this->conn->prepare("SELECT o.id as offer_id, o.name as offer_name, r.id as retailer_id, r.name as retailer_name, cr.created_at, cr.status
FROM offer_categories o, retailers r, coupon_requests cr
 WHERE
	o.id = 	cr.id_offer
    AND r.id = cr.id_retailer
    AND cr.id_user = $user_id
	AND cr.status = 1
     ");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $item_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }
    
    
    public function getServiceProviders($city_id, $category_id,$serach_query) {


        if($serach_query != 'null'){
              $stmt = $this->conn->prepare("SELECT s.*, c.city_name
 FROM service_providers s, cities c, service_categories sc
WHERE s.city_id = c.id AND s.category_id =sc.id AND s.city_id=$city_id
AND s.name LIKE '%' '$serach_query' '%'
    
 AND s.status = 1 
 ORDER BY name  ASC");
              
        }else{
              $stmt = $this->conn->prepare("SELECT s.*, c.city_name
 FROM service_providers s, cities c, service_categories sc
WHERE s.city_id = c.id AND s.category_id =sc.id AND s.city_id=$city_id AND s.category_id = $category_id 
 AND s.status = 1 
 ORDER BY name  ASC");
        }
            

      
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $item_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }

    public function getServiceCategories() {


        $stmt = $this->conn->prepare("SELECT * from service_categories  
    				WHERE status = 1 AND parent_id = 0 ORDER BY parent_id  ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $item_array = array();



        while ($row = $result->fetch_assoc()) {




            $stmt1 = $this->conn->prepare("SELECT * from service_categories  
    				WHERE status = 1 AND parent_id = " . $row['id'] . " ORDER BY parent_id  ASC");
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $stmt1->close();
            $sub_cat = array();

            while ($row1 = $result1->fetch_assoc()) {
                array_push($sub_cat, $row1);
            }

            //array_push($item_array, $row1);    


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }
            $item_temp['subcategories'] = $sub_cat;

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }

    public function getOrders($id) {


        $stmt = $this->conn->prepare("SELECT o.* , u.id as user_id, u.fname as user_fname, u.lname as user_lname,  u.phone, u.email, a.fname,a.lname,a.address,a.phone,a.pincode 
			FROM orders o, users u, addresses a 
			WHERE o.user_id = u.id AND a.id = o.address_id");

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $item_array = array();



        while ($row = $result->fetch_assoc()) {




            $stmt1 = $this->conn->prepare("SELECT od.* , 
p.id as product_id ,
p.category_id,
p.retailer_id,
p.name,
p.image1,
p.image2,
p.image3,
p.description,
p.quantity,
p.mrp,
p.price,
p.member_price,
p.status,
p.created_on,

r.id as retailer_id,
r.name as retailer_name,
r.city_id as retailer_city_id,
r.address as retailer_address,
r.description as retailer_description,
r.phone as retailer_phone,
r.image as retailer_image,
r.status as retailer_status


FROM order_details od, products p, retailers r
WHERE od.product_id = p.id AND p.retailer_id = r.id
AND od.order_id = " . $row['id'] . "");


            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $stmt1->close();
            $sub_cat = array();

            while ($row1 = $result1->fetch_assoc()) {
                array_push($sub_cat, $row1);
            }

            //array_push($item_array, $row1);    


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }
            $item_temp['products'] = $sub_cat;

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }

    public function getCities() {


        $stmt = $this->conn->prepare("SELECT * from cities  
    				WHERE status = 1 AND parent_id = 0 ORDER BY parent_id  ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $item_array = array();



        while ($row = $result->fetch_assoc()) {




            $stmt1 = $this->conn->prepare("SELECT * from cities  
    				WHERE status = 1 AND parent_id = " . $row['id'] . " ORDER BY parent_id  ASC");
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $stmt1->close();
            $sub_cat = array();

            while ($row1 = $result1->fetch_assoc()) {
                array_push($sub_cat, $row1);
            }

            //array_push($item_array, $row1);    


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }
            $item_temp['areas'] = $sub_cat;

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }

    public function getOfferCategories() {




        $stmt = $this->conn->prepare("SELECT * from offer_categories  
    				WHERE status = 1 ORDER BY position  ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $item_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }

    function buildTree(array $elements, $parentId = 0) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = buildTree($elements, $element['id']);
                if ($children) {
                    $element['subCat'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    public function getShoppingCategories() {


        $stmt = $this->conn->prepare("SELECT * from shopping_categories  
    				WHERE status = 1 AND parent_id = 0 ORDER BY position  ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $item_array = array();



        while ($row = $result->fetch_assoc()) {




            $stmt1 = $this->conn->prepare("SELECT * from shopping_categories  
    				WHERE status = 1 AND parent_id = " . $row['id'] . " ORDER BY position  ASC");
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $stmt1->close();
            $sub_cat = array();

            while ($row1 = $result1->fetch_assoc()) {
                array_push($sub_cat, $row1);
            }

            //array_push($item_array, $row1);    


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }
            $item_temp['subcategories'] = $sub_cat;

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }

    public function updateValidateCoupon($coupon_id) {
        $stmt = $this->conn->prepare("UPDATE coupon_requests set status = 1 WHERE id = $coupon_id");
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function getRetailers($city_id, $retailer_category_id,$search_query) {


        if($search_query != 'null'){
            
                        $stmt = $this->conn->prepare("SELECT s.*, c.city_name, o.id as offer_category_id, o.name as offer_name
            FROM retailers s, cities c, offer_categories o
            WHERE s.city_id = c.id AND  s.offer_category_id = o.id AND s.city_id=$city_id  
			AND s.name LIKE '%' '$search_query' '%'
            AND s.status = 1 
            ORDER BY name  ASC");
            
        }else{
            
            
            if ($retailer_category_id == 'all') {



            $stmt = $this->conn->prepare("SELECT s.*, c.city_name, o.id as offer_category_id, o.name as offer_name
            FROM retailers s, cities c, offer_categories o
            WHERE s.city_id = c.id AND  s.offer_category_id = o.id AND s.city_id=$city_id  
            AND s.status = 1 
            ORDER BY name  ASC");
            } 
            else 
                {
            $stmt = $this->conn->prepare("SELECT s.*, c.city_name, o.id as offer_category_id, o.name as offer_name
            FROM retailers s, cities c, retailer_categories sc , offer_categories o
            WHERE s.city_id = c.id AND s.category_id =sc.id AND s.offer_category_id = o.id AND s.city_id=$city_id AND s.category_id = $retailer_category_id 
            AND s.status = 1 
            ORDER BY name  ASC");
            
    
            }
        
        }
        

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $item_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }

            array_push($item_array, $item_temp);
        }



        return $item_array;
    }

    public function getProducts($category_id, $distrubutor_id, $search_query) 
            {
        
        if($search_query != 'null'){
            
         
            $qry = "SELECT p.*
            FROM products p, shopping_categories sc
            WHERE p.category_id = sc.id 
            AND p.name LIKE '%' '$search_query' '%'
            AND p.status = 1 
            ORDER BY name  ASC";
            
            
            $stmt = $this->conn->prepare($qry);
            
        }
        else{
            
    


        if ($category_id != 'all' && $distrubutor_id == 'all') {
            
            
            
            $stmt = $this->conn->prepare("SELECT p.*
 FROM products p, shopping_categories sc
WHERE p.category_id = sc.id 
AND p.category_id =$category_id

 AND p.status = 1 
 ORDER BY name  ASC");
        } else {

            $stmt = $this->conn->prepare("SELECT p.*
 FROM products p, shopping_categories sc
WHERE p.category_id = sc.id AND p.category_id = " . $category_id . " 
 AND p.status = 1 
 ORDER BY name  ASC");
        }

    }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $item_array = array();



        while ($row = $result->fetch_assoc()) {



                    

            $stmt1 = $this->conn->prepare("SELECT p.* , 
( SELECT AVG(ratings) 
      FROM ratings r
      WHERE r.product_varient_id = p.id
    )
    AS avg_rating 
    
    from product_varients p 
    WHERE status = 1 AND p.product_id = " . $row['id'] . " ORDER BY id  ASC");
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $stmt1->close();
            $sub_cat = array();

            while ($row1 = $result1->fetch_assoc()) {
                array_push($sub_cat, $row1);
            }

            //array_push($item_array, $row1);    


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }
            $item_temp['product_varients'] = $sub_cat;

            array_push($item_array, $item_temp);
        }


        return $item_array;




//                $stmt = $this->conn->prepare("SELECT p.*
// FROM products p, shopping_categories sc
//WHERE p.category_id = sc.id AND p.category_id = ".$category_id." 
// AND p.status = 1 
// ORDER BY name  ASC");
//    		$stmt->execute();
//    		$result = $stmt->get_result();
//    		$stmt->close();
//                
//                
//            }
//
//    		
//                
//                
//    		$item_array = array();
//    
//    		while($row = $result->fetch_assoc())
//    		{
//    			
//    			
//    			foreach( $row as $key=>$value )
//    			{
//    				$item_temp[$key] = $value;
//    			}
//    			 
//    			array_push($item_array,$item_temp);
//    			 
//    		}
//    		return $item_array;
    }

    public function getAdSliders() {




        $stmt = $this->conn->prepare("SELECT * from adsliders  
    				WHERE status = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $item_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }
    
    public function getUser($user_id) {




        $stmt = $this->conn->prepare("SELECT * from users  
    				WHERE id = $user_id ");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $item_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }

            array_push($item_array, $item_temp);
        }


        return $item_array;
    }
    

    public function deleteAdSlider($id) {
        $stmt = $this->conn->prepare("DELETE t FROM adsliders t WHERE id = $id");
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function deleteRetailer($id) {
        $stmt = $this->conn->prepare("DELETE t FROM retailers t WHERE id = $id");
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function getRetailerCategories() {


        $stmt = $this->conn->prepare("SELECT * from retailer_categories  
    				WHERE status = 1 AND parent_id = 0 ORDER BY parent_id  ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $item_array = array();



        while ($row = $result->fetch_assoc()) {




            $stmt1 = $this->conn->prepare("SELECT * from retailer_categories  
    				WHERE status = 1 AND parent_id = " . $row['id'] . " ORDER BY parent_id  ASC");
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $stmt1->close();
            $sub_cat = array();

            while ($row1 = $result1->fetch_assoc()) {
                array_push($sub_cat, $row1);
            }

            //array_push($item_array, $row1);    


            foreach ($row as $key => $value) {
                $item_temp[$key] = $value;
            }
            $item_temp['subcategories'] = $sub_cat;

            array_push($item_array, $item_temp);
        }


        return $item_array;


//                
//                
//
//    		$stmt = $this->conn->prepare("SELECT * from retailer_categories  
//    				WHERE status = 1");
//    		$stmt->execute();
//    		$result = $stmt->get_result();
//    		$stmt->close();
//                
//                
//    		$item_array = array();
//    
//    		while($row = $result->fetch_assoc())
//    		{
//    			
//    			
//    			foreach( $row as $key=>$value )
//    			{
//    				$item_temp[$key] = $value;
//    			}
//    			 
//    			array_push($item_array,$item_temp);
//    			 
//    		}
//    
//
//    		return $item_array;
//    
    }

    public function getCategories() {




        $stmt = $this->conn->prepare("SELECT * from categories 
    				ORDER BY category_name  ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        $categories_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $categories_temp[$key] = $value;
            }

            array_push($categories_array, $categories_temp);
        }


        return $categories_array;
    }

    public function getAuthors($sort) {


        $stmt = null;

        if ($sort == "by_asc") {

            $stmt = $this->conn->prepare("SELECT * from authors
    				ORDER BY author_name  ASC");
        } else if ($sort == "by_likes") {

            $stmt = $this->conn->prepare("SELECT * from authors
    				ORDER BY author_likes_count DESC");
        }

        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();
        $authors_array = array();

        while ($row = $result->fetch_assoc()) {


            foreach ($row as $key => $value) {
                $authors_temp[$key] = $value;
            }

            array_push($authors_array, $authors_temp);
        }


        return $authors_array;
    }

    /**
     * Fetching all user tasks
     * @param String $user_id id of the user
     */
    public function getAllUserTasks($user_id) {
        $stmt = $this->conn->prepare("SELECT t.* FROM tasks t, user_tasks ut WHERE t.id = ut.task_id AND ut.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();

        return $tasks;
    }

    /**
     * Updating task
     * @param String $task_id id of the task
     * @param String $task task text
     * @param String $status task status
     */
    public function updateTask($user_id, $task_id, $task, $status) {
        $stmt = $this->conn->prepare("UPDATE tasks t, user_tasks ut set t.task = ?, t.status = ? WHERE t.id = ? AND t.id = ut.task_id AND ut.user_id = ?");
        $stmt->bind_param("siii", $task, $status, $task_id, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /**
     * Deleting a task
     * @param String $task_id id of the task to delete
     */
    public function deleteTask($user_id, $task_id) {
        $stmt = $this->conn->prepare("DELETE t FROM tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /* ------------- `user_tasks` table method ------------------ */

    /**
     * Function to assign a task to user
     * @param String $user_id id of the user
     * @param String $task_id id of the task
     */
    public function createUserTask($user_id, $task_id) {
        $stmt = $this->conn->prepare("INSERT INTO user_tasks(user_id, task_id) values(?, ?)");
        $stmt->bind_param("ii", $user_id, $task_id);
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }

}

?>

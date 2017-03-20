<?php


ini_set('display_errors', 1); 
error_reporting(E_ALL);


    require './db_connection.php';

if ($_GET['page'] == 'adslider') {

    if (isset($_POST['name'])) {
        // include Database connection file 
        $name = $_POST['name'];

        // get values 
        $name = $_POST['name'];

        $imgFile = $_FILES['image']['name'];
        $tmp_dir = $_FILES['image']['tmp_name'];
        $imgSize = $_FILES['image']['size'];

        $errMSG = "";

        if (empty($name)) {
            $errMSG = "Please Enter Username.";
        } else {
            
            $folder_name = "imagesliders/";
            
            $upload_dir = BASE_IMAGE_DIR.$folder_name; // upload directory

            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
            // valid image extensions
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
            // rename uploading image
            $userpic = rand(1000, 1000000) . "." . $imgExt;

            $imagename = $folder_name.$userpic;
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




        $query = "INSERT INTO adsliders(name, image)"
                . " VALUES('$name', '$imagename' )";


        if (!$result = mysqli_query($mysqli,$query)) {
            exit(mysql_error());
        }
        echo "1 Record Added!";
    }
} else {
    echo "<script type='text/javascript'>alert('no');</script>";

    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])) {
        // include Database connection file 
        include("db_connection.php");

        // get values 
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];

        $query = "INSERT INTO users(first_name, last_name, email) VALUES('$first_name', '$last_name', '$email')";
        if (!$result = mysql_query($query)) {
            exit(mysql_error());
        }
        echo "1 Record Added!";
    }
}
?>
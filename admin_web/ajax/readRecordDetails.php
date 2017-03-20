<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
 
// include Database connection file
include("db_connection.php");

// check request
if (isset($_POST['id']) && isset($_POST['id']) != "") {
    // get User ID

    if ($_GET['page'] == 'adslider') {

        $id = $_POST['id'];

        // Get User Details
        $query = "SELECT * FROM adsliders WHERE id = $id";
        if (!$result = mysqli_query($mysqli,$query)) {
            exit(mysqli_error());
        }
        $response = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $response = $row;
            }
        } else {
            $response['status'] = 200;
            $response['message'] = "Data not found!";
        }
        // display JSON data
        echo json_encode($response);
    }
} else {
    $response['status'] = 200;
    $response['message'] = "Invalid Request!";
}
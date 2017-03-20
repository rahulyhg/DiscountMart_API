<?php

// check request
if (isset($_POST['id']) && isset($_POST['id']) != "") {
    // include Database connection file

    require './db_connection.php';
    // get user id
    $id = $_POST['id'];

    if ($_GET['page'] == 'adslider') {

        $query = "DELETE FROM adsliders WHERE id = '$id'";
        if (!$result = mysqli_query($mysqli,$query)) {
            exit(mysql_error());
        }
    }
}
?>
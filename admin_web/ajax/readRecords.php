<?php
	// include Database connection file

ini_set('display_errors', 1); 
error_reporting(E_ALL);
 
    require './db_connection.php';
        
         
$page = $_GET['page'];

//echo "<script type='text/javascript'>alert('$message');</script>";

if($page == 'adslider' ){
    
    	// Design initial table header 
	$data = '<table class="table table-bordered table-striped">
						<tr>
							<th>ID.</th>
							<th>Name</th>
							<th>Image</th>
							<th>Status</th>
							<th>Action</th>

						</tr>';

	$query = "SELECT * FROM adsliders";

	if (!$result = mysqli_query($mysqli,$query)) {
        exit(mysqli_error());
    }

    // if query results contains rows then featch those rows 
    if(mysqli_num_rows($result) > 0)
    {
    	while($row = mysqli_fetch_assoc($result))
    	{
    		$data .= '<tr>
				<td>'.$row['id'].'</td>
				<td>'.$row['name'].'</td>
                                <td><img src='.BASE_URL_IMAGES.''.$row['image'].' alt="" style="width:200px; height:auto;"></td>
				<td>'.$row['status'].'</td>
				<td>
					<button onclick="GetRecordDetails(`'.$page.'`,'.$row['id'].')" class="btn btn-warning">Update</button>
					<button onclick="DeleteRecord(`adslider`,'.$row['id'].')" class="btn btn-danger">Delete</button>
				

</td>
				
				
                                

    		</tr>';
    	}
    }
    else
    {
    	// records now found 
    	$data .= '<tr><td colspan="6">Records not found!</td></tr>';
    }

    $data .= '</table>';

    echo $data;
    
    
}else{
    
}


?>
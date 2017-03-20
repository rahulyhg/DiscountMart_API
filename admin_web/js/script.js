// Add Record

function handleFileSelect()
  {               
    if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
      alert('The File APIs are not fully supported in this browser.');
      return;
    }   

    input = document.getElementById('image');
    if (!input) {
      alert("Um, couldn't find the fileinput element.");
    }
    else if (!input.files) {
      alert("This browser doesn't seem to support the `files` property of file inputs.");
    }
    else if (!input.files[0]) {
      alert("Please select a file before clicking 'Load'");               
    }
    else {
      file = input.files[0];
      fr = new FileReader();
      fr.onload = receivedText;
      //fr.readAsText(file);
      fr.readAsDataURL(file);
    }
  }

  function receivedText() {
    document.getElementById('editor').appendChild(document.createTextNode(fr.result));
  }           
  
  
function addRecord(page) {
    // get values
    
    if(page == 'adslider'){
        

    var fd = new FormData();
    var file_data = $('input[type="file"]')[0].files; // for multiple files
    for(var i = 0;i<file_data.length;i++){
        fd.append("image", file_data[i]);
    }
 
     fd.append('name',$("#name").val());
    
    $.ajax({
        url: 'ajax/addRecord.php/?page='+page,
        data: fd,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data){
            console.log(data);
                alert('s');
        }
    });

    // Add record
//    $.post("ajax/addRecord.php", 
//    {
//        name: name,
//        image: image
//    }, 
//    function (data, status) 
//    {
//        // close the popup
//        $("#add_new_record_modal").modal("hide");
//
//        // read records again
//        readRecords();
//
//        // clear fields from the popup
//        $("#first_name").val("");
//        $("#last_name").val("");
//        $("#email").val("");
//    });
    
    }else{
        
    }
    

}

// READ records
function readRecords(page) {
    
    //alert("ajax/readRecords.php/?page="+page);
    
    
    $.get("ajax/readRecords.php/?page="+page, {},
    
    function (data, status)
    {
        $(".records_content").html(data);
    });
}


function DeleteRecord(page,id) {
    


            var conf = confirm("Are you sure, do you really want to delete this Slider?");
    if (conf == true) {
        $.post("ajax/deleteRecord.php/?page="+page, {
                id: id
            },
            function (data, status) {
                // reload Users by using readRecords();
                readRecords(page);
            }
        );
    
    }

}


function DeleteUser(page,id) {
    var conf = confirm("Are you sure, do you really want to delete User?");
    if (conf == true) {
        $.post("ajax/deleteUser.php", {
                id: id
            },
            function (data, status) {
                // reload Users by using readRecords();
                readRecords();
            }
        );
    }
}


function GetRecordDetails(page,id) {
    // Add User ID to the hidden field for furture usage
        
        //alert("ajax/readRecords.php/?page="+page);


    if(page == 'adslider'){
        
         $("#hidden_user_id").val(id);
         
    $.post("ajax/readRecordDetails.php/?page="+page, {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var adslider = JSON.parse(data);
            // Assing existing values to the modal popup fields
                    
            alert(adslider.image);

            
            $("#update_name").val(adslider.name);
            $("#update_image").val(adslider.image);
//            $('#update_image_prev').attr("src",adslider.image);

        $("#update_image_prev").attr('src', $("#update_image_prev")
           .attr('src') + '?' + Math.random() );
   

        }
    );
    // Open modal popup
    $("#update_user_modal").modal("show");
    
    }
   
}


function GetUserDetails(id) {
    // Add User ID to the hidden field for furture usage
    $("#hidden_user_id").val(id);
    $.post("ajax/readUserDetails.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var user = JSON.parse(data);
            // Assing existing values to the modal popup fields
            $("#update_first_name").val(user.first_name);
            $("#update_last_name").val(user.last_name);
            $("#update_email").val(user.email);
        }
    );
    // Open modal popup
    $("#update_user_modal").modal("show");
}

function UpdateUserDetails() {
    // get values
    var first_name = $("#update_first_name").val();
    var last_name = $("#update_last_name").val();
    var email = $("#update_email").val();

    // get hidden field value
    var id = $("#hidden_user_id").val();

    // Update the details by requesting to the server using ajax
    $.post("ajax/updateUserDetails.php", {
            id: id,
            first_name: first_name,
            last_name: last_name,
            email: email
        },
        function (data, status) {
            // hide modal popup
            $("#update_user_modal").modal("hide");
            // reload Users by using readRecords();
            readRecords();
        }
    );
}

$(document).ready(function () {
    // READ recods on page load
    var div_id = $('#current_div').val();  
    if(div_id == 'adslider'){
            readRecords('adslider'); // calling function

    }
});
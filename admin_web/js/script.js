// Add Record
path_to_index = "../v1/";
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
        
   var function_name = 'add_record/';
   var url = path_to_index+function_name+page;
       

    var fd = new FormData();
    var file_data = $('input[type="file"]')[0].files; // for multiple files
    for(var i = 0;i<file_data.length;i++){
        fd.append("image", file_data[i]);
    }
 
     fd.append('name',$("#name").val());
    
    $.ajax({
        url: url,
        data: fd,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data){
            console.log(data);
                    
            $("#add_new_record_modal").modal("hide");

        // read records again
        readRecords(page);

        // clear fields from the popup
        $("#name").val("");

                
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
    
    
     var function_name = 'read_records/';
       var url = path_to_index+function_name+page;
    
    
    $.get(url, {},
    
    function (data, status)
    {
        $(".records_content").html(data);
    });
}


function DeleteRecord(page,id) {
    
     var function_name = 'delete_records/';
       var url = path_to_index+function_name+page;

            var conf = confirm("Are you sure, do you really want to delete this Slider?");
    if (conf == true) {
        $.post(url, {
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
        

       var BASE_IMAGE_DIR= 'http://192.168.1.100/discountmart_web/admin_web/images/';

       var function_name = 'get_record_details/';
       var url = path_to_index+function_name+page;
       
       
         $("#hidden_user_id").val(id);

         
    $.post(url, {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var adslider = JSON.parse(data);
            // Assing existing values to the modal popup fields
                        
            var src = $("#update_image_prev").attr('src'); 
//            alert(src);
            $("#update_image_prev").attr("src",BASE_IMAGE_DIR+adslider.image);

            $("#update_name").val(adslider.name);
            $("#update_image").val(adslider.image);
            
    

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

function UpdateRecord(page) {
    // get values
//    var name = $("#name").val();
//    var image = $("#image").val();

   var function_name = 'update_record/';
   var url = path_to_index+function_name+page;
   
   
    var fd = new FormData();
    var file_data = $('input[type="file"]')[0].files; // for multiple files
alert(file_data.length);
    for(var i = 0;i<file_data.length;i++){
                

        fd.append("image", file_data[i]);
    }
 
    // get hidden field value
    var id = $("#hidden_user_id").val();
    
     fd.append('name',$("#update_name").val());
          fd.append('id',id);

     
 
    
        $.ajax({
        url: url,
        data: fd,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data){
            console.log(data);
                    
//            $("#update_user_modal").modal("hide");

        // read records again
        readRecords(page);

                
        }
    });
    
    
//
//    // Update the details by requesting to the server using ajax
//    $.post("ajax/updateUserDetails.php", {
//            id: id,
//            first_name: first_name,
//            last_name: last_name,
//            email: email
//        },
//        function (data, status) {
//            // hide modal popup
//            $("#update_user_modal").modal("hide");
//            // reload Users by using readRecords();
//            readRecords();
//        }
//    );
}

$(document).ready(function () {
    // READ recods on page load
    var div_id = $('#current_div').val();  
    if(div_id == 'adslider'){
            readRecords('adslider'); // calling function

    }
});
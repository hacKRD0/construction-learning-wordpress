jQuery(document).ready(function(){ 	


 

/*
*The following script for enable/disble buttons and form fileds
*@Templates: College and company profiles
*/

jQuery("#removeDisable").click(function(event){
   event.preventDefault();
   jQuery('.inputDisabled').removeAttr("disabled").removeClass("d-none").removeClass("hide");
   jQuery(this).addClass('d-none');
   jQuery("#removeDisable2").addClass('d-none');
});
jQuery("#removeDisable2").click(function(event){
   event.preventDefault();
   jQuery('.inputDisabled').removeAttr("disabled").removeClass("d-none").removeClass("hide");
   jQuery(this).addClass('d-none');
   jQuery("#removeDisable").addClass('d-none');
});


/*
*The following script for enable disable mannage emypoyes table fileds
*@Templates: College and company profiles
*/


jQuery("#edit").click(function(){
 	jQuery('.update').prop('contenteditable', true);
 	jQuery('.update').addClass('updatetd');
});
 	 var dataTable =  new DataTable('#user_data', {
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    'ajax': {
        url : ajaxurl,
        type:"POST",
       'data': function(data){
          data.action = "fetch_empyolees_data";
       }
    },
    
  });



/*
*The following script for update data table records.
*@Templates: College and company profiles
*/


function update_data(id, column_name, value,email)
  {
  	jQuery('#loader').show();
   jQuery.ajax({
    url : ajaxurl,
    method:"POST",
    data:{id:id, column_name:column_name, value:value,email:email,action: "update_emp_profile_dataTable"},
    success:function(data)
    {
     jQuery('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
     //jQuery('#user_data').DataTable().destroy();
    // fetch_data();
    jQuery('#loader').hide();
    }
   });
   setInterval(function(){
    jQuery('#alert_message').html('');
   }, 5000);
  }


jQuery(document).on('blur', '.update', function(){
   
   var id = jQuery(this).data("id");
   var column_name = jQuery(this).data("column");
   var value = jQuery(this).text();
    var email = jQuery(this).data("email");
   update_data(id, column_name, value,email);
  });


/*
*The following script for Add/EDIT profile Images.
*@Templates: College and company profiles
*/

jQuery("#but_upload").click(function(){  
         var fd = new FormData();
        var files = jQuery('#imgInp')[0].files[0];
        fd.append('file',files); 
        fd.append( "action", 'update_members_profile_image');

         jQuery.ajax({
         	type: "POST",
            url : ajaxurl,
           data: fd,      
            contentType: false,
            processData: false,
            success: function(response){
                if(response == 'success0'){
                   alert('Image uploaded');
                }else{
                    alert('file not uploaded');
                }
            },
        });
    });

 function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                jQuery('.avatar').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
   jQuery("#imgInp").change(function(){
        readURL(this);
        
    });

/*
*The following script for validator  Phone Number Fileds.
*@Templates: College and company profiles
*/

jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length > 9 && 
    // phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
    phone_number.match(/^(0|91)?[6-9][0-9]{9}$/);
}, "Please specify a valid phone number");
    jQuery('#updateUserProfile').validate({
        rules: {
            phoneNo: {
                phoneUS: true,
                required: true
            }
        },
       
    });



 /*
*The following script Jquery UI Calender date pickers.
*@Templates: College and company profiles
*/
 
jQuery(document).on('focus',".StartDate", function(){ 
    jQuery(this).datepicker({
        dateFormat: 'dd-M-yy', 
        changeMonth: true, 
        changeYear: true, 
       yearRange: "-90:+00"

    });
});

/*
*The following script for ajax calling when member Update / add own porifle data
*@Templates: College and company profiles
*/

jQuery("#updateUserProfile").submit(function(e) {
  if (!jQuery(this).valid()) return false;
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = jQuery(this);
    var actionUrl = form.attr('action');
    jQuery.ajax({
          type: "POST",
        url : ajaxurl,
         data : {action: "update_members_profile", data : form.serialize()},
       
        success: function(data)
        {
         jQuery('#updateproInfo1').show();
          location.reload(true);
        }
    });
  });



/*
*The following script for Add remove fileds
*@Templates: College and company profiles
*/




/*
*The following script for Add remove project_table fileds
*@Templates: College and company profiles
*/

 var max_rows   = 10; //Maximum allowed group of input fields 
    var wrapper    = jQuery("#project_table"); //Group of input fields wrapper
    var add_button = jQuery(".add_employee1"); //Add button class or ID
    var x = 1

    jQuery(add_button).click(function(e){ //On click add employee button
        e.preventDefault();
        if(x < max_rows){ //max group of input fields allowed
            x++; //group of input fields increment
            jQuery('#project_table tr:last td:last-child').html("");
            jQuery(wrapper).append('<tr><td><input type="text" name="comProjects[title][]" placeholder="Enter Title" class="form-control" value="">	</td><td> <textarea placeholder="Enter Content " name="comProjects[content][]" class="form-control "  rows="3"></textarea></td><td><div class="preview"><img id="file-ip-1-preview"></div><input type="file" name="upload_attachment[]" accept="image/*" ></td><td><button class="remove_projects">Remove</button></td></td></tr>'); 
	}
    }); 

    jQuery(wrapper).on("click",".remove_projects", function(e){ // On click to remove button
        e.preventDefault(); jQuery(this).parent().parent().remove(); x--;
        var rowCount = jQuery("#project_table tr").length;
        if(rowCount>2){
            jQuery('#project_table tr:last td:last-child').html('<button class="remove_projects">Remove</button></td>');
        }
    });

/*
*The following script for Choose and PREVIEW Image
*@Templates: College and company profiles
*/


ImgUpload();

/*
*The following script for Choose and PREVIEW Image
*@Templates: College and company profiles
*/


function ImgUpload() {
  var imgWrap = "";
  var imgArray = [];

  jQuery('.upload__inputfile').each(function () {
    jQuery(this).on('change', function (e) {
      imgWrap = jQuery(this).closest('.upload__box').find('.upload__img-wrap');
      var maxLength = jQuery(this).attr('data-max_length');

      var files = e.target.files;
      var filesArr = Array.prototype.slice.call(files);
      var iterator = 0;
      filesArr.forEach(function (f, index) {

        if (!f.type.match('image.*')) {
          return;
        }

        if (imgArray.length > maxLength) {
          return false
        } else {
          var len = 0;
          for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i] !== undefined) {
              len++;
            }
          }
          if (len > maxLength) {
            return false;
          } else {
            imgArray.push(f);

            var reader = new FileReader();
            reader.onload = function (e) {
              var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + jQuery(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
              imgWrap.append(html);
              iterator++;
            }
            reader.readAsDataURL(f);
          }
        }
      });
    });
  });

  jQuery('body').on('click', ".upload__img-close", function (e) {
    var file = jQuery(this).parent().data("file");
    for (var i = 0; i < imgArray.length; i++) {
      if (imgArray[i].name === file) {
        imgArray.splice(i, 1);
        break;
      }
    }
    jQuery(this).parent().parent().remove();
  });
}



//close ready function
});
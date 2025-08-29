jQuery(document).ready(function(){ 

jQuery("#uploadResume").submit(function(e) {

         var fd = new FormData();
        var files = jQuery('#input-b1')[0].files[0];
        fd.append('file',files); 
        fd.append( "action", 'student_uploadResume');

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


jQuery('.update-btn').hide();

jQuery(".imgbgchk").click(function(){
   jQuery('.inputDisabled').removeAttr("disabled");
  
});
	jQuery("#removeDisable").click(function(event){
   event.preventDefault();
   jQuery('.inputDisabled').removeAttr("disabled");
   jQuery('#removeDisable').hide();
});

jQuery("#removeDisable2").click(function(event){
	
   event.preventDefault();
       jQuery('.add_employee').removeClass("inputDisabledadmore");

   jQuery('.inputDisabled').removeAttr("disabled");
   jQuery('#removeDisable2').hide();
   jQuery('.update-btn').show();
});
   //jQuery("#but_upload").click(function(){  

   	jQuery("#myform").submit(function(e) {
         var fd = new FormData();
        var files = jQuery('#input-b7')[0].files[0];
        fd.append('file',files); 
        fd.append( "action", 'update_members_profile_image');

         jQuery.ajax({
         	type: "POST",
             url : ajaxurl,
           data: fd,      
            contentType: false,
            processData: false,
            success: function(response){
            	 location.reload(true);
                if(response == 'success0'){
                   alert('Image uploaded');
                }else{
                    alert('file not uploaded');
                }
            },
        });
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

jQuery(document).ready(function() {
    jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length > 9 && 
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
    
});

 jQuery(document).on('focus',".StartDate", function(){ 
    jQuery(this).datepicker({
        dateFormat: 'dd-M-yy', 
        changeMonth: true, 
        changeYear: true, 
       yearRange: "-90:+00"

    });
});


jQuery("#updateUserProfile").submit(function(e) {
  if (!jQuery(this).valid()) return false;
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = jQuery(this);
    var actionUrl = form.attr('action');
    console.log(form.serialize());
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




jQuery("#updateBillingAddress").submit(function(e) {
  if (!jQuery(this).valid()) return false;
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = jQuery(this);
    jQuery.ajax({
          type: "POST",
         url : ajaxurl,
         data : {action: "update_billing_address", data : form.serialize()},
       
        success: function(data)
        {
         jQuery('#updateproInfo1').show();
          //location.reload(true);
        }
    });
    });



jQuery(document).ready(function(){
	jQuery(document).on("click", ".editBtn" , function() {
        //hide edit span
        jQuery(this).closest("tr").find(".editSpan").hide();

        //show edit input
        jQuery(this).closest("tr").find(".editInput").show();

        //hide edit button
        jQuery(this).closest("tr").find(".editBtn").hide();

        //hide delete button
        jQuery(this).closest("tr").find(".deleteBtn").hide();
        
        //show save button
        jQuery(this).closest("tr").find(".saveBtn").show();

        //show cancel button
        jQuery(this).closest("tr").find(".cancelBtn").show();
        
    });
    
	jQuery(document).on("click", ".saveBtn" , function() {
        jQuery('#userData').css('opacity', '.5');
         
        var trObj = jQuery(this).closest("tr");
        var ID = jQuery(this).closest("tr").attr('id');
        var inputData = jQuery(this).closest("tr").find(".editInput").serialize();
        jQuery.ajax({
            type:'POST',
            url : ajaxurl,
            dataType: "json",
            data:'action=edit_user_exprances&id='+ID+'&'+inputData,
            success:function(response){
            	 location.reload(true);
                if(response.status == 1){
                    trObj.find(".editSpan.todate ").text(response.todate);
                    trObj.find(".editSpan.formdate ").text(response.fromdate);
                    trObj.find(".editSpan.Organization").text(response.organization);
                    trObj.find(".editSpan.position").text(response.position);
                     trObj.find(".editSpan.experience").text(response.experience);
                      trObj.find(".editSpan.responsibility").text(response.responsibility);

                    
                    trObj.find(".editInput.todate").val(response.todate);
                    trObj.find(".editInput.formdate").val(response.fromdate);
                    trObj.find(".editInput.Organization").val(response.organization);
                    trObj.find(".editInput.position").val(response.position);
                    trObj.find(".editInput.experience").val(response.experience);
                    trObj.find(".editInput.responsibility").val(response.responsibility);
                    
                    trObj.find(".editInput").hide();
                    trObj.find(".editSpan").show();
                    trObj.find(".saveBtn").hide();
                    trObj.find(".cancelBtn").hide();
                    trObj.find(".editBtn").show();
                    trObj.find(".deleteBtn").show();
                }else{
                   
                }
                jQuery('#userData').css('opacity', '');

            }
        });
    });

	jQuery(document).on("click", ".cancelBtn" , function() {
        jQuery('#userData').css('opacity', '.5');
        //hide & show buttons
        jQuery(this).closest("tr").find(".saveBtn").hide();
        jQuery(this).closest("tr").find(".cancelBtn").hide();
        jQuery(this).closest("tr").find(".confirmBtn").hide();
        jQuery(this).closest("tr").find(".editBtn").show();
        jQuery(this).closest("tr").find(".deleteBtn").show();

        //hide input and show values
        jQuery(this).closest("tr").find(".editInput").hide();
        jQuery(this).closest("tr").find(".editSpan").show();
    });
    
	jQuery(document).on("click", ".deleteBtn" , function() {
        //hide edit & delete button
       
		
        jQuery(this).closest("tr").find(".editBtn").hide();
       jQuery(this).closest("tr").find(".deleteBtn").hide();
        
        //show confirm & cancel button
        jQuery(this).closest("tr").find(".confirmBtn").show();
        jQuery(this).closest("tr").find(".cancelBtn").show();
    });
    
	jQuery(document).on("click", ".confirmBtn" , function() {
        jQuery('#userData').css('opacity', '.5');

        var trObj = jQuery(this).closest("tr");
        var ID = jQuery(this).closest("tr").attr('id');
        jQuery.ajax({
            type:'POST',
            url : ajaxurl,
            dataType: "json",
            data:'action=delete_exprances&id='+ID,
            success:function(response){
                if(response.status == 1){
                    trObj.remove();
                }else{
                    trObj.find(".confirmBtn").hide();
                    trObj.find(".cancelBtn").hide();
                    trObj.find(".editBtn").show();
                    trObj.find(".deleteBtn").show();
                    alert(response.msg);
                }
                jQuery('#userData').css('opacity', '');
                location.reload(true);
            }
        });
    });

    jQuery('form[name=setDefaultProfile]').submit(function(e){
        e.preventDefault();
        var form = jQuery(this);

        jQuery.ajax({
            type: "POST",
            url : ajaxurl,
            data : {action: "set_default_profile", data : form.serialize()},
            success: function(data){
              location.reload(true);
            }
        });
    });
});



//eduction script
//**************************************************//
jQuery(document).ready(function(){
	jQuery(document).on("click", ".editBtn1" , function() {
        //hide edit span
        jQuery(this).closest("tr").find(".editSpan").hide();

        //show edit input
        jQuery(this).closest("tr").find(".editInput").show();

        //hide edit button
        jQuery(this).closest("tr").find(".editBtn1").hide();

        //hide delete button
        jQuery(this).closest("tr").find(".deleteBtn1").hide();
        
        //show save button
        jQuery(this).closest("tr").find(".saveBtn1").show();

        //show cancel button
        jQuery(this).closest("tr").find(".cancelBtn1").show();

        if (jQuery(this).closest("tr").find(".institute_name").length) {
            jQuery(this).closest("tr").find("select.institute_name").select2({
                tags: true
            });
        }
        
    });
    
	jQuery(document).on("click", ".saveBtn1" , function() {
        jQuery('#userData').css('opacity', '.5');

        var trObj = jQuery(this).closest("tr");
        var ID = jQuery(this).closest("tr").attr('id');
        var inputData = jQuery(this).closest("tr").find(".editInput").serialize();
        jQuery.ajax({
            type:'POST',
            url : ajaxurl,
            dataType: "json",
            data:'action=edit_user_education&id='+ID+'&'+inputData,
            success:function(response){
            	//location.reload(true);
                if(response.status == 1){
                    trObj.find(".editSpan.institute_name").text(response.institute_name);
                    trObj.find(".editSpan.qualification ").text(response.qualification);
                    trObj.find(".editSpan.courseofStudy").text(response.courseofStudy);
                    trObj.find(".editSpan.duration").text(response.duration);
                     trObj.find(".editSpan.startDate").text(response.startDate);
                    trObj.find(".editSpan.year_passout").text(response.year_passout);
                    trObj.find(".editSpan.description").text(response.description);

                    
                    trObj.find(".editInput.institute_name").val(response.institute_name);
                    trObj.find(".editInput.qualification").val(response.qualification);
                    trObj.find(".editInput.courseofStudy").val(response.courseofStudy);
                    trObj.find(".editInput.duration").val(response.duration);
                    trObj.find(".editInput.startDate").val(response.startDate);
                    trObj.find(".editInput.year_passout").val(response.year_passout);
                     trObj.find(".editInput.description").val(response.description);
                    
                    trObj.find(".editInput").hide();
                    trObj.find(".editSpan").show();
                    trObj.find(".saveBtn1").hide();
                    trObj.find(".cancelBtn1").hide();
                    trObj.find(".editBtn1").show();
                    trObj.find(".deleteBtn1").show();
                }else{
                	location.reload(true);
                   // alert(response.msg);
                }
                jQuery('#userData').css('opacity', '');

            }
        });
    });

	jQuery(document).on("click", ".cancelBtn1" , function() {
        jQuery('#userData').css('opacity', '.5');

        //hide & show buttons
        jQuery(this).closest("tr").find(".saveBtn1").hide();
        jQuery(this).closest("tr").find(".cancelBtn1").hide();
        jQuery(this).closest("tr").find(".confirmBtn1").hide();
        jQuery(this).closest("tr").find(".editBtn1").show();
        jQuery(this).closest("tr").find(".deleteBtn1").show();

        //hide input and show values
        jQuery(this).closest("tr").find(".editInput").hide();
        jQuery(this).closest("tr").find(".editSpan").show();

        if (jQuery(this).closest("tr").find(".institute_name").length) {
            jQuery(this).closest("tr").find("select.institute_name").select2('destroy');
            jQuery('#userData').css('opacity', '');
        }
    });
    
	jQuery(document).on("click", ".deleteBtn1" , function() {
        //hide edit & delete button
        jQuery(this).closest("tr").find(".editBtn1").hide();
        jQuery(this).closest("tr").find(".deleteBtn1").hide();
        
        //show confirm & cancel button
        jQuery(this).closest("tr").find(".confirmBtn1").show();
        jQuery(this).closest("tr").find(".cancelBtn1").show();
    });
    
	jQuery(document).on("click", ".confirmBtn1" , function() {
        jQuery('#userData').css('opacity', '.5');

        var trObj = jQuery(this).closest("tr");
        var ID = jQuery(this).closest("tr").attr('id');
        jQuery.ajax({
            type:'POST',
            url : ajaxurl,
            dataType: "json",
            data:'action=delete_education&id='+ID,
            success:function(response){
                if(response.status == 1){
                    trObj.remove();
                }else{
                    trObj.find(".confirmBtn1").hide();
                    trObj.find(".cancelBtn1").hide();
                    trObj.find(".editBtn1").show();
                    trObj.find(".deleteBtn1").show();
                    alert(response.msg);
                }
                jQuery('#userData').css('opacity', '');
                location.reload(true);
            }
        });
    });
});

//**************************************************//
jQuery(document).ready(function($){
	var actions = jQuery("table td:last-child").html();
	// Append table with add row form on add new button click
    jQuery(".add-new").click(function(){
		jQuery(this).attr("disabled", "disabled");
        let current_user_id = jQuery(this).data('id');
		var index = jQuery("table tbody tr:last-child").index();
        var row = '<tr id="insertRoew">' +
            '<td><input class="editInput" type="hidden" name="current_user_id" value="'+current_user_id+'"><input class="form-control editInput formdate StartDate" type="text" name="formdate" value=""></td>' +
            '<td><input class="form-control editInput todate StartDate" type="text" name="todate" value=""></td>' +
            '<td><input class="form-control editInput Organization" type="text" name="email" value=""></td>' +'<td><input class="form-control editInput position" type="text" name="position" value=""></td>' +'<td><select class="form-control editInput " name="experience"><option value="0-1">0-1</option><option value="1-5">1-5</option><option value="5-10">5-10</option><option value="15-20" >15-20</option><option value="10-15">10-15</option><option value="20+">20+</option></select></td>' +'<td><input class="form-control editInput responsibility" type="text" name="responsibility" value=""></td>' +'<td class="d-flex">' + '<button type="button" class="btn btn-success saveBtn" ><i class="fa-solid fa-circle-check"></i></button> <button type="button"" class="deleteBtn deleteone btn btn-secondary"><i class="fa-solid fa-circle-xmark"></i></button>' + '</td>' +
        '</tr>';
    	jQuery("table").append(row);		
		//jQuery("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
    });

    

	// Add row on add button click
	jQuery(document).on("click", ".add", function(){
		var empty = false;
		var input = jQuery(this).parents("tr").find('input[type="text"]');
        input.each(function(){
			if(!$(this).val()){
				$(this).addClass("error");
				empty = true;
			} else{
                jQuery(this).removeClass("error");
            }
		});
		jQuery(this).parents("tr").find(".error").first().focus();
		if(!empty){
			input.each(function(){
				jQuery(this).parent("td").html(jQuery(this).val());
			});			
			jQuery(this).parents("tr").find(".add, .edit").toggle();
			jQuery(".add-new").removeAttr("disabled");
			jQuery(".add-newedu").removeAttr("disabled");
		}		
    });
	// Edit row on edit button click
	
	// Delete row on delete button click
	jQuery(document).on("click", ".deleteone", function(){
		
       jQuery(this).parents("tr").remove();
		jQuery(".add-new").removeAttr("disabled");
    });


});



jQuery(document).ready(function() {
    // Define admin-ajax for front-end usage
    var ajaxurl =   ajaxurl;
   
    // Inlcude the jquery-ui.min.js to be able to use this form validation function
    function cvf_form_validate(element) {
        element.effect("highlight", { color: "#F2DEDE" }, 1500);
        element.parent().effect('shake');
    }
   
    // Detect if button with a class "btn-change-pass" was clicked
    jQuery('body').on('click', '.btn-change-pass', function(e) {
       var pass1 = document.getElementById('password');
        if(pass1.value.length < 6){
          jQuery(".change-password-messages").html('<p class = "bg-danger"><span class = "glyphicon glyphicon-remove-circle"></span>&nbsp; Password length must 6 characters required.</p>');

          return false;    

        }
        if (jQuery('.change-password-form .password1').val() === '') {
            cvf_form_validate(jQuery('.password1'));
        } else if (jQuery('.change-password-form .password2').val() === '') {
            cvf_form_validate(jQuery('.password2'));
        } else if (jQuery('.change-password-form .password2').val() !== jQuery('.change-password-form .password1').val()) {
            jQuery(".change-password-messages").html('<p class = "bg-danger"><span class = "glyphicon glyphicon-remove-circle"></span>&nbsp; Passwords do not match</p>');
        } else {
            // if everything is validated, we're ready to send an AJAX request
           
            // Defining your own loading gif image
            jQuery(".change-password-messages").html('<p><img src = "http://www.construction-world.in/wp-content/uploads/2023/03/loading-buffering.gif" class = "loader" /></p>');
           
            // Define the ajax arguments
            var data = {
                'action': 'cvf_ngp_change_password',
                'cvf_action': 'change_password',
                'new_password': jQuery('.change-password-form .password2').val()
            };
           
            jQuery.post(ajaxurl, data, function(response) {
                // Detect the recieved AJAX response, then do the necessary logics you need for each specific response
                if(response === 'success') {
                    jQuery(".change-password-messages").html('<p class = "bg-success">Password Successfully Changed <br /></p>');
                    jQuery('.change-password-form').hide(); 
                } else if (response === 'error') {
                    jQuery(".change-password-messages").html('<p class = "bg-danger"><span class = "glyphicon glyphicon-remove-circle"></span>&nbsp; Error Changing Password</p>');
                    jQuery('.change-password-form').show(); 
                }
            });
        }
    });
});


jQuery(document).ready(function () {
  jQuery("#password").on('keyup', function(){
    var number = /([0-9])/;
    var alphabets = /([a-zA-Z])/;
    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
    if (jQuery('#password').val().length < 6) {
        jQuery('#password-strength-status').removeClass();
       jQuery('#password-strength-status').addClass('weak-password');
        jQuery('#password-strength-status').html("Weak (should be atleast 6 characters.)");
    } else {
        if (jQuery('#password').val().match(number) && jQuery('#password').val().match(alphabets) && jQuery('#password').val().match(special_characters)) {
            jQuery('#password-strength-status').removeClass();
           jQuery('#password-strength-status').addClass('strong-password');
            jQuery('#password-strength-status').html("Strong");
        } else {
            jQuery('#password-strength-status').removeClass();
            jQuery('#password-strength-status').addClass('medium-password');
            jQuery('#password-strength-status').html("Medium (should include alphabets, numbers and special characters or some combination.)");
        }
    }
  });
}); 




jQuery("#chooseResume").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = jQuery(this);
    var actionUrl = form.attr('action');
    console.log(form.serialize());
    jQuery.ajax({
          type: "POST",
         url : ajaxurl,
         data : {action: "choose_resume_style", data : form.serialize()},
       
        success: function(data)
        {
         jQuery('#chooseRes1').show();
          location.reload(true);
        }
    });
    });



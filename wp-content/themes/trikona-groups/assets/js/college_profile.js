jQuery(document).ready(function() {


jQuery("#removeDisable").click(function(event) {
    event.preventDefault();
    jQuery('.inputDisabled').removeAttr("disabled").removeClass("d-none");
    jQuery(this).addClass('d-none');
});



jQuery("#removeDisable2, .removeDisable2").click(function(event){
   event.preventDefault();
   jQuery('.inputDisabled').removeAttr("disabled").removeClass("d-none");
   jQuery(this).addClass('d-none');
});

    jQuery("#edit").click(function() {
        jQuery('.update').prop('contenteditable', true);
        jQuery('.update').addClass('updatetd');
    });







//table data scripts

jQuery("#user_data_filter").append(jQuery("#clearFilters"));

 fetch_data();

function fetch_data() {
    let group_id = jQuery('input[name=group_id]').val();
	 var dataTable =  new DataTable('#student_user_data', {
        //var dataTable = jQuery('#user_data').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'searching': false, // Remove default Search Control
            'lengthChange': false, // Remove default Search Control
            'ajax': {
                url: ajaxurl,
                type: "POST",
                'data': function(data) {
                    var clgcourses = jQuery('#clgcourses').val();
                    var yearpss = jQuery('#yearpss').val();
                    var edus = jQuery('#edus').val();
                    var status = jQuery('#status').val();
                    data.searchByclgcourses = clgcourses;
                    data.searchByyearpss = yearpss;
                    data.searchByedus = edus;
                    data.searchBystatus = status;
                    data.group_id = group_id;
                    data.action = "fetch_members_profile";
                }
            },

        });


        jQuery('#clgcourses').change(function() {
            jQuery('#loader').show();
            var dataTable = new DataTable('#student_user_data'); ;
            dataTable.draw();
            jQuery('#loader').hide();
        });

        jQuery('#yearpss').change(function() {
            jQuery('#loader').show();
            var dataTable = new DataTable('#student_user_data'); ;
            dataTable.draw();
            jQuery('#loader').hide();
        });

        jQuery('#edus').change(function() {
            jQuery('#loader').show();
            var dataTable = new DataTable('#student_user_data'); 
            dataTable.draw();
            jQuery('#loader').hide();
        });

        jQuery('#status').change(function() {
            jQuery('#loader').show();
            var dataTable = new DataTable('#student_user_data'); ;
            dataTable.draw();
            jQuery('#loader').hide();
        });

    }

 function update_data(id, column_name, value, email) {
        jQuery('#loader').show();
        jQuery.ajax({
            url: ajaxurl,
            method: "POST",
            data: {
                id: id,
                column_name: column_name,
                value: value,
                email: email,
                action: "update_members_profile_dataTable"
            },
            success: function(data) {
                jQuery('#alert_message').html('<div class="alert alert-success">' + data +
                '</div>');
                //jQuery('#user_data').DataTable().destroy();
                // fetch_data();
                jQuery('#loader').hide();
            }
        });
        setInterval(function() {
            jQuery('#alert_message').html('');
        }, 5000);
    }

 jQuery(document).on('blur', '.update', function() {

        var id = jQuery(this).data("id");
        var column_name = jQuery(this).data("column");
        var value = jQuery(this).text();
        var email = jQuery(this).data("email");
        update_data(id, column_name, value, email);
    });


 jQuery('#add').click(function() {
        var html = '<tr>';
        html += '<td contenteditable id="data1"></td>';
        html += '<td contenteditable id="data2"></td>';
        html +=
            '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Insert</button></td>';
        html += '</tr>';
        jQuery('#user_data tbody').prepend(html);
    });

 

 jQuery(document).on('click', '.delete', function() {
        var id = jQuery(this).attr("id");
        if (confirm("Are you sure you want to remove this?")) {
            jQuery('#loader').show();
            jQuery.ajax({
                url: ajaxurl,
                method: "POST",
                data: {
                    id: id,
                    action: "delete_members_profile_dataTable"
                },
                success: function(data) {
                    jQuery('#alert_message').html('<div class="alert alert-success">' +
                        data + '</div>');
                    jQuery('#user_data').DataTable().destroy();
                    fetch_data();
                    jQuery('#loader').hide();
                }
            });
            setInterval(function() {
                jQuery('#alert_message').html('');
            }, 5000);
        }
    });




jQuery(document).on("change", ".member_status", function() {
    var value = jQuery(this).val();
    var id = jQuery(this).data("id");
    var column_name = jQuery(this).data("column");
    var user_email = jQuery(this).data("email");

    if (confirm("Are you sure you want to update status?")) {
        jQuery('#loader').show();
        jQuery.ajax({
            url: ajaxurl,
            method: "POST",
            data: {
                id: id,
                column_name: column_name,
                user_email: user_email,
                value: value,
                action: "update_members_profile_status"
            },
            success: function(data) {

                jQuery('#loader').hide();
                jQuery('#alert_message').html('<div class="alert alert-success">' + data +
                '</div>');
                jQuery('#user_data').DataTable().destroy();
                fetch_data();

            }
        });
        setInterval(function() {
            jQuery('#alert_message').html('');
        }, 5000);
    }

});



jQuery(document).ready(function() {
    jQuery("#but_upload").click(function() {
        var fd = new FormData();
        var files = jQuery('#imgInp')[0].files[0];
        fd.append('file', files);
        fd.append("action", 'update_members_profile_image');

        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: fd,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 'success0') {
                    alert('Image uploaded');
                } else {
                    alert('file not uploaded');
                }
            },
        });
    });
});




function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            jQuery('.avatar').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

jQuery("#imgInp").change(function() {
    readURL(this);

});

jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
    }, "Please specify a valid phone number");
    jQuery('#updateUserProfile').validate({
        rules: {
            phoneNo: {
                phoneUS: true,
                required: true
            }
        },

    });

jQuery(document).on('focus', ".StartDate", function() {
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
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            action: "update_members_profile",
            data: form.serialize()
        },

        success: function(data) {
            jQuery('#updateproInfo1').show();
            //location.reload(true);
        }
    });
});



jQuery("#updateBillingAddress").submit(function(e) {
    if (!jQuery(this).valid()) return false;
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = jQuery(this);
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            action: "update_billing_address",
            data: form.serialize()
        },

        success: function(data) {
            jQuery('#updateproInfo1').show();
            //location.reload(true);
        }
    });
});


//updatecollegeData
jQuery("#updatecollegeData").submit(function(e) {
    if (!jQuery(this).valid()) return false;
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = jQuery(this);
    var actionUrl = form.attr('action');
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            action: "update_updatecollegeData",
            data: form.serialize()
        },

        success: function(data) {
            jQuery('#updateproInfo2').show();
            //location.reload(true);
        }
    });
});


function cvf_form_validate(element) {
        element.effect("highlight", {
            color: "#F2DEDE"
        }, 1500);
        element.parent().effect('shake');
    }




 // Detect if button with a class "btn-change-pass" was clicked
    jQuery('body').on('click', '.btn-change-pass', function(e) {
        var pass1 = document.getElementById('password');
        if (pass1.value.length < 6) {
            jQuery(".change-password-messages").html(
                '<p class = "bg-danger"><span class = "glyphicon glyphicon-remove-circle"></span>&nbsp; Password length must 6 characters required.</p>'
                );

            return false;

        }
        if (jQuery('.change-password-form .password1').val() === '') {
            cvf_form_validate(jQuery('.password1'));
        } else if (jQuery('.change-password-form .password2').val() === '') {
            cvf_form_validate(jQuery('.password2'));
        } else if (jQuery('.change-password-form .password2').val() !== jQuery(
                '.change-password-form .password1').val()) {
            jQuery(".change-password-messages").html(
                '<p class = "bg-danger"><span class = "glyphicon glyphicon-remove-circle"></span>&nbsp; Passwords do not match</p>'
                );
        } else {
            // if everything is validated, we're ready to send an AJAX request

            // Defining your own loading gif image
            jQuery(".change-password-messages").html(
                '<p><img src = "http://www.construction-world.in/wp-content/uploads/2023/03/loading-buffering.gif" class = "loader" /></p>'
                );

            // Define the ajax arguments
            var data = {
                'action': 'cvf_ngp_change_password',
                'cvf_action': 'change_password',
                'new_password': jQuery('.change-password-form .password2').val()
            };

            jQuery.post(ajaxurl, data, function(response) {
                // Detect the recieved AJAX response, then do the necessary logics you need for each specific response
                if (response === 'success') {
                    jQuery(".change-password-messages").html(
                        '<p class = "bg-success">Password Successfully Changed <br /><a href = "<?php echo home_url(); ?>">Click here to continue</a></p>'
                        );
                    jQuery('.change-password-form').hide();
                } else if (response === 'error') {
                    jQuery(".change-password-messages").html(
                        '<p class = "bg-danger"><span class = "glyphicon glyphicon-remove-circle"></span>&nbsp; Error Changing Password</p>'
                        );
                    jQuery('.change-password-form').show();
                }
            });
        }
    });

jQuery("#password").on('keyup', function() {
        var number = /([0-9])/;
        var alphabets = /([a-zA-Z])/;
        var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
        if (jQuery('#password').val().length < 6) {
            jQuery('#password-strength-status').removeClass();
            jQuery('#password-strength-status').addClass('weak-password');
            jQuery('#password-strength-status').html("Weak (should be atleast 6 characters.)");
        } else {
            if (jQuery('#password').val().match(number) && jQuery('#password').val().match(alphabets) &&
                jQuery('#password').val().match(special_characters)) {
                jQuery('#password-strength-status').removeClass();
                jQuery('#password-strength-status').addClass('strong-password');
                jQuery('#password-strength-status').html("Strong");
            } else {
                jQuery('#password-strength-status').removeClass();
                jQuery('#password-strength-status').addClass('medium-password');
                jQuery('#password-strength-status').html(
                    "Medium (should include alphabets, numbers and special characters or some combination.)"
                    );
            }
        }
    });    

//end ready function
 });
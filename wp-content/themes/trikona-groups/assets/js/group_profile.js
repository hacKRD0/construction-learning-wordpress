jQuery(document).ready(function() {
	jQuery('.group-info-card').click(function(){
		jQuery('.loader').removeClass('d-none');
		var group_id = jQuery(this).data('group-id');

	    jQuery.ajax({
	        type: "POST",
	        url : ajaxurl,
	        data : {action: "fetch_group_managers", group_id : group_id}, 
	        dataType: "json",      
	        success: function(response) {
        		if (response.success) {
					jQuery('.loader').addClass('d-none');
        			jQuery('form[name=group_manager_form]').html(response.group_managers);
					jQuery('#groupManagerModal').modal('show');
        		} else {
        			jQuery('.loader').addClass('d-none');
        			$('.group-manager-error').removeClass('d-none');
        			$('.group-manager-error').html(response.message);
					jQuery('#groupManagerModal').modal('show');
        		}
	        }
	    });
	});
});
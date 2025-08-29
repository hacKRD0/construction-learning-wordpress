<?php
	class TrikonaCommonAjax {

		public function __construct() {
			add_action( 'wp_ajax_filter_city_by_state', [$this,'filter_city_by_state']);
			add_action( 'wp_ajax_nopriv_filter_city_by_state', [$this,'filter_city_by_state']);

			add_action( 'wp_ajax_assign_user_manager', array($this,'assign_user_manager' ));
			add_action( 'wp_ajax_nopriv_assign_user_manager', array($this,'assign_user_manager' ));
		}

		// Declare a public method named filter_city_by_state
		public function filter_city_by_state(){
			global $wpdb, $trikona_obj; // Make the global $wpdb object (for DB queries) and $trikona_obj object accessible

			 // Get the state code (like 'KA' for Karnataka) from POST data
			$state_code = $_POST["state_id"];


			switch_to_blog(1); // Switch context to blog ID 1 (useful in multisite WordPress setups)
			$states = WC()->countries->get_states( 'IN' ); // Get the list of Indian states from WooCommerce

			// Get the state name corresponding to the submitted state code, or set it to an empty string
			$state_name = isset( $states[ $state_code ] ) ? $states[ $state_code ] : '';
			$state_id = '';// Initialize the variable to hold the state ID from the database

			// If a matching state name was found
			if (!empty($state_name)) {
				// Call a custom function from $trikona_obj to get state details by name (expects a single record)
				$state_info = $trikona_obj->getStates(['name' => $state_name], $is_single_record = true);

				// If state information is found, extract the ID
				if (!empty($state_info))
					$state_id = $state_info->id;
			}
			// If no state ID was found, output the default city dropdown option and stop script execution
			if (empty($state_id)) { ?>
				<option value="">Select City</option>
			<?php die; }
			// Run a database query to get all cities where the state_id matches the selected state
			$cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cities  where state_id = $state_id");

			// Start the dropdown with a default option
			?>
			   <option value="">Select City</option>
			   <!-- Loop through each city and output it as an <option> element -->
			  	<?php foreach ($cities as $city_info) {  ?>
			   		<option value="<?= $city_info->city ?>"><?= $city_info->city ?></option>
				<?php }
			restore_current_blog(); // Restore the original blog context (important in multisite)
			die; // Terminate script
		}

		// Declare a public method named assign_user_manager
		public function assign_user_manager(){
			// Retrieve user IDs from POST data, or set to empty string if not provided
			$user_ids 		= isset($_POST['user_ids']) ? $_POST['user_ids'] : "";

			// Retrieve selected group manager from POST data, or set to empty string if not provided
			$group_manager 	= isset($_POST['group_manager']) ? $_POST['group_manager'] : "";

			// Initialize arrays to store errors and success messages
			$errors = $success = [];

			// Check if group manager is not selected
			if (empty($group_manager)) {
				$errors[] = "Please select group manager.";
			}

			// Check if no users are selected
			if (empty($user_ids)) {
				$errors[] = "Please select at least 1 user.";
			}

			// If there are any errors, return them as a JSON response and stop execution
			if (!empty($errors)) {
				$response['success'] = false;
				$response['message'] = implode("<br>", $errors);
				echo json_encode($response);die();
			} else {
				// No errors - proceed to assign the group manager to users

				$meta_key = 'group_manager'; // Define the user meta key
				$meta_value = $group_manager; // Define the meta value (group manager ID)

				// Loop through each selected user ID
				foreach ( $user_ids as $user_id ) {
					// Update user meta to assign the group manager to this user
			        update_user_meta( $user_id, $meta_key, $meta_value );
			    }

			    // Prepare a success response
			    $response['success'] = true; // Set response status to true
			    $response['message'] = "Group manager has been successfully assigned to the selected users.";
			    echo json_encode($response); // Output JSON response
			    die(); // Terminate script
			}
		}
	}//end class

	new TrikonaCommonAjax();
?>
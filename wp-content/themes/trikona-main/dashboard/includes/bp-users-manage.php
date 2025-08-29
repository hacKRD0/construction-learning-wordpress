<?php
	// Hook the custom function to BuddyPress 'bp_screens' action, with priority 1
	add_action( 'bp_screens', 'trikona_group_template_redirect_dynamic', 1 );

	// Define the function that handles dynamic group template redirection
	function trikona_group_template_redirect_dynamic() {
		// Get the currently viewed BuddyPress group object
	    $group = groups_get_current_group();

	    // Check if a group is actually being viewed
	    if (!empty($group)) {
	    	// Get the type of the current group (e.g., college, company)
	        $group_type = bp_groups_get_group_type( $group->id );

	         // Get the template ID associated with the group from group metadata
	        $template_id = groups_get_groupmeta( $group->id, 'trikona_group_template' );

	         // If a template ID exists, proceed with the template override logic
	        if ( ! empty( $template_id ) ) {
	        	// Add a filter to override the template used to display the group
	            add_filter( 'template_include', function( $template ) use ( $template_id, $group_type ) {
	            	// Build the template filename based on the template ID
	                $template_file = 'template-'.$template_id.'.php';

	                // Build the full path to the custom group template file
	                $template_path = get_stylesheet_directory() . '/group-templates/'.$group_type.'/'.$template_file;

	                // Replace 'trikona-main' with 'trikona-groups' in the path, if present
	                $template_path = str_replace("trikona-main", "trikona-groups", $template_path);

	                // If the file exists at the path, use it as the new template
	                if ( file_exists( $template_path ) ) {
	                    return $template_path;
	                }

	                // Otherwise, return the default template
	                return $template;
	            });
	        }
	    }
	}

	// Add a filter to modify user queries before execution — probably to adjust DISTINCT clause
	add_filter('pre_get_users', 'add_custom_distinct_to_user_query');

	// Hook function to the 'pre_get_users' filter to modify user queries before they are run
	function add_custom_distinct_to_user_query($query) {
	    // Check if the query contains a custom flag in its arguments to indicate it should be modified
	    if (!empty($query->query_vars['is_custom_user_query'])) {
	    	// If the flag is set, add a filter to modify the raw SQL of the query
	        add_filter('query', 'inject_distinct_in_user_query_sql', 20, 1);
	    }
	}

	// Define the function to modify the SQL by injecting DISTINCT
	function inject_distinct_in_user_query_sql($sql) {
	    // Check if the SQL query starts with 'SELECT SQL_CALC_FOUND_ROWS' and does NOT already include 'DISTINCT'
	    if (stripos($sql, 'SELECT SQL_CALC_FOUND_ROWS') !== false && stripos($sql, 'DISTINCT') === false) {
	    	// Inject 'DISTINCT' into the SELECT statement to eliminate duplicate users in the result
	        $sql = str_replace('SELECT SQL_CALC_FOUND_ROWS', 'SELECT DISTINCT SQL_CALC_FOUND_ROWS', $sql);
	    }

	    // Remove this filter immediately after it's used to prevent affecting other unrelated queries
	    remove_filter('query', 'inject_distinct_in_user_query_sql', 20);

	     // Return the modified (or unmodified) SQL query
	    return $sql;
	}
?>
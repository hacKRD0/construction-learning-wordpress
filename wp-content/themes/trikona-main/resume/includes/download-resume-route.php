<?php

	/* Download Resume - Custom rewrite rule, query vars, and template */

	// Define a function to add a custom rewrite rule for downloading a CV
	function add_download_cv_rule() {
		// Add a new rewrite rule that maps URLs like /download-cv/{product_id}/{template_id}/{user_id}
		// to WordPress query vars: _cv_product_id, _cv_template_id, _cv_user_id
	    add_rewrite_rule(
	        '^download-cv/([^/]+)/([^/]+)/([^/]+)/?', // Regex pattern for the custom URL
	        'index.php?_cv_product_id=$matches[1]&_cv_template_id=$matches[2]&_cv_user_id=$matches[3]',// Mapping to query vars
	        'top' // Priority: add this rule at the top of the list
	    );

	    // Flush rewrite rules to ensure the new rule is registered (NOTE: doing this on every page load is not recommended in production)
	    flush_rewrite_rules();
	}
	// Hook the function to the 'init' action so it's called during WordPress initialization
	add_action( 'init', 'add_download_cv_rule', 10, 0 );

	// Function to register new query variables so WP recognizes them in URLs
	function add_download_cv_vars($vars){
	    $vars[] = '_cv_product_id';// Add product ID query var
	    $vars[] = '_cv_template_id';// Add template ID query var
	    $vars[] = '_cv_user_id';// Add user ID query var
	    return $vars;// Return the extended array
	};
	// Hook the function to 'query_vars' filter to register custom query vars
	add_filter( 'query_vars', 'add_download_cv_vars' );

	// Function to load a custom template when all required query vars are present
	function add_download_cv_template($template) {
		// Retrieve the custom query vars from the request
	    $_cv_product_id = get_query_var('_cv_product_id');
	    $_cv_template_id = get_query_var('_cv_template_id');
	    $_cv_user_id = get_query_var('_cv_user_id');

	    // If any of the required variables are missing, return the default template
	    if ( empty($_cv_product_id) || empty($_cv_template_id) || empty($_cv_user_id)) {
	        return $template;
	    }

	    // All required vars are present — return the path to the custom template for downloading the CV
	    return get_stylesheet_directory() . '/dashboard/template-parts/download_cv.php';
	}
	// Hook this template override into the 'template_include' filter
	add_action( 'template_include', 'add_download_cv_template' );
?>
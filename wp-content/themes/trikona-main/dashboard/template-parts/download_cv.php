<title>Download CV</title>
<?php
 	/* Template Name: User Dashboard */
 
	if (!is_user_logged_in()) {
       wp_redirect(home_url('/user-login/'));
        exit;
    }
	$current_user_id = get_current_user_id();

	$_cv_product_id = get_query_var('_cv_product_id');
	$_cv_template_id = get_query_var('_cv_template_id');
	$_cv_user_id = get_query_var('_cv_user_id');
	$product_bought = wc_customer_bought_product( '', $current_user_id, $_cv_product_id );
	
	$validated = true;
	$_template_type = get_post_meta($_cv_product_id, 'template_type', true);

	if (empty($_cv_user_id) || empty($_cv_product_id) || empty($_cv_template_id)) {
		$validated = false;
	} else if (($_cv_user_id != $current_user_id) || !$product_bought || ($_template_type != $_cv_template_id)){
		$validated = false;
	} else {
		$validated = true;
	}

	get_header('dashboard');

	$current_user = get_user_by( 'id', $_cv_user_id );
	$template_file_name = get_stylesheet_directory() . '/resume/cv-templates/cv_template_'.$_cv_template_id.'.php';
?>

<div class="container">
	<div class="row display-table-row">
		<div class="col-md-12 col-sm-12 display-table-cell v-align">
			<div class="user-dashboard">
				<?php if(!$validated){ ?>
					<div class="alert alert-danger">You are not authorized.</div>
				<?php } else if(file_exists($template_file_name)){ 
					require_once($template_file_name);
				} ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
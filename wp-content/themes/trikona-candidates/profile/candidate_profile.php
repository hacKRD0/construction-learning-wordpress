<?php 
/**
 * Template Name: Candidate Profile 
 *
 * @package Trikona
 * @subpackage Trikona
 * @since Trikona 1.0 - 17-APR-2024
 */

	get_header();
	switch_to_blog(1);

	$current_user_id =  base64_decode($_GET['user']);
	$current_user = get_user_by('id', $current_user_id);
	$default_profile = get_user_meta( $current_user_id, 'default_profile', true );
	$template_file_name = '';
	if (!empty($default_profile)) {
		$_cv_template_id = get_post_meta($default_profile, 'template_type', true);

		if (!empty($_cv_template_id)){
			$template_file_name = get_stylesheet_directory() . '/resume/cv-templates/cv_template_'.$_cv_template_id.'.php';
			if (!file_exists($template_file_name)) {
				$template_file_name = '';
			} else {
				require_once($template_file_name);
			}
		}
	}
	if (empty($template_file_name)){ ?>
		<div class="container-fluid cs-directory-page">
			<div class="card pt-4">
				<div class="card-block text-center">
					<h3>No default profile available.</h3>
				</div>
			</div>
		</div>
<?php	}
	get_footer();
?>
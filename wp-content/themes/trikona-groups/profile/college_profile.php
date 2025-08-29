<?php 
/**
 * Template Name: College Profile 
 *
 * @package Trikona
 * @subpackage Trikona
 * @since Trikona 1.0 - 17-APR-2024
 */
	require_once get_theme_root() . '/class-trikona.php';
	get_header();
	$obj = new Trikona();

	$current_user_id =  base64_decode($_GET['user']);
	$current_user = get_user_by('id', $current_user_id);
	$default_profile = get_user_meta( $current_user_id, 'default_profile', true );
	// $_profile_template_id = 2;
	$template_file_name = '';
	$template_directory = get_stylesheet_directory();
	if (!empty($default_profile)) {
		$_profile_template_id = get_post_meta($default_profile, 'profile_template_type', true);

		if (!empty($_profile_template_id)){
			switch_to_blog( $obj->main_site_blog_id );
			$template_file_name = $template_directory . '/profile/college/profile_template_'.$_profile_template_id.'.php';
			restore_current_blog();
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
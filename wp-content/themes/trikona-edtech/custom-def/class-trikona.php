<?php
	/**
	 * This class contains the centralize variabes and values.
	 *
	 * Author: Gsuswami Mahendragiri
	 * Author URI: https://in.linkedin.com/in/mpgauswami-86a825254
	 *
	 */
	require_once 'trikona-functions.php';

	class Trikona extends TrikonaFunctions {
		/*
			Common variables used in entire site
			Used in following files:
			1. /wp-content/themes/trikona-main/resume/cv-templates/cv_data.php
		*/
		public $main_site_blog_id = 1;
		public $jobs_site_blog_id = 7;
		public $directories_site_blog_id = 2;
		public $candidates_site_blog_id = 3;
		public $events_site_blog_id = 4;
		public $dev_site_blog_id = 5;
		public $help_site_blog_id = 17;

		/*
			Following centralize variable used for trikona-main theme

			Used in following files:
			1. /wp-content/themes/trikona-main/functions.php
			2. /wp-content/themes/trikona-main/woocommerce/notices/error.php
		*/
		// Woocommerce products
		public $free_500_credits_product_id = 398;

		// Woocommerce categories
		public $credit_category_id = 72;
		public $credit_category = 'credits';
		public $free_credits_product_category = 'Free Credits';
		public $student_categories = [79, 80, 81, 82];
		public $professional_categories = [78, 80, 81, 82];
		public $corporate_categories = [78, 79, 81, 82];
		public $college_admin_categories = [78, 79, 80, 82];
		public $instructor_categories = [78, 79, 80, 81,];
		public $resume_category_id = 60;

		// Woocommerce Payment methods
		public $mycred_payment_method = 'mycred';

		/*
			Below variables are used in candiadte profile
			
			Used in following files:
			1. /wp-content/themes/trikona-main/dashboard/class_profiles_ajax.php
			2. /wp-content/themes/trikona-main/dashboard/template-parts/career.php
			3. /wp-content/themes/trikona-candidates/templates/candidate_directory.php
			4. /wp-content/themes/trikona-directory/template-parts/dashboard/company/dashboard.php
			5. /wp-content/themes/trikona-directory/includes/class_profiles_ajax.php
			6. /wp-content/themes/trikona-directory/includes/class_members_profile_ajax.php
			7. /wp-content/themes/trikona-events/shortcodes/class_shortcodes.php
			8. /wp-content/themes/trikona-jobs/shortcodes/class_shortcodes.php
		*/
		// xProfile field ids
		public $phone_no_field_id = 5;
		public $mobile_no_field_id = 6;
		public $bio_field_id = 15;
		public $highest_education_field_id = 16;
		public $year_of_passout_field_id = 23;
		public $total_year_of_study_field_id = 40;
		public $current_Year_Of_study_field_id = 46;
	
	    public $address_field_id = 220;
        public $total_expereince_field_id = 71;
		public $student_skills_field_id = 78;
		public $professional_skills_field_id = 84;
		public $vertical_field_id = 78;
		public $current_company_field_id = 69;
		public $current_position_field_id = 68;
	
		public $linkedin_profile_field_key = 'Linkedin Profile';
		public $phone_field_key = 'Phone';
		public $mobile_field_key = 'Mobile';
		public $address_field_key = 'Address';
		public $total_expereince_field_key = 'Total Expereince';

		// Student membership types
		public $elite_membership_types = [ 'student-rookie', 'student-champion', 'student-pro', 'prof-gold', 'prof-silver', 'prof-platinum' ];
		public $prime_membership_types = [ 'student-champion', 'student-pro', 'prof-gold', 'prof-platinum' ];
		public $basic_membership_types = [ 'student-pro', 'prof-platinum' ];

		// Corporate roles
		public $corporate_elite_role = 'corporate-elite';
		public $corporate_prime_role = 'corporate-prime';
		public $corporate_basic_role = 'corporate-basic';

		// Membership IDs
		public $student_rookie_mem_id = 1;
		public $student_champ_mem_id = 2;
		public $student_pro_mem_id = 3;
		public $corporate_basic_mem_id = 7;
		public $corporate_prime_mem_id = 8;
		public $corporate_elite_mem_id = 9;
		public $professional_silver_mem_id = 4;
		public $professional_gold_mem_id = 5;
		public $professional_platinum_mem_id = 6;
		public $college_basic_mem_id = 11;
		public $college_elite_mem_id = 12;

		/*
			The PMPro membership ids of various user roles

			Used in following files:
			1. /wp-content/themes/trikona-candidates/templates/candidate_directory.php
		*/
	
		public $student_membership = [1, 2, 3];
		public $professional_membership = [4, 5, 6];
		public $corporate_membership = [7, 8, 9];
		public $college_membership = [10, 11, 12];
		public $instructor_membership = [13, 14, 15];

		/*
			Student and Professional membership id for corporate user having Corporate Basic membership

			Used in following files:
			1. /wp-content/themes/trikona-candidates/templates/candidate_directory.php
		*/
		public $coporate_basic_stud_mem_ids = [3];
		public $coporate_basic_prof_mem_ids = [10];
		public $coporate_basic_instructor_mem_ids = [13];

		/*
			Student and Professional membership id for corporate user having Corporate Prime membership

			Used in following files:
			1. /wp-content/themes/trikona-candidates/templates/candidate_directory.php
		*/
		public $coporate_prime_stud_mem_ids = [2, 3];
		public $coporate_prime_prof_mem_ids = [9, 10];
		public $coporate_prime_instructor_mem_ids = [13, 14];

		/*
			Student and Professional membership id for corporate user having Corporate Elite membership

			Used in following files:
			1. /wp-content/themes/trikona-candidates/templates/candidate_directory.php
		*/
		public $coporate_elite_stud_mem_ids = [1, 2, 3];
		public $coporate_elite_prof_mem_ids = [8, 9, 10];
		public $coporate_elite_instructor_mem_ids = [13, 14, 15];

		/*
			User roles

			Used in following files:
			1. /wp-content/themes/trikona-candidates/templates/candidate_directory.php
			2. /wp-content/themes/trikona-directory/functions_directory.php
			3. /wp-content/themes/trikona-events/functions_jobs.php
			4. /wp-content/themes/trikona-directory/templates/manage_groups.php
		*/
		public $student_role 		= 'student';
		public $professional_role 	= 'professional';
		public $corporate_role 		= 'corporate';
		public $instructor_role 	= 'instructor';
		public $collage_admin_role 	= 'college_admin';
		public $subscriber_role 	= 'subscriber';
		public $stuRole 			= 'stuRole';
		public $proRole 			= 'proRole';
		public $corRole 			= 'corRole';
		public $insRole 			= 'insRole';
		public $conRole 			= 'conRole';
		public $trikona_admin_role 	= 'trikona-admin';
		public $candidate_directory_allowed_roles = ['administrator', 'group_manager', 'corporate'];
		public $manage_groups_allowed_roles = ['administrator', 'group_manager'];

		// Group Role
		public $group_mod_role = 'Group Mod';
		public $group_admin_role = 'Group Admin';
		public $member_role = 'Member';


		/*
			Recacptch configuration

			Used in following files:
			1. /wp-content/themes/trikona-directory/functions_directory.php
		*/
		public $recaptcha_secret 	= '6LfCoTUmAAAAAOJGfBW77u0hjqhqa6v1n00kUltj';
		public $recaptcha_secret_2 	= '6LcLNKQcAAAAAKz9REA8HDTJlT7iBg1Zh1kSGYd8';
		public $recaptcha_sitekey 	= '6LcLNKQcAAAAAC_efnDr7sM1SaxKa6dG4h31S2lM';

		/*
			Usermeta configuration

			Used in following files:
			1. /wp-content/themes/trikona-directory/functions_directory.php
			2. /wp-content/themes/trikona-directory/templates/college_dashboard.php
			3. /wp-content/themes/trikona-directory/templates/company_dashboard.php
			4. /wp-content/themes/trikona-directory/template-parts/dashboard/company/dashboard.php
			5. /wp-content/themes/trikona-directory/template-parts/dashboard/company/myaccout.php
			6. /wp-content/themes/trikona-directory/includes/class_profiles_ajax.php
			7. /wp-content/themes/trikona-directory/includes/class_members_profile_ajax.php
			8. /wp-content/themes/trikona-events/shortcodes/class_shortcodes.php
		*/
		public $first_name_meta 			= 'first_name';
		public $last_name_meta 				= 'last_name';
		public $linkedin_profile_meta 		= 'linkedinProfile';
		public $member_bio_meta 	  		= 'member_bio';
		public $members_experience_meta 	= 'members_experience_';
		public $total_expereince_meta 		= 'Total Expereince';
		public $highest_education_meta 		= 'Highest Education';
		public $skills_meta 				= 'Skills';
		public $professional_skills_meta 	= 'Professional Skills';
		public $current_year_of_study_meta 	= 'Current Year Of Study';
		public $year_of_passout_meta 		= 'Year Of Passout';
		public $total_year_of_study_meta 	= 'Total year of study';
		public $members_education_meta 		= 'members_education_';
		public $skill_set_meta 				= 'skill_set';
		public $memberDob_meta 				= 'memberDob';
		public $designation_current_meta 	= 'designation_current';
		public $gender_meta 				= 'gender';
		public $company_current_meta 		= 'company_current';
		public $vertical_meta 				= 'Vertical';
		public $institute_meta 				= 'Institute';
		public $billing_first_name_meta 	= 'billing_first_name';
		public $billing_last_name_meta 		= 'billing_last_name';
		public $billing_company_meta 		= 'billing_company';
		public $billing_country_meta 		= 'billing_country';
		public $billing_address_1_meta 		= 'billing_address_1';
		public $billing_address_2_meta 		= 'billing_address_2';
		public $billing_city_meta 			= 'billing_city';
		public $billing_postcode_meta 		= 'billing_postcode';
		public $billing_phone_meta 			= 'billing_phone';
		public $billing_email_meta 			= 'billing_email';
		public $billing_state_meta 			= 'billing_state';
		public $ngp_changepass_status_meta 	= 'ngp_changepass_status';
		public $member_resume_stype_meta 	= 'member_resume_stype';
		public $instructor_status_meta 		= 'instructor_status';
		public $instructor_msg_by_meta 		= 'instructor_msg_by';
		public $instructor_approve_by_meta 	= 'instructor_approve_by';

		/*
			Groupmeta configuration

			Used in following files:
			1. /wp-content/themes/trikona-directory/templates/college_directory.php
			2. /wp-content/themes/trikona-directory/templates/company_directory.php
			3. /wp-content/themes/trikona-directory/template-parts/company-dashboard/company/edit-companes/tab_general.php
			4. /wp-content/themes/trikona-directory/template-parts/company-dashboard/company/edit-companes/tab_services.php
			5. /wp-content/themes/trikona-directory/includes/class_members_profile_ajax.php
			6. /wp-content/themes/trikona-directory/template-parts/college-dashboard/edit-college/tab_general_info.php
		*/
		public $company_staff_meta = 'company_staff';
		public $state_meta = 'state';
		public $gstates_meta = 'gstates';
		public $company_website_url_meta = 'company_website_url';
		public $city_meta = 'city';
		public $address_meta = 'address';
		public $email_address_meta = 'email_address';
		public $phone_number_meta = 'phone_number';
		public $services_meta = 'services';
		public $sectors_meta = 'sectors';
		public $industries_type_meta = 'industries_type';
		public $verified_meta = 'verified';
		public $service_post_id = 76;
		public $courses_meta = 'courses';

		/*
			Group types configuration

			Used in following files:
			1. /wp-content/themes/trikona-directory/templates/college_directory.php
		*/
		public $college_group_type = 'college';
		public $company_group_type = 'company';

		/*
			Post type configuration

			Used in following files:
			1. /wp-content/themes/trikona-directory/templates/college_directory.php
			2. /wp-content/themes/trikona-directory/template-parts/dashboard/company/top-header/top_header.php
		*/
		public $student_courses_post_type = 'studentcourses';
		public $job_listing_post_type = 'job_listing';

		/*
			Post meta configuration

			Used in following files:
			1. /wp-content/themes/trikona-directory/templates/company_directory.php
			2. /wp-content/themes/trikona-directory/template-parts/dashboard/company/edit-companes/tab_general.php
		*/
		public $industries_type_post_meta = 63; // Previous Value: 33926
		public $sectors_post_meta = 61; // Previous Value: 33927
		public $services_type_post_meta = 76; // Previous Value: 32461

		public $allowed_max_file_size = 150;
		public $allowed_max_files = 4;
		public $allowed_mime_types = ["image/png", "image/gif", "image/jpeg", "image/svg+xml", "image/webp", "text/csv"];


		/*
			BP Group Type
		*/
		public $bp_group_type_company = 'company';
		public $bp_group_type_college = 'college';
	}
?>
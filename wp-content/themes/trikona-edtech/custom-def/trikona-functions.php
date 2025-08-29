<?php
	/**
	 * This class contains the centralize functions to fetch data.
	 *
	 * Author: Gsuswami Mahendragiri
	 * Author URI: https://in.linkedin.com/in/mpgauswami-86a825254
	 *
	 */

	class TrikonaFunctions {
		public function ValidateFiles($files) {
			$is_multiple_file = is_array($files['name']) ? true : false;

			$errors = [];
		    $size_error = "File should not be exceeded to ".$this->allowed_max_file_size." KB.";

			if ($is_multiple_file) {
				if (sizeof($files['name']) > $this->allowed_max_files) {
					$errors[] = "Maximum ".$this->allowed_max_files." files are allowed.";
				} else {
					foreach ($files['name'] as $key => $value) {
					    $size = ($files['size'][$key] / 1024);
					    $mime_type = $files['type'][$key];


					    if ( $size > $this->allowed_max_file_size && !in_array($size_error, $errors)) {
					        $errors[] = $size_error;
					    }

					    if (!in_array($mime_type, $this->allowed_mime_types)) {
					    	$mime_type_error = $mime_type." type not allowed.";

					    	if (!in_array($mime_type_error, $errors)) {
					    		$errors[] = $mime_type_error;
					    	}
					    }
					}
				}
			} else {
				$size = ($files['size'] / 1024);
				$mime_type = $files['type'];
				if ( $size > $this->allowed_max_file_size ) {
				    $errors[] = $size_error;
				}

				if (!in_array($mime_type, $this->allowed_mime_types)) {
					$mime_type_error = $mime_type." type not allowed.";

					if (!in_array($mime_type_error, $errors)) {
						$errors[] = $mime_type_error;
					}
				}
			}

			return $errors;
		}

		// To obtain the membership details of the logged-in user.
		public function getLoggedInUserMembership($user_id) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tabmemberships = $wpdb->prefix . "pmpro_memberships_users";
			$current_user_plan = $wpdb->get_row("SELECT * FROM $tabmemberships WHERE user_id='".$user_id."' AND status='active'");
			restore_current_blog();

			return $current_user_plan;
		}

		public function checkIsAccessibleForCurrentUser($current_user, $type = '') {
			switch_to_blog($this->main_site_blog_id);
			$current_user = wp_get_current_user();
			$roles = $current_user->roles;
			$manage_groups_allowed_roles = $this->manage_groups_allowed_roles;
			$allowed_roles = [];

			if (is_super_admin($current_user->ID)) {
				$roles = ['administrator'];
			}

			if (!empty($type)) {
				switch ($type) {
					case 'college':
							$manage_groups_allowed_roles[] = "college_admin";
						break;
					case 'company':
							$manage_groups_allowed_roles[] = "corporate";
						break;
				}
			}

			if (!empty($manage_groups_allowed_roles) && !empty($roles)) {
				$allowed_roles = array_intersect($manage_groups_allowed_roles, $roles);
			}
			restore_current_blog();
			return $allowed_roles;
		}

		public function getStates($filters = [], $is_single_record = false) {
			global $wpdb;
			extract($filters);
			switch_to_blog($this->main_site_blog_id);
			$sql = "SELECT * FROM ".$wpdb->prefix."states";
			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			$sql .= " order by name";
			if (isset($id) && !empty($id) || $is_single_record) {
				$states = $wpdb->get_row($sql);
			} else {
				$states = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $states;
		}

		public function getCurrentUserMemberShips($user_id) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$memberships  = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."pmpro_memberships_users WHERE user_id = $user_id" );
			restore_current_blog();

			return $memberships;
		}

		public function getMembershipLevel($membership_id) {
			global $wpdb;
		 	switch_to_blog($this->main_site_blog_id);
			$memberships_level  = $wpdb->get_row( "SELECT name FROM ".$wpdb->prefix."pmpro_membership_levels WHERE id = $membership_id" );
			restore_current_blog();

			return $memberships_level;
		}

		public function getGroupMembers($filters = [], $is_single_record = false) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."bp_groups_members";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			if ((isset($id) && !empty($id)) || $is_single_record) {
				$group_members_list = $wpdb->get_row($sql);
			} else {
				$group_members_list = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $group_members_list;
		}

		public function getBPGroups($filters = []) {
			global $wpdb;
			extract($filters);
			switch_to_blog($this->main_site_blog_id);
			$sql = "SELECT * FROM ".$wpdb->prefix."bp_groups";
			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			if (isset($id) && !empty($id)) {
				$groups = $wpdb->get_row($sql);
			} else {
				$groups = $wpdb->get_results($sql);
			}
			restore_current_blog();
			return $groups;
		}

		public function getUserProfiles($filters = []) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."user_profile";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			if ((isset($id) && !empty($id)) || $is_single_record) {
				$user_profiles = $wpdb->get_row($sql);
			} else {
				$user_profiles = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $user_profiles;
		}

		public function getUserProfilesData($filters = []) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."user_profile_data";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			if ((isset($id) && !empty($id)) || $is_single_record) {
				$user_profiles_data = $wpdb->get_row($sql);
			} else {
				$user_profiles_data = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $user_profiles_data;
		}

		public function getMembershipPlans($filters = [], $is_single_record = false) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."pmpro_memberships_users";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($filter_key == 'membership_ids') {
						if ($i == 0 ) {
							$sql .= '`membership_id` IN ('.$filter_value.')';
						} else {
							$sql .= ' AND `membership_id` IN ('.$filter_value.')';
						}
					} else {
						if ($i == 0 ) {
							$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
						} else {
							$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
						}
					}
					$i++;
				}
			}
			if ((isset($id) && !empty($id)) || $is_single_record) {
				$memberships = $wpdb->get_row($sql);
			} else {
				$memberships = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $memberships;
		}

		public function getInquiries($filters = [], $is_single_record = false) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."emp_inquiry";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			if ((isset($id) && !empty($id)) || $is_single_record) {
				$inquiries = $wpdb->get_row($sql);
			} else {
				$inquiries = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $inquiries;
		}

		public function getProjects($filters = [], $is_single_record = false) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."bp_group_projects";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			if ((isset($id) && !empty($id)) || $is_single_record) {
				$projects = $wpdb->get_row($sql);
			} else {
				$projects = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $projects;
		}

		public function getDocuments($filters = [], $is_single_record = false) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."bp_group_documents";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			if ((isset($id) && !empty($id)) || $is_single_record) {
				$documents = $wpdb->get_row($sql);
			} else {
				$documents = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $documents;
		}

		public function getYearsOfPassout() {
			global $wpdb, $bp;

			switch_to_blog($this->main_site_blog_id);
			$passOutYears = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$this->year_of_passout_field_id."'");
			restore_current_blog();

			return $passOutYears;
		}

		public function getEdications() {
			global $wpdb, $bp;

			switch_to_blog($this->main_site_blog_id);
			$educations = $wpdb->get_results("SELECT id,name,type FROM {$bp->profile->table_name_fields} WHERE parent_id='".$this->highest_education_field_id."'");
			restore_current_blog();

			return $educations;
		}

		/**
		 * Get users who have the 'student' role and belong to BuddyPress group ID 4.
		 *
		 * @param int $group_id The BuddyPress group ID (default 4).
		 * @return array List of WP_User objects.
		 */
		function get_students_in_buddypress_group( $filter ) {
		    global $wpdb;
		    extract($filter);
		    $defaults = [
		    	'start'              => null,
    	        'length'             => null,
	            'group_id'           => 4,
	            'course_id'          => null,
	            'year_of_passout'    => null,
	            'highest_education'  => null,
	            'status'             => null, // Only active users by default
	        ];
			$args = wp_parse_args($filter, $defaults);

		    switch_to_blog($this->main_site_blog_id);
		    $sql = "
		            SELECT u.ID
		            FROM {$wpdb->users} u
		            INNER JOIN {$wpdb->prefix}usermeta um ON u.ID = um.user_id AND um.meta_key = '{$wpdb->prefix}capabilities' AND um.meta_value LIKE '%student%'
		            INNER JOIN {$wpdb->prefix}bp_groups_members gm ON gm.user_id = u.ID AND gm.group_id = ".$args['group_id']." AND gm.is_confirmed = 1
		            LEFT JOIN {$wpdb->prefix}usermeta course ON course.user_id = u.ID AND course.meta_key = 'course-id'
		            LEFT JOIN {$wpdb->prefix}bp_xprofile_data year_field ON year_field.user_id = u.ID AND year_field.field_id = ".$this->year_of_passout_field_id."
		            LEFT JOIN {$wpdb->prefix}bp_xprofile_data edu_field ON edu_field.user_id = u.ID AND edu_field.field_id = ".$this->highest_education_field_id."
		        ";

		    // Add status filter only if provided
		    $where = [];
	        if (!is_null($args['status'])) {
	            $where[] = "u.user_status = ".$args['status'];
	        }

	        if (!empty($args['course_id'])) {
	            $where[] = "course.meta_value = ".$args['course_id'];
	        }

	        if (!empty($args['year_of_passout'])) {
	            $where[] = "year_field.value = ".$args['year_of_passout'];
	        }

	        if (!empty($args['highest_education'])) {
	            $where[] = "edu_field.value = ".$args['highest_education'];
	        }

	        $where_sql = '';
	        if (!empty($where))
	        	$where_sql = implode(' AND ', $where);

	        $total_sql = $wpdb->query( $sql );
	        $recordsTotal = $wpdb->num_rows ;

	        // Filtered records count (with all filters)
	        $filtered_sql = $wpdb->query( $sql."WHERE ".$where_sql );
	        $recordsFiltered = $wpdb->num_rows;

	        $data_sql = $sql;
	        if (!empty($where_sql))
	        	$data_sql .= "WHERE ".$where_sql;

	        // Pagination (LIMIT/OFFSET)
	        if (!is_null($args['start']) && !is_null($args['length'])) {
	            $start = intval($args['start']);
	            $length = intval($args['length']);
	            $data_sql .= " LIMIT {$length} OFFSET {$start}";
	        }

	        $user_ids = $wpdb->get_col($data_sql);

	        if (empty($user_ids)) {
	            return [
	                'recordsTotal'    => 0,
	                'recordsFiltered' => 0,
	                'data'            => [],
	            ];
	        }

	        $students = get_users([
	            'include' => $user_ids,
	        ]);
		    restore_current_blog();

		    return [
		        'recordsTotal'    => intval($recordsTotal),
		        'recordsFiltered' => intval($recordsFiltered),
		        'data'            => $students,
		    ];
		}

		function get_student_course_id_by_title($title) {
		    $query = new WP_Query([
		        'post_type'      => 'studentcourses', // Change to your custom post type if needed
		        'title'          => $title,
		        'posts_per_page' => 1,
		        'post_status'    => 'any', // Optional: to include drafts, etc.
		    ]);

		    if ($query->have_posts()) {
		        return $query->posts[0]->ID;
		    }

		    return false;
		}

		function get_filter_fields($hide_fields) {
			global $wpdb;
			// Sanitize and validate $hide_fields
			$hide_fields = array_filter(array_map('intval', $hide_fields));

			// Prepare the NOT IN clause or set it to always true if the array is empty
			$not_in_clause = '';
			if (!empty($hide_fields)) {
			    $placeholders = implode(',', array_fill(0, count($hide_fields), '%d'));
			    $not_in_clause = "AND field.id NOT IN ($placeholders)";
			}
			switch_to_blog($this->main_site_blog_id);
			// Build the full SQL query
			$sql = "
			    SELECT field.id AS id, field.name AS name
			    FROM {$wpdb->prefix}bp_xprofile_fields AS field
			    INNER JOIN {$wpdb->prefix}bp_xprofile_meta AS meta
			        ON field.id = meta.object_id
			    WHERE meta.object_type = 'field'
			      AND meta.meta_key = 'do_autolink'
			      AND meta.meta_value = 'on'
			      $not_in_clause
			";

			// Prepare and execute the query
			if (!empty($hide_fields)) {
			    $filters = $wpdb->get_results($wpdb->prepare($sql, ...$hide_fields));
			} else {
			    $filters = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $filters;
		}

		public function get_bp_xprofile_fields ($filters = [], $is_single_record = false) {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."bp_xprofile_fields";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}
			if ((isset($id) && !empty($id)) || $is_single_record) {
				$fields = $wpdb->get_row($sql);
			} else {
				$fields = $wpdb->get_results($sql);
			}
			restore_current_blog();

			return $fields;
		}

		function get_xprofile_data($filters = [], $is_single_record = false) {
			global $wpdb, $bp;

			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."bp_xprofile_data";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}

			if ((isset($id) && !empty($id)) || $is_single_record) {
				$records = $wpdb->get_row($sql);
			} else {
				$records = $wpdb->get_results($sql);
			}

			restore_current_blog();

			return $records;
		}

		function getBuddyPressGroups($bp_group_type = '') {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$prefix = $wpdb->prefix; // Automatically uses correct prefix
			$query = "
			    SELECT g.*
			    FROM {$prefix}bp_groups AS g
			    JOIN {$prefix}term_relationships AS tr ON g.id = tr.object_id
			    JOIN {$prefix}term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
			    JOIN {$prefix}terms AS t ON tt.term_id = t.term_id
			    WHERE tt.taxonomy = %s
			      AND t.slug = %s
			";
			if (empty($bp_group_type)) {
				$bp_group_type = $this->bp_group_type_company;
			}
			$sql = $wpdb->prepare($query, 'bp_group_type', $bp_group_type);

			$groups = $wpdb->get_results($sql);
			restore_current_blog();

			return $groups;
		}

		function getStudentCourses() {
			switch_to_blog($this->main_site_blog_id);
			$args = [  
			    'post_type' => 'studentcourses',
			    'post_status' => 'publish',
			    'posts_per_page' => -1, 
			    'orderby' => 'title', 
			    'order' => 'ASC', 
			];
		    $posts = get_posts($args);
		    $courses = [];

		    foreach ($posts as $post) {
		        // Setup post data manually if needed for template functions
		        setup_postdata($post);

		        $courses[] = array(
		            'ID'        => $post->ID,
		            'title'     => get_the_title($post),
		        );
		    }

		    wp_reset_postdata();
		    restore_current_blog();
			return $courses;
		}

		function getUserEducations($filters = [], $is_single_record = false) {
			global $wpdb, $bp;

			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."user_educations";
			$sql = 'SELECT * FROM '.$tablename;

			if (!empty($filters)) {
				$sql .=  ' WHERE ';

				$i = 0;

				foreach ($filters as $filter_key => $filter_value) {
					if ($i == 0 ) {
						$sql .= '`'.$filter_key.'` = "'.$filter_value.'"';
					} else {
						$sql .= ' AND `'.$filter_key.'` = "'.$filter_value.'"';
					}
					$i++;
				}
			}

			$sql .= ' ORDER By id DESC';

			if ((isset($id) && !empty($id)) || $is_single_record) {
				$records = $wpdb->get_row($sql);
			} else {
				$records = $wpdb->get_results($sql);
			}

			restore_current_blog();

			return $records;
		}

		function addEditCollegeStaff($data, $id='') {
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$tablename = $wpdb->prefix."college_staff";
			if (empty($id)) {
			    $result = $wpdb->insert($tablename, $data);
			    return $result;
			}else{
			    $result = $wpdb->update($tablename, $data, [ 'id' => $id ] );
			}
			restore_current_blog();
			return $result;
		}

		function getUsers($filter = []){
			global $wpdb;
			switch_to_blog($this->main_site_blog_id);
			$records = get_users($filter);
			restore_current_blog();
			return $records;
		}

		function trikona_upload_image_to_media_library($file) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		    require_once(ABSPATH . 'wp-admin/includes/media.php');
		    require_once(ABSPATH . 'wp-admin/includes/image.php');

		    $upload = wp_handle_upload($file, ['test_form' => false]);

		    if (!isset($upload['error']) && isset($upload['file'])) {
		        $filetype = wp_check_filetype($upload['file'], null);
		        $title = sanitize_file_name(basename($upload['file']));
		        $attachment = [
		            'post_mime_type' => $filetype['type'],
		            'post_title'     => $title,
		            'post_content'   => '',
		            'post_status'    => 'inherit',
		        ];

		        $attach_id = wp_insert_attachment($attachment, $upload['file']);

		        $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
		        wp_update_attachment_metadata($attach_id, $attach_data);

		        return $attach_id;
			}
			return new WP_Error('upload_failed', 'Image upload failed');
		}

		function getDepartments() {
			switch_to_blog($this->directories_site_blog_id);
			$departments = get_posts([
			  'post_type' => 'department',
			  'post_status' => 'publish',
			  'numberposts' => -1,
			  'order'    => 'ASC'
			]);
			restore_current_blog();

			return $departments;
		}

		function getDesignations() {
			switch_to_blog($this->directories_site_blog_id);
			$departments = get_posts([
			  'post_type' => 'designation',
			  'post_status' => 'publish',
			  'numberposts' => -1,
			  'order'    => 'ASC'
			]);
			restore_current_blog();

			return $departments;
		}

		function getPubicationTypes() {
			switch_to_blog($this->directories_site_blog_id);
			$publication_types = get_posts([
			  'post_type' => 'publication_type',
			  'post_status' => 'publish',
			  'numberposts' => -1,
			  'order'    => 'ASC'
			]);
			restore_current_blog();

			return $publication_types;
		}
	}
?>
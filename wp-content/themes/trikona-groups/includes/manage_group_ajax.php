<?php
class TrikonaManageGroupAjax {

	public function __construct() {
		add_action( 'wp_ajax_fetch_group_managers', array($this,'fetch_group_managers' ));
		add_action( 'wp_ajax_nopriv_fetch_group_managers', array($this,'fetch_group_managers' ));

		add_action( 'wp_ajax_assign_group_manager', array($this,'assign_group_manager' ));
		add_action( 'wp_ajax_nopriv_assign_group_manager', array($this,'assign_group_manager' ));

		add_action( 'wp_ajax_manage_group_staff_data', array($this,'manage_group_staff_data' ));
		add_action( 'wp_ajax_nopriv_manage_group_staff_data', array($this,'manage_group_staff_data' ));
	}

	public function fetch_group_managers() {
		$group_id = isset($_POST['group_id']) ? base64_decode($_POST['group_id']) : "";

		switch_to_blog(1);
		$group_manager_args = [
		    'role__in'    => ['group_manager']
		];

		$group_managers = get_users( $group_manager_args );
		restore_current_blog();

		if (!empty($group_managers)) {
			$manager_list_html = '<div class="row">';
			foreach ($group_managers as $group_manager) {
				$checked = '';
				if (groups_is_user_member($group_manager->ID, $group_id)) {
					$checked = 'checked=checked';
				}

				$manager_list_html .= '<div class="col-md-3">
				        				<div class="row">
				        					<div class="col-md-3">
				        						<input id="group_manager'.$group_manager->ID.'" type="radio" name="group_manager" value="'.$group_manager->ID.'" '.$checked.'/>
				        					</div>
				        					<div class="col-md-9">
				        						<label for="group_manager'.$group_manager->ID.'">'.$group_manager->data->display_name.'</label>
				        					</div>
				        				</div>
				        			</div>';
			}
			$manager_list_html .= '</div>';

			$response['success'] = true;
			$response['message'] = 'Group manager found successfully.';
			$response['group_managers'] = $manager_list_html;
			echo json_encode($response);die();
		} else {
			$response['success'] = false;
			$response['message'] = 'No group managers found yet.';
		}

		echo json_encode($response);die();
	}

	public function assign_group_manager() {
		global $wpdb;

		$group_ids 		= isset($_POST['group_ids']) ? $_POST['group_ids'] : "";
		$group_manager 	= isset($_POST['group_manager']) ? $_POST['group_manager'] : "";

		$errors = $success = [];
		if (empty($group_manager)) {
			$errors[] = "Please select group manager.";
		}
		if (empty($group_ids)) {
			$errors[] = "Please select at least 1 group.";
		}

		if (!empty($errors)) {
			$response['success'] = false;
			$response['message'] = implode("<br>", $errors);
			echo json_encode($response);die();
		} else {
			switch_to_blog(1);
			$tablename = $wpdb->prefix."bp_groups_members";
			$group_manager_info = get_user_by('ID', $group_manager);

			foreach ($group_ids as $group_id) {
				$sql_query = "SELECT * FROM ".$tablename." WHERE `is_admin` = 1 AND `group_id` = ".$group_id;
				$group_managers = $wpdb->get_results($sql_query);

				$group = groups_get_group( $group_id );

				if (!empty($group_managers) && sizeof($group_managers) > 1) {
					$errors[] = $group->name." has ".sizeof($group_managers)." group managers.";
				} else if (!empty($group_managers)) {
					$result = $wpdb->update($tablename, [ 'user_id' => $group_manager ], [ 'group_id'=> $group_id ]);

					if (!empty($result)) {
						$success[] = ucfirst($group_manager_info->data->display_name)." has been assigned to <b>".$group->name."</b>";
					}
				} else {
					$member_data = [
						'group_id'=> $group_id,
						'user_id'=> $group_manager,
						'is_admin'=> 1,
						'user_title'=> 'Group Admin',
						'is_confirmed'=> 1,
					];
					$result = $wpdb->insert($tablename, $member_data);

					if (!empty($result)) {
						$success[] = ucfirst($group_manager_info->data->display_name)." has been assigned to <b>".$group->name."</b>";
					}
				}
			}
			restore_current_blog();

			if (!empty($success)) {
				$response['success'] = true;
				$response['message'] = implode("<br>", $success);
			} else {
				$response['success'] = false;
				$errors[] = "Something went wrong. Please try again later.";
			}
			if (!empty($errors)) {
				$response['errors'] = implode("<br>", $errors);
			}
		}
		echo json_encode($response);die();
	}

	public function manage_group_staff_data() {
		global $trikona_obj;

		$group_id 			= isset($_POST['group_id']) ? base64_decode($_POST['group_id']) : "";
		$staff_id 			= isset($_POST['staff_id']) ? base64_decode($_POST['staff_id']) : "";
		$first_name 		= isset($_POST['first_name']) ? $_POST['first_name'] : "";
		$last_name 			= isset($_POST['last_name']) ? $_POST['last_name'] : "";
		$user_name 			= isset($_POST['user_name']) ? $_POST['user_name'] : "";
		$email_address 		= isset($_POST['email_address']) ? $_POST['email_address'] : "";
		$gender 			= isset($_POST['gender']) ? $_POST['gender'] : "";
		$dob 				= isset($_POST['dob']) ? $_POST['dob'] : "";
		$contact 			= isset($_POST['contact']) ? $_POST['contact'] : "";
		$national_id 		= isset($_POST['national_id']) ? $_POST['national_id'] : "";
		$department 		= isset($_POST['department']) ? $_POST['department'] : "";
		$designation 		= isset($_POST['designation']) ? $_POST['designation'] : "";
		$emergency_name 	= isset($_POST['emergency_name']) ? $_POST['emergency_name'] : "";
		$relationship 		= isset($_POST['relationship']) ? $_POST['relationship'] : "";
		$emergency_phone 	= isset($_POST['emergency_phone']) ? $_POST['emergency_phone'] : "";
		$education 			= isset($_POST['education']) ? $_POST['education'] : "";
		$skills 			= isset($_POST['skills']) ? $_POST['skills'] : "";

		$errors = $success = [];
		if (empty($group_id)) {
			$errors[] = "Something went wrong. Please try again later.";
		}
		if (empty($first_name)) {
			$errors[] = "First Name is required.";
		}
		if (empty($last_name)) {
			$errors[] = "Last Name is required.";
		}
		if (empty($user_name)) {
			$errors[] = "User name is required.";
		} else {
			$user_by_login = get_user_by( 'login', $user_name );
			if (!empty($staff_id) && $user_by_login && $user_by_login->ID !== $staff_id) {
				$errors[] = "Username has already been taken.";
			}
		}
		if (empty($email_address)) {
			$errors[] = "Email is required.";
		} else if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Please enter valid email is required.";
		} else {
			$user = get_user_by('email', $email);
			if (!empty($staff_id) && $user && $user->ID !== $staff_id) {
				$errors[] = "Email already in use by another user.";
			}
		}
		if (empty($gender)) {
			$errors[] = "Gender is required.";
		}
		if (empty($dob)) {
			$errors[] = "Date of Birth is required.";
		}
		if (empty($contact)) {
			$errors[] = "Contact Number is required.";
		}
		if (empty($national_id)) {
			$errors[] = "National ID / Passport No. is required.";
		}
		if (empty($department)) {
			$errors[] = "Department is required.";
		}
		if (empty($designation)) {
			$errors[] = "Designation is required.";
		}
		if (empty($emergency_name)) {
			$errors[] = "Contact Person is required.";
		}
		if (empty($relationship)) {
			$errors[] = "Relationship is required.";
		}
		if (empty($emergency_phone)) {
			$errors[] = "Phone is required.";
		}
		if (empty($education)) {
			$errors[] = "Education is required.";
		}
		if (empty($skills)) {
			$errors[] = "Skills is required.";
		}
		if (isset($_FILES['photo']) && $_FILES['photo']['error'] != 0) {
			$errors[] = "Photo upload failed due to an unexpected issue.";
		} else {
			$file_validations = $trikona_obj->ValidateFiles($_FILES['photo']);
			if (!empty($file_validations)) {
			    $errors = array_merge($errors, $file_validations);
			}
		}

		if (!empty($errors)) {
			$response['success'] = false;
			$response['message'] = implode("<br>", $errors);
			echo json_encode($response);die();
		} else {
			$profile_img_id = '';
			if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
				$profile_img_id = $trikona_obj->trikona_upload_image_to_media_library($_FILES['photo']);
			}
			switch_to_blog($trikona_obj->main_site_blog_id);
			$is_new_user = true;
			$userdata = [
		        'user_login'    => $user_name,
		        'user_pass'     => $email_address,
		        'user_email'    => $email_address,
		        'first_name'    => $first_name,
		        'last_name'     => $last_name,
		        'display_name'  => $first_name." ".$last_name,
		        'role'          => 'professional'
		    ];
			if (empty($staff_id)) {
			    $staff_id = wp_insert_user($userdata);
				$is_new_user = false;
			} else {
				$userdata['ID'] = $staff_id;
				$staff_id = wp_insert_user($userdata);
			}
			if (is_wp_error($staff_id)) {
				$response['success'] = false;
				$response['message'] = $staff_id->get_error_message();
				echo json_encode($response);die();
			}

			if (!empty($profile_img_id)) {
				// Save the attachment ID as user meta
				update_user_meta($staff_id, 'profile_picture', $profile_img_id);
			}

			$user_meta_data = [
				'group_id' 			=> $group_id,
				'dob' 				=> date('Y-m-d', strtotime($dob)),
				'billing_phone' 	=> $contact,
				'gender' 			=> $gender,
				'national_id' 		=> $national_id,
				'department' 		=> $department,
				'designation' 		=> $designation,
				'emergency_name' 	=> $emergency_name,
				'relationship' 		=> $relationship,
				'emergency_phone' 	=> $emergency_phone,
				'education' 		=> $education,
				'skills' 			=> $skills,
			];
			foreach ($user_meta_data as $meta_key => $meta_value) {
			    update_user_meta($staff_id, $meta_key, $meta_value);
			}
			restore_current_blog();

			$success_msg = "New staff entry created successfully.";
			if (!$is_new_user) {
				$success_msg = "Staff details have been updated successfully.";
			}
			$response['success'] = true;
			$response['message'] = $success_msg;
			echo json_encode($response);die();
		}
	}
}
new TrikonaManageGroupAjax();
?>
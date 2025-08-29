<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 * 
 * Updated on 28-May-24 by Shailendra
 *  added code item# 
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'TRIKONA_CHILD_VERSION', '1.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function trikona_child_scripts_styles() {

    // Enqueue the  child theme style
    wp_enqueue_style( 'trikona-directory-style', get_stylesheet_directory_uri() . '/style.css', [], TRIKONA_CHILD_VERSION );

    // Enqueue manage group dashboard stylesheet
    /*wp_enqueue_style( 'trikona-dashboard-additional-style', get_stylesheet_directory_uri() . '/assets/css/style-manage-group-dashboard.css', [], '1.0.0');*/

    // Enque Dashboard stylesheet File
    /*wp_enqueue_style( 'trikona-dashboard-style', get_stylesheet_directory_uri() . '/assets/css/style-dashboard.css', [], '1.0.0' );*/

}
add_action( 'wp_enqueue_scripts', 'trikona_child_scripts_styles', 100 );

/*function trikona_parent_scripts_styles() {
    // Main parent custom CSS
    wp_enqueue_style(
        'trikona-main-style',
        get_template_directory_uri() . '/assets/css/trikona_main.css',
        [],
        '1.0.0'
    );

    // Parent JS
    wp_enqueue_script(
        'trikona-main-js',
        get_template_directory_uri() . '/assets/js/trikona_main.js',
        [],
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'trikona_parent_scripts_styles', 20 );*/

function trikona_main_scripts_styles() {
    $theme_url = get_template_directory_uri();
    $themes_dir_url = dirname($theme_url);
    // Main parent custom CSS
    wp_enqueue_style(
        'trikona-main-user-dashboard-style',
        $themes_dir_url . '/trikona-main/dashboard/assets/css/user-dashboard-addon.css',
        [],
        '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'trikona_main_scripts_styles', 20 );

require_once( get_stylesheet_directory() . '/shortcodes/shortcode_studentdata.php');
require_once( get_stylesheet_directory() . '/includes/class_members_profile_ajax.php');
require_once( get_stylesheet_directory() . '/includes/class_profiles_ajax.php');
require_once( get_stylesheet_directory() . '/includes/manage_group_ajax.php');
require_once( get_stylesheet_directory() . '/custom_post_type.php');

//2. Add a group type / group layout / goup manager meta-boxes to Create / edit Group wp-admin page 

/*function add_custom_meta_box() {
    add_meta_box("demo-meta-box", "Group Layouts", "ccustom_meta_box_markup", "group-layout", "side", "high", 10);
}
    
add_action("add_meta_boxes", "add_custom_meta_box");

function ccustom_meta_box_markup($object) {
    $group_type = get_post_meta($object->ID,'group_type',true);
?>
    <div>
        <br>
        <select name="groupsLayouts" style="width:100%;">
            <?php 
               $terms = get_terms( ['taxonomy'   => 'bp_group_type', 'hide_empty' => false] );

                foreach($terms as $key => $value)  { ?>
                    <option value="<?= $value->slug ?>" <?php if($group_type==$value->slug) echo 'selected="selected"'; ?> ><?= $value->name ?></option>
            <?php } ?>
        </select>
        <br>
    </div>
<?php   }   
function wpse_save_meta_fields( $post_id ) {
    // verify nonce
    update_post_meta($post_id,'group_type',$_POST['groupsLayouts']);
}

add_action( 'save_post', 'wpse_save_meta_fields' );
add_action( 'new_to_publish', 'wpse_save_meta_fields' );*/

/*******************************************/


// meta-box for Group Layout

if( class_exists( 'BP_Group_Extension' ) ) :
    class bp_fan_club {
        public function __construct() {
            $this->setup_hooks();
        }
        private function setup_hooks() {
            add_action( 'bp_groups_admin_meta_boxes', array( $this, 'bp_fan_club_page' ) );
        }
        public function bp_fan_club_page() {
            add_meta_box( 'bp_fan_club', __( 'Group Layout' ), array( &$this, 'bp_fan_club_page_metabox'), get_current_screen()->id, 'side', 'core');
        }
        public function bp_fan_club_page_metabox( $item = false ) {
     		$layout = new WP_Query(array(
    		'post_type'=>'group_layout',
    		'orderby'=>'date',
    		'order'=>'ASC',
    		'posts_per_page'=>-1,
    	));

     ?>
        <select name="hide_from_anonymous">
        <?php while ( $layout->have_posts() ) :
    		$layout->the_post(); ?>
            <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
    	<?php endwhile;?>
        </select>
    <?php wp_nonce_field(basename(__FILE__), "bpgroupmeta-box-nonce"); }
        public function bp_fan_club_option() {
        }
    }
    function bp_fan_club_group() {
        if( bp_is_active( 'groups') )
            return new bp_fan_club();
    }
    add_action( 'bp_init', 'bp_fan_club_group' );
endif;

add_action( 'bp_group_admin_edit_after', 'bpgcp_save_metabox_fields' );

function bpgcp_save_metabox_fields( $group_id ) {
	$hide_from_anonymous = intval( $_POST['hide_from_anonymous'] );
    $updateGroup_manager = intval( $_POST['updateGroup_manager'] );
    $trikona_group_template = intval( $_POST['trikona_group_template'] );
    groups_update_groupmeta( $group_id, 'hide_from_anonymous', $hide_from_anonymous );
    groups_update_groupmeta( $group_id, 'updateGroup_manager', $updateGroup_manager );
    groups_update_groupmeta( $group_id, 'trikona_group_template', $trikona_group_template );
}

// Buddypress Group MetaBox
if( class_exists( 'BP_Group_Extension' ) ) :
    class groups_mnager_user {
        public function __construct() {
            $this->setup_hooks();
        }
        private function setup_hooks() {
            add_action( 'bp_groups_admin_meta_boxes', array( $this, 'bp_group_manager_page' ) );
        }
        public function bp_group_manager_page() {
            add_meta_box( 'bp_group_manager', __( 'Choose Group Manager' ), array( &$this, 'bp_manager_club_page_metabox'), get_current_screen()->id, 'side', 'core');
        }
        public function bp_manager_club_page_metabox( $item = false ) {
            $group_manager = groups_get_groupmeta($_GET['gid'],'updateGroup_manager',true);
            $args = [ 'role' => 'group_manager', 'orderby' => 'user_nicename', 'order'   => 'ASC' ];
            $users = get_users( $args ); 
     ?>
            <p>
                <select name="updateGroup_manager"> 
                    <option>Select Group Manager</option>
                    <?php foreach ( $users as $user ) {  ?>
                        <option value="<?=  $user->ID ?>" <?php if( $user->ID == $group_manager ): ?> selected="selected"<?php endif; ?>><?= $user->display_name ?></option>
                    <?php } ?>
                </select>
            </p>
    <?php wp_nonce_field(basename(__FILE__), "bpgroupmeta-box-nonce"); }
        public function bp_fan_club_option() {
    ?>
            <option value="featured"><?php _e( 'Featured' ); ?></option>
    <?php }
    }
    function bp_group_manager_group() {
        if( bp_is_active( 'groups') )
            return new groups_mnager_user();
    }
    add_action( 'bp_init', 'bp_group_manager_group' );
endif;

// 4. Add company enquiry form by @mahendra 

add_action( 'wp_ajax_company_empInquiry', 'company_empInquiry' );
add_action( 'wp_ajax_nopriv_company_empInquiry', 'company_empInquiry' );

function company_empInquiry(){
    global $wpdb,$bp;
    parse_str($_POST['data'], $searcharray);
    $group_id = bp_get_current_group_id();
    if( $group_id!=""){
        $bp_group_id = $wpdb->get_results( "SELECT user_id FROM {$wpdb->prefix}bp_groups_members WHERE group_id='$group_id' and user_title='Group Mod'");
        $user_email = [];
        foreach ($bp_group_id as $key => $user_id) {
    	   $user_info =  get_userdata( $user_id->user_id );
    	   $user_email[] = $user_info->user_email;
        }

        $user_email = implode(', ', $user_email);
        parse_str($_POST['data'], $searcharray);
        $wpdb->insert($wpdb->prefix.'emp_inquiry', array(
            'group_id' => $group_id,
            'company_email' => $user_email,
            'Username' => $searcharray['inquiry_name'], 
            'userEmap' => $searcharray['inquiry_email'],
            'userPhone' => $searcharray['inquiry_phone'],
            'userSubject' => $searcharray['inquiry_subject'],
            'userMsg' => $searcharray['inquiry_msg'],
        ));
    }
    wp_die();
}

/*
    Added by Mahendragiri on 09 June 2025

    Functionality
    --------------
    1. trikona_directory_override_bp_group_admin_member_autocomplete
        - Removed existing handler for wp_ajax_bp_group_admin_member_autocomplete
        - Implemented new handler for wp_ajax_bp_group_admin_member_autocomplete with trikona_directory_bp_group_admin_member_autocomplete
    2. trikona_directory_bp_group_admin_member_autocomplete()
        - To get current group type based on group id
        - Get user role based on group type
        - Call bp_get_members_not_in_any_group() with user role and search term and return json
    2. bp_get_members_not_in_any_group()
        - To get members list based on user role (based on group type) and search term
        - Find out members which are not assigned to any group
        - Prepare suggestion list and retrun the suggestion list
*/
/*function trikona_directory_override_bp_group_admin_member_autocomplete() {
    remove_action( 'wp_ajax_bp_group_admin_member_autocomplete', 'bp_groups_admin_autocomplete_handler' );
    add_action( 'wp_ajax_bp_group_admin_member_autocomplete', 'trikona_directory_bp_group_admin_member_autocomplete' );
}
add_action( 'bp_init', 'trikona_directory_override_bp_group_admin_member_autocomplete', 20 );

function trikona_directory_bp_group_admin_member_autocomplete($group) {
    // Bail if user user shouldn't be here, or is a large network.
    if ( ! bp_current_user_can( 'bp_moderate' ) || bp_is_large_install() ) {
        wp_die( -1 );
    }

    $term     = isset( $_GET['term'] )     ? sanitize_text_field( $_GET['term'] ) : '';
    $group_id = isset( $_GET['group_id'] ) ? absint( $_GET['group_id'] )          : 0;

    if ( ! $term || ! $group_id ) {
        wp_die( -1 );
    }

    $obj = new Trikona();

    $group_type = bp_groups_get_group_type( $group_id );

    $members = [];
    if (!empty($group_type)) {
        switch ($group_type) {
            case 'college':
                    $members = bp_get_members_not_in_any_group($user_role="college_admin", $term);
                break;
            case 'company':
                    $members = bp_get_members_not_in_any_group($user_role="corporate", $term);
                break;
        }
    }

    wp_die( json_encode( $members ) );
}

function bp_get_members_not_in_any_group($user_role, $search_term) {
    global $wpdb;

    // Get all user IDs
    $users_with_role = get_users(
        [
            'role'   => $user_role,
            'search' => '*' . esc_attr( $search_term ) . '*',
            'search_columns' => ['display_name'],
            'fields' => 'ID',
            'number' => -1, // get all users
        ] 
    );

    // Get all group member user IDs
    switch_to_blog(1);
    $group_member_ids = $wpdb->get_col("SELECT DISTINCT user_id FROM {$wpdb->prefix}bp_groups_members WHERE is_confirmed = 1 ");
    restore_current_blog();

    // Get users not in any group
    $users_not_in_groups = array_diff( $users_with_role, $group_member_ids );

    // Get user objects (optional)
    $suggestions = [];
    foreach ( $users_not_in_groups as $user_id ) {
        $user = get_userdata( $user_id );
        $name = bp_core_get_user_displayname( $user->ID );
        $id = $user->user_nicename;

        $suggestions[] = array(
            // Translators: 1: user_login, 2: user_email.
            'label' => sprintf( __( '%1$s (%2$s)', 'buddypress' ), $name, $id ),
            'value' => $id,
        );
    }

    return $suggestions;
}*/


/*
    Added by Mahendragiri on 18 June 2025

    Functionality
    --------------
    1. Check role for current user based on dashboard type
        1.1 Company Dashboard
            - Allow following roles
                - administrator
                - group_manager
                - corporate
        1.2 College Dashboard
            - Allow following roles
                - administrator
                - group_manager
                - college_admin
    2. Check if user exist in only single group of same type
    3. Check if user has "Group Admin" BP group role or not
*/
function checkDashboardAccessibility($type = '') {
    global $status_messages, $trikona_obj;

    // Always sanitize any user input, especially if used in DB queries
    $type = sanitize_key($type); // ✅ Sanitize input to prevent invalid values or injection

    // Switch to the main site context (ID 1) in a multisite network
    switch_to_blog(1);

    // Get the current logged-in user object
    $current_user = wp_get_current_user();
    $roles = $current_user->roles; // Get user's roles

    // Get the roles allowed to manage groups from the Trikona object
    $manage_groups_allowed_roles = $trikona_obj->manage_groups_allowed_roles;

    $allowed_roles = []; // Roles that are both in allowed list and user’s roles

    // Default response structure
    $response = [
        'success' => false,
        'group_id' => null,
        'message' => $status_messages->error[101],
    ];

    // If the user is a network super admin, force their role to administrator
    if (is_super_admin($current_user->ID)) {
        $roles = ['administrator'];
    }

    // Append role based on dashboard type (college/company)
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

    // Determine roles that are allowed for this user
    if (!empty($manage_groups_allowed_roles) && !empty($roles)) {
        $allowed_roles = array_intersect($manage_groups_allowed_roles, $roles);
    }

    // If user has at least one allowed role
    if (!empty($allowed_roles)) {
        $allowed_user_groups = [];

        // Get user's groups and group IDs filtered by type (e.g., college/company)
        $user_groups = get_user_groups_by_user_id($current_user->ID);
        $group_ids = get_group_ids_by_group_type( $type );

        // Only allow access to groups the user is part of and match the group type
        if (!empty($user_groups) && !empty($group_ids)) {
            $allowed_user_groups = array_intersect($user_groups, $group_ids);
        }

        if (!empty($allowed_user_groups)) {

            // Only proceed if exactly one matching group (enforces one-group-per-user rule)
            if (sizeof($allowed_user_groups) == 1) {
                $group_id = reset($allowed_user_groups);

                // Verify the user is a Group Admin in that group
                $group_info = $trikona_obj->getGroupMembers(['user_id' => $current_user->ID, 'group_id' => $group_id, 'user_title' => 'Group Admin'], $is_single_record = true);

                // If group info found, user is authorized
                if (!empty($group_info)) {
                    $response = [
                        'success' => true,
                        'group_id' => $group_id,
                        'message' => $status_messages->sucess[101],
                    ];
                }
            } else {
                $response['message'] = $status_messages->error[201];
            }
        } else {
            $response['message'] = $status_messages->error[102];
        }
    } else {
        $response['message'] = $status_messages->error[103];
    }

    // Restore original blog context (important for multisite)
    restore_current_blog();

    // Return access response
    return $response;
}

/*
    To get the group ids by group type
*/
function get_group_ids_by_group_type( $group_type ) {
    // Always sanitize any user input, especially if used in DB queries
    $group_type = sanitize_key($group_type); // ✅ Sanitize input to prevent invalid values or injection
    // Switch to the main blog/site (ID 1) to query group data from the correct context
    switch_to_blog(1);

    // Prepare query arguments
    $args = [
        'group_type' => $group_type, // Filter groups by specified type
        'fields'     => 'ids', // Return only the group IDs (not full objects)
        'per_page'   => 0,     // Retrieve all matching groups with no pagination
    ];

    // Retrieve groups using BuddyPress API
    $groups = groups_get_groups( $args ); // This will return an array with 'groups' => [ids], 'total', etc.

    // Restore the previous blog context after querying (critical in multisite)
    restore_current_blog();

    // Return group IDs if any are found; otherwise return an empty array
    return !empty($groups) ? $groups['groups'] : [];
}

/*
    To get the group ids by user id
*/
function get_user_groups_by_user_id($user_id) {
    // Sanitize the user ID to ensure it's a valid integer
    $user_id = intval($user_id); // ✅ Prevents type juggling and injection risks

    // Switch to the main blog context (blog ID 1), assuming BuddyPress is active there
    switch_to_blog(1);

    // Retrieve all group IDs the user belongs to
    $user_groups = (groups_get_user_groups($user_id)); // Expected format: [ 'groups' => [...], 'total' => int ]

    // Restore the original blog context after fetching the data
    restore_current_blog();

    // Return the group IDs array, or an empty array if none are found
    return !empty($user_groups) ? $user_groups['groups'] : [];
}

if( class_exists( 'BP_Group_Extension' ) ) :
    class bp_group_templates {
        public function __construct() {
            $this->setup_hooks();
        }
        private function setup_hooks() {
            add_action( 'bp_groups_admin_meta_boxes', array( $this, 'bp_fan_club_page' ) );
        }
        public function bp_fan_club_page() {
            add_meta_box( 'bp_group_templates', __( 'Group Templates' ), array( &$this, 'trikon_group_templates_metabox'), get_current_screen()->id, 'side', 'core');
        }

        public function trikon_group_templates_metabox($item = false) {
            $group_id = isset($_GET['gid']) ? $_GET['gid'] : '';

            $template_options = '';

            $group_type = '';
            if (!empty($group_id)) {
                $group_type = bp_groups_get_group_type( $group_id );
                $existing_template = groups_get_groupmeta( $group_id, $meta_key='trikona_group_template' );

                if (!empty($group_type)) {
                    if ($group_type == "college") {
                        for($i = 1; $i <= 10; $i++) {
                            $selected = ($existing_template == $i) ? 'selected=selected' : '';
                            $template_options .= '<option value="'.$i.'" '.$selected.'>Template - '.$i.'</option>';
                        }
                    } else if ($group_type == "company") {
                        for($i = 1; $i <= 5; $i++) {
                            $selected = ($existing_template == $i) ? 'selected=selected' : '';
                            $template_options .= '<option value="'.$i.'" '.$selected.'>Template - '.$i.'</option>';
                        }
                    }
                }
            }
        ?>
            <select name="trikona_group_template">
                <option value="">Select Group Template</option>
                <?= $template_options ?>
            </select>
        <?php }
    }
    function trikona_group_templates() {
        if( bp_is_active( 'groups') )
            return new bp_group_templates();
    }
    add_action( 'bp_init', 'trikona_group_templates' );
endif;

add_action( 'bp_screens', 'trikona_group_template_redirect_dynamic', 1 );
function trikona_group_template_redirect_dynamic() {
    if ( ! bp_is_group() ) return;

    $group = groups_get_current_group();
    $group_type = bp_groups_get_group_type( $group->id );
    $template_id = groups_get_groupmeta( $group->id, 'trikona_group_template' );

    if ( ! empty( $template_id ) ) {
        remove_action( 'bp_template_include_reset_dummy_post_data', 'bp_template_include_reset_dummy_post_data' );
        remove_action( 'bp_template_include_reset_global_post', 'bp_template_include_reset_global_post' );

        add_filter( 'template_include', function( $template ) use ( $template_id, $group_type ) {
            $template_file = 'template-'.$template_id.'.php';
            $template_path = get_stylesheet_directory() . '/group-templates/'.$group_type.'/'.$template_file;
            if ( file_exists( $template_path ) ) {
                return $template_path;
            }
            return $template;
        });
    }
}
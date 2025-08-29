<?php 
  class TrikonaCustomPost{
    /**
    * Constructor
    */
    public function __construct() {
    	add_action( 'init', array($this,'custom_post_type_courses'));
    }
            
    //
    /*
    * Creating a function to create our CPT
    */
    public function custom_post_type_courses() {
      /*Create 'Group Layout' Custom Post Type */
      register_post_type('group_layout', [
        'labels' => [
            'name' => __('Group Layouts', 'text_domain'),
            'singular_name' => __('Group Layout', 'text_domain'),
            'menu_name' => __('Group Layouts', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'add_new_item' => __('Add New Group Layout', 'text_domain'),
            'edit_item' => __('Edit Group Layout', 'text_domain'),
            'new_item' => __('New Group Layout', 'text_domain'),
            'view_item' => __('View Group Layout', 'text_domain'),
            'search_items' => __('Search Group Layouts', 'text_domain'),
            'not_found' => __('Not Found', 'text_domain'),
            'not_found_in_trash' => __('Not Found in Trash', 'text_domain'),
        ],
        'public' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'group-layouts'],
        'supports' => ['title', 'author', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields', 'post-formats'],
        'menu_icon' => 'dashicons-layout',
        'show_in_rest' => true,
      ]);

      /*Create 'Group Categories' Custom taxonomy */
      register_taxonomy('group_category', 'group_layout', [
        'labels' => [
          'name' => __('Group Categories', 'text_domain'),
          'singular_name' => __('Group Category', 'text_domain'),
          'search_items' => __('Search Group Categories', 'text_domain'),
          'all_items' => __('All Group Categories', 'text_domain'),
          'edit_item' => __('Edit Group Category', 'text_domain'),
          'update_item' => __('Update Group Category', 'text_domain'),
          'add_new_item' => __('Add New Group Category', 'text_domain'),
          'new_item_name' => __('New Group Category Name', 'text_domain'),
          'menu_name' => __('Group Categories', 'text_domain'),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
      ]);

      register_post_type('department', [
        'labels' => [
            'name' => __('Departments', 'text_domain'),
            'singular_name' => __('Department', 'text_domain'),
            'menu_name' => __('Departments', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'add_new_item' => __('Add New Department', 'text_domain'),
            'edit_item' => __('Edit Department', 'text_domain'),
            'new_item' => __('New Department', 'text_domain'),
            'view_item' => __('View Department', 'text_domain'),
            'search_items' => __('Search Departments', 'text_domain'),
            'not_found' => __('Not Found', 'text_domain'),
            'not_found_in_trash' => __('Not Found in Trash', 'text_domain'),
        ],
        'public' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'departments'],
        'supports' => ['title','excerpt', 'revisions', 'custom-fields', 'post-formats'],
        'menu_icon' => 'dashicons-schedule',
        'show_in_rest' => true,
      ]);

      register_post_type('designation', [
        'labels' => [
            'name' => __('Designations', 'text_domain'),
            'singular_name' => __('Designation', 'text_domain'),
            'menu_name' => __('Designations', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'add_new_item' => __('Add New Designation', 'text_domain'),
            'edit_item' => __('Edit Designation', 'text_domain'),
            'new_item' => __('New Designation', 'text_domain'),
            'view_item' => __('View Designation', 'text_domain'),
            'search_items' => __('Search Designations', 'text_domain'),
            'not_found' => __('Not Found', 'text_domain'),
            'not_found_in_trash' => __('Not Found in Trash', 'text_domain'),
        ],
        'public' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'designations'],
        'supports' => ['title','excerpt', 'revisions', 'custom-fields', 'post-formats'],
        'menu_icon' => 'dashicons-awards',
        'show_in_rest' => true,
      ]);

      register_post_type('publication_type', [
        'labels' => [
            'name' => __('Publication types', 'text_domain'),
            'singular_name' => __('Publication type', 'text_domain'),
            'menu_name' => __('Publication types', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'add_new_item' => __('Add New Publication type', 'text_domain'),
            'edit_item' => __('Edit Publication type', 'text_domain'),
            'new_item' => __('New Publication type', 'text_domain'),
            'view_item' => __('View Publication type', 'text_domain'),
            'search_items' => __('Search Publication types', 'text_domain'),
            'not_found' => __('Not Found', 'text_domain'),
            'not_found_in_trash' => __('Not Found in Trash', 'text_domain'),
        ],
        'public' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'publication-type'],
        'supports' => ['title','excerpt', 'revisions', 'custom-fields', 'post-formats'],
        'menu_icon' => 'dashicons-translation',
        'show_in_rest' => true,
      ]);
    }
  }

  new TrikonaCustomPost();
?>
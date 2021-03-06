<?php
/**
 * Register post type and taxonomy.
 * 
 * @package pixbox
 * @since 0.1.0
 */

function pixbox_register_post_tax() {
	register_post_type('pixbox_photo', array(
		'label'                 => __( 'Photo', 'pixbox' ),
		'description'           => __( 'Photos in Pixbox', 'pixbox' ),
		'labels'                => array(
      'name'                  => _x( 'Photos', 'Post Type General Name', 'pixbox' ),
      'singular_name'         => _x( 'Photo', 'Post Type Singular Name', 'pixbox' ),
      'menu_name'             => __( 'Photos', 'pixbox' ),
      'name_admin_bar'        => __( 'Photo', 'pixbox' ),
      'archives'              => __( 'Photo Archives', 'pixbox' ),
      'attributes'            => __( 'Photo Attributes', 'pixbox' ),
      'parent_item_colon'     => __( 'Parent Photo:', 'pixbox' ),
      'all_items'             => __( 'All Photos', 'pixbox' ),
      'add_new_item'          => __( 'Add New Photo', 'pixbox' ),
      'new_item'              => __( 'New Photo', 'pixbox' ),
      'edit_item'             => __( 'Edit Photo', 'pixbox' ),
      'update_item'           => __( 'Update Photo', 'pixbox' ),
      'view_item'             => __( 'View Photo', 'pixbox' ),
      'view_items'            => __( 'View Photos', 'pixbox' ),
      'search_items'          => __( 'Search Photo', 'pixbox' ),
      'featured_image'        => __( 'Photo featured Image', 'pixbox' ),
      'set_featured_image'    => __( 'Set photo featured image', 'pixbox' ),
      'remove_featured_image' => __( 'Remove photo featured image', 'pixbox' ),
      'use_featured_image'    => __( 'Use as photo featured image', 'pixbox' ),
      'insert_into_item'      => __( 'Insert into photo', 'pixbox' ),
      'uploaded_to_this_item' => __( 'Uploaded to this photo', 'pixbox' ),
      'items_list'            => __( 'Photos list', 'pixbox' ),
      'items_list_navigation' => __( 'Photos list navigation', 'pixbox' ),
      'filter_items_list'     => __( 'Filter photos list', 'pixbox' ),
    ),
		'supports'              => array( 'title', 'custom-fields' ),
		'taxonomies'            => array( 'pixbox_albums' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'rewrite'               => false,
		'capability_type'       => 'page',
	));

	register_taxonomy('pixbox_albums', array( 'pixbox_photo' ), array(
		'labels'                     => array(
      'name'                       => _x( 'Albums', 'Taxonomy General Name', 'pixbox' ),
      'singular_name'              => _x( 'Album', 'Taxonomy Singular Name', 'pixbox' ),
      'menu_name'                  => __( 'Album', 'pixbox' ),
      'all_items'                  => __( 'All Albums', 'pixbox' ),
      'parent_item'                => __( 'Parent Album', 'pixbox' ),
      'parent_item_colon'          => __( 'Parent Album:', 'pixbox' ),
      'new_item_name'              => __( 'New Album Name', 'pixbox' ),
      'add_new_item'               => __( 'Add New Album', 'pixbox' ),
      'edit_item'                  => __( 'Edit Album', 'pixbox' ),
      'update_item'                => __( 'Update Album', 'pixbox' ),
      'view_item'                  => __( 'View Album', 'pixbox' ),
      'separate_items_with_commas' => __( 'Separate albums with commas', 'pixbox' ),
      'add_or_remove_items'        => __( 'Add or remove albums', 'pixbox' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'pixbox' ),
      'popular_items'              => __( 'Popular Albums', 'pixbox' ),
      'search_items'               => __( 'Search Albums', 'pixbox' ),
      'not_found'                  => __( 'Not Found', 'pixbox' ),
      'no_terms'                   => __( 'No albums', 'pixbox' ),
      'items_list'                 => __( 'Albums list', 'pixbox' ),
      'items_list_navigation'      => __( 'Albums list navigation', 'pixbox' ),
    ),
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => false,
	));
}
add_action( 'init', 'pixbox_register_post_tax', 0 );
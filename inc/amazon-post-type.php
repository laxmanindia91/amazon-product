<?php 
/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
class amazon_post_type {

	public function __construct() {
		//add_theme_support( 'post-thumbnails' );
		//add_post_type_support( 'amazon_product', 'thumbnail' );
		add_action( 'init', array( $this, 'amazon_register_post_type') );
	}
	
	
	public function amazon_register_post_type() {
		$labels = array(
			'name'                  => _x( 'Amazon Product', 'Post type general name', 'amazon-product' ),
			'singular_name'         => _x( 'Amazon Product', 'Post type singular name', 'amazon-product' ),
			'menu_name'             => _x( 'Amazon Products', 'Admin Menu text', 'amazon-product' ),
			'edit_item'             => __( 'Edit Amazon Product', 'amazon-product' ),
			'view_item'             => __( 'View Amazon Product', 'amazon-product' ),
			'featured_image'        => _x( 'Amazon Product Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'amazon-product' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'amazon-product' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'amazon-product' ),
		);
		
		//add_post_type_support( 'amazon_product', array( 'comments' ) );
		
		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'exclude_from_search' => true,
			'menu_icon'			 => 'dashicons-translation',
			'has_archive'        => false,
			'rewrite' => array(
							'feeds' => false
						),
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'revisions', 'thumbnail', 'comments'),
		);
		
		register_post_type( 'amazon_product', $args );
	
		$labels1 = array(
			'name' => _x( 'Amazon Category', 'taxonomy general name' ),
			'all_items' => __( 'All Category' ),
			'parent_item' => __( 'Parent Category' ),
			'parent_item_colon' => __( 'Parent Category:' ),
			'edit_item' => __( 'Edit Category' ), 
			'update_item' => __( 'Update Category' ),
			'add_new_item' => __( 'Add New Category' ),
			'new_item_name' => __( 'New Category Name' ),
			'menu_name' => __( 'Amazon Category' ),
		);
	// Now register the taxonomy

	  register_taxonomy('amazon_category','amazon_product', array(
		'hierarchical' => true,
		'labels' => $labels1,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => false,
		'rewrite' => false,
		'public' => false,
	  ));
	}
}
 
new amazon_post_type;
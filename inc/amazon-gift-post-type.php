<?php 
/**
 * Register a custom post type called "Gift Guide".
 */
class gift_guides_amazon_post_type {

	public function __construct() {
		add_action( 'init', array( $this, 'gift_guides_post_type') );
		//add_action( 'init', array( $this, 'gift_guide_post_category_init', 0) );
	}
	
	public function gift_guides_post_type() {
		$labels = array(
			'name'                  => _x( 'Gift Guide', 'Post type general name', 'gift-guide' ),
			'singular_name'         => _x( 'Gift Guide', 'Post type singular name', 'gift-guide' ),
			'menu_name'             => _x( 'Gift Guide', 'Admin Menu text', 'gift-guide' ),
			'edit_item'             => __( 'Edit Gift Guide', 'gift-guide' ),
			'view_item'             => __( 'View Gift Guide', 'gift-guide' ),
			'featured_image'        => _x( 'Gift Guide Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'gift-guide' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'gift-guide' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'gift-guide' ),
		);
		
		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			//'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'exclude_from_search' => true,
			'menu_icon'			 => 'dashicons-translation',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			//'supports'           => array( 'title', 'editor', 'revisions', 'thumbnail', 'comments'),
			'supports'           => array( 'title'),
			'show_in_menu'       => 'edit.php?post_type=amazon_product'
		);
		
		register_post_type( 'gift_guide', $args );
		
		add_action('admin_menu', 'gift_guide_admin_sub_menu'); 
		function gift_guide_admin_sub_menu() { 
			add_submenu_page('edit.php?post_type=gift_guide', __('Gift Guide'), __('Settings'), 'manage_options', 'Gift Guide');
		}
		
		
		
		$labels1 = array( 
			'name' => _x( 'Gift Category', 'taxonomy general name' ),
			'all_items' => __( 'All Category' ),
			'parent_item' => __( 'Parent Category' ),
			'parent_item_colon' => __( 'Parent Category:' ),
			'edit_item' => __( 'Edit Category' ), 
			'update_item' => __( 'Update Category' ),
			'add_new_item' => __( 'Add New Category' ),
			'new_item_name' => __( 'New Category Name' ),
			'menu_name' => __( 'Gift Category' ),
		);
		
	  	register_taxonomy('gift_category','post', array(
			'hierarchical' => true,
			'labels' => $labels1,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => false,
			'rewrite' => false,
		));
	}
}
 
new gift_guides_amazon_post_type;
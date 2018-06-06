<?php 
/**
* Register meta box(es) pros.
*/
class gift_guide_meta_box {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'gift_guide_register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'gift_guide_save_meta_box' ) );
		add_action( 'save_post', array( $this, 'post_save_meta_box' ) );
		
		add_action('admin_print_scripts', array($this, 'admin_scripts'));
		add_action('wp_ajax_nopriv_get_post_name',array($this, 'get_post_name') );
		add_action('wp_ajax_get_post_name', array($this, 'get_post_name') );
		add_action('admin_footer', array($this, 'drag_drop') );
	}
	
	
	
	public function admin_scripts() {
		wp_enqueue_media();
		wp_register_style('myprefix-jquery-ui','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
		wp_register_style('myprefix-jquery-ui','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
 		wp_enqueue_script( 'gift_search_autocomplete', plugin_dir_url( __FILE__ ) . 'assets/js/jquery-ui-autocomplete.js', array('jquery'), '1.0' ); 
		wp_register_script('gift_upload_img', plugins_url( 'assets/js/upload-gift-media.js', __FILE__ ) , array('jquery')); 
		wp_enqueue_script('gift_upload_img');
		wp_localize_script('gift_upload_img', 'WPOPTION', array( 'siteurl' => get_option('siteurl') ));	
		wp_localize_script( 'gift_upload_img', 'ajax_object_auto',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

	public function gift_guide_register_meta_boxes() {
		  add_meta_box('amazon-product-imag-add-list','Gift Guide options',array( $this, 'gift_guide_product_meta_box'),array('gift_guide'),'advanced','high','');
		  add_meta_box('amazon-product-default-post','Gift Guide',array( $this, 'post_product_meta_box'),array('post'),'advanced','high','');
	}
	public function drag_drop(){ ?>
		<script>
		  jQuery( function($) {
			$( "#sortable" ).sortable();
			$( "#sortable" ).disableSelection();
		  } );
	  </script>
      <style>
		  #sortable { margin: 0; padding: 0; width: 100%; }
		  #sortable li{ border-bottom: 1px solid rgb(204, 204, 204);padding: 0px 0px 10px;}
		  #sortable tr { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
		  #sortable tr span { 
		  	position: absolute; cursor: move;
		    width: 25px;
		    height: 25px;
		    margin-top: 10px; }
			 #sortable tr span img{
				max-width:100%; 
			 }
	  </style>
	<?php }
	/**
	 * Meta box display callback.
	 *add_amazon_pros_meta_box
	 * @param WP_Post $post Current post object.
	 */
	public function gift_guide_product_meta_box( $post ) {
	    if( 'gift_guide' != $post->post_type ){
			return;
		}
		$title = explode('^',get_post_meta($post->ID, 'gift_title', true));	
		$link = explode('^',get_post_meta($post->ID, 'gift_link', true));
		$img = explode('^',get_post_meta($post->ID, 'gift_image', true));
		$description = explode('^',get_post_meta($post->ID, 'gift_description', true));
		
		$gift_link_uk = explode('^',get_post_meta($post->ID, 'gift_link_uk', true));
		$gift_link_inbound = explode('^',get_post_meta($post->ID, 'gift_link_inbound', true));
		
		$args = array (
			'tinymce' => true,
			'quicktags' => true,
			'textarea_rows' => 5,
			'editor_class' => 'gift_guide_editor',
			'editor_height' => 30,
		);
		
		$gift_array = array();
		for($gift=0;$gift<count($title);$gift++){
			$gift_array[$gift][]=$title[$gift];
			$gift_array[$gift][]=$link[$gift];
			$gift_array[$gift][]=$img[$gift];
			$gift_array[$gift][]= $description[$gift];
			$gift_array[$gift][]=$gift_link_uk[$gift];
			$gift_array[$gift][]=$gift_link_inbound[$gift];
		}
		
		
		?>
        
		<table style="width:100%" id="gifttr"><!--id="sortable"-->
        
        	<tr class="ui-state-default">
              <td colspan="4" align="right"><button type="button" id="add_more_gift" class="addmore">Add more</button></td>
            </tr>
            <tr>
            <td colspan="8">
            	<ul id="sortable">
        <?php
		foreach($gift_array as $gifts){ ?>
        
        <li class="ui-state-default">
        	<table width="100%">
                <tr>
                <td width="30px"><span><img src="<?php echo plugins_url();?>/amazon-product/inc/assets/img/drag.png" class="img-responsive img"></td>
            	<td><p>Title: </p><input type="text" name="gift_title[]" id="gift_title" value="<?php echo $gifts[0]; ?>" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Gift Title"></td>
                <td><p>Link US: </p><input type="text" name="gift_link[]" id="gift_link" value="<?php echo $gifts[1]; ?>" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Gift Link"></td>
                <td><p>Link UK: </p> <input type="text" name="gift_link_uk[]" id="gift_link_uk" value="<?php echo $gifts[4]; ?>" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Link UK"></td>
                <td><p>Link Inbound: </p> <input type="text" name="gift_link_inbound[]" id="gift_link_inbound" value="<?php echo $gifts[5]; ?>" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Link Inbound"></td>
                 <td class="upload_image_button_url"><p>Image: </p><input type="text" name="gift_image[]" id="gift_image" value="<?php echo $gifts[2]; ?>" style="height:40px; width:80%;" class="amazon-form-control upload_image_button_image" placeholder="Gift Image">
                <input id="upload_image_button" class="upload_image_button" type = "button" value = "Upload"></td>
                 <td><p>Description: </p><?php //wp_editor( $gifts[3], 'gift_description-'.$description_id,$settings); ?><textarea name="gift_description[]" id="gift_description-<?php echo $description_id;?>" style="height:80px;width:100%;" class="amazon-form-control widefat" placeholder="Description"><?php echo $gifts[3]; ?></textarea></td>
                <td><a href="javascript:void(0)" class="btn btn-default remove_more_gift">Remove</a></td>
            </tr>
            </table>
            </li>
            <?php //$description_id++; 
			} ?>
            </ul>
            
            </td>
            </tr>
         </table>
         <style>
		 #add_more_gift{
			position: absolute;
			bottom: 10px;
			right: 10px;
		 }
		 a.btn-default {
			color: #333;
			background: #ddd;
			border: 1px solid #ddd;
			position: relative;
			top: 22px;
			left: 20px;
			padding: 6px 12px;
		 }
		 
		 </style>
<?php
	}
	
	
	
// MEta boxes for post_type => posts_nav_link
	public function post_product_meta_box( $post ) {
		$title = get_post_meta($post->ID, 'gift_post_title', true);	
		$link = get_post_meta($post->ID, 'gift_post_link_us', true);
		$link_uk = get_post_meta($post->ID, 'gift_post_link_uk', true);
		$img = get_post_meta($post->ID, 'gift_post_image', true);
		$inbound = get_post_meta($post->ID, 'gift_post_inbound', true);
		?>
        <table style="width:100%">
        	<tr>
            	<td><p>Title: </p><input type="text" name="gift_post_title" id="gift_post_title" value="<?php echo $title; ?>" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Title"></td>
                <td><p>Link US: </p><input type="text" name="gift_post_link_us" id="gift_post_link_us" value="<?php echo $link; ?>" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Link US"></td>
                <td><p>Link UK: </p> <input type="text" name="gift_post_link_uk" id="gift_post_link_uk" value="<?php echo $link_uk; ?>" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Link UK"></td>
                <td><p>Inbound link: </p> <input type="text" name="gift_post_inbound" id="gift_post_inbound" value="<?php echo $inbound; ?>" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Inbound link"></td>
                <td class="upload_image_button_url"><p>Image: </p><input type="text" name="gift_post_image" id="gift_post_image" value="<?php echo $img; ?>" style="height:40px; width:80%;" class="amazon-form-control upload_image_button_image" placeholder="Image"><input id="upload_image_button" class="upload_image_button" type = "button" value = "Upload">
            </tr>
           
         </table>   
         <table style="width:100%;    margin-top: 50px;">
          <tr>
            <td><?php $content = get_post_meta($post->ID, 'gift_guide_content', true);
					wp_editor(htmlspecialchars_decode($content) , 'gift_guide_content', array(
					"media_buttons" => true
					)); ?>
              </td>
            </tr>
         </table>    
<?php }
	
	/**
	 * Save meta box content for post type=> post.
	 */
	public function post_save_meta_box($post_id){
		
		if($_POST['post_type'] != 'post'){
			return $post_id;
		} 
		update_post_meta($post_id, 'gift_post_title', $_POST['gift_post_title']);
		update_post_meta($post_id, 'gift_post_link_us', $_POST['gift_post_link_us']);
		update_post_meta($post_id, 'gift_post_link_uk', $_POST['gift_post_link_uk']);
		update_post_meta($post_id, 'gift_post_inbound', $_POST['gift_post_inbound']);
		update_post_meta($post_id, 'gift_post_image', $_POST['gift_post_image']);
		update_post_meta($post_id, 'gift_guide_content', $_POST['gift_guide_content']);
	}
	
	
	/**
	 * Save meta box content for post type=> gift_guide.
	 *
	 * @param int $post_id Post ID
	 */
	public function gift_guide_save_meta_box($post_id){
		if($_POST['post_type'] != 'gift_guide'){
			return $post_id;
		}
		
		$my_cat = array('cat_name' => $_POST['post_title'], 'category_description' => '', 'taxonomy' => 'gift_category' );
		$my_cat_id = wp_insert_category($my_cat);
		
		$title = implode('^', $_POST['gift_title']); 
		update_post_meta($post_id,'gift_title',$title);
		$link = implode('^',$_POST['gift_link']);
		update_post_meta($post_id,'gift_link',$link);
		$img = implode('^',$_POST['gift_image']);
		update_post_meta($post_id,'gift_image', $img);
		$description = implode('^',$_POST['gift_description']);
		update_post_meta($post_id,'gift_description',$description);
		$gift_link_uk = implode('^',$_POST['gift_link_uk']);
		update_post_meta($post_id,'gift_link_uk',$gift_link_uk);
		$gift_link_inbound = implode('^',$_POST['gift_link_inbound']);
		update_post_meta($post_id,'gift_link_inbound',$gift_link_inbound);
		
	}
	
	public function get_post_name(){
		$posts = get_posts( 
			array(
			'posts_per_page' 	=> -1,		
			'post_type'			=> 'gift_guide',	
			's'					=>$_REQUEST['term'],
			)
		);	
		$result=array();
		foreach ( $posts as $post ){
			$result['myData'][] = array(			
				'value' => $post->ID,
				'label' => $post->post_title,
			);	
		}
		echo json_encode($result);
		die();
	}
}
new gift_guide_meta_box;
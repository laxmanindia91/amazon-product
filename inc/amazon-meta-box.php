<?php 
/**
* Register meta box(es) pros.
*/
class amazon_meta_box {

	public function __construct() {
		$amazon_access_key = get_option('amazon_access_key');
		$amazon_secret_key = get_option('amazon_secret_key');
		if(!empty($amazon_access_key) && !empty($amazon_secret_key))
		{
			add_action( 'add_meta_boxes', array( $this, 'amazon_register_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'amazon_save_meta_box' ) );
			if(isset($_GET['post']) && isset($_GET['action']) && $_GET['action']=='edit')
			{
				add_action( 'media_buttons', array( $this, 'add_my_shortcode_amazon_product') , 20 );
			}
		}
		
	}
	public function add_my_shortcode_amazon_product($post) {
		if(get_post_type( $_GET['post'] ) == 'amazon_product')
		{ 
		?>
        	<p>Amazon Product Shortcode:- [amazon-shortcode product_id="<?php echo $_GET['post']; ?>"]</p>
        <?php
		}
	}

	public function amazon_register_meta_boxes() {
		  add_meta_box('amazon-product-imag-add-list','Product Image',array( $this, 'add_amazon_product_image_meta_box'),array('amazon_product'),'advanced','high','');
		  add_meta_box('amazon-pros-add-list','PROS',array( $this, 'add_amazon_pros_meta_box'),array('amazon_product'),'advanced','high','');
		  add_meta_box('amazon-coins-add-list','CONS',array( $this, 'add_amazon_coins_meta_box'),array('amazon_product'),'advanced','high','');
		  add_meta_box('amazon-summery-add-list','SUMMARY',array( $this, 'add_amazon_summery_meta_box'),array('amazon_product'),'advanced','high','');
		  add_meta_box('amazon-link-add-list','LINK',array( $this, 'add_amazon_link_meta_box'),array('amazon_product'),'advanced','low','');
		  add_meta_box('amazon-link-add-list_UK','',array( $this, 'add_amazon_link_meta_box'),array('amazon_product'),'advanced','low','');
	}
	/**
	 * Meta box display callback.
	 *add_amazon_pros_meta_box
	 * @param WP_Post $post Current post object.
	 */
	public function add_amazon_product_image_meta_box( $post ) {
		$amazon_product_image = get_post_meta($post->ID,'amazon_product_image',true);
		$amazon_insertPrice = get_post_meta($post->ID,'amazon_insertPrice',true);
		$amazon_searchurl = get_post_meta($post->ID,'amazon_searchurl',true);
		$amazon_img_searchurl = get_post_meta($post->ID,'amazon_img_searchurl',true);
		$amazon_searchtitle = get_post_meta($post->ID,'amazon_searchtitle',true);
		$amazon_search_offer_price = get_post_meta($post->ID,'amazon_search_offer_price',true);
		$amazon_offer_price_saved = get_post_meta($post->ID,'amazon_offer_price_saved',true);
		$amazon_top_pick = get_post_meta($post->ID,'amazon_top_pick',true);
		$amazon_image_alt_tag = get_post_meta($post->ID,'amazon_image_alt_tag',true);
		$amazon_age_range = get_post_meta($post->ID,'amazon_age_range',true);
		?>
	
		<h2>Amazon Product</h2>
		<div class="amazon_product_add">
		  
		  <p>Our Top Pick title</p>
		  <input type="text" name="amazon_top_pick" id="amazon_top_pick" value="<?php echo $amazon_top_pick; ?>" style="height:40px; width:97%;" class="amazon-form-control" placeholder="Our Top Pick title">
		
		  <img src="<?php echo $amazon_product_image; ?>" style="width:200px; height:150px;display:<?php if(!empty($amazon_product_image)){ echo 'block';}else{echo 'none';} ?>" class="amazon_product_image_show">
		  <input type="hidden" name="amazon_product_image" id="amazon_product_image" value="<?php echo $amazon_product_image; ?>">
		  
		  <p>Image Alt Tag</p>
		  <input type="text" name="amazon_image_alt_tag" id="amazon_image_alt_tag" value="<?php echo $amazon_image_alt_tag; ?>" style="height:40px; width:97%;" class="amazon-form-control" placeholder="Alt tag for image">
		  
		  <p>
		  <label>Product Price:- <span id="amazon_insertPrice_show"><?php echo $amazon_insertPrice?$amazon_insertPrice:''; ?></span></label>
		  <input type="hidden" name="amazon_insertPrice" id="amazon_insertPrice" value="<?php echo $amazon_insertPrice ?>" style="height:40px; width:97%;" readonly class="amazon-form-control">
		  </p>
		  <p>Product Search Url</p>
		  <input type="text" name="amazon_searchurl" id="amazon_searchurl" value="<?php echo $amazon_searchurl ?>" style="height:40px; width:97%;" readonly class="amazon-form-control" placeholder="Search Url">
		  
		  <input type="hidden" name="amazon_img_searchurl" id="amazon_img_searchurl" value="<?php if(isset($amazon_img_searchurl)){echo $amazon_img_searchurl; } ?>">
		  
		  <p>Product Brand</p>
		  <input type="text" name="amazon_searchtitle" id="amazon_searchtitle" value="<?php echo $amazon_searchtitle ?>" style="height:40px; width:97%;" readonly class="amazon-form-control" placeholder="Search Title">
		  
		 <p>Product Offer Price</p>
		  <input type="text" name="amazon_search_offer_price" id="amazon_search_offer_price" value="<?php echo $amazon_search_offer_price; ?>" style="height:40px; width:97%;" readonly class="amazon-form-control" placeholder="Amazon offer price">
		 <p>Product Price Saved</p>
		  <input type="text" name="amazon_offer_price_saved" id="amazon_offer_price_saved" value="<?php echo $amazon_offer_price_saved; ?>" style="height:40px; width:97%;" readonly class="amazon-form-control" placeholder="Amazon price saved">
		  
		  <p>Age range:</p>
		  <input type="text" name="amazon_age_range" id="amazon_age_range" value="<?php echo $amazon_age_range; ?>" style="height:40px; width:97%;" class="amazon-form-control" placeholder="Amazon Age range...">
		  
		</div>
<?php
	}
 
	/**
	 * Meta box display callback.
	 *add_amazon_pros_meta_box
	 * @param WP_Post $post Current post object.
	 */
	public function add_amazon_pros_meta_box( $post ) {
	$amazon_pros = get_post_meta($post->ID,'amazon_pros',true);
	?>

    <h2>Pros Info</h2>
    <div class="amazon_product_add">
      <div class="amazon_pros_section ">
        <?php
		if(!empty($amazon_pros))
		{
		  $pr = 0;
		   foreach($amazon_pros as $pros) { ?>
			<div class="remove_section"><br>
			  <input type="text" name="amazon_pros[]" value="<?php echo $pros; ?>" style="height:40px; width:97%;" class="amazon-form-control" id="" placeholder="Add Pros">
			  <?php if($pr>0) { ?>
			  <a href="javascript:void(0)" class="remove_field"><span class="dashicons dashicons-no"></span></a>
			  <?php } ?>
			</div>
			<?php $pr++; } 
		}else
		{
			?>
            <div class="remove_section"><br>
			  <input type="text" name="amazon_pros[]" value="<?php echo $pros; ?>" style="height:40px; width:97%;" class="amazon-form-control" id="" placeholder="Add Pros">
			  	
			</div>
            <?php
		}?>
      </div>
      <br>
  <a href="javascript:void(0);" id="add_pros" class="add_button">Add More Pros</a> </div>
<?php
	}
 
	/**
	 * Meta box display callback.
	 *add_amazon_coins_meta_box
	 * @param WP_Post $post Current post object.
	 */
	public function add_amazon_coins_meta_box( $post ) {
		$amazon_cons = get_post_meta($post->ID,'amazon_cons',true);
		?>
    <h2>Cons Info</h2>
    <div class="amazon_product_add">
      <div class="amazon_cons_section">
      <?php
	  if(!empty($amazon_cons))
	  {
		  $cn = 0;
			foreach($amazon_cons as $cons)
			{
		   ?>
			<div class="remove_section"><br>
			  <input type="text" name="amazon_cons[]" id="amazon_cons" value="<?php echo $cons ?>" style="height:40px; width:97%;" class="amazon-form-control" placeholder="Add Cons">
			  <?php if($cn>0) { ?>
			  <a href="javascript:void(0)" class="remove_field"><span class="dashicons dashicons-no"></span></a>
			  <?php } ?>
			</div>
			<?php $cn++; } 
		}else
		{
			?>
            <div class="remove_section"><br>
              <input type="text" name="amazon_cons[]" id="amazon_cons" value="<?php echo $cons ?>" style="height:40px; width:97%;" class="amazon-form-control" placeholder="Add Cons">
              
            </div>
            <?php
		}?>
  </div>
  <br>
  <a href="javascript:void(0);" id="add_cons" class="add_button">Add More Cons</a> </div>
<?php
	}
 
 
	 /**
	 * Meta box display callback .
	 *add_amazon_summery_meta_box
	 * @param WP_Post $post Current post object.
	 */
	public function add_amazon_summery_meta_box( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
		$args = array (
			'tinymce' => true,
			'quicktags' => true,
		);
		echo '<h2>Summary Info</h2>';
		$amazon_summary = get_post_meta($post->ID,'amazon_summary',true);
		wp_editor( $amazon_summary, 'amazon_summary', $args);
		$amazon_like_about = get_post_meta($post->ID,'amazon_like_about',true);
		?>
		<style>
			#amazon-summery-add-list #wp-amazon_summary-media-buttons .amazonmedia{ display: none}
			#amazon-summery-add-list #wp-amazon_summary-media-buttons .amazonmedia_uk{display: none}
			#amazon-summery-add-list #wp-amazon_summary-media-buttons p{display: none}
		</style>
		
		<!--<h2>Summary Info</h2>
		<div class="amazon_product_add">
		  <textarea name="amazon_summary" id="amazon_summary" class="amazon-form-control widefat" rows="5"  placeholder="Add Summary"><?php echo $amazon_summary; ?></textarea>
		</div>-->
		
		<h2>What We Like About It</h2>
		<div class="amazon_product_add">
		  <textarea name="amazon_like_about" id="amazon_like_about" class="amazon-form-control widefat" rows="5"  placeholder="Add What We Like About"><?php echo $amazon_like_about; ?></textarea>
		  <span style="font-size:12px;color:#ccc">Optional</span>
		</div>
	
<?php }
 
	 /**
	 * Meta box display callback .
	 *add_amazon_summery_meta_box
	 * @param WP_Post $post Current post object.
	 */
	public function add_amazon_link_meta_box( $post ) {
		$amazon_link = get_post_meta($post->ID,'amazon_link',true);
		$amazon_link_uk = get_post_meta($post->ID,'amazon_link_uk',true);
		$amazon_link_disable = get_post_meta($post->ID,'amazon_link_disable',true);
		?>
		<h2>Disable view more link?
		<div class="amazon_product_add">
		  <input type="checkbox" name="amazon_link_disable" value="1" <?php if($amazon_link_disable == 1){ echo "checked";} ?> class="amazon-form-control widefat" id="amazon_link_disable">
		</div></h2>
		
		<h2>Amozon US Link</h2>
		<div class="amazon_product_add">
		  <input type="text" name="amazon_link" value="<?php echo $amazon_link ?>" class="amazon-form-control widefat" style="height:50px" id="amazon_link">
		</div>
		<h2>Amozon UK Link</h2>
		<div class="amazon_product_add">
		  <input type="text" name="amazon_link_uk" value="<?php echo $amazon_link_uk; //echo $newurl = str_replace('com', 'co.uk', $amazon_link); ?>" class="amazon-form-control widefat" style="height:50px" id="amazon_link_uk">
		</div>
<?php }
 
	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID
	 */
	public function amazon_save_meta_box($post_id){
		if($_POST['post_type']!='amazon_product'){
			return $post_id;
		}
		
		$terms = get_the_terms( $post_id, 'amazon_category' );
		
		foreach($terms as $term){
			$getTerm[] = $term->name;
		}
		$getTerm = implode(' ',$getTerm);
		
		$url_search = "https://www.amazon.com/s/?url=search-alias&field-keywords=". $getTerm;
		
		update_post_meta($post_id,'amazon_insertPrice',$_POST['amazon_insertPrice']);
		update_post_meta($post_id,'amazon_product_image',$_POST['amazon_product_image']);
		if(!empty($getTerm)){
			update_post_meta($post_id,'amazon_searchurl', $url_search);
		}else{
			update_post_meta($post_id,'amazon_searchurl',$_POST['amazon_searchurl']);
		}
		$url_search = "https://www.amazon.com/s/?url=search-alias&field-keywords=". get_the_title();
		
		update_post_meta($post_id,'amazon_img_searchurl', $url_search);
		update_post_meta($post_id,'amazon_searchtitle',$_POST['amazon_searchtitle']);
		update_post_meta($post_id,'amazon_search_offer_price',$_POST['amazon_search_offer_price']);
		update_post_meta($post_id,'amazon_offer_price_saved',$_POST['amazon_offer_price_saved']);
		
		update_post_meta($post_id,'amazon_pros',$_POST['amazon_pros']);
		update_post_meta($post_id,'amazon_cons',$_POST['amazon_cons']);
		if(isset($_POST['amazon_summary'])){
			update_post_meta($post_id,'amazon_summary',$_POST['amazon_summary']);
		}
		update_post_meta($post_id,'amazon_link',$_POST['amazon_link']);
		update_post_meta($post_id,'amazon_link_uk',$_POST['amazon_link_uk']);
		
		update_post_meta($post_id,'amazon_top_pick',$_POST['amazon_top_pick']);
		update_post_meta($post_id,'amazon_like_about',$_POST['amazon_like_about']);
		update_post_meta($post_id,'amazon_image_alt_tag',$_POST['amazon_image_alt_tag']);
		update_post_meta($post_id,'amazon_link_disable',$_POST['amazon_link_disable']);
		update_post_meta($post_id,'amazon_age_range',$_POST['amazon_age_range']);
	}

}
new amazon_meta_box;

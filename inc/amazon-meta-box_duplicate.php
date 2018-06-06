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
		$amazon_searchtitle = get_post_meta($post->ID,'amazon_searchtitle',true);
		?>
	
		<h2>Amazon Product</h2>
		<div class="amazon_product_add">
		<img src="<?php echo $amazon_product_image; ?>" style="width:200px; height:150px;display:<?php if(!empty($amazon_product_image)){ echo 'block';}else{echo 'none';} ?>" class="amazon_product_image_show">
		  <input type="hidden" name="amazon_product_image" id="amazon_product_image" value="<?php echo $amazon_product_image; ?>">
		  <p>
		  <label>Product Price:- <span id="amazon_insertPrice_show"><?php echo $amazon_insertPrice?$amazon_insertPrice:''; ?></span></label>
          <!--p>UK Price: <?php $temp_price = str_replace('$', '', $amazon_insertPrice); $uk_product_price = $temp_price/1.27847483067;  echo '&pound;'; echo $uk_converted_price = bcdiv($uk_product_price, 1, 2); ?></p-->
		  
		  <input type="hidden" name="amazon_insertPrice" id="amazon_insertPrice" value="<?php echo $amazon_insertPrice ?>" style="height:40px; width:97%;" readonly class="amazon-form-control">
		  </p>
		  <p>Product Search Url</p>
		  <input type="text" name="amazon_searchurl" id="amazon_searchurl" value="<?php echo $amazon_searchurl ?>" style="height:40px; width:97%;" readonly class="amazon-form-control" placeholder="Search US Url">
		  <!--p>Product Search Url for UK</p-->
		  <!--input type="text" name="amazon_searchurl_uk" id="amazon_searchurl_uk" value="<?php echo str_replace('com', 'co.uk', $amazon_searchurl); ?>" style="height:40px; width:97%;" readonly class="amazon-form-control" placeholder="Search UK Url"-->

		  <p>Product Brand</p>
		  <input type="text" name="amazon_searchtitle" id="amazon_searchtitle" value="<?php echo $amazon_searchtitle ?>" style="height:40px; width:97%;" readonly class="amazon-form-control" placeholder="Search Title">
		  
		 
		 
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
		$amazon_summary = get_post_meta($post->ID,'amazon_summary',true);
		?>
    <h2>Summary Info</h2>
    <div class="amazon_product_add">
      <textarea name="amazon_summary" id="amazon_summary" class="amazon-form-control widefat" rows="5"  placeholder="Add Summary"><?php echo $amazon_summary; ?></textarea>
    </div>
<?php
	}
 
 
 
	 /**
	 * Meta box display callback .
	 *add_amazon_summery_meta_box
	 * @param WP_Post $post Current post object.
	 */
	public function add_amazon_link_meta_box( $post ) {
		$amazon_link = get_post_meta($post->ID,'amazon_link',true);
		
		$uk_url = str_replace('com', 'co.uk', $amazon_link);
		?>
<h2>Amozon US Link</h2>
<div class="amazon_product_add">
  <input type="text" name="amazon_link" value="<?php echo $amazon_link ?>" class="amazon-form-control widefat" style="height:50px" id="amazon_link">
</div>
<h2>Amozon UK Link</h2>
<div class="amazon_product_add">
  <input type="text" name="amazon_link_uk" value="<?php echo $uk_url ?>" class="amazon-form-control widefat" style="height:50px" id="amazon_link">
</div>

<?php
	}

 
	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID
	 */
	public function amazon_save_meta_box( $post_id) {
		if($_POST['post_type']!='amazon_product')
		{
			return $post_id;
		}
		update_post_meta($post_id,'amazon_insertPrice',$_POST['amazon_insertPrice']);
		update_post_meta($post_id,'amazon_product_image',$_POST['amazon_product_image']);
		update_post_meta($post_id,'amazon_searchurl',$_POST['amazon_searchurl']);
		//update_post_meta($post_id,'amazon_searchurl',$_POST['amazon_searchurl_uk']);
		update_post_meta($post_id,'amazon_searchtitle',$_POST['amazon_searchtitle']);
		update_post_meta($post_id,'amazon_pros',$_POST['amazon_pros']);
		update_post_meta($post_id,'amazon_cons',$_POST['amazon_cons']);
		update_post_meta($post_id,'amazon_summary',$_POST['amazon_summary']);
		update_post_meta($post_id,'amazon_link',$_POST['amazon_link']);
		//update_post_meta($post_id,'amazon_link',$_POST['amazon_link_uk']);
	}

}
new amazon_meta_box;

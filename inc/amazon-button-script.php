<?php 
/*********add wp style and script for backend*******************/

/**
 * Register and enqueue a custom stylesheet in the WordPress admin.
 */
 class amazon_button_script {

	public function __construct() {
		add_action( 'media_buttons', array( $this, 'add_my_media_button') , 15 );
		add_action('admin_footer', array( $this, 'amazon_api_get_model' ) );
	}

	public function add_my_media_button($post_type) {
		$secret_key = get_option('amazon_secret_key ');
		$access_key = get_option('amazon_access_key');
			if( get_post_type( $post_type->ID ) == 'amazon_product' && !empty($secret_key) && !empty($access_key)):
				echo '<button type="button" class="button amazonmedia" data-toggle="modal" data-target="#myModal">Add US Product</button>';
				echo '<button type="button" class="button amazonmedia_uk" data-toggle="modal" data-target="#myModaluk">Add UK Product</button>';
			else:
				?>

<button type="button" class="button" onClick="alert('Please enter secret key and access key. Go to Amazon Setting to define.');">Add Product</button>
<?php
			endif;		
	}
	public function amazon_api_get_model()
	{
		?>
<!-- US Products modal -->
<div class="media-modal wp-core-ui" id="amazon_model_popup" style="display:none;">
  <button type="button" class="button-link close_amazon_model media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>
  <div class="media-modal-content">
  <div class="load-spinner"><img src="http://www.cevaex.com/laxman/wp-content/uploads/2017/04/page-loader.gif"></div>
    <div class="media-frame mode-select wp-core-ui hide-toolbar hide-router" id="__wp-uploader-id-0">
      <div class="media-frame-title">
        <h1>Amazon<span class="dashicons dashicons-arrow-down"></span></h1>
      </div>
      <div class="media-frame-content">
        <div class="wrap" style="display: block;">
          <div class="amazon-popup-state">
            <h3>Search Options</h3>
            <form action="admin-ajax.php" id="amazon-popup-form" method="post">
              <input type="hidden" name="action" value="amazon_query_products">
              <input type="hidden" name="page" id="pages_show" value="1">
              <table class="form-table">
                <tbody>
                  <tr>
                    <th scope="row"><label for="amazon-search-keywords">Search Keywords or ASIN</label></th>
                    <td><input type="text" class="large-text" id="amazon-search-keywords" name="keywords" value="">
                      <p class="description amazon-search-result-error" style="display: none;"></p></td>
                  </tr>
                  <tr>
                    <th scope="row"><label for="amazon-search-locale">Search Locale</label></th>
                    <td><select id="amazon-search-locale" name="locale">
                        <option value="US">United States</option>
                        <option value="BR">Brazil</option>
                        <option value="CA">Canada</option>
                        <option value="CN">China</option>
                        <option value="FR">France</option>
                        <option value="DE">Germany</option>
                        <option value="IT">Italy</option>
                        <option value="IN">India</option>
                        <option value="JP">Japan</option>
                        <option value="ES">Spain</option>
                        <option value="UK">United Kingdom</option>
                      </select></td>
                  </tr>
                  <tr >
                    <th scope="row"><label for="amazon-search-search-index">Search Index</label></th>
                    <td><select id="amazon-search-search-index" name="index">
                        <option>All</option>
                        <option>Apparel</option>
                        <option>Appliances</option>
                        <option>Arts And Crafts</option>
                        <option>Automotive</option>
                        <option>Baby</option>
                        <option>Beauty</option>
                        <option>Blended</option>
                        <option>Books</option>
                        <option>Classical</option>
                        <option>Collectibles</option>
                        <option>DVD</option>
                        <option>Digital Music</option>
                        <option>Electronics</option>
                        <option>Fashion</option>
                        <option>Fashion Baby</option>
                        <option>Fashion Boys</option>
                        <option>Fashion Girls</option>
                        <option>Fashion Men</option>
                        <option>Fashion Women</option>
                        <option>Gift Cards</option>
                        <option>Gourmet Food</option>
                        <option>Grocery</option>
                        <option>Health Personal Care</option>
                        <option>Home Garden</option>
                        <option>Industrial</option>
                        <option>Jewelry</option>
                        <option>Kindle Store</option>
                        <option>Kitchen</option>
                        <option>Lawn And Garden</option>
                        <option>Luggage</option>
                        <option>MP3Downloads</option>
                        <option>Magazines</option>
                        <option>Miscellaneous</option>
                        <option>Mobile Apps</option>
                        <option>Music</option>
                        <option>Music Tracks</option>
                        <option>Musical Instruments</option>
                        <option>Office Products</option>
                        <option>Outdoor Living</option>
                        <option>PC Hardware</option>
                        <option>Pet Supplies</option>
                        <option>Photo</option>
                        <option>Shoes</option>
                        <option>Software</option>
                        <option>Sporting Goods</option>
                        <option>Tools</option>
                        <option>Toys</option>
                        <option>Unbox Video</option>
                        <option>VHS</option>
                        <option>Video</option>
                        <option>Video Games</option>
                        <option>Watches</option>
                        <option>Wireless</option>
                        <option>Wireless Accessories</option>
                      </select></td>
                  </tr>
                  <tr style="display: none;">
                    <th scope="row"><label for="amazon-search-minimum-price">Minimum Price</label></th>
                    <td><input type="text" class="code regular-text" id="amazon-search-minimum-price" name="priceMin">
                      <p class="description">This value will be interpreted in the context of the locale you are searching within (e.g. $ in the United States)</p></td>
                  </tr>
                  <tr style="display: none;">
                    <th scope="row"><label for="amazon-search-maximum-price">Maximum Price</label></th>
                    <td><input type="text" class="code regular-text" id="amazon-search-maximum-price" name="priceMax">
                      <p class="description">This value will be interpreted in the context of the locale you are searching within (e.g. $ in the United States)</p></td>
                  </tr>
                  <tr class="show_on" style="display: none;">
                    <th scope="row"><label for="easyazon-search-search-sort">Sort</label></th>
                    <td><select id="easyazon-search-search-sort" name="sort">
                        <option></option>
                        <option>Featured items</option>
                        <option>Highest to lowest ratings in customer reviews</option>
                        <option>Price: high to low</option>
                        <option>Price: low to high</option>
                        <option>Relevance</option>
                        <option>Review rank: high to low</option>
                      </select></td>
                  </tr>
                </tbody>
              </table>
              <button class="button button-primary button-large" type="button" id="amazon-search-submit">Search</button>
            </form>
            <div class="amazon-search-results" style="display: none;">
            <div class="tablenav bottom">
                <div class="tablenav-pages"> <span class="displaying-num" style="display: block; float: left; margin-top:0px; font-size: 16px;"></span>
                  <div class="pagination" style="    display: inline-block;    margin-top: -16px;"> </div>
                </div>
              </div>
              <table class="widefat fixed">
                <thead>
                  <tr>
                    <th class="amazon-search-result-column-image" scope="col">Image</th>
                    <th class="amazon-search-result-column-title" scope="col">Title</th>
                    <th class="amazon-search-result-column-insert" scope="col">Insert</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th class="amazon-search-result-column-image" scope="col">Image</th>
                    <th class="amazon-search-result-column-title" scope="col">Title</th>
                    <th class="amazon-search-result-column-insert" scope="col">Insert</th>
                  </tr>
                </tfoot>
                <tbody id="append_product">
                </tbody>
              </table>
              <div class="tablenav bottom">
                <div class="tablenav-pages"> <span class="displaying-num-uk" style="display: block; float: left; margin-top: 17px; font-size: 16px;"></span>
                  <div class="pagination" style="    display: inline-block;"> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- UK Products modal -->
<div class="media-modal wp-core-ui" id="amazon_model_popup_uk" style="display:none;">
  <button type="button" class="button-link close_amazon_model_uk media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>
  <div class="media-modal-content">
  <div class="load-spinner"><img src="http://www.cevaex.com/laxman/wp-content/uploads/2017/04/page-loader.gif"></div>
    <div class="media-frame mode-select wp-core-ui hide-toolbar hide-router" id="__wp-uploader-id-0">
      <div class="media-frame-title">
        <h1>Amazon<span class="dashicons dashicons-arrow-down"></span></h1>
      </div>
      <div class="media-frame-content">
        <div class="wrap" style="display: block;">
          <div class="amazon-popup-state_uk">
            <h3>Search Options</h3>
            <form action="admin-ajax.php" id="amazon-popup-form_uk" method="post">
            <!--input type="hidden" name="action" value="amazon_query_products_uk"-->
              <input type="hidden" name="action" value="amazon_query_products">
              <input type="hidden" name="page" id="pages_show_uk" value="1">
              <table class="form-table">
                <tbody>
                  <tr>
                    <th scope="row"><label for="amazon-search-keywords-uk">Search Keywords or ASIN</label></th>
                    <td><input type="text" class="large-text" id="amazon-search-keywords" name="keywords" value="">
                      <p class="description amazon-search-result-error" style="display: none;"></p></td>
                  </tr>
                  <tr>
                    <th scope="row"><label for="amazon-search-locale">Search Locale</label></th>
                    <td><select id="amazon-search-locale" name="locale">
                        <option value="US">United States</option>
                        <option value="BR">Brazil</option>
                        <option value="CA">Canada</option>
                        <option value="CN">China</option>
                        <option value="FR">France</option>
                        <option value="DE">Germany</option>
                        <option value="IT">Italy</option>
                        <option value="IN">India</option>
                        <option value="JP">Japan</option>
                        <option value="ES">Spain</option>
                        <option value="UK" selected>United Kingdom</option>
                      </select></td>
                  </tr>
                  <tr >
                    <th scope="row"><label for="amazon-search-search-index-uk">Search Index</label></th>
                    <td><select id="amazon-search-search-index-uk" name="index">
                        <option>All</option>
                        <option>Apparel</option>
                        <option>Appliances</option>
                        <option>Arts And Crafts</option>
                        <option>Automotive</option>
                        <option>Baby</option>
                        <option>Beauty</option>
                        <option>Blended</option>
                        <option>Books</option>
                        <option>Classical</option>
                        <option>Collectibles</option>
                        <option>DVD</option>
                        <option>Digital Music</option>
                        <option>Electronics</option>
                        <option>Fashion</option>
                        <option>Fashion Baby</option>
                        <option>Fashion Boys</option>
                        <option>Fashion Girls</option>
                        <option>Fashion Men</option>
                        <option>Fashion Women</option>
                        <option>Gift Cards</option>
                        <option>Gourmet Food</option>
                        <option>Grocery</option>
                        <option>Health Personal Care</option>
                        <option>Home Garden</option>
                        <option>Industrial</option>
                        <option>Jewelry</option>
                        <option>Kindle Store</option>
                        <option>Kitchen</option>
                        <option>Lawn And Garden</option>
                        <option>Luggage</option>
                        <option>MP3Downloads</option>
                        <option>Magazines</option>
                        <option>Miscellaneous</option>
                        <option>Mobile Apps</option>
                        <option>Music</option>
                        <option>Music Tracks</option>
                        <option>Musical Instruments</option>
                        <option>Office Products</option>
                        <option>Outdoor Living</option>
                        <option>PC Hardware</option>
                        <option>Pet Supplies</option>
                        <option>Photo</option>
                        <option>Shoes</option>
                        <option>Software</option>
                        <option>Sporting Goods</option>
                        <option>Tools</option>
                        <option>Toys</option>
                        <option>Unbox Video</option>
                        <option>VHS</option>
                        <option>Video</option>
                        <option>Video Games</option>
                        <option>Watches</option>
                        <option>Wireless</option>
                        <option>Wireless Accessories</option>
                      </select></td>
                  </tr>
                  <tr style="display: none;">
                    <th scope="row"><label for="amazon-search-minimum-price">Minimum Price</label></th>
                    <td><input type="text" class="code regular-text" id="amazon-search-minimum-price" name="priceMin">
                      <p class="description">This value will be interpreted in the context of the locale you are searching within (e.g. $ in the United States)</p></td>
                  </tr>
                  <tr style="display: none;">
                    <th scope="row"><label for="amazon-search-maximum-price">Maximum Price</label></th>
                    <td><input type="text" class="code regular-text" id="amazon-search-maximum-price" name="priceMax">
                      <p class="description">This value will be interpreted in the context of the locale you are searching within (e.g. $ in the United States)</p></td>
                  </tr>
                  <tr class="show_on" style="display: none;">
                    <th scope="row"><label for="easyazon-search-search-sort">Sort</label></th>
                    <td><select id="easyazon-search-search-sort" name="sort">
                        <option></option>
                        <option>Featured items</option>
                        <option>Highest to lowest ratings in customer reviews</option>
                        <option>Price: high to low</option>
                        <option>Price: low to high</option>
                        <option>Relevance</option>
                        <option>Review rank: high to low</option>
                      </select></td>
                  </tr>
                </tbody>
              </table>
              <button class="button button-primary button-large" type="button" id="amazon-search-submit-uk">Search</button>
            </form>
            <div class="amazon-search-results-uk" style="display: none;">
            <div class="tablenav bottom">
                <div class="tablenav-pages"> <span class="displaying-num" style="display: block; float: left; margin-top:0px; font-size: 16px;"></span>
                  <div class="pagination" style="    display: inline-block;    margin-top: -16px;"> </div>
                </div>
              </div>
              <table class="widefat fixed">
                <thead>
                  <tr>
                    <th class="amazon-search-result-column-image" scope="col">Image</th>
                    <th class="amazon-search-result-column-title" scope="col">Title</th>
                    <th class="amazon-search-result-column-insert" scope="col">Insert</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th class="amazon-search-result-column-image" scope="col">Image</th>
                    <th class="amazon-search-result-column-title" scope="col">Title</th>
                    <th class="amazon-search-result-column-insert" scope="col">Insert</th>
                  </tr>
                </tfoot>
                <tbody id="append_product_uk">
                </tbody>
              </table>
              <div class="tablenav bottom">
                <div class="tablenav-pages"> <span class="displaying-num" style="display: block; float: left; margin-top: 17px; font-size: 16px;"></span>
                  <div class="pagination" style="    display: inline-block;"> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
	}

 }
new amazon_button_script;





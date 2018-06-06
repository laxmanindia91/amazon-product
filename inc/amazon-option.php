<?php 
class amazon_options_page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'amazon_products_settings') );
	}

	public function admin_menu() {
		add_submenu_page('edit.php?post_type=amazon_product', 'Amazon Product Options',  'Amazon Settings', 'manage_options','amazon_option', array( $this,  'amazon_product_options_display' ) );
		
	}

	
	/**
	 * Register settings and add settings sections and fields to the admin page.
	 */
	 function amazon_products_settings() {
	//register our settings
		register_setting( 'amazon_products_key', 'amazon_access_key' );
		register_setting( 'amazon_products_key', 'amazon_secret_key' );
		
		register_setting( 'amazon_associates', 'amazon_us' );
		register_setting( 'amazon_associates', 'amazon_uk' );
		
		register_setting( 'amazon_related', 'amazon_related_post_per_page' );
	}
	/**
	 * Settings fields callbacks.
	 */
	public function amazon_product_options_display(){ ?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<div id="exTab2" class="container-fluid" style="margin-top: 30px;">	
			<ul class="nav nav-tabs">
				<li class="active"><a href="#credentials" data-toggle="tab" style="font-weight: 600;font-size: 20px;">Credentials</a></li>
				<li><a href="#associates" data-toggle="tab" style="font-weight: 600;font-size: 20px;">Amazon Associates</a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="credentials">
					<div class="wrap">
						<h1>Credentials</h1>
						
						<form method="post" action="options.php">
							<?php settings_fields( 'amazon_products_key' ); ?>
							<?php do_settings_sections( 'amazon_products_key' ); ?>
							<table class="form-table">
								<tr valign="top">
								<th scope="row">Amazon Access Key</th>
								<td><input type="text" name="amazon_access_key" style="width: 50%;" value="<?php echo esc_attr( get_option('amazon_access_key') ); ?>" /></td>
								</tr>
								 
								<tr valign="top">
								<th scope="row">Amazon Secret Key</th>
								<td><input type="text" name="amazon_secret_key" style="width: 50%;" value="<?php echo esc_attr( get_option('amazon_secret_key') ); ?>" /></td>
								</tr>
							</table>
							
							<?php submit_button(); ?>
						
						</form>
					</div>
				</div>
				<div class="tab-pane" id="associates">
					
					<form method="post" action="options.php">
						<?php settings_fields( 'amazon_associates' ); ?>
						<?php do_settings_sections( 'amazon_associates' ); ?>
							<table class="form-table">
								<tr valign="top">
								<th scope="row">United States</th>
								<td><input type="text" name="amazon_us" style="width: 50%;" value="<?php echo esc_attr( get_option('amazon_us') ); ?>" />
									<p class="description">(i.e. yourtag-21)</p>
								</td>
								</tr>
								 
								<tr valign="top">
								<th scope="row">United Kingdom</th>
									<td><input type="text" name="amazon_uk" style="width: 50%;" value="<?php echo esc_attr( get_option('amazon_uk') ); ?>" />
									<p class="description">(i.e. yourtag-21)</p>
								</td>
								</tr>
							</table>
							
							<?php submit_button(); ?>
						
						</form>
					
				</div>
				
			</div>
		</div>

		
		
		
        <?php
	}
	
}

new amazon_options_page;

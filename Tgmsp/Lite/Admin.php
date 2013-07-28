<?php
/**
 * Admin class for Soliloquy Lite.
 *
 * @since 1.0.0
 *
 * @package	Soliloquy Lite
 * @author	Thomas Griffin
 */
class Tgmsp_Lite_Admin {

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Holds a copy of the upgrade page slug.
	 *
	 * @since 1.5.0
	 *
	 * @var bool|string
	 */
	public $upgrade_slug = false;

	/**
	 * Holds the version of the plugin for cache busting.
	 *
	 * @since 1.5.0
	 *
	 * @var bool|string
	 */
	public $version = '1.5.0';

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		self::$instance = $this;

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'add_meta_boxes', array( $this, 'remove_seo_support' ), 99 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

	}

	/**
	 * Deactivate Soliloquy Lite if the full version is installed and active.
	 *
	 * @since 1.0.0
	 */
	public function admin_init() {

		/** If the main Soliloquy plugin exists, update default post meta fields and deactivate ourself in favor of the full version */
		if ( class_exists( 'Tgmsp', false ) ) {
			/** Get current sliders and update default post meta fields */
			$sliders = get_posts( array( 'post_type' => 'soliloquy', 'posts_per_page' => -1 ) );
			if ( $sliders ) {
				foreach ( (array) $sliders as $slider ) {
					/** Grab Soliloquy meta from the slider */
					$meta = get_post_meta( $slider->ID, '_soliloquy_settings', true );

					/** Set default post meta fields */
					if ( empty( $meta['default'] ) ) 	$meta['default'] 	= 'default';
					if ( empty( $meta['custom'] ) ) 	$meta['custom'] 	= false;
					if ( empty( $meta['animate'] ) ) 	$meta['animate'] 	= 1;
					if ( empty( $meta['video'] ) ) 		$meta['video'] 		= 1;
					if ( empty( $meta['navigation'] ) ) $meta['navigation'] = 1;
					if ( empty( $meta['control'] ) ) 	$meta['control'] 	= 1;
					if ( empty( $meta['keyboard'] ) ) 	$meta['keyboard'] 	= 1;
					if ( empty( $meta['number'] ) ) 	$meta['number'] 	= 0;
					if ( empty( $meta['loop'] ) ) 		$meta['loop'] 		= 1;
					if ( empty( $meta['action'] ) ) 	$meta['action'] 	= 1;
					if ( empty( $meta['css'] ) ) 		$meta['css'] 		= 1;
					if ( empty( $meta['animate'] ) ) 	$meta['animate'] 	= 1;
					if ( empty( $meta['smooth'] ) ) 	$meta['smooth'] 	= 1;
					if ( empty( $meta['touch'] ) ) 		$meta['touch'] 		= 1;
					if ( empty( $meta['delay'] ) ) 		$meta['delay'] 		= 0;
					if ( empty( $meta['type'] ) ) 		$meta['type'] 		= 'default';
					if ( empty( $meta['preloader'] ) )  $meta['preloader']  = 0;

					/** Update post meta for the slider */
					update_post_meta( $slider->ID, '_soliloquy_settings', $meta );
				}
			}

			/** Deactive the plugin */
			deactivate_plugins( Tgmsp_Lite::get_file() );
		}

	}

	/**
	 * Adds a custom upgrade item to the Soliloquy post type menu.
	 *
	 * @since 1.0.0
	 */
	public function admin_menu() {

		$this->upgrade_slug = add_submenu_page( 'edit.php?post_type=soliloquy', __( 'Get Immediate Access to the Pro Version of Soliloquy!', 'soliloquy-lite' ), __( 'Instant Upgrade', 'soliloquy-lite' ), 'manage_options', 'soliloquy-lite-upgrade', array( $this, 'upgrade_page' ) );

		if ( $this->upgrade_slug )
			add_action( 'load-' . $this->upgrade_slug, array( $this, 'upgrade_assets' ) );

	}

	/**
	 * Outputs content on the upgrade page.
	 *
	 * @since 1.0.0
	 */
	public function upgrade_page() {

		?>
        <div id="tgm-plugin-settings" class="container">
            <header class="row">
                <div class="col-lg-12">
                    <h2><img style="margin:0 8px 4px 0;vertical-align:middle;" src="<?php echo plugins_url( 'css/images/title-icon.png', dirname( dirname( __FILE__ ) ) ); ?>" alt="<?php esc_attr_e( 'Get Soliloquy Pro!', 'soliloquy-lite' ); ?>" /><?php echo esc_html( get_admin_page_title() ); ?></h2>
                </div>
            </header>

            <p style="font-size:16px;margin:15px 0;"><?php _e( 'Do you want <strong>immediate</strong> access to all of the incredible features of the pro version of Soliloquy? You can upgrade to the pro version of Soliloquy <strong>instantly</strong> from this page. It only takes a couple minutes to be using the fully featured, best responsive WordPress slider plugin on the market - Soliloquy! <strong>Just choose the license below that best suits your needs to get started!</strong>', 'soliloquy-lite' ); ?></p>

            <p><strong><?php _e( 'Not convinced you should upgrade? <a href="#" class="soliloquy-comparison-show" data-popup="#soliloquy-comparison">Click here to view a comparison chart to see why Soliloquy Pro is so much better!</a>', 'soliloquy-lite' ); ?></strong></p>

            <p><em><?php _e( 'If you would rather do this from the official Soliloquy website, you can do so by <a href="http://soliloquywp.com/" title="Go to the main Soliloquy Site" target="_blank">clicking on this link to visit the official Soliloquy website.</a>', 'soliloquy-lite' ); ?></em></p>

            <div id="soliloquy-comparison" class="mfp-hide">
            	<p style="font-size:16px;"><strong><?php _e( 'You can view the features below to see why Soliloquy Pro is so much better!', 'soliloquy-lite' ); ?></strong></p>
            	<table class="table table-striped table-bordered comparison-table">
					<thead>
						<tr>
							<th>Plugin Feature</th>
							<th>Soliloquy Lite</th>
							<th>Soliloquy Pro</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Uses a custom post type to handle the sliders</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td>Ability to create an unlimited number of sliders</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td>Ajax uploading, drag-and-drop sorting and saving of image order</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td>Media buttons for easy slider insertion into the post editor</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td>Touch swipe support for touch-enabled devices</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td>Fully compatible with WordPress MultiSite</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td>Shortcode and template tags for slider display</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td>Custom metadata (title, alt, caption, link title, etc.) for each image</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td>Uses WordPress media uploader for uploading images</td>
							<td><i class="icon-ok"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Custom video slides</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Custom HTML slides (use any HTML you want to build your slide!)</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Use images already in Media Library for sliders</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Use thumbnail sizes registered with WordPress for slider sizes</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Captions can accept any HTML (forms, email signups, iframe videos, etc.)</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Full customization of all slider options</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Ability to utilize Soliloquy Addons (developer license only)</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Ajax preloading of images for lightening fast load times</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Configurable widget for outputting slider in widgetized areas</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Access to all slider transition effects</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Internal linking feature to quickly link images to internal content</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Embedded video and API support for YouTube and Vimeo</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Over 50+ hooks and filters to customize the slider output</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Ability to be completely white-labeled for client use (via hooks and filters)</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Enhanced SEO and speed for better rankings</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Show/hide navigation, control and pause-play controls</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Randomize slide order on a per slider basis</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Choose between native CSS and JS slide transitions</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Reverse animation direction for slide transitions</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Animate slider height when images in slider are different sizes</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
						<tr>
							<td><strong class="text-success">Get access to official support forums and documentation</strong></td>
							<td><i class="icon-remove"></i></td>
							<td><i class="icon-ok"></i></td>
						</tr>
					</tbody>
				</table>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading text-center"><?php _e( 'Soliloquy Single License', 'soliloquy-lite' ); ?></div>
                        <h4 style="margin:20px 0 0;" class="text-center"><strong><?php _e( 'Upgrade for Only $19!', 'soliloquy-lite' ); ?></strong></h4>
                        <hr />
                        <div class="panel-area">
	                        <table class="table table-striped table-bordered clearfix clear">
	                        	<tbody>
	                        		<tr><td class="text-center"><?php _e( '<strong>Full Access to All Slider Options</strong>', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( 'Updates for <strong>One Site</strong> for Life', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( '<strong>Unlimited</strong> Sliders', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( 'Complete Documentation Access', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( 'One Complimentary Support Token', 'soliloquy-lite' ); ?></td></tr>
	                        	</tbody>
	                        </table>
	                        <a class="btn btn-default btn-block soliloquy-do-upgrade" title="<?php esc_attr_e( 'Get Soliloquy Today!', 'soliloquy-lite' ); ?>" href="#" data-license="single" target="_blank"><strong><?php _e( 'Click Here to Get Started with Your Upgrade for Only $19!', 'soliloquy-lite' ); ?></strong></a>
                    	</div>
                    	<div class="account-information-single row" style="display:none;">
	                    	<form class="col-lg-12 clearfix clear">
	                    		<p class="clear clearfix"><label for="account-email-single"><?php _e( 'Email Address', 'soliloquy-lite' ); ?></label><small class="help-block text-muted" style="margin-bottom:10px;"><?php _e( 'This email address will be used for your account login and purchase receipt.', 'soliloquy-lite' ); ?></small>
	                    		<input id="account-email-single" type="text" class="col-lg-12" placeholder="<?php esc_attr_e( 'Enter your email address...', 'soliloquy-lite' ); ?>" value="" /></p>
	                    		<p style="margin:15px 0 0;"><input type="submit" value="<?php _e( 'Get Started with the Upgrade for Only $19!', 'soliloquy' ); ?>" class="btn btn-block btn-default soliloquy-start-upgrade" data-amount="1900" data-name="<?php esc_attr_e( 'Single License', 'soliloquy-lite' ); ?>" data-plugin="Soliloquy Single License" data-slug="soliloquy-single-license" data-desc="<?php esc_attr_e( 'x1 Single License - $19', 'soliloquy-lite' ); ?>" data-panel="<?php esc_attr_e( 'Pay Securely via Stripe - ', 'soliloquy-lite' ); ?>" /></p>
	                    	</form>
                    	</div>
                        <div class="panel-footer">
                            <p class="no-margin text-center"><?php _e( 'Your upgrade will be applied <strong>instantly</strong> once your purchase is complete!', 'soliloquy-lite' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-success">
                        <div class="panel-heading text-center"><?php _e( 'Soliloquy Developer License <strong>(best value)</strong>', 'soliloquy-lite' ); ?></div>
                        <h4 style="margin:20px 0 0;" class="text-center text-success"><strong><?php _e( 'Upgrade for Only $99! That\'s A Steal!', 'soliloquy-lite' ); ?></strong></h4>
                        <hr />
                        <div class="panel-area">
	                        <table class="table table-striped table-bordered clearfix clear">
	                        	<tbody>
	                        		<tr><td class="text-center"><?php _e( '<strong>Full Access to All Slider Options</strong>', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( '<strong>Exclusive Access to <a href="#" data-popup="#addons-image" class="soliloquy-show-addons">Soliloquy Addons</a></strong><br /><small class="help-block no-margin text-muted">(can install <strong>immediately</strong> after upgrading from this screen)</small>', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( 'Updates for <strong>Unlimited Sites</strong> for Life', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( '<strong>Unlimited</strong> Sliders', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( 'Complete Documentation Access', 'soliloquy-lite' ); ?></td></tr>
	                        		<tr><td class="text-center"><?php _e( 'One Complimentary Support Token', 'soliloquy-lite' ); ?></td></tr>
	                        	</tbody>
	                        </table>
	                        <div id="addons-image" class="mfp-hide"><img src="<?php echo plugins_url( '/css/images/addons.png', dirname( dirname( __FILE__ ) ) ); ?>" /></div>
	                        <a class="btn btn-success btn-block soliloquy-do-upgrade" data-license="developer" title="<?php esc_attr_e( 'Get Soliloquy Today!', 'fsb' ); ?>" href="#" target="_blank"><strong><?php _e( 'Click Here to Get Started with Your Upgrade for Only $99!', 'soliloquy-lite' ); ?></strong></a>
                        </div>
                        <div class="account-information-developer row" style="display:none;">
	                    	<form class="col-lg-12 clearfix clear">
	                    		<p class="clear clearfix"><label for="account-email-developer"><?php _e( 'Email Address', 'soliloquy-lite' ); ?></label><small class="help-block text-muted" style="margin-bottom:10px;"><?php _e( 'This email address will be used for your account login and purchase receipt.', 'soliloquy-lite' ); ?></small>
	                    		<input id="account-email-developer" type="text" class="col-lg-12" placeholder="<?php esc_attr_e( 'Enter your email address...', 'soliloquy-lite' ); ?>" value="" /></p>
	                    		<p style="margin:15px 0 0;"><input type="submit" value="<?php _e( 'Get Started with the Upgrade for Only $99!', 'soliloquy' ); ?>" class="btn btn-block btn-success soliloquy-start-upgrade" data-amount="9900" data-name="<?php esc_attr_e( 'Developer License', 'soliloquy-lite' ); ?>" data-plugin="Soliloquy Developer License" data-slug="soliloquy-developer-license" data-desc="<?php esc_attr_e( 'x1 Developer License - $99', 'soliloquy-lite' ); ?>" data-panel="<?php esc_attr_e( 'Pay Securely via Stripe - ', 'soliloquy-lite' ); ?>" /></p>
	                    	</form>
                    	</div>
                        <div class="panel-footer">
                            <p class="no-margin text-center"><?php _e( 'Your upgrade will be applied <strong>instantly</strong> once your purchase is complete!', 'soliloquy-lite' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <small class="help-block text-muted no-margin text-center"><strong><?php _e( 'Upgrades are processed securely (256-bit SSL) by credit card via Stripe and are made to Griffin Media, LLC.', 'soliloquy-lite' ); ?></strong></small>
            <small class="help-block text-muted no-margin text-center"><em><?php _e( 'By purchasing Soliloquy, you agree to the <a href="http://soliloquywp.com/terms-and-conditions/" title="Soliloquy Terms and Conditions" target="_blank">Terms and Conditions</a> of use.', 'soliloquy-lite' ); ?></em></small>

            <div class="tgm-plugin-overlay" style="display:none;">
            	<div class="tgm-plugin-cover">
            		<div class="tgm-plugin-processing">
            			<h3 class="tgm-plugin-title"><?php _e( 'Your upgrade is being processed. Hang tight for just a few more moments...', 'soliloquy-lite' ); ?></h3>
            		</div>
            	</div>
            </div>
        </div>
        <?php

	}

	/**
	 * Outputs assets on the upgrade page.
	 *
	 * @since 1.0.0
	 */
	public function upgrade_assets() {

		// Remove admin notices from this page.
		remove_action( 'admin_notices', array( $this, 'admin_notices' ) );

		wp_enqueue_style( $this->upgrade_slug . '-lightbox', plugins_url( 'css/lightbox.css', dirname( dirname( __FILE__ ) ) ), array(), $this->version );
		wp_enqueue_style( $this->upgrade_slug . '-bootstrap', plugins_url( 'lib/bootstrap/css/bootstrap.min.css', dirname( dirname( __FILE__ ) ) ), array(), $this->version );
        wp_enqueue_style( $this->upgrade_slug . '-google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600', array( $this->upgrade_slug . '-bootstrap' ), $this->version );
        wp_enqueue_style( $this->upgrade_slug . '-font-awesome', plugins_url( 'css/font-awesome.css', dirname( dirname( __FILE__ ) ) ), array(), $this->version );

        // Enqueue scripts.
        wp_enqueue_script( $this->upgrade_slug . '-bootstrap', plugins_url( 'lib/bootstrap/js/bootstrap.min.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), $this->version );
        wp_enqueue_script( 'soliloquy-lite-lightbox' );

	}

	/**
	 * There is no need to apply SEO to the Soliloquy post type, so we check to
	 * see if some popular SEO plugins are installed, and if so, remove the inpost
	 * meta boxes from view.
	 *
	 * This method also has a filter that can be used to remove any unwanted metaboxes
	 * from the Soliloquy screen - tgmsp_remove_metaboxes.
	 *
	 * @since 1.0.0
	 */
	public function remove_seo_support() {

		$plugins = array(
			array( 'WPSEO_Metabox', 'wpseo_meta', 'normal' ),
			array( 'All_in_One_SEO_Pack', 'aiosp', 'advanced' ),
			array( 'Platinum_SEO_Pack', 'postpsp', 'normal' ),
			array( 'SEO_Ultimate', 'su_postmeta', 'normal' )
		);
		$plugins = apply_filters( 'tgmsp_remove_metaboxes', $plugins );

		/** Loop through the arrays and remove the metaboxes */
		foreach ( $plugins as $plugin )
			if ( class_exists( $plugin[0] ) )
				remove_meta_box( $plugin[1], convert_to_screen( 'soliloquy' ), $plugin[2] );

	}

	/**
	 * Add the metaboxes to the Soliloquy edit screen.
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {

		add_meta_box( 'soliloquy_uploads', Tgmsp_Lite_Strings::get_instance()->strings['meta_uploads'], array( $this, 'soliloquy_uploads' ), 'soliloquy', 'normal', 'high' );
		add_meta_box( 'soliloquy_settings', Tgmsp_Lite_Strings::get_instance()->strings['meta_settings'], array( $this, 'soliloquy_settings' ), 'soliloquy', 'normal', 'high' );
		add_meta_box( 'soliloquy_upgrade', Tgmsp_Lite_Strings::get_instance()->strings['meta_upgrade'], array( $this, 'soliloquy_upgrade' ), 'soliloquy', 'side', 'core' );
		add_meta_box( 'soliloquy_instructions', Tgmsp_Lite_Strings::get_instance()->strings['meta_instructions'], array( $this, 'soliloquy_instructions' ), 'soliloquy', 'side', 'core' );

	}

	/**
	 * Callback function for Soliloquy image uploads.
	 *
	 * @since 1.0.0
	 *
	 * @param object $post Current post object data
	 */
	public function soliloquy_uploads( $post ) {

		/** Always keep security first */
		wp_nonce_field( 'soliloquy_uploads', 'soliloquy_uploads' );

		?>
		<input id="soliloquy-uploads" type="hidden" name="soliloquy-uploads" value="1" />
		<div id="soliloquy-area">
			<p><?php echo Tgmsp_Lite_Strings::get_instance()->strings['upload_info']; ?></p>
			<a href="#" id="soliloquy-upload" class="button-secondary" title="<?php echo esc_attr( Tgmsp_Lite_Strings::get_instance()->strings['upload_images'] ); ?>"><?php echo esc_html( Tgmsp_Lite_Strings::get_instance()->strings['upload_images'] ); ?></a>

			<ul id="soliloquy-images">
				<?php
					/** List out all image attachments for the slider */
					$args = apply_filters( 'tgmsp_list_images_args', array(
						'orderby' 			=> 'menu_order',
						'order' 			=> 'ASC',
						'post_type' 		=> 'attachment',
						'post_parent' 		=> $post->ID,
						'post_mime_type' 	=> 'image',
						'post_status' 		=> null,
						'posts_per_page' 	=> -1
					) );
					$attachments = get_posts( $args );

					if ( $attachments ) {
						foreach ( $attachments as $attachment ) {
							echo '<li id="' . $attachment->ID . '" class="soliloquy-image attachment-' . $attachment->ID . '">';
								echo wp_get_attachment_image( $attachment->ID, 'soliloquy-thumb' );
								echo '<a href="#" class="remove-image" title="' . Tgmsp_Lite_Strings::get_instance()->strings['remove_image'] . '"></a>';
								echo '<a href="#" class="modify-image" title="' . Tgmsp_Lite_Strings::get_instance()->strings['modify_image'] . '"></a>';

								/** Begin outputting the meta information for each image */
								echo '<div id="meta-' . $attachment->ID . '" class="soliloquy-image-meta" style="display: none;">';
									echo '<div class="soliloquy-meta-wrap">';
										echo '<h2>' . Tgmsp_Lite_Strings::get_instance()->strings['update_meta'] . '</h2>';
										echo '<p>' . Tgmsp_Lite_Strings::get_instance()->strings['image_meta'] . '</p>';
										do_action( 'tgmsp_before_image_meta_table', $attachment );
										echo '<table id="soliloquy-meta-table-' . $attachment->ID . '" class="form-table soliloquy-meta-table">';
											echo '<tbody>';
												do_action( 'tgmsp_before_image_title', $attachment );
												echo '<tr id="soliloquy-title-box-' . $attachment->ID . '" valign="middle">';
													echo '<th scope="row">' . Tgmsp_Lite_Strings::get_instance()->strings['image_title'] . '</th>';
													echo '<td>';
														echo '<input id="soliloquy-title-' . $attachment->ID . '" class="soliloquy-title" type="text" size="75" name="_soliloquy_uploads[title]" value="' . esc_attr( strip_tags( $attachment->post_title ) ) . '" />';
													echo '</td>';
												echo '</tr>';
												do_action( 'tgmsp_before_image_alt', $attachment );
												echo '<tr id="soliloquy-alt-box-' . $attachment->ID . '" valign="middle">';
													echo '<th scope="row">' . Tgmsp_Lite_Strings::get_instance()->strings['image_alt'] . '</th>';
													echo '<td>';
														echo '<input id="soliloquy-alt-' . $attachment->ID . '" class="soliloquy-alt" type="text" size="75" name="_soliloquy_uploads[alt]" value="' . esc_attr( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ) . '" />';
													echo '</td>';
												echo '</tr>';
												do_action( 'tgmsp_before_image_link', $attachment );
												echo '<tr id="soliloquy-link-box-' . $attachment->ID . '" valign="middle">';
													echo '<th scope="row">' . Tgmsp_Lite_Strings::get_instance()->strings['image_link'] . '</th>';
													echo '<td>';
														echo '<label class="soliloquy-link-url">' . Tgmsp_Lite_Strings::get_instance()->strings['image_url'] . '</label>';
														echo '<input id="soliloquy-link-' . $attachment->ID . '" class="soliloquy-link" type="text" size="70" name="_soliloquy_uploads[link]" value="' . esc_url( get_post_meta( $attachment->ID, '_soliloquy_image_link', true ) ) . '" />';
														echo '<label class="soliloquy-link-title-label">' . Tgmsp_Lite_Strings::get_instance()->strings['image_url_title'] . '</label>';
														echo '<input id="soliloquy-link-title-' . $attachment->ID . '" class="soliloquy-link-title" type="text" size="40" name="_soliloquy_uploads[link_title]" value="' . esc_attr( strip_tags( get_post_meta( $attachment->ID, '_soliloquy_image_link_title', true ) ) ) . '" />';
														echo '<input id="soliloquy-link-tab-' . $attachment->ID . '" class="soliloquy-link-check" type="checkbox" name="_soliloquy_uploads[link_tab]" value="' . esc_attr( get_post_meta( $attachment->ID, '_soliloquy_image_link_tab', true ) ) . '"' . checked( get_post_meta( $attachment->ID, '_soliloquy_image_link_tab', true ), 1, false ) . ' />';
														echo '<span class="description">' . Tgmsp_Lite_Strings::get_instance()->strings['new_tab'] . '</span>';
													echo '</td>';
												echo '</tr>';
												do_action( 'tgmsp_before_image_caption', $attachment );
												echo '<tr id="soliloquy-caption-box-' . $attachment->ID . '" valign="middle">';
													echo '<th scope="row">' . Tgmsp_Lite_Strings::get_instance()->strings['image_caption'] . '</th>';
													echo '<td>';
														echo '<textarea id="soliloquy-caption-' . $attachment->ID . '" class="soliloquy-caption" rows="3" cols="75" name="_soliloquy_uploads[caption]">' . esc_html( $attachment->post_excerpt ) . '</textarea>';
													echo '</td>';
												echo '</tr>';
												do_action( 'tgmsp_after_meta_defaults', $attachment );
											echo '</tbody>';
										echo '</table>';
										do_action( 'tgmsp_after_image_meta_table', $attachment );

										echo '<a href="#" class="soliloquy-meta-submit button-secondary" title="' . Tgmsp_Lite_Strings::get_instance()->strings['save_meta'] . '">' . Tgmsp_Lite_Strings::get_instance()->strings['save_meta'] . '</a>';
									echo '</div>';
								echo '</div>';
							echo '</li>';
						}
					}
				?>
			</ul>
		</div><!-- end #soliloquy-area -->
		<?php

	}

	/**
	 * Callback function for Soliloquy settings.
	 *
	 * @since 1.0.0
	 *
	 * @global array $_wp_additional_image_sizes Additional registered image sizes
	 * @param object $post Current post object data
	 */
	public function soliloquy_settings( $post ) {

		global $_wp_additional_image_sizes;

		/** Always keep security first */
		wp_nonce_field( 'soliloquy_settings_script', 'soliloquy_settings_script' );

		do_action( 'tgmsp_before_settings_table', $post );

		?>
		<table class="form-table">
			<tbody>
				<?php do_action( 'tgmsp_before_setting_size', $post ); ?>
				<tr id="soliloquy-size-box" valign="middle">
					<th scope="row"><?php echo Tgmsp_Lite_Strings::get_instance()->strings['slider_size']; ?></th>
					<td>
						<div id="soliloquy-default-sizes">
							<input id="soliloquy-width" type="text" name="_soliloquy_settings[width]" value="<?php echo esc_attr( $this->get_custom_field( '_soliloquy_settings', 'width' ) ); ?>" /> &#215; <input id="soliloquy-height" type="text" name="_soliloquy_settings[height]" value="<?php echo esc_attr( $this->get_custom_field( '_soliloquy_settings', 'height' ) ); ?>" />
							<p class="description"><?php printf( '%s <a class="soliloquy-size-more" href="#">%s</a>', Tgmsp_Lite_Strings::get_instance()->strings['slider_size_desc'], Tgmsp_Lite_Strings::get_instance()->strings['slider_size_more'] ); ?></p>
							<p id="soliloquy-explain-size" class="description" style="display: none;"><?php printf( '%s <a href="%s">%s</a>.', Tgmsp_Lite_Strings::get_instance()->strings['slider_size_explain'], add_query_arg( array( 'post_type' => 'soliloquy', 'page' => 'soliloquy-lite-upgrade' ), admin_url( 'edit.php' ) ), Tgmsp_Lite_Strings::get_instance()->strings['slider_size_upgrade'] ); ?></p>
						</div>
					</td>
				</tr>
				<?php do_action( 'tgmsp_before_setting_transition', $post ); ?>
				<tr id="soliloquy-transition-box" valign="middle">
					<th scope="row"><?php echo Tgmsp_Lite_Strings::get_instance()->strings['slider_transition']; ?></th>
					<td>
					<?php
						$transitions = apply_filters( 'tgmsp_slider_transitions', array( 'fade' ) );
						echo '<select id="soliloquy-transition" name="_soliloquy_settings[transition]">';
							foreach ( $transitions as $transition ) {
								echo '<option value="' . esc_attr( $transition ) . '"' . selected( $transition, $this->get_custom_field( '_soliloquy_settings', 'transition' ), false ) . '>' . esc_html( $transition ) . '</option>';
							}
						echo '</select>';
					?>
					</td>
				</tr>
				<?php do_action( 'tgmsp_before_setting_speed', $post ); ?>
				<tr id="soliloquy-speed-box" valign="middle">
					<th scope="row"><?php echo Tgmsp_Lite_Strings::get_instance()->strings['slider_speed']; ?></th>
					<td>
						<input id="soliloquy-speed" type="text" name="_soliloquy_settings[speed]" value="<?php echo esc_attr( $this->get_custom_field( '_soliloquy_settings', 'speed' ) ); ?>" />
						<span class="description"><?php echo Tgmsp_Lite_Strings::get_instance()->strings['slider_milliseconds']; ?></span>
					</td>
				</tr>
				<?php do_action( 'tgmsp_before_setting_duration', $post ); ?>
				<tr id="soliloquy-duration-box" valign="middle">
					<th scope="row"><?php echo Tgmsp_Lite_Strings::get_instance()->strings['slider_animation_dur']; ?></th>
					<td>
						<input id="soliloquy-duration" type="text" name="_soliloquy_settings[duration]" value="<?php echo esc_attr( $this->get_custom_field( '_soliloquy_settings', 'duration' ) ); ?>" />
						<span class="description"><?php echo Tgmsp_Lite_Strings::get_instance()->strings['slider_milliseconds']; ?></span>
					</td>
				</tr>
				<?php do_action( 'tgmsp_before_setting_preloader', $post ); ?>
				<tr id="soliloquy-preloader-box" valign="middle">
					<th scope="row"><label for="soliloquy-preloader"><?php echo Tgmsp_Lite_Strings::get_instance()->strings['slider_preloader']; ?></label></th>
					<td>
						<input id="soliloquy-preloader" type="checkbox" name="_soliloquy_settings[preloader]" value="<?php echo esc_attr( $this->get_custom_field( '_soliloquy_settings', 'preloader' ) ); ?>" <?php checked( $this->get_custom_field( '_soliloquy_settings', 'preloader' ), 1 ); ?> />
						<span class="description"><?php echo Tgmsp_Lite_Strings::get_instance()->strings['slider_preloader_desc']; ?></span>
					</td>
				</tr>
				<?php do_action( 'tgmsp_end_of_settings', $post ); ?>
			</tbody>
		</table>

		<?php do_action( 'tgmsp_after_settings_table', $post ); ?>

		<div class="soliloquy-advanced">
			<p><strong><?php echo sprintf( Tgmsp_Lite_Strings::get_instance()->strings['slider_cb'], sprintf( '<a href="' . add_query_arg( array( 'post_type' => 'soliloquy', 'page' => 'soliloquy-lite-upgrade' ), admin_url( 'edit.php' ) ) . '" title="%1$s">%1$s</a>', Tgmsp_Lite_Strings::get_instance()->strings['slider_cb_up'] ) ); ?></strong></p>
		</div>
		<?php

		do_action( 'tgmsp_after_settings', $post );

	}

	/**
	 * Callback function for Soliloquy upgrading methods.
	 *
	 * @since 1.0.0
	 *
	 * @param object $post Current post object data
	 */
	public function soliloquy_upgrade( $post ) {

		$upgrade = '<p><strong>' . Tgmsp_Lite_Strings::get_instance()->strings['upgrade'] . '</strong></p>';
		$upgrade .= sprintf( '<p><a href="' . add_query_arg( array( 'post_type' => 'soliloquy', 'page' => 'soliloquy-lite-upgrade' ), admin_url( 'edit.php' ) ) . '" title="%1$s"><strong>%1$s</strong></a></p>', Tgmsp_Lite_Strings::get_instance()->strings['upgrade_now'] );

		echo $upgrade;

	}

	/**
	 * Callback function for Soliloquy instructions.
	 *
	 * @since 1.0.0
	 *
	 * @param object $post Current post object data
	 */
	public function soliloquy_instructions( $post ) {

		$instructions = '<p>' . Tgmsp_Lite_Strings::get_instance()->strings['instructions'] . '</p>';
		$instructions .= '<p><code>[soliloquy id="' . $post->ID . '"]</code></p>';
		$instructions .= '<p>' . Tgmsp_Lite_Strings::get_instance()->strings['instructions_more'] . '</p>';
		$instructions .= '<p><code>if ( function_exists( \'soliloquy_slider\' ) ) soliloquy_slider( \'' . $post->ID . '\' );</code></p>';

		echo apply_filters( 'tgmsp_slider_instructions', $instructions, $post );

	}

	/**
	 * Outputs any error messages when verifying license keys.
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {

		if ( Tgmsp_Lite::is_soliloquy_screen() && current_user_can( 'manage_options' ) ) {
			/** If a user hasn't dismissed the notice yet, output it for them to upgrade */
			if ( ! get_user_meta( get_current_user_id(), 'soliloquy_dismissed_notice', true ) )
				add_settings_error( 'tgmsp', 'tgmsp-upgrade-soliloquy', sprintf( Tgmsp_Lite_Strings::get_instance()->strings['upgrade_nag'], sprintf( '<a href="' . add_query_arg( array( 'post_type' => 'soliloquy', 'page' => 'soliloquy-lite-upgrade' ), admin_url( 'edit.php' ) ) . '" title="%1$s">%1$s</a>', Tgmsp_Lite_Strings::get_instance()->strings['upgrade_nag_link'] ), sprintf( '<a id="soliloquy-dismiss-notice" href="#" title="%1$s">%1$s</a>', Tgmsp_Lite_Strings::get_instance()->strings['upgrade_nag_dismiss'] ) ), 'updated' );

			/** Allow settings notices to be filtered */
			apply_filters( 'tgmsp_output_notices', settings_errors( 'tgmsp' ) );
		}

	}

	/**
	 * Helper function to get custom field values for the Soliloquy post type.
	 *
	 * @since 1.0.0
	 *
	 * @global int $id The current Soliloquy ID
	 * @global object $post The current Soliloquy post type object
	 * @param string $field The custom field name to retrieve
	 * @param string|int $setting The setting or array index to retrieve within the custom field
	 * @param int $index The array index number to retrieve
	 * @return string|boolean The custom field value on success, false on failure
	 */
	public function get_custom_field( $field, $setting = null, $index = null ) {

		global $id, $post;

		/** Do nothing if the field is not set */
		if ( ! $field )
			return false;

		/** Get the current Soliloquy ID */
		$post_id = ( null === $id ) ? $post->ID : $id;

		$custom_field = get_post_meta( $post_id, $field, true );

		/** Return the sanitized field and setting if an array, otherwise return the sanitized field */
		if ( $custom_field && isset( $custom_field[$setting] ) ) {
			if ( is_int( $index ) && is_array( $custom_field[$setting] ) )
				return stripslashes_deep( $custom_field[$setting][$index] );
			else
				return stripslashes_deep( $custom_field[$setting] );
		} elseif ( is_array( $custom_field ) ) {
			return stripslashes_deep( $custom_field );
		} else {
			return stripslashes( $custom_field );
		}

		return false;

	}

	/**
	 * Getter method for retrieving the object instance.
	 *
	 * @since 1.0.0
	 */
	public static function get_instance() {

		return self::$instance;

	}

}
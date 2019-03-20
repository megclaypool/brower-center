<?php
/**
 * License handler for GEO my WP
 *
 * This class should simplify the process of adding license information
 * to GEO my WP add-ons.
 * 
 * @author Eyal Fitoussi. Inspired by a class written by Pippin Williamson
 * @version 1.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) 
	exit; 

//abort if this page already loaded
if ( ! class_exists( 'GMW_License' ) ) :
	
/**
 * GMW_License Class
 * 
 * Responsiable for updates of the premium add-ons as well for the action links
 * of the Plugins page.
 * 
*/
class GMW_License {

	private $file;
	private $license;
	private $item_name;
	private $version;
	private $author;
	private $api_url = 'https://geomywp.com';

	/**
	 * Class constructor
	 *
	 * @param string  $_file
	 * @param string  $_item_name
	 * @param string  $_version
	 * @param string  $_author
	 * @param string  $_optname
	 * @param string  $_api_url
	 */					
	function __construct( $_file, $_item_name = '', $_license = false, $_version = '1.0', $_author = 'Eyal Fitoussi' , $_api_url = null, $_item_id = null ) {

		$this->licenses 	= get_option( 'gmw_license_keys' );
		$this->statuses 	= get_option( 'gmw_premium_plugin_status' );
		$this->file         = $_file;
		$this->item_name    = $_item_name;
		$this->item_id 		= $_item_id;
		$this->license_name = $_license;
		$this->license      = isset( $this->licenses[$_license] ) ? trim( $this->licenses[$_license] ) : '';
		$this->version      = $_version;
		$this->author       = $_author;
		$this->api_url      = is_null( $_api_url ) ? $this->api_url : $_api_url;

		//action links
		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ) , array( $this, 'addon_action_links' ), 10 );
		add_action( 'after_plugin_row_' . plugin_basename( $this->file ), array( $this, 'license_key_input' ), 10 );
		
		// Setup hooks
		$this->includes();
		$this->auto_updater();	
	}
		
	/**
	 * add gmw add-ons action links in plugins page
	 * @param  $links
	 * @return $links
	 */
	public function addon_action_links( $links ) {
			
		//if license is not activated display the "Activate License" message
		if ( empty( $this->licenses[$this->license_name] ) || !isset( $this->statuses[$this->license_name] ) || $this->statuses[$this->license_name] != 'valid' ) {
			return $links;
		} 
		
		//if license activate display "Diactivate license before...." message
		$links['deactivate'] = __( 'Deactivate the license key before deactivating the plugin', 'GMW' );
						
		return $links;
	}
	
	/**
	 * Append license key input box in plugins page
	 * 
	 * @return [type] [description]
	 */
	public function license_key_input() {
		$license_key = new GMW_License_Key( $this->file, $this->item_name, $this->license_name, $this->item_id );
		$basename    = basename( dirname( $this->file ) );
		?>
		<tr id="<?php echo esc_attr( $basename ); ?>-license-key-row" class="active gmw-license-key-row" >
			<td class="plugin-update" colspan="3">
				<?php $license_key->license_key_output(); ?>
			</td>
			<script>
			jQuery( function($) {
				$('tr#<?php echo esc_attr( $basename ); ?>-license-key-row').prev().addClass('gmw-license-key-addon-wrapper');
				if ( $('tr#<?php echo esc_attr( $basename ); ?>-license-key-row').prev().hasClass('update') ) {
					$('tr#<?php echo esc_attr( $basename ); ?>-license-key-row').addClass( 'update');
				}				 
			});
			</script>
		</tr>
		<?php
	}

	/**
	 * Include the updater class
	 *
	 * @access  private
	 * @return  void
	 */
	private function includes() {
		if ( ! class_exists( 'GMW_Premium_Plugin_Updater' ) ) {
			include( 'geo-my-wp-updater.php' );
		}
	}

	/**
	 * Auto updater
	 *
	 * @access  private
	 * @return  void
	 */
	private function auto_updater() {

		if ( empty( $this->license ) )
			return;

		if ( !isset( $this->statuses[$this->license_name] ) || 'valid' !== $this->statuses[$this->license_name] )
			return;

		// Setup the updater
		$gmw_updater = new GMW_Premium_Plugin_Updater(
			$this->api_url,
			$this->file,
			array(
				'version'   => $this->version,
				'license'   => $this->license,
				'item_name' => $this->item_name,
				'item_id'	=> $this->item_id,
				'author'    => $this->author
			)
		);
	}
}

/**
 * GMW_License_Key input field Class
 * 
 * create input field for a license key
 */
class GMW_License_Key {

	private $file;
	private $license_name;

	/**
	 * Class constructor
	 */
	function __construct( $file ,$item_name, $license_name, $item_id = NULL ) {

		$this->licenses	 	= get_option( 'gmw_license_keys' );
		$this->statuses  	= get_option( 'gmw_premium_plugin_status' );
		$this->basename		= plugin_basename( $file );
		$this->file      	= basename( dirname( $file ) );
		$this->item_name	= $item_name;
		$this->item_id		= $item_id;
		$this->license_name = $license_name;
		$this->messages		= gmw_update_key_api_notices();
	}

	/**
	 * Display license key field
	 *
	 */
	public function license_key_output() {

		// get some default args
		if ( gmw_is_license_valid( $this->license_name ) ) { 	
			
			$message = $this->messages['valid'];
			$action  = 'deactivate';
			$title   = __( 'Deactivate License Key', 'GMW' );
			$status  = 'active';
			$btn_cls = 'button-secondary deactivate-license-btn';
		
		} else { 

			$action  = 'activate';
			$message = $this->messages['activate'];
			$title   = $title = __( 'Activate License Key', 'GMW' );
			$status  = 'inactive';
			$btn_cls = 'button-primary activate-license-btn';

			if ( ! empty( $this->licenses[$this->license_name] ) && isset( $this->statuses[$this->license_name] ) ) {
				$status  .= ' gmw-license-error';
				$message  = ( array_key_exists( $this->statuses[$this->license_name], $this->messages ) ) ? $this->messages[$this->statuses[$this->license_name]] : $this->messages['missing'];	
				$message .= '<br />';
				$message .= $this->messages['retrieve_key'];
			} 
		} 
		$allow_html = array( 'a' => array( 'href' => array(), 'title' => array() ),'br' => array() );
		?>			
		<div id="gmw-<?php echo $this->license_name; ?>-license-key-wrapper" class="gmw-license-key-wrapper <?php echo $status; ?>">
																
			<i class="icon-lock"></i>
			<span><?php _e( 'License Key:', 'GMW' ); ?></span>
			<input 
				name="gmw_license[<?php echo $this->license_name; ?>][key]" 
				class="gmw-license-key-input-field" 
				type="<?php echo ( $status == 'active' ) ? 'hidden' : 'text'; ?>" 
				value="<?php if ( !empty( $this->licenses[$this->license_name] ) ) echo esc_attr( sanitize_text_field( $this->licenses[$this->license_name] ) ); ?>" 
				onkeydown="if (event.keyCode == 13) { return false; }"
			/>

			<?php if ( $status == 'active' ) { ?>

				<input 
					disabled="disabled"
					class="gmw-license-key-input-field" 
					type="text" 
					value="<?php if ( !empty( $this->licenses[$this->license_name] ) ) echo esc_attr( sanitize_text_field( $this->licenses[$this->license_name] ) ); ?>" 
				/>
			<?php } ?>

			<input 
				type="submit"
				name="gmw_license[submitted][<?php echo $this->license_name; ?>]"
				id="gmw-<?php echo $this->license_name; ?>-license-key-button"
				class="gmw-license-key-button <?php echo $btn_cls; ?>"
				style="padding: 0 9px !important;"
				title="<?php echo $title; ?>"
				value="<?php echo $title; ?>" />
			<br />
			
			<p class="description"><?php echo wp_kses( $message, $allow_html ); ?></p>			
		
			<input type="hidden" name="gmw_license[<?php echo $this->license_name; ?>][action]"    value="<?php echo $action; ?>" class="license-action" /> 
			<input type="hidden" name="gmw_license[<?php echo $this->license_name; ?>][item_name]" value ="<?php echo esc_attr( $this->item_name ); ?>" />
			<input type="hidden" name="gmw_license[<?php echo $this->license_name; ?>][item_id]"   value ="<?php echo esc_attr( $this->item_id ); ?>" />

			<?php wp_nonce_field( 'gmw_license_update_nonce', $this->license_name.'_nonce' ); ?>
		</div>
		<?php 

		if ( ! wp_style_is( 'gmw-updater', 'enqueued' ) ) {
			wp_enqueue_style( 'gmw-updater',untrailingslashit( plugins_url( '', __FILE__ ) ) .'/assets/css/gmw.updater.css', array() );
		} 
	}
}

/**
 * GMW messages for license status and notices
 * 
 * @since  2.5
 * @author Eyal Fitoussi
 */
function gmw_update_key_api_notices() {
	
	return $messages = apply_filters( 'gmw_update_key_api_notices', array(
		'activate'				=> __( 'Please enter your license key and click "Activate License". The license key is required in order to receive automatic updated for the plugin.', 'GMW' ),
		'activated'				=> __( 'Your license for %s plugin successfully activated. Thank you for your support!', 'GMW' ),
		'deactivated'			=> __( 'Your license for %s plugin successfully deactivated.', 'GMW' ),
		'valid'					=> __( 'License is activated. Thank you for your support!', 'GMW' ),
		'no_key_entered'		=> __( 'No license key entered. Please enter the license key and try again.', 'GMW' ),
		'expired' 				=> __( 'Your license has expired. Please renew your license in order to keep getting its updates and support.', 'GMW' ),
		'no_activations_left' 	=> sprintf( __( 'Your license has no activations left. Click <a %s>here</a> to manager your license activations.', 'GMW' ), 'href="http://geomywp.com/purchase-history/" target="_blank"' ),
		'missing'				=> __( 'Something is wrong with the key you entered. Please verify the key and try again.', 'GMW' ),
		'retrieve_key'			=> sprintf( __( 'Lost or forgot your license key? <a %s >Retrieve it here.</a>', 'GMW' ), 'href="http://geomywp.com/purchase-history/" target="_blank"' ),
		'activation_error'		=> __( 'Your license for %s plugin could not be activated. See error message below!', 'GMW' ),
		'item_name_mismatch'	=> __( 'An error occurred while trying to activate your %s license. ERROR item_name_mismatch', 'GMW' )
	) );
	
}

/**
 * GMW Update license key API activate/deactivate
 *
 * @since  2.5
 * @author Eyal Fitoussi
 */
function gmw_update_key_api() {

	//check if updating license key
	if ( empty( $_POST['gmw_license']['submitted'] ) )
		return;
	
	//get the license being updated
	$license_name = key( $_POST['gmw_license']['submitted'] );
	$data 	      = $_POST['gmw_license'][$license_name];

	// run a quick security check
	if ( ! check_admin_referer( 'gmw_license_update_nonce', $license_name.'_nonce' ) )
		return;

	// get all licenses info from database
	$licenses = get_option( 'gmw_license_keys' );
	$statuses = get_option( 'gmw_premium_plugin_status' );

	// get submitted info
	$action   	  = ! empty( $data['action'] )    ? $data['action'] : 'activate';
	$license_key  = ! empty( $data['key'] )       ? sanitize_text_field( trim( $data['key'] ) ) : '';
	$item_name    = ! empty( $data['item_name'] ) ? $data['item_name'] : '';
	$item_id      = ! empty( $data['item_id'] )   ? $data['item_id']   : false;
	$page         = ( ! empty( $_GET['page'] ) && $_GET['page'] == 'gmw-add-ons' ) ? 'admin.php?page=gmw-add-ons' : 'plugins.php?';
	
	//if license key field is empty
	if ( empty( $license_key ) ) {
		
		// if we are trying to activate an empty field clear the current license key from database
		if ( $action == 'activate' ) {

			// remove the license from database
			unset( $licenses[$license_name] );
			update_option( 'gmw_license_keys', $licenses );
			
			//relaod
			wp_safe_redirect( admin_url( $page.'&gmw_license_status_notice=no_key_entered&license_name='.$license_name.'&item_name='.str_replace( ' ', '-', $item_name ).'&gmw_notice_status=error' ) );
			exit;

		// otehrwise do nothing
		} else {
			return;
		}
	}
		
	// data to send in our API request
	$api_params = array(
		'edd_action' => $action .'_license',
		'license'    => $license_key,
		'item_name'  => urlencode( $item_name ), // the name of our product in EDD
		'item_id'	 => $item_id
	);
		
	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, GMW_REMOTE_SITE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
		
	// make sure the response came back okay
	if ( is_wp_error( $response ) )
		return false;
		
	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
	// if license succesfully activated
	if ( $license_data->license == 'valid' ) {
		
		$license_notice 			= 'activated';
		$notice_status  			= 'updated';
		$statuses[$license_name] 	= $license_data->license;
		$licenses[$license_name]  	= $license_key;

		update_option( 'gmw_premium_plugin_status', $statuses );
		update_option( 'gmw_license_keys', $licenses );
	
	// if license is invalid show message error
	} elseif ( $license_data->license == 'invalid' ) {
		
		$license_notice 			= $license_data->error;
		$notice_status  			= 'error';
		$statuses[$license_name] 	= $license_data->error;
		$licenses[$license_name]  	= $license_key;
		
		update_option( 'gmw_premium_plugin_status', $statuses );
		update_option( 'gmw_license_keys', $licenses );
				
	// if license deactivated of failed
	} elseif ( $license_data->license == 'deactivated' || $license_data->license == 'failed' ) {
		
		$license_notice = 'deactivated';
		$notice_status  = 'updated';
		
		unset( $statuses[$license_name] );
		update_option( 'gmw_premium_plugin_status', $statuses );
	}
	
	//reload the page to prevent resubmission
	wp_safe_redirect( admin_url( $page.'&gmw_license_status_notice='.$license_notice.'&license_name='.$license_name.'&item_name='.str_replace( ' ', '-', $item_name ).'&gmw_notice_status='.$notice_status ) );
	exit;
	
}
add_action( 'admin_init', 'gmw_update_key_api');

/**
 * Check license status
 * @param unknown_type $addon
 * @return boolean
 */
function gmw_is_license_valid( $addon ) {

	$licenses = get_option( 'gmw_license_keys' );
	$statuses = get_option('gmw_premium_plugin_status');

	if ( !empty( $statuses[$addon] ) && $statuses[$addon] == 'valid' && !empty( $licenses[$addon] ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * GMW Cheack Licenses
 * 
 * Do check of licenses every 72 hours to varify that thier status is correct
 * 
 * @since  2.5
 * @author Eyal Fitoussi
 */
function gmw_check_license() {

	//run licenses check every 96 hours just to make sure that their status is correct
	if ( get_transient( 'gmw_licenses_check_transient' ) == true )
		return;
	
	//set new transient
	set_transient( 'gmw_licenses_check_transient' , true, 60*60*96 );
		
	$licenses = get_option( 'gmw_license_keys' );
	
	if ( empty( $licenses ) )
		return;
	
	$statuses = get_option( 'gmw_premium_plugin_status' );

	foreach ( $licenses as $license_name => $license ) {
		
		if ( isset( $statuses[$license_name] ) && $statuses[$license_name] == 'valid' ) {
					
			$this_license = trim( $license );
			$this_name    = ucwords( str_replace( '_', ' ', $license_name ) );
		
			$api_params = array(
					'edd_action' => 'check_license',
					'license'    => $this_license,
					'item_name'  => urlencode( $this_name )
			);
		
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, GMW_REMOTE_SITE_URL ), array(
					'timeout' 	=> 15,
					'sslverify' => false
			));
		
			if ( is_wp_error( $response ) )
				return false;
		
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				
			if ( !isset( $license_data ) ) {
				$statuses[$license_name] = 'inactive';
			} else {
				$statuses[$license_name] = $license_data->license;
			}
			
			// $license_data->license will be either "active" or "inactive"
			update_option( 'gmw_premium_plugin_status', $statuses );

		}
	}
}
add_action( 'admin_init', 'gmw_check_license');

/**
 * License API status Notices
 * 
 */
function gmw_update_api_notices() {

	//check if updating license key
	if (  empty( $_GET['gmw_license_status_notice'] ) )
		return;
	
	$statuses  	  = get_option( 'gmw_premium_plugin_status' );
	$messages  	  = gmw_update_key_api_notices();
	$item_name 	  = str_replace( '-', ' ', $_GET['item_name'] );
	$license_name = $_GET['license_name'];
	?>
	<div class="<?php echo $_GET['gmw_notice_status']; ?>">
		<p>
		<?php esc_html( printf( $messages[$_GET['gmw_license_status_notice']], $item_name ) ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'gmw_update_api_notices' );

endif;
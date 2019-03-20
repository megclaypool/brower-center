<?php
/*
Plugin Name: BulkPress
Description: Create and manage (restructure hierarchy) vast amounts of categories, terms of custom taxonomies, posts, pages and posts of custom post types in the blink of an eye! The easy-to-use interface allows you to quickly create both hierarchical and non-hierarchical posts and terms by just speciying the title and optionally the slug, allowing you to quickly populate your website with content.
Version: 0.3.5
Author: Jesper van Engelen
Author URI: http://www.jepps.nl
License: GPLv2 or later
*/

/**
 * @since 0.3
 */
class JWBP_Plugin
{

	/**
	 * Holds the only instance of this plugin
	 *
	 * @static
	 * @var JWBP_Plugin
	 * @access private
	 * @since 0.3
	 */
	private static $_instance = NULL;
	
	/**
	 * Plugin version
	 *
	 * @var string
	 * @access protected
	 * @since 0.3
	 */
	protected $version = '0.3.5';
	
	/**
	 * The absolute path of the plugin file
	 *
	 * @var string
	 * @access protected
	 * @since 0.3
	 */
	protected $plugin_file_path = '';
	
	/**
	 * The absolute path of the plugin directory
	 *
	 * @var string
	 * @access protected
	 * @since 0.3
	 */
	protected $plugin_dir_path = '';
	
	/**
	 * Constructor
	 *
	 * @since 0.3
	 */
	private function __construct() {}
	
	/**
	 * Initialize
	 *
	 * @since 0.3
	 */
	private function init()
	{
		// Actions
		add_action( 'plugins_loaded', array( &$this, 'finish_setup' ) );
		add_action( 'init', array( &$this, 'localize' ) );
		
		require_once $this->get_plugin_dir_path() . 'lib/ajax.php';
		
		if ( is_admin() ) {
			require_once $this->get_plugin_dir_path() . 'lib/admin.php';
		}
		
		// Activation hook
		register_activation_hook( $this->get_plugin_file_path(), array( &$this, 'plugin_activate' ) );
	}
	
	/**
	 * Get the instance of this class, insantiating it if it doesn't exist yet
	 *
	 * @since 0.3
	 *
	 * @return JWBP_Plugin Class instance
	 */
	public static function get_instance()
	{
		if ( !is_object( self::$_instance ) ) {
			self::$_instance = new JWBP_Plugin();
			self::$_instance->init();
		}
		
		return self::$_instance;
	}
	
	/**
	 * Handle localization, loading the plugin textdomain
	 *
	 * @since 0.3
	 */
	public function localize()
	{
		load_plugin_textdomain( 'bulkpress', false, dirname( plugin_basename( $this->get_plugin_file_path() ) ) . '/languages/' );
	}
	
	/**
	 * Handle final aspects of plugin setup, such as adding action hooks
	 *
	 * @since 0.3
	 */
	public function finish_setup()
	{
		do_action( 'jwbp/after_setup', $this );
	}
	
	/**
	 * Handle inital installation and upgrading of the plugin
	 *
	 * @since 0.3
	 */
	public function plugin_activate()
	{
		$version = $this->get_version();
		$db_version = get_option( 'jwbp_version' );
		
		$difference = version_compare( $db_version, $version );
		
		if ($difference != 0) {
			// Save new version
			update_option( 'jwbp_version', $this->get_version() );
		}
	}
	
	/**
	 * Get the main plugin file path
	 *
	 * @since 0.3
	 *
	 * @return string Absolute path to the main plugin file
	 */
	public function get_plugin_file_path()
	{
		if ( empty( $this->plugin_file_path ) ) {
			$this->plugin_file_path =  WP_PLUGIN_DIR . '/bulkpress/' . basename( __FILE__ );
		}
		
		return $this->plugin_file_path;
	}
	
	/**
	 * Get the main plugin file path
	 *
	 * @since 0.3
	 *
	 * @return string Absolute path to the main plugin file
	 */
	public function get_plugin_dir_path()
	{
		if ( empty( $this->plugin_dir_path ) ) {
			$this->plugin_dir_path = plugin_dir_path( $this->get_plugin_file_path() );
		}
		
		return trailingslashit( $this->plugin_dir_path );
	}
	
	/**
	 * Get the plugin version
	 *
	 * @since 0.3
	 *
	 * @return string Plugin version
	 */
	public function get_version()
	{
		return $this->version;
	}

}

JWBP_Plugin::get_instance();
?>
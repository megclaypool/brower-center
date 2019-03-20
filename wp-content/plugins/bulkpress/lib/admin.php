<?php
/**
 * @since 0.3
 */
class JWBP_Admin
{

	/**
	 * List of menu page instances (JWBP_AdminMenuPage_Abstract) added
	 *
	 * @since 0.3
	 * @var array
	 */
	public static $menupages = array();
	
	/**
	 * Using a private constructor to make sure the class doesn't get instantiated
	 *
	 * @since 0.3
	 * @access private
	 */
	private function __construct() {}
	
	/**
	 * Initialize functionality
	 *
	 * @since 0.3
	 */
	public static function init()
	{
		add_action( 'admin_enqueue_scripts', array( 'JWBP_Admin', 'scripts' ) );
		
		// Admin menu
		require_once JWBP_Plugin::get_instance()->get_plugin_dir_path() . 'lib/classes/AdminMenuPage/class.BulkPress.php';
		require_once JWBP_Plugin::get_instance()->get_plugin_dir_path() . 'lib/classes/AdminMenuPage/class.Terms.php';
		require_once JWBP_Plugin::get_instance()->get_plugin_dir_path() . 'lib/classes/AdminMenuPage/class.Posts.php';
		//require_once JWBP_Plugin::get_instance()->get_plugin_dir_path() . 'lib/classes/AdminMenuPage/class.Users.php';
		require_once JWBP_Plugin::get_instance()->get_plugin_dir_path() . 'lib/classes/AdminMenuPageTab/class.AddTerms.php';
		require_once JWBP_Plugin::get_instance()->get_plugin_dir_path() . 'lib/classes/AdminMenuPageTab/class.AddPosts.php';
		//require_once JWBP_Plugin::get_instance()->get_plugin_dir_path() . 'lib/classes/AdminMenuPageTab/class.AddUsers.php';
		
		$page = JWBP_Admin::$menupages['JWBP_AdminMenuPage_BulkPress'] = new JWBP_AdminMenuPage_BulkPress();
		
		$page = JWBP_Admin::$menupages['JWBP_AdminMenuPage_Terms'] = new JWBP_AdminMenuPage_Terms();
		$page->add_tab( new JWBP_AdminMenuPageTab_AddTerms() );
		
		$page = JWBP_Admin::$menupages['JWBP_AdminMenuPage_Posts'] = new JWBP_AdminMenuPage_Posts();
		$page->add_tab( new JWBP_AdminMenuPageTab_AddPosts() );
		
		//$page = JWBP_Admin::$menupages['JWBP_AdminMenuPage_Users'] = new JWBP_AdminMenuPage_Users();
		//$page->add_tab( new JWBP_AdminMenuPageTab_AddUsers() );
	}
	
	/**
	 * Register and enqueue scripts and styles
	 *
	 * @since 0.3
	 */
	public static function scripts()
	{
		// Scripts
		wp_register_script( 'jquery-linedtextarea', plugins_url( '/external/linedtextarea/jquery-linedtextarea.js', JWBP_Plugin::get_instance()->get_plugin_file_path() ), array( 'jquery' ), JWBP_Plugin::get_instance()->get_version() );
		
		wp_register_script( 'jwbp-admin-bulkpress-posts-add', plugins_url( 'assets/js/admin-bulkpress-posts-add.js', JWBP_Plugin::get_instance()->get_plugin_file_path() ), array( 'jquery' ), JWBP_Plugin::get_instance()->get_version() );
		wp_register_script( 'jwbp-admin-bulkpress-terms-add', plugins_url( 'assets/js/admin-bulkpress-terms-add.js', JWBP_Plugin::get_instance()->get_plugin_file_path() ), array( 'jquery' ), JWBP_Plugin::get_instance()->get_version() );
		//wp_register_script( 'jwbp-admin-bulkpress-users-add', plugins_url( 'assets/js/admin-bulkpress-users-add.js', JWBP_Plugin::get_instance()->get_plugin_file_path() ), array( 'jquery' ), JWBP_Plugin::get_instance()->get_version() );
		wp_register_script( 'jwbp-admin-bulkpress', plugins_url( '/assets/js/admin-bulkpress.js', JWBP_Plugin::get_instance()->get_plugin_file_path() ), array( 'jquery', 'jquery-linedtextarea' ), JWBP_Plugin::get_instance()->get_version() );
		
		// Styles
		wp_register_style( 'jquery-linedtextarea', plugins_url( '/external/linedtextarea/jquery-linedtextarea.css', JWBP_Plugin::get_instance()->get_plugin_file_path() ), array(), JWBP_Plugin::get_instance()->get_version() );
		
		wp_register_style( 'jwbp-admin-bulkpress', plugins_url( '/assets/css/admin-bulkpress.css', JWBP_Plugin::get_instance()->get_plugin_file_path() ), array(), JWBP_Plugin::get_instance()->get_version() );
	}

}

JWBP_Admin::init();
?>
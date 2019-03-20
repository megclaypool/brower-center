<?php
require_once dirname( __FILE__ ) . '/class.Abstract.php';

/**
 * @since 0.3
 */
class JWBP_AdminMenuPage_Posts extends JWBP_AdminMenuPage_Abstract
{

	/**
	 * Constructor
	 *
	 * @since 0.3
	 */
	public function __construct()
	{
		// Construct
		parent::__construct();
		
		// Menu item settings
		$this->id = 'bulkpress-posts';
		$this->parent_id = 'bulkpress';
		$this->page_title = __( 'BulkPress: Posts', 'bulkpress' );
		$this->menu_title = __( 'Posts' );
		$this->capability = 'manage_options';
	}

}
?>
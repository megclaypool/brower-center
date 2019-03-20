<?php
require_once dirname( __FILE__ ) . '/class.Abstract.php';

/**
 * @since 0.4
 */
class JWBP_AdminMenuPage_Users extends JWBP_AdminMenuPage_Abstract
{

	/**
	 * Constructor
	 *
	 * @since 0.4
	 */
	public function __construct()
	{
		// Construct
		parent::__construct();
		
		// Menu item settings
		$this->id = 'bulkpress-users';
		$this->parent_id = 'bulkpress';
		$this->page_title = __( 'BulkPress: Users', 'bulkpress' );
		$this->menu_title = __( 'Users' );
		$this->capability = 'create_users';
	}

}
?>
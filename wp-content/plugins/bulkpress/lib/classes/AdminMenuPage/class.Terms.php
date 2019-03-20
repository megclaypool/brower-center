<?php
require_once dirname( __FILE__ ) . '/class.Abstract.php';

/**
 * @since 0.3
 */
class JWBP_AdminMenuPage_Terms extends JWBP_AdminMenuPage_Abstract
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
		$this->id = 'bulkpress-terms';
		$this->parent_id = 'bulkpress';
		$this->page_title = __( 'BulkPress: Terms', 'bulkpress' );
		$this->menu_title = __( 'Terms' );
		$this->capability = 'manage_options';
	}

}
?>
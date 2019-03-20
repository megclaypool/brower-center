<?php
require_once dirname( __FILE__ ) . '/class.Abstract.php';

/**
 * @since 0.3
 */
class JWBP_AdminMenuPage_BulkPress extends JWBP_AdminMenuPage_Abstract
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
		$this->id = 'bulkpress';
		$this->page_title = __( 'BulkPress', 'bulkpress' );
		$this->menu_title = __( 'BulkPress', 'bulkpress' );
		$this->capability = 'manage_options';
		
		// Actions
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
	}
	
	/**
	 * Register and enqueue scripts
	 *
	 * @since 0.3
	 */
	public function scripts( $hookname )
	{
		$screen = get_current_screen();
		
		foreach ( JWBP_Admin::$menupages as $index => $menupage ) {
			if ( $menupage->parent_id == $this->id && $menupage->hookname == $hookname ) {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-linedtextarea' );
				wp_enqueue_script( 'jwbp-admin-bulkpress' );
				wp_enqueue_style( 'jquery-linedtextarea' );
				wp_enqueue_style( 'jwbp-admin-bulkpress' );
				break;
			}
		}
	}
	
	/**
	 * Output the menu page contents
	 *
	 * @since 0.3
	 */
	public function display()
	{
		$posts_link = '<a href="' . admin_url( 'admin.php?page=bulkpress-posts' ) . '" title="' . esc_attr__( 'BulkPress: Posts' ) . '">' . esc_attr__( 'posts' ) . '</a>';
		$terms_link = '<a href="' . admin_url( 'admin.php?page=bulkpress-terms' ) . '" title="' . esc_attr__( 'BulkPress: Terms' ) . '">' . esc_attr__( 'terms' ) . '</a>';
		?>
		<div class="wrap">
			<h2><?php _e( 'BulkPress', 'bulkpress' ); ?></h2>
			<p><?php printf( __( 'Easily create %s and %s in bulk.', 'bulkpress' ), $posts_link, $terms_link ); ?></p>
		</div>
		<?php
	}

}
?>
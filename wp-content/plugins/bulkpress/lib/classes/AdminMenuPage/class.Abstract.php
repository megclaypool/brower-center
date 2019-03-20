<?php
class JWBP_AdminMenuPage_Abstract
{

	/**
	 * Menu page ID, used as menu_slug in add_menu_page or add_submenu_page
	 *
	 * @since 0.3
	 *
	 * @var string
	 */
	protected $id;
	
	/**
	 * Parent menu page ID, used as parent_slug in add_submenu_page if supplied
	 *
	 * @since 0.3
	 *
	 * @var string
	 */
	protected $parent_id;
	
	/**
	 * Menu page title
	 *
	 * @since 0.3
	 *
	 * @var string
	 */
	protected $page_title;
	
	/**
	 * Menu item title
	 *
	 * @since 0.3
	 *
	 * @var string
	 */
	protected $menu_title;
	
	/**
	 * Capability required to access the page
	 *
	 * @since 0.3
	 *
	 * @var string
	 */
	protected $capability;
	
	/**
	 * URL of the icon to show for the menu item
	 *
	 * @since 0.3
	 *
	 * @var string
	 */
	protected $icon_url = '';
	
	/**
	 * Position of the menu item within the menu
	 *
	 * @since 0.3
	 *
	 * @var int
	 */
	protected $position = NULL;
	
	/**
	 * Callback function for displaying the page content
	 *
	 * @since 0.3
	 *
	 * @var string|array
	 */
	protected $callback;
	
	/**
	 * Menu hook, set when registering the menu
	 *
	 * @since 0.3
	 *
	 * @var string
	 */
	protected $hookname;
	
	/**
	 * Callback function for handling the logic of the page
	 *
	 * @since 0.3
	 *
	 * @var string|array
	 */
	protected $handle_callback;
	
	/**
	 * List of tab objects that are used for this page
	 *
	 * @since 0.3
	 *
	 * @var array
	 */
	protected $tabs = array();
	
	/**
	 * Current tab ID
	 *
	 * @since 0.3
	 *
	 * @var string
	 */
	protected $current_tab;
	
	/**
	 * Message to display as admin notices
	 *
	 * @since 0.3
	 *
	 * @var array
	 */
	public $messages = array();
	
	/**
	 * Constructor
	 *
	 * @since 0.3
	 */
	public function __construct()
	{
		// Actions
		add_action( 'admin_menu', array( &$this, 'register_menu' ) );
	}
	
	/************************
	 * Main functionality
	 ***********************/
	
	/**
	 * Register the menu to WordPress
	 *
	 * @since 0.3
	 */
	public function register_menu()
	{
		$callback = $this->get_callback();
		
		// Add menu item
		
		if ( $this->parent_id ) {
			$this->hookname = add_submenu_page( $this->parent_id, $this->page_title, $this->menu_title, $this->capability, $this->id, $callback );
		}
		else {
			$this->hookname = add_menu_page( $this->page_title, $this->menu_title, $this->capability, $this->id, $callback, $this->icon_url, $this->position );
		}
		
		// Hook into loading of this page for handling the logic of this page
		add_action( 'load-' . $this->hookname, $this->get_handle_callback() );
		add_action( 'load-' . $this->hookname, array( $this, 'handle_current_tab' ) );
	}
	
	/**
	 * Handle logic of the page
	 *
	 * @since 0.3
	 */
	public function handle() {}
	
	/**
	 * Check current tab and store its id
	 *
	 * @since 0.3
	 */
	public function handle_current_tab()
	{
		if ( !$this->is_tabbed() ) {
			return;
		}
		
		$this->current_tab = false;
		
		// Get current tab
		if ( isset( $_GET['tab'] ) ) {
			$tab = $this->get_tab( $_GET['tab'] );
			
			if ( $tab !== false ) {
				$this->current_tab = $tab->get_id();
			}
		}
		
		if ( $this->current_tab === false ) {
			// Use first tab as default current tab
			reset( $this->tabs );
			
			$this->current_tab = current( $this->tabs )->get_id();
		}
		
		// Handle tab logic
		call_user_func( $this->get_tab( $this->get_current_tab() )->get_handle_callback() );
	}
	
	/**
	 * Output the page contents
	 *
	 * @since 0.3
	 */
	public function display()
	{
		?>
		<h1><?php echo $this->page_title; ?></h1>
		<?php $this->nav_tabs(); ?>
		<div class="wrap">
			<?php call_user_func( $this->get_tab( $this->get_current_tab() )->get_callback() ); ?>
		</div>
		<?php
	}
	
	/**
	 * Output the tab nagiation
	 *
	 * @since 0.3
	 */
	public function nav_tabs()
	{
		if ( $this->is_tabbed() ) {
			$current_tab = $this->get_current_tab();
			?>
			<h2 class="nav-tab-wrapper">
				<?php foreach ( $this->tabs as $index => $tab ) : ?>
					<a href="<?php echo add_query_arg( 'tab', $tab->get_id() ); ?>" class="nav-tab<?php if ( $current_tab == $tab->get_id() ) echo ' nav-tab-active'; ?>"><?php echo $tab->get_title(); ?></a>
				<?php endforeach; ?>
			</h2>
			<?php
		}
	}
	
	/************************
	 * Messages
	 ***********************/
	
	/**
	 * Add a message to display as an admin notice
	 *
	 * @since 0.3
	 *
	 * @param string $message Message content
	 * @param string $type Optional. Message type, either "updated" (default) or "error", used as CSS class for the message element
	 */
	public function add_message( $message, $type = 'updated' )
	{
		$this->messages[] = array(
			'content' => $message,
			'type' => $type
		);
	}

	/**
	 * Display messages
	 *
	 * @since 0.3
	 */
	public function display_messages()
	{
		foreach ( $this->messages as $index => $message ) {
			?>
			<div class="<?php echo esc_attr( $message['type'] ); ?>"><?php echo wpautop( $message['content'] ); ?></div>
			<?php
		}
	}
	
	/************************
	 * Tabs
	 ***********************/
	
	/**
	 * Add a tab to this menu page
	 *
	 * @since 0.3
	 *
	 * @param stdObject $tab Tab object
	 */
	public function add_tab( $tab )
	{
		if ( !$tab->get_id() || !$tab->get_title() ) {
			return;
		}
		
		$this->tabs[ $tab->get_id() ] = $tab;
		$tab->menupage = $this;
	}
	
	/************************
	 * Menu information
	 ***********************/
	
	/**
	 * Get the ID for this menu page
	 *
	 * @since 0.3
	 *
	 * @return string Menu page ID
	 */
	public function get_id()
	{
		return $this->id;
	}
	
	/**
	 * Get the current tab id
	 * Only call this after the current tab has been set
	 *
	 * @since 0.3
	 *
	 * @return string|bool Current tab ID on success, false on failure
	 */
	public function get_current_tab()
	{
		return $this->current_tab ? $this->current_tab : false;
	}
	
	/**
	 * Get a tab by its id
	 *
	 * @since 0.3
	 *
	 * @param string $tabid Tab ID of tab to fetch
	 * @return JWBP_AdminMenuPageTab_Abstract|bool Returns the tab object on success, false otherwise
	 */
	public function get_tab( $tabid )
	{
		return isset( $this->tabs[ $tabid ] ) ? $this->tabs[ $tabid ] : false;
	}
	
	/**
	 * Get tabs associated with this menu page
	 *
	 * @since 0.3
	 *
	 * @return array Returns the tab objects (JWBP_AdminMenuPageTab_Abstract) for this menu page
	 */
	public function get_tabs()
	{
		return $this->tabs;
	}
	
	/**
	 * Get the callback to use for displaying this menu item
	 *
	 * @since 0.3
	 *
	 * @return string|array Callback used
	 */
	public function get_callback()
	{
		return $this->callback ? $this->callback : array( $this, 'display' );
	}
	
	/**
	 * Get the callback to use for handling the logic of this menu item
	 *
	 * @since 0.3
	 *
	 * @return string|array Callback used
	 */
	public function get_handle_callback()
	{
		return $this->handle_callback ? $this->handle_callback : array( $this, 'handle' );
	}
	
	/**
	 * Get whether the current menu page has tabs enabled
	 *
	 * @since 0.3
	 *
	 * @return bool Returns true when tabs are enabled, false otherwise
	 */
	public function is_tabbed()
	{
		return ( is_array( $this->tabs ) && !empty( $this->tabs ) ) ? true : false;
	}
	
	/**
	 * Get the menu page hook name
	 *
	 * @since 0.3
	 *
	 * @return string Hook name
	 */
	public function get_hookname()
	{
		return $this->hookname;
	}

}
?>
<?php
require_once dirname( __FILE__ ) . '/class.Abstract.php';

/**
 * @since 0.4
 */
class JWBP_AdminMenuPageTab_AddUsers extends JWBP_AdminMenuPageTab_Abstract
{

	/**
	 * Status of adding users
	 *
	 * @since 0.4
	 * @var string
	 */
	public $status;
	
	/**
	 * Items in the queue for being added
	 *
	 * @since 0.4
	 * @var array
	 */
	public $items = array();
	
	/**
	 * Constructor
	 *
	 * @since 0.4
	 */
	public function __construct()
	{
		// Construct
		parent::__construct();
		
		// Tab information
		$this->id = 'users-add';
		$this->title = __( 'Add Users', 'bulkpress' );
		
		// Actions
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
	}
	
	public function get_columns()
	{
		$roles = get_editable_roles();
		$role_options = array();
		
		foreach ( $roles as $index => $role ) {
			$role_options[ $index ] = translate_user_role( $role['name'] );
		}
		
		$columns = array(
			'username' => array(
				'title' => __( 'Username' ),
				'object_property' => 'user_login',
				'active' => true,
				'default_disabled' => true,
				'type' => 'text',
				'required' => true
			),
			'email' => array(
				'title' => __( 'Email' ),
				'object_property' => 'user_email',
				'active' => true,
				'default_disabled' => true,
				'type' => 'text',
				'required' => true
			),
			'password' => array(
				'title' => __( 'Password' ),
				'object_property' => 'user_password',
				'active' => false,
				'default_disabled' => true,
				'type' => 'password',
				'placeholder' => __( 'Auto-generate', 'bulkpress' )
			),
			'role' => array(
				'title' => __( 'Role' ),
				'object_property' => 'role',
				'active' => false,
				'type' => 'select',
				'options' => $role_options,
				'default_default' => 'subscriber'
			),
			'first_name' => array(
				'title' => __( 'First Name' ),
				'object_property' => 'first_name',
				'active' => false,
				'type' => 'text',
				'placeholder' => __( 'Use default', 'bulkpress' )
			),
			'last_name' => array(
				'title' => __( 'Last Name' ),
				'object_property' => 'last_name',
				'active' => false,
				'type' => 'text',
				'placeholder' => __( 'Use default', 'bulkpress' )
			),
			'website' => array(
				'title' => __( 'Website' ),
				'object_property' => 'user_url',
				'active' => false,
				'type' => 'text',
				'placeholder' => __( 'Use default', 'bulkpress' )
			)
		);
		
		$defaults = array(
			'default_default' => '',
			'default' => ''
		);
		
		foreach ( $columns as $index => $column ) {
			$columns[ $index ] = array_merge( $defaults, $column );
		}
		
		return $columns;
	}
	
	/**
	 * Register and enqueue scripts
	 *
	 * @since 0.4
	 */
	public function scripts()
	{
		// Scripts
		wp_enqueue_script( 'jwbp-admin-bulkpress-users-add');
	}
	
	/**
	 * Handle business logic
	 *
	 * @since 0.4
	 */
	public function handle()
	{
		// Check request
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' || empty( $_POST['jwbp-users-add-nonce'] ) || !wp_verify_nonce( $_POST['jwbp-users-add-nonce'], 'jwbp-users-add' ) ) {
			return;
		}
		
		$columns = $this->get_columns();
		
		// Flag to prevent user insertion if global criteria are not met
		$prevent_insert = false;
		
		// Check new items values and build new items list
		$this->items = array();
		
		if ( empty( $_POST['jwbp-addnew-data'][0] ) ) {
			$this->messages[] = array(
				'type' => 'error',
				'content' => sprintf( __( 'There is no data available for users to be added.', 'bulkpress' ), $column['title'] )
			);
			
			$this->status = 'addusers_error';
			
			return;
		}
		
		// Loop through items
		foreach ( $_POST['jwbp-addnew-data'] as $index => $itemdata ) {
			$invalid = false;
			
			// Check index
			if ( !isset( $itemdata['index'] ) || $index != $itemdata['index'] ) {
				$this->messages[] = array(
					'type' => 'error',
					'content' => sprintf( __( 'Invalid index at user <strong>#%d</strong>.', 'bulkpress' ), $index + 1 )
				);
				
				$this->status = 'addusers_error';
				
				$invalid = true;
			}
			
			// Skip row used for adding new rows via JS
			if ( $index === 'JWBP_NEWINDEX' || ( empty( $itemdata['username'] ) && empty( $itemdata['email'] ) ) ) {
				continue;
			}
			
			// Add new item to queue
			$this->items[ $index ] = array(
				'valid' => true,
				'data' => array()
			);
			
			// Check column values
			foreach ( $columns as $index2 => $column ) {
				$required = !empty( $column['required'] );
				$enabled = !empty( $_POST['jwbp-column-active'][ $index2 ] ) || $required;
				$column_invalid = false;
				
				$value = NULL;
				
				if ( !isset( $itemdata[ $index2 ] ) ) {
					if ( $enabled ) {
						$this->messages[] = array(
							'type' => 'error',
							'content' => sprintf( __( 'Invalid value for <em>%s</em> at user <strong>#%d</strong>.', 'bulkpress' ), $column['title'], $index + 1 )
						);
						
						$this->status = 'addusers_error';
						
						$column_invalid = true;
					}
				}
				else {
					$value = $itemdata[ $index2 ];
					
					if ( $required && !$itemdata[ $index2 ] ) {
						$this->messages[] = array(
							'type' => 'error',
							'content' => sprintf( __( 'Required field <em>%s</em> left blank at user <strong>#%d</strong>.', 'bulkpress' ), $column['title'], $index + 1 )
						);
						
						$this->status = 'addusers_error';
						
						$column_invalid = true;
					}
					else if ( $enabled ) {
						$invalid_local = false;
						
						// Check value for column type-specific requirements
						switch ( $column['type'] ) {
						case 'select':
							if ( !isset( $column['options'][ $value ] ) && ( $value || !empty( $column['default_disabled'] ) ) ) {
								$invalid_local = true;
							}
							break;
						}
						
						if ( $index2 == 'email' && !is_email( $value ) ) {
							$invalid_local = true;
						}
						
						if ( $invalid_local ) {
							$this->messages[] = array(
								'type' => 'error',
								'content' => sprintf( __( 'Value for <em>%s</em> at user <strong>#%d</strong> is invalid.', 'bulkpress' ), $column['title'], $index + 1 )
							);
							
							$this->status = 'addusers_error';
							
							$column_invalid = true;
						}
					}
				}
				
				if ( $column_invalid ) {
					$invalid = true;
				}
				
				if ( $enabled && $value !== NULL ) {
					$this->items[ $index ]['data'][ $index2 ] = $value;
				}
			}
			
			if ( $invalid ) {
				$this->items[ $index ]['valid'] = false;
			}
		}
		
		// Check defaults
		foreach ( $columns as $index => $column ) {
			// Handle only columns that can have a default value
			if ( empty( $column['default_disabled'] ) ) {
				$invalid = false;
				
				if ( !isset( $_POST[ 'jwbp-addnew-defaults' ][ $index ] ) ) {
					$invalid = true;
				}
				else {
					// Check default value for column type-specific requirements
					$default = $_POST[ 'jwbp-addnew-defaults' ][ $index ];
					
					switch ( $column['type'] ) {
					case 'select':
						if ( !isset( $column['options'][ $default ] ) ) {
							$invalid = true;
						}
						break;
					}
				}
				
				// Cast error if the default value is invalid
				if ( $invalid ) {
					$this->messages[] = array(
						'type' => 'error',
						'content' => sprintf( __( 'The default value for <em>%s</em> is invalid.', 'bulkpress' ), $column['title'] )
					);
					
					$this->status = 'addusers_error';
					
					$prevent_insert = true;
				}
			}
		}
		
		// Add items
		if ( !$prevent_insert ) {
			foreach ( $this->items as $index => $item ) {
				if ( !$item['valid'] ) {
					continue;
				}
				
				$insert_data = array(
					'user_pass' => wp_generate_password()
				);
				
				foreach ( $columns as $index2 => $column ) {
					if ( !empty( $column['object_property'] ) && !empty( $_POST[ 'jwbp-addnew-defaults' ][ $index2 ] ) ) {
						$insert_data[ $column['object_property'] ] = $_POST[ 'jwbp-addnew-defaults' ][ $index2 ];
					}
				}
				
				foreach ( $item['data'] as $index2 => $value ) {
					if ( $value && !empty( $columns[ $index2 ]['object_property'] ) ) {
						$insert_data[ $columns[ $index2 ]['object_property'] ] = $value;
					}
				}
				
				$result = wp_insert_user( $insert_data );
				
				if ( is_wp_error( $result ) ) {
					if ( !empty( $result->errors['existing_user_login'] ) ) {
						$this->messages[] = array(
							'type' => 'error',
							'content' => sprintf( __( 'A user with the username <em>%s</em> at user <strong>#%d</strong> already exists.', 'bulkpress' ), $insert_data['user_login'], $index + 1 )
						);
						
						$this->status = 'addusers_error';
					}
					if ( !empty( $result->errors['existing_user_email'] ) ) {
						$this->messages[] = array(
							'type' => 'error',
							'content' => sprintf( __( 'A user with the email address <em>%s</em> at user <strong>#%d</strong> already exists.', 'bulkpress' ), $insert_data['user_email'], $index + 1 )
						);
						
						$this->status = 'addusers_error';
					}
					
					$this->items[ $index ]['valid'] = false;
				}
			}
		}
		
		$num_items = 0;
		$has_failure = false;
		
		foreach ( $this->items as $index => $item ) {
			if ( $item['valid'] ) {
				$num_items++;
			}
			else {
				$has_failure = true;
			}
		}
		
		if ( $num_items ) {
			// Add message to notify user of completion
			$this->add_message( sprintf( _n( '%d user successfully added.', '%d users successfully added.', $num_items, 'bulkpress' ), $num_items ) );
		}
		
		if ( !$has_failure ) {
			// Change page status
			$this->status = 'addusers_success';
		}
	}
	
	/**
	 * Output tab contents
	 *
	 * @since 0.4
	 */
	public function display()
	{
		$columns = $this->get_columns();
		$num_rows = 1;
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			foreach ( $columns as $index => $column ) {
				$columns[ $index ]['active'] = !empty( $_POST['jwbp-column-active'][ $index ] );
			}
			
			if ( !empty( $_POST['jwbp-addnew-data'] ) && is_array( $_POST['jwbp-addnew-data'] ) ) {
				$num_rows = count( $_POST['jwbp-addnew-data'] ) - 1;
			}
		}
		
		if ( $this->status == 'addusers_success' ) {
			$items = array();
			$num_rows = 1;
		}
		else {
			$items = $this->items;
		}
		?>
		<div class="wrap">
			<form action="" method="post">
				<?php wp_nonce_field( 'jwbp-users-add', 'jwbp-users-add-nonce' ); ?>
				
				<div class="jwbp-select-columns">
					<?php _e( 'Show columns: ', 'bulkpress' ); ?>
					<ul class="subsubsub">
						<?php foreach ( $columns as $index => $column ) : ?>
							<?php if ( empty( $column['required'] ) ) : ?>
								<li>
									<label for="jwbp-column-active-<?php echo $index; ?>">
										<input type="checkbox" name="jwbp-column-active[<?php echo $index; ?>]" id="jwbp-column-active-<?php echo $index; ?>" <?php checked( $column['active'] ); ?> />
										<?php echo $column['title']; ?>
									</label>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="clear"></div>
				<h3><?php _e( 'Settings' ); ?></h3>
				<table class="form-table">
					<tbody>
						<tr>
							<th><?php _e( 'Passwords' ); ?></th>
							<td>
								<?php _e( 'Passwords are automatically generated for every user by default. This setting can be overridden per-user by entering a password for that user.', 'bulkpress' ); ?>
							</td>
						</tr>
						<tr>
							<th><?php _e( 'Send Password?' ); ?></th>
							<td>
								<label for="jwbp-addnew-sendpassword">
									<input type="checkbox" name="jwbp-addnew-sendpassword" id="jwbp-addnew-sendpassword" <?php checked( !empty( $_POST['jwbp-addnew-sendpassword'] ) ); ?>> <?php _e( 'Send the passwords to the users by email', 'bulkpress' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<h3><?php _e( 'Defaults' ); ?></h3>
				<table class="jwbp-addnew-defaults wp-list-table widefat fixed">
					<thead>
						<tr>
							<?php foreach ( $columns as $index => $column ) : ?>
								<?php if ( !empty( $column['default_disabled'] ) ) continue; ?>
								<th class="jwbp-column-<?php echo $index; ?>"><?php echo $column['title']; ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php foreach ( $columns as $index => $column ) : ?>
								<?php if ( !empty( $column['default_disabled'] ) ) continue; ?>
								<?php $currentvalue = !empty( $_POST['jwbp-addnew-defaults'][ $index ] ) ? stripslashes( $_POST['jwbp-addnew-defaults'][ $index ] ) : $column['default_default']; ?>
								<td class="jwbp-column-<?php echo $index; ?>">
									<?php if ( $column['type'] == 'select' ) : ?>
										<select name="jwbp-addnew-defaults[<?php echo $index; ?>]" id="jwbp-addnew-defaults-<?php echo $index; ?>" class="widefat">
											<?php foreach ( $column['options'] as $index2 => $option ) : ?>
												<option value="<?php echo esc_attr( $index2 ); ?>" <?php selected( $index2, $currentvalue ); ?>><?php echo $option; ?></option>
											<?php endforeach; ?>
										</select>
									<?php else : ?>
										<input type="text" class="widefat" name="jwbp-addnew-defaults[<?php echo $index; ?>]" id="jwbp-addnew-defaults-<?php echo $index; ?>" value="<?php echo esc_attr( $currentvalue ); ?>" />
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					</tbody>
				</table>
				<h3><?php _e( 'Users' ); ?></h3>
				<table class="jwbp-addnew wp-list-table widefat fixed">
					<thead>
						<tr>
							<th class="jwbp-addnew-index"></th>
							<?php foreach ( $columns as $index => $column ) : ?>
								<th class="jwbp-column-<?php echo $index; ?>">
									<?php echo $column['title']; ?>
									<?php if ( !empty( $column['required'] ) ) : ?>*<?php endif; ?>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php for ( $i = 0; $i < $num_rows + 1; $i++ ) : ?>
							<?php
							$islast = ( $i == $num_rows );
							$rowindex = $islast ? 'JWBP_NEWINDEX' : $i;
							$rowindex_visual = $islast ? 'JWBP_NEWINDEX_VISUAL' : strval( $i + 1 );
							?>
							<tr class="<?php if ( $i < $num_rows - 1 ) echo ' jwbp-addnew-row-active'; ?><?php if ( $islast ) echo ' jwbp-newitem'; ?>">
								<td class="jwbp-addnew-index">
									<?php echo $rowindex_visual; ?>
									<input type="hidden" name="jwbp-addnew-data[<?php echo $rowindex; ?>][index]" value="<?php echo $rowindex; ?>" />
								</td>
								<?php foreach ( $columns as $index => $column ) : ?>
									<?php $currentvalue = ( isset( $items[ $i ]['data'][ $index ] ) && empty( $items[ $i ]['valid'] ) ) ? $items[ $i ]['data'][ $index ] : $column['default']; ?>
									<td class="jwbp-column-<?php echo $index; ?>">
										<?php if ( $column['type'] == 'select' ) : ?>
											<select name="jwbp-addnew-data[<?php echo $rowindex; ?>][<?php echo $index; ?>]" id="jwbp-addnew-data-<?php echo $rowindex; ?>-<?php echo $index; ?>" class="widefat">
												<?php if ( empty( $column['default_disabled'] ) ) : ?>
													<option value="" <?php selected( '', $currentvalue ); ?>><?php _e( 'Use default', 'bulkpress' ); ?></option>
												<?php endif; ?>
												<?php foreach ( $column['options'] as $index2 => $option ) : ?>
													<option value="<?php echo esc_attr( $index2 ); ?>" <?php selected( $index2, $currentvalue ); ?>><?php echo $option; ?></option>
												<?php endforeach; ?>
											</select>
										<?php elseif ( $column['type'] == 'password' ) : ?>
											<input type="password" class="widefat" name="jwbp-addnew-data[<?php echo $rowindex; ?>][<?php echo $index; ?>]" id="jwbp-addnew-data-<?php echo $rowindex; ?>-<?php echo $index; ?>" <?php if ( !empty( $column['placeholder'] ) ) echo 'placeholder="' . esc_attr( $column['placeholder'] ) . '"'; ?> value="<?php echo esc_attr( $currentvalue ); ?>" />
										<?php else : ?>
											<input type="text" class="widefat" name="jwbp-addnew-data[<?php echo $rowindex; ?>][<?php echo $index; ?>]" id="jwbp-addnew-data-<?php echo $rowindex; ?>-<?php echo $index; ?>" <?php if ( !empty( $column['placeholder'] ) ) echo 'placeholder="' . esc_attr( $column['placeholder'] ) . '"'; ?> value="<?php echo esc_attr( $currentvalue ); ?>" />
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endfor; ?>
					</tbody>
				</table>
				
				<?php submit_button( __( 'Add users', 'bulkpress' ) ); ?>
			</form>
		</div>
		<?php
	}

}
?>
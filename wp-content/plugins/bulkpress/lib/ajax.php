<?php
/**
 * @since 0.3
 */
class JWBP_Ajax
{

	/**
	 * Initialize
	 * Mainly used for registering action and filter hooks
	 *
	 * @since 0.3
	 */
	public static function init()
	{
		// Actions
		add_action( 'wp_ajax_jwbp_ajax_get_posttype', array( 'JWBP_Ajax', 'get_posttype' ) );
		add_action( 'wp_ajax_nopriv_jwbp_ajax_get_posttype', array( 'JWBP_Ajax', 'get_posttype' ) );
		add_action( 'wp_ajax_jwbp_ajax_get_taxonomy', array( 'JWBP_Ajax', 'get_taxonomy' ) );
		add_action( 'wp_ajax_nopriv_jwbp_ajax_get_taxonomy', array( 'JWBP_Ajax', 'get_taxonomy' ) );
	}
	
	/**
	 * Handle AJAX request for getting details for a taxonomy
	 *
	 * @since 0.3
	 */
	public static function get_taxonomy()
	{
		$result = array();
		
		if ( !empty( $_POST['taxonomy'] ) ) {
			$taxonomy = get_taxonomy( $_POST['taxonomy'] );
			
			if ( $taxonomy ) {
				if ( $taxonomy->hierarchical ) {
					$result['terms_select_html'] = wp_dropdown_categories( array( 
						'show_option_none' => __( 'No parent', 'bulkpress' ),
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'taxonomy' => $taxonomy->name,
						'name' => 'jwbp-addterms-topparent',
						'id' => 'jwbp-addterms-topparent',
						'echo' => false,
						'hierarchical' => $taxonomy->hierarchical
					 ) );
				}
				else {
					$result['terms_select_html'] = '<div id="jwbp-addterms-topparent"></div>';
				}
				
				// Add basic taxonomy details to the result
				$result['taxonomy'] = ( object ) array( 
					'name' => $taxonomy->name,
					'hierarchical' => $taxonomy->hierarchical
				 );
			}
			else {
				$result = array( 'error' => 'unknown_taxonomy' );
			}
		}
		else {
			$result = array( 'error' => 'missing_parameters' );
		}
		
		echo json_encode( $result );
		
		exit;
	}
	
	/**
	 * Handle AJAX request for getting details for a post type
	 *
	 * @since 0.3
	 */
	public static function get_posttype()
	{
		$result = array();
		
		if ( !empty( $_POST['posttype'] ) ) {
			$posttype = get_post_type_object( $_POST['posttype'] );
			
			$result['posts_select_html'] = '';
			
			if ( is_object( $posttype ) && $posttype->name ) {
				if ( $posttype->hierarchical ) {
					$result['posts_select_html'] = wp_dropdown_pages( array( 
						'show_option_none' => __( 'No parent', 'bulkpress' ),
						'post_type' => $posttype->name,
						'name' => 'jwbp-addposts-topparent',
						'id' => 'jwbp-addposts-topparent',
						'selected' => !empty( $_POST['jwbp-addposts-topparent'] ) ? $_POST['jwbp-addposts-topparent'] : '',
						'echo' => false,
						'post_status' => array( 'publish', 'draft' )
					 ) );
				}
				else {
					$result['posts_select_html'] = '<div id="jwbp-addposts-topparent"></div>';
				}
				
				// Add basic post type details to the result
				$result['posttype'] = (object) array( 
					'name' => $posttype->name,
					'hierarchical' => $posttype->hierarchical
				 );
			}
			else {
				$result = array( 'error' => 'unknown_posttype' );
			}
		}
		else {
			$result = array( 'error' => 'missing_parameters' );
		}
		
		echo json_encode( $result );
		
		exit;
	}

}

JWBP_Ajax::init();
?>
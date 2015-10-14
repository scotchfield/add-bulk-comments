<?php
/**
 * Plugin Name: Add Bulk Comments
 * Plugin URI: http://scootah.com/
 * Description: Add bulk anonymous comments to posts.
 * Version: 1.0
 * Author: Scott Grant
 * Author URI: http://scootah.com/
 */
class WP_AddBulkComments {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post_meta' ) );
	}

	/**
	 * Initialize meta box.
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'add-bulk-comments',
			'Add Bulk Comments',
			array( $this, 'generate_meta_box' ),
			'',
			'normal'
		);
	}

	/**
	 * Show HTML for the zone details stored in post meta.
	 */
	public function generate_meta_box( $post ) {
		$post_id = intval( $post->ID );
		$post_url = get_post_meta( $post_id, 'add-bulk-comments', true );

		echo '<p>Number of bulk comments to add to post: ' .
			'<input type="text" name="add-bulk-comments" value="' .
			$post_url . '"></p>';
	}

	/**
	 * Extract the updates from $_POST and save in post meta.
	 */
	public function save_post_meta( $post_id ) {
		if ( isset( $_POST[ 'add-bulk-comments' ] ) ) {
			$n = intval( $_POST[ 'add-bulk-comments' ] );
			$data = array(
				'comment_post_ID' => $post_id,
				'comment_author' => 'Bulk Comment',
				'comment_content' => 'Content',
				'comment_approved' => 1,
			);

			for ( $i = 0; $i < $n; $i += 1 ) {
				$data['comment_content'] = wp_generate_password( 12, false );

				wp_insert_comment( $data );
			}
		}
	}

}

$wp_addbulkcomments = new WP_AddBulkComments();

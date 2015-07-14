<?php

/**
 * Class _WP_Comments
 */
class _WP_Comments extends WP_Module {

	static function on_load() {

		WP::register_helper( __CLASS__ );

	}

	function add_comments_admin_menu() {
		global $menu, $submenu;
		$awaiting_mod = wp_count_comments();
		$awaiting_mod = $awaiting_mod->moderated;
		$menu[25]     = array(
			sprintf( __( 'Comments %s' ),
				"<span class='awaiting-mod count-$awaiting_mod'><span class='pending-count'>" . number_format_i18n( $awaiting_mod ) . "</span></span>" ),
			'edit_posts',
			'edit-comments.php',
			'',
			'menu-top menu-icon-comments',
			'menu-comments',
			'dashicons-admin-comments'
		);
		unset( $awaiting_mod );

		$submenu['edit-comments.php'][0] = array( __( 'All Comments' ), 'edit_posts', 'edit-comments.php' );
	}

	function the_comments_dashboard_right_now() {

		// Comments
		$num_comm = wp_count_comments();
		if ( $num_comm && $num_comm->approved ) {
			$text = sprintf( _n( '%s Comment', '%s Comments', $num_comm->approved ), number_format_i18n( $num_comm->approved ) );
			?>
			<li class="comment-count"><a href="edit-comments.php"><?php echo $text; ?></a></li>
			<?php
			if ( $num_comm->moderated ) {
				/* translators: Number of comments in moderation */
				$text = sprintf( _nx( '%s in moderation', '%s in moderation', $num_comm->moderated, 'comments' ),
					number_format_i18n( $num_comm->moderated ) );
				?>
				<li class="comment-mod-count"><a href="edit-comments.php?comment_status=moderated"><?php echo $text; ?></a></li>
				<?php
			}
		}

	}

	/**
	 * @return bool
	 */
	function recent_dashboard_comments() {

		return wp_dashboard_recent_comments();

	}
}

_WP_Comments::on_load();

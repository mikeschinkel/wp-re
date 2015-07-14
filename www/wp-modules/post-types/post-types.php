<?php

/**
 * Class _WP_Post_Types
 */
class _WP_Post_Types extends WP_Module {

	static function on_load() {

		WP::register_helper( __CLASS__ );

	}

	function on_register() {

		add_action( 'init', array( $this, 'create_initial_post_types' ), 0 ); // highest priority

	}

	function add_post_types_admin_menu() {
		global $menu, $submenu;
		$menu[5]                = array(
			__( 'Posts' ),
			'edit_posts',
			'edit.php',
			'',
			'open-if-no-js menu-top menu-icon-post',
			'menu-posts',
			'dashicons-admin-post'
		);
		$submenu['edit.php'][5] = array( __( 'All Posts' ), 'edit_posts', 'edit.php' );
		/* translators: add new post */
		$submenu['edit.php'][10] = array( _x( 'Add New', 'post' ), get_post_type_object( 'post' )->cap->create_posts, 'post-new.php' );

		$i = 15;
		foreach ( get_taxonomies( array(), 'objects' ) as $tax ) {
			if ( ! $tax->show_ui || ! $tax->show_in_menu || ! in_array( 'post', (array) $tax->object_type, true ) ) {
				continue;
			}

			$submenu['edit.php'][ $i ++ ] = array(
				esc_attr( $tax->labels->menu_name ),
				$tax->cap->manage_terms,
				'edit-tags.php?taxonomy=' . $tax->name
			);
		}
		unset( $tax );

		$menu[10] = array( __('Media'), 'upload_files', 'upload.php', '', 'menu-top menu-icon-media', 'menu-media', 'dashicons-admin-media' );
			$submenu['upload.php'][5] = array( __('Library'), 'upload_files', 'upload.php');
			/* translators: add new file */
			$submenu['upload.php'][10] = array( _x('Add New', 'file'), 'upload_files', 'media-new.php');
			foreach ( get_taxonomies_for_attachments( 'objects' ) as $tax ) {
				if ( ! $tax->show_ui || ! $tax->show_in_menu )
					continue;

				$submenu['upload.php'][$i++] = array( esc_attr( $tax->labels->menu_name ), $tax->cap->manage_terms, 'edit-tags.php?taxonomy=' . $tax->name . '&amp;post_type=attachment' );
			}
			unset($tax);

		$menu[20]                              = array(
			__( 'Pages' ),
			'edit_pages',
			'edit.php?post_type=page',
			'',
			'menu-top menu-icon-page',
			'menu-pages',
			'dashicons-admin-page'
		);
		$submenu['edit.php?post_type=page'][5] = array( __( 'All Pages' ), 'edit_pages', 'edit.php?post_type=page' );
		/* translators: add new page */
		$submenu['edit.php?post_type=page'][10] = array(
			_x( 'Add New', 'page' ),
			get_post_type_object( 'page' )->cap->create_posts,
			'post-new.php?post_type=page'
		);
		$i                                      = 15;
		foreach ( get_taxonomies( array(), 'objects' ) as $tax ) {
			if ( ! $tax->show_ui || ! $tax->show_in_menu || ! in_array( 'page', (array) $tax->object_type, true ) ) {
				continue;
			}

			$submenu['edit.php?post_type=page'][ $i ++ ] = array(
				esc_attr( $tax->labels->menu_name ),
				$tax->cap->manage_terms,
				'edit-tags.php?taxonomy=' . $tax->name . '&amp;post_type=page'
			);
		}
		unset( $tax );
	}

	/**
	 * Creates the initial post types when 'init' action is fired.
	 *
	 * @since 2.9.0
	 */
	function create_initial_post_types() {
		register_post_type( 'post', array(
			'labels'           => array(
				'name_admin_bar' => _x( 'Post', 'add new on admin bar' ),
			),
			'public'           => true,
			'_builtin'         => true, /* internal use only. don't use this when registering your own post type. */
			'_edit_link'       => 'post.php?post=%d', /* internal use only. don't use this when registering your own post type. */
			'capability_type'  => 'post',
			'map_meta_cap'     => true,
			'hierarchical'     => false,
			'rewrite'          => false,
			'query_var'        => false,
			'delete_with_user' => true,
			'supports'         => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'trackbacks',
				'custom-fields',
				'comments',
				'revisions',
				'post-formats'
			),
		) );

		register_post_type( 'page', array(
			'labels'             => array(
				'name_admin_bar' => _x( 'Page', 'add new on admin bar' ),
			),
			'public'             => true,
			'publicly_queryable' => false,
			'_builtin'           => true, /* internal use only. don't use this when registering your own post type. */
			'_edit_link'         => 'post.php?post=%d', /* internal use only. don't use this when registering your own post type. */
			'capability_type'    => 'page',
			'map_meta_cap'       => true,
			'hierarchical'       => true,
			'rewrite'            => false,
			'query_var'          => false,
			'delete_with_user'   => true,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes', 'custom-fields', 'comments', 'revisions' ),
		) );

		register_post_type( 'attachment', array(
			'labels'            => array(
				'name'           => _x( 'Media', 'post type general name' ),
				'name_admin_bar' => _x( 'Media', 'add new from admin bar' ),
				'add_new'        => _x( 'Add New', 'add new media' ),
				'edit_item'      => __( 'Edit Media' ),
				'view_item'      => __( 'View Attachment Page' ),
			),
			'public'            => true,
			'show_ui'           => true,
			'_builtin'          => true, /* internal use only. don't use this when registering your own post type. */
			'_edit_link'        => 'post.php?post=%d', /* internal use only. don't use this when registering your own post type. */
			'capability_type'   => 'post',
			'capabilities'      => array(
				'create_posts' => 'upload_files',
			),
			'map_meta_cap'      => true,
			'hierarchical'      => false,
			'rewrite'           => false,
			'query_var'         => false,
			'show_in_nav_menus' => false,
			'delete_with_user'  => true,
			'supports'          => array( 'title', 'author', 'comments' ),
		) );
		add_post_type_support( 'attachment:audio', 'thumbnail' );
		add_post_type_support( 'attachment:video', 'thumbnail' );

		register_post_type( 'revision', array(
			'labels'           => array(
				'name'          => __( 'Revisions' ),
				'singular_name' => __( 'Revision' ),
			),
			'public'           => false,
			'_builtin'         => true, /* internal use only. don't use this when registering your own post type. */
			'_edit_link'       => 'revision.php?revision=%d', /* internal use only. don't use this when registering your own post type. */
			'capability_type'  => 'post',
			'map_meta_cap'     => true,
			'hierarchical'     => false,
			'rewrite'          => false,
			'query_var'        => false,
			'can_export'       => false,
			'delete_with_user' => true,
			'supports'         => array( 'author' ),
		) );

		register_post_type( 'nav_menu_item', array(
			'labels'           => array(
				'name'          => __( 'Navigation Menu Items' ),
				'singular_name' => __( 'Navigation Menu Item' ),
			),
			'public'           => false,
			'_builtin'         => true, /* internal use only. don't use this when registering your own post type. */
			'hierarchical'     => false,
			'rewrite'          => false,
			'delete_with_user' => false,
			'query_var'        => false,
		) );

		register_post_status( 'publish', array(
			'label'       => _x( 'Published', 'post' ),
			'public'      => true,
			'_builtin'    => true, /* internal use only. */
			'label_count' => _n_noop( 'Published <span class="count">(%s)</span>', 'Published <span class="count">(%s)</span>' ),
		) );

		register_post_status( 'future', array(
			'label'       => _x( 'Scheduled', 'post' ),
			'protected'   => true,
			'_builtin'    => true, /* internal use only. */
			'label_count' => _n_noop( 'Scheduled <span class="count">(%s)</span>', 'Scheduled <span class="count">(%s)</span>' ),
		) );

		register_post_status( 'draft', array(
			'label'       => _x( 'Draft', 'post' ),
			'protected'   => true,
			'_builtin'    => true, /* internal use only. */
			'label_count' => _n_noop( 'Draft <span class="count">(%s)</span>', 'Drafts <span class="count">(%s)</span>' ),
		) );

		register_post_status( 'pending', array(
			'label'       => _x( 'Pending', 'post' ),
			'protected'   => true,
			'_builtin'    => true, /* internal use only. */
			'label_count' => _n_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>' ),
		) );

		register_post_status( 'private', array(
			'label'       => _x( 'Private', 'post' ),
			'private'     => true,
			'_builtin'    => true, /* internal use only. */
			'label_count' => _n_noop( 'Private <span class="count">(%s)</span>', 'Private <span class="count">(%s)</span>' ),
		) );

		register_post_status( 'trash', array(
			'label'                     => _x( 'Trash', 'post' ),
			'internal'                  => true,
			'_builtin'                  => true, /* internal use only. */
			'label_count'               => _n_noop( 'Trash <span class="count">(%s)</span>', 'Trash <span class="count">(%s)</span>' ),
			'show_in_admin_status_list' => true,
		) );

		register_post_status( 'auto-draft', array(
			'label'    => 'auto-draft',
			'internal' => true,
			'_builtin' => true, /* internal use only. */
		) );

		register_post_status( 'inherit', array(
			'label'               => 'inherit',
			'internal'            => true,
			'_builtin'            => true, /* internal use only. */
			'exclude_from_search' => false,
		) );
	}

	function the_post_types_dashboard_right_now() {

		foreach ( array( 'post', 'page' ) as $post_type ) {
			$num_posts = wp_count_posts( $post_type );
			if ( $num_posts && $num_posts->publish ) {
				if ( 'post' == $post_type ) {
					$text = _n( '%s Post', '%s Posts', $num_posts->publish );
				} else {
					$text = _n( '%s Page', '%s Pages', $num_posts->publish );
				}
				$text             = sprintf( $text, number_format_i18n( $num_posts->publish ) );
				$post_type_object = get_post_type_object( $post_type );
				if ( $post_type_object && current_user_can( $post_type_object->cap->edit_posts ) ) {
					printf( '<li class="%1$s-count"><a href="edit.php?post_type=%1$s">%2$s</a></li>', $post_type, $text );
				} else {
					printf( '<li class="%1$s-count"><span>%2$s</span></li>', $post_type, $text );
				}

			}
		}
	}

	function future_dashboard_posts() {

		return wp_dashboard_recent_posts( array(
			'max'    => 5,
			'status' => 'future',
			'order'  => 'ASC',
			'title'  => __( 'Publishing Soon' ),
			'id'     => 'future-posts',
		) );

	}

	function recent_dashboard_posts() {

		return wp_dashboard_recent_posts( array(
			'max'    => 5,
			'status' => 'future',
			'order'  => 'ASC',
			'title'  => __( 'Publishing Soon' ),
			'id'     => 'future-posts',
		) );

	}

}

_WP_Post_Types::on_load();

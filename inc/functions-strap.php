<?php

/**
 * Bootstrap menu class injection
 */
class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {
	/**
	 * The starting level of the menu.
	 *
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth Depth of page. Used for padding.
	 * @param mixed  $args Rest of arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"dropdown-menu\" role=\"menu\">\n";
	}

	/**
	 * Open element.
	 *
	 * @param string $output      Passed by reference. Used to append additional content.
	 * @param object $data_object Menu item data object.
	 * @param int    $depth       Depth of menu item. Used for padding.
	 * @param mixed  $args        Rest arguments.
	 * @param int    $id          Element's ID.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_el()
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $data_object->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li class="divider" role="presentation">';
		} elseif ( strcasecmp( $data_object->title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li class="divider" role="presentation">';
		} elseif ( strcasecmp( $data_object->attr_title, 'dropdown-header' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li class="dropdown-header" role="presentation">' . esc_html( $data_object->title );
		} elseif ( strcasecmp( $data_object->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li class="disabled" role="presentation"><a href="#">' . esc_html( $data_object->title ) . '</a>';
		} else {
			$class_names = $value = '';
			$classes     = empty( $data_object->classes ) ? array() : (array) $data_object->classes;
			$classes[]   = 'nav-item menu-item-' . $data_object->ID;
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $data_object, $args ) );
			/*
			if ( $args->has_children )
			  $class_names .= ' dropdown';
			*/
			if ( $args->has_children && $depth === 0 ) {
				$class_names .= ' dropdown';
			} elseif ( $args->has_children && $depth > 0 ) {
				$class_names .= ' dropdown-submenu';
			}
			if ( in_array( 'current-menu-item', $classes ) ) {
				$class_names .= ' active';
			}
			// remove Font Awesome icon from classes array and save the icon
			// we will add the icon back in via a <span> below so it aligns with
			// the menu item
			if ( in_array( 'fa', $classes ) ) {
				$key         = array_search( 'fa', $classes );
				$icon        = $classes[ $key + 1 ];
				$class_names = str_replace( $classes[ $key + 1 ], '', $class_names );
				$class_names = str_replace( $classes[ $key ], '', $class_names );
			}

			$class_names    = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			$id             = apply_filters( 'nav_menu_item_id', 'menu-item-' . $data_object->ID, $data_object, $args );
			$id             = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			$output        .= $indent . '<li' . $id . $value . $class_names . '>';
			$atts           = array();
			$atts['title']  = ! empty( $data_object->title ) ? $data_object->title : '';
			$atts['target'] = ! empty( $data_object->target ) ? $data_object->target : '';
			$atts['rel']    = ! empty( $data_object->xfn ) ? $data_object->xfn : '';
			// If item has_children add atts to a.

			if ( $args->has_children && $depth === 0 ) {
				$atts['href']        = ! empty( $data_object->url ) ? $data_object->url : '';
				$atts['data-toggle'] = 'hover';
				$atts['class']       = 'nav-link dropdown-toggle';
			} else {
				$atts['href']  = ! empty( $data_object->url ) ? $data_object->url : '';
				$atts['class'] = 'nav-link';
			}
			$atts       = apply_filters( 'nav_menu_link_attributes', $atts, $data_object, $args );
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			$item_output = $args->before;
			// Font Awesome icons
			if ( ! empty( $icon ) ) {
				$item_output .= '<a' . $attributes . '><span class="fa ' . esc_attr( $icon ) . '"></span>&nbsp;';
			} else {
				$item_output .= '<a' . $attributes . '>';
			}
			$item_output .= $args->link_before . apply_filters(
				'the_title',
				$data_object->title,
				$data_object->ID
			) . $args->link_after;
			$item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
			$item_output .= $args->after;
			$output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $data_object, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array  $children_elements List of elements to continue traversing.
	 * @param int    $max_depth Max depth to traverse.
	 * @param int    $depth Depth of current element.
	 * @param array  $args
	 * @param string $output Passed by reference. Used to append additional content.
	 *
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}
		$id_field = $this->db_fields['id'];
		// Display this element.
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {
			extract( $args );
			$fb_output = null;
			if ( $container ) {
				$fb_output = '<' . $container;
				if ( $container_class ) {
					$fb_output .= ' class="' . $container_class . '"';
				}
				if ( $container_id ) {
					$fb_output .= ' id="' . $container_id . '"';
				}
				$fb_output .= '>';
			}
			$fb_output .= '<ul';
			if ( $menu_class ) {
				$fb_output .= ' class="' . $menu_class . '"';
			}
			if ( $menu_id ) {
				$fb_output .= ' id="' . $menu_id . '"';
			}
			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';
			if ( $container ) {
				$fb_output .= '</' . $container . '>';
			}
			echo $fb_output;
		}
	}
}


// Custom Page Menu
class Bootstrap_Page_Menu extends Walker_Page {

	/**
	 * @see Walker::start_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth Depth of page. Used for padding.
	 * @param array  $args
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Page data object.
	 * @param int    $depth Depth of page. Used for padding.
	 * @param int    $current_page Page ID.
	 * @param array  $args
	 */
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( $depth ) {
			$indent = str_repeat( "\t", $depth );
		} else {
			$indent = '';
		}

		$css_class = array( 'page_item', 'page-item-' . $page->ID );

		$has_childen = (bool) isset( $args['pages_with_children'][ $page->ID ] );
		if ( $has_childen ) {
			$css_class[] = 'page_item_has_children';
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'active current_page_ancestor';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'active current_page_item';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'active current_page_parent';
			}
		} elseif ( $page->ID == get_option( 'page_for_posts' ) ) {
			$css_class[] = 'active current_page_parent';
		}
		if ( $has_childen && $depth > 0 ) {
			$css_class[] = 'dropdown-submenu';
		}

		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

		if ( '' === $page->post_title ) {
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after']  = empty( $args['link_after'] ) ? '' : $args['link_after'];
		if ( $has_childen && $depth == 0 ) {
			$args['link_after'] .= ' <b class="caret"></b>';
		}

		$output .= $indent . sprintf(
			'<li class="%s"><a class="nav-link" href="%s"%s>%s%s%s</a>',
			$css_classes,
			get_permalink( $page->ID ),
			$has_childen ? ' class="dropdown-toggle" data-toggle="dropdown" data-target="#"' : '',
			$args['link_before'],
			get_the_title( $page->ID ), // apply_filters( 'the_title', get_field('menu', $page->ID), $page->ID ),
			$args['link_after']
		);

		if ( ! empty( $args['show_date'] ) ) {
			if ( 'modified' == $args['show_date'] ) {
				$time = $page->post_modified;
			} else {
				$time = $page->post_date;
			}

			$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$output     .= ' ' . mysql2date( $date_format, $time );
		}
	}
}

/**
 * Bootstrap styled Caption shortcode.
 * Hat tip: http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
add_filter( 'img_caption_shortcode', 'bootstrap_img_caption_shortcode', 10, 3 );

if ( ! function_exists( 'bootstrap_img_caption_shortcode' ) ) {
	function bootstrap_img_caption_shortcode( $output, $attr, $content ) {

		/* We're not worried abut captions in feeds, so just return the output here. */
		if ( is_feed() ) {
			return '';
		}

		extract(
			shortcode_atts(
				array(
					'id'      => '',
					'align'   => 'alignnone',
					'width'   => '',
					'caption' => '',
				),
				$attr
			)
		);

		if ( 1 > (int) $width || empty( $caption ) ) {
			return $content;
		}

		if ( $id ) {
			$id = 'id="' . esc_attr( $id ) . '" ';
		}

		return '<div ' . $id . 'class="thumbnail ' . esc_attr( $align ) . '" style="width: ' . $width . 'px">'
			. do_shortcode( $content ) . '<div class="caption">' . $caption . '</div></div>';
	}
}

/**
 * Bootstrap styled Comment form.
 */

add_filter( 'comment_form_defaults', 'bootstrap_comment_form_defaults', 10, 1 );
if ( ! function_exists( 'bootstrap_comment_form_defaults' ) ) {
	function bootstrap_comment_form_defaults( $defaults ) {
		$commenter                 = wp_get_current_commenter();
		$req                       = get_option( 'require_name_email' );
		$aria_req                  = ( $req ? " aria-required='true'" : '' );
		$defaults['fields']        = array(
			'author' => '<div class="form-group comment-form-author row">' .
				'<label for="author" class="col-md-2 control-label">' . __( 'Name', 'dd_theme' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
				'<div class="col-md-10">' .
				'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"  class="form-control"' . $aria_req . ' />' .
				'</div>' .
				'</div>',
			'email'  => '<div class="form-group comment-form-email row">' .
				'<label for="email" class="col-md-2 control-label">' . __( 'Email', 'dd_theme' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
				'<div class="col-md-10">' .
				'<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '"  class="form-control"' . $aria_req . ' />' .
				'</div>' .
				'</div>',
			'url'    => '<div class="form-group comment-form-url row">' .
				'<label for="url" class="col-md-2 control-label"">' . __( 'Website', 'dd_theme' ) . '</label>' .
				'<div class="col-md-10">' .
				'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '"  class="form-control" />' .
				'</div>' .
				'</div>',
		);
		$defaults['comment_field'] = '<div class="form-group comment-form-comment row">' .
			'<label for="comment" class="col-md-2 control-label">' . _x( 'Comment', 'noun', 'dd_theme' ) . '</label>' .
			'<div class="col-md-10">' .
			'<textarea id="comment" name="comment" aria-required="true" class="form-control" rows="8"></textarea>' .
			'<span class="help-block form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</span>' .
			'</div>' .
			'</div>';

		$defaults['comment_notes_after'] = '<div class="form-group comment-form-submit">';

		return $defaults;
	}
}

add_action( 'comment_form', 'bootstrap_comment_form', 10, 1 );
if ( ! function_exists( 'bootstrap_comment_form' ) ) {
	function bootstrap_comment_form( $post_id ) {
		// closing tag for 'comment_notes_after'
		echo '</div><!-- .form-group .comment-form-submit -->';
	}
}


if ( ! function_exists( 'bootstrap_searchform_class' ) ) {
	/**
	 * @param $bt
	 * @deprecated
	 * @return string
	 */
	function bootstrap_searchform_class( $bt = array() ) {
		$caller = basename( $bt[1]['file'], '.php' );
		switch ( $caller ) {
			case 'header':
				return 'navbar-form navbar-right';
			default:
				return 'form-inline';
		}
	}
}

add_filter( 'embed_oembed_html', 'bootstrap_oembed_html', 10, 4 );

if ( ! function_exists( 'bootstrap_oembed_html' ) ) {
	/**
	 * Filters the oEmbed HTML to wrap YouTube embeds in a responsive container.
	 *
	 * @param string $html    The HTML embed code provided by oEmbed.
	 * @param string $url     The URL of the embedded content.
	 * @param array  $attr    An array of attributes for the embed element.
	 * @param int    $post_ID The current post ID.
	 *
	 * @return string The filtered oEmbed HTML.
	 */
	function bootstrap_oembed_html( $html, $url, $attr, $post_ID ) {
		if ( str_contains( $url, 'youtube.com' ) ) {
			return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
		} else {
			return $html;
		}
	}
}

if ( ! function_exists( 'understrap_pagination' ) ) :
	function understrap_pagination() {
		if ( is_singular() ) {
			return;
		}

		global $wp_query;

		/** Stop execution if there's only 1 page */
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}

		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max   = intval( $wp_query->max_num_pages );

		/**    Add current page to the array */
		if ( $paged >= 1 ) {
			$links[] = $paged;
		}

		/**    Add the pages around the current page to the array */
		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}

		echo '<nav aria-label="Page navigation" class="understrap-pagination"><ul class="pagination ">' . "\n";

		/**    Link to first page, plus ellipses if necessary */
		if ( ! in_array( 1, $links, true ) ) {
			$class = 1 === $paged ? ' class="active page-item"' : ' class="page-item"';

			printf(
				'<li %s><a class="page-link" href="%s"><i class="fa fa-step-backward" aria-hidden="true"></i></a></li>' . "\n",
				esc_attr( $class ),
				esc_url( get_pagenum_link( 1 ) ),
				'1'
			);

			/**    Previous Post Link */
			if ( get_previous_posts_link() ) {
				printf(
					'<li class="page-item page-item-direction page-item-prev"><span class="page-link">%1$s</span></li> ' . "\n",
					get_previous_posts_link( '<span aria-hidden="true">&laquo;</span><span class="sr-only">Previous page</span>' )
				);
			}

			if ( ! in_array( 2, $links ) ) {
				echo '<li class="page-item"></li>';
			}
		}

		// Link to current page, plus 2 pages in either direction if necessary.
		sort( $links );
		foreach ( (array) $links as $link ) {
			$class = $paged == $link ? ' class="active page-item"' : ' class="page-item"';
			printf(
				'<li %s><a href="%s" class="page-link">%s</a></li>' . "\n",
				$class,
				esc_url( get_pagenum_link( $link ) ),
				$link
			);
		}

		// Next Post Link.
		if ( get_next_posts_link() ) {
			printf(
				'<li class="page-item page-item-direction page-item-next"><span class="page-link">%s</span></li>' . "\n",
				get_next_posts_link( '<span aria-hidden="true">&raquo;</span><span class="sr-only">Next page</span>' )
			);
		}

		// Link to last page, plus ellipses if necessary.
		if ( ! in_array( $max, $links ) ) {
			if ( ! in_array( $max - 1, $links ) ) {
				echo '<li class="page-item"></li>' . "\n";
			}

			$class = $paged == $max ? ' class="active "' : ' class="page-item"';
			printf(
				'<li %s><a class="page-link" href="%s" aria-label="Next"><span aria-hidden="true"><i class="fa fa-step-forward" aria-hidden="true"></i></span><span class="sr-only">%s</span></a></li>' . "\n",
				$class . '',
				esc_url( get_pagenum_link( esc_html( $max ) ) ),
				esc_html( $max )
			);
		}

		echo '</ul></nav>' . "\n";
	}

endif;

if ( ! function_exists( 'pagination' ) ) {
	function pagination( $pages = '', $range = 1 ) {
		$showitems = ( $range * 2 ) + 1;
		global $wp_query;
		$paged = (int) $wp_query->query_vars['paged'];
		if ( empty( $paged ) || $paged == 0 ) {
			$paged = 1;
		}
		if ( $pages == '' ) {
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}
		if ( 1 != $pages ) {
			echo '<div class="pagination pagination__posts"><ul>';
			if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
				echo "<li class='first'><a href='" . get_pagenum_link( 1 ) . "'>First</a></li>";
			}
			if ( $paged > 1 && $showitems < $pages ) {
				echo "<li class='prev'><a href='" . get_pagenum_link( $paged - 1 ) . "'>Prev</a></li>";
			}
			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					echo ( $paged == $i ) ? '<li class="active"><span>' . $i . '</span></li>' : "<li><a href='" . get_pagenum_link( $i ) . "' class=\"inactive\">" . $i . '</a></li>';
				}
			}
			if ( $paged < $pages && $showitems < $pages ) {
				echo "<li class='next'><a href=\"" . get_pagenum_link( $paged + 1 ) . '">Next</a></li>';
			}
			if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
				echo "<li class='last'><a href='" . get_pagenum_link( $pages ) . "'>Last</a></li>";
			}
			echo "</ul></div>\n";
		}
	}
}

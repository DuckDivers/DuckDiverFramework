<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Duck Diver Custom
 */

if ( ! function_exists( 'dd_theme_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function dd_theme_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		}

		$modified_time = '<time class="updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		$modified_string = sprintf(
			$modified_time,
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( 'Posted on %s', 'post date', 'dd_theme' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		if ( get_the_date() !== get_the_modified_date() ) {
			$posted_on .= sprintf(
				esc_html_x( ' Updated on %s', 'post modified date', 'dd_theme' ),
				$modified_string
			);
		}
		$byline = sprintf(
			esc_html_x( 'by %s', 'post author', 'dd_theme' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
 endif;

if ( ! function_exists( 'dd_theme_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function dd_theme_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'dd_theme' ) );
			if ( $categories_list && dd_theme_categorized_blog() ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'dd_theme' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'dd_theme' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'dd_theme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'dd_theme' ), esc_html__( '1 Comment', 'dd_theme' ), esc_html__( '% Comments', 'dd_theme' ) );
			echo '</span>';
		}

		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'dd_theme' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function dd_theme_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'dd_theme_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'dd_theme_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so dd_theme_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so dd_theme_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in dd_theme_categorized_blog.
 */
function dd_theme_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	delete_transient( 'dd_theme_categories' );
}
add_action( 'edit_category', 'dd_theme_category_transient_flusher' );
add_action( 'save_post', 'dd_theme_category_transient_flusher' );

if ( ! function_exists( 'understrap_post_nav' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 *
	 * @global WP_Post|null $post The current post.
	 */
	function understrap_post_nav() {
		global $post;
		if ( ! $post ) {
			return;
		}

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );
		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="container navigation post-navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'understrap' ); ?></h2>
			<div class="d-flex nav-links justify-content-between">
				<?php
				if ( get_previous_post_link() ) {
					previous_post_link( '<span class="nav-previous">%link</span>', _x( '<i class="fa fa-angle-left"></i>&nbsp;%title', 'Previous post link', 'understrap' ) );
				}
				if ( get_next_post_link() ) {
					next_post_link( '<span class="nav-next">%link</span>', _x( '%title&nbsp;<i class="fa fa-angle-right"></i>', 'Next post link', 'understrap' ) );
				}
				?>
			</div><!-- .nav-links -->
		</nav><!-- .post-navigation -->
		<?php
	}
}

if ( ! function_exists( 'dd_custom_excerpt' ) ) {
	/**
	 * Generates a custom excerpt for a given post, either using its explicit excerpt
	 * or deriving it from its content with a fallback word length.
	 *
	 * @param int $post_id The ID of the post for which the excerpt is generated.
	 *
	 * @return string The generated excerpt for the post.
	 */
	function dd_custom_excerpt( $post_id ) {
		if ( has_excerpt( $post_id ) ) {
			$excerpt = get_the_excerpt( $post_id );
		} else {
			$excerpt_length = ( get_theme_mod( 'dd_blog_excerpt_length' ) ) ? get_theme_mod( 'dd_blog_excerpt_length' ) : 55;
			$the_content    = get_post_field( 'post_content', $post_id );
			$the_content    = strip_shortcodes( $the_content );
			$excerpt        = wp_trim_words( $the_content, $excerpt_length, '' );
		}
		return $excerpt;
	}
}

if ( ! function_exists( 'dd_get_default_blog_image' ) ) {
	/**
	 * Outputs the default blog image for a given post.
	 *
	 * If the post has a featured image, it will use that. If not, it will search for the first image
	 * in the post content. If no image is found, it will output a fallback image configured in the theme settings.
	 *
	 * @param int|null $_post_id The ID of the post to retrieve the image for. Defaults to null for the current post.
	 *
	 * @return void This function directly echoes the HTML for the image and does not return a value.
	 */
	function dd_get_default_blog_image( $_post_id = null ) {
		if ( has_post_thumbnail( $_post_id ) ) {
			echo get_the_post_thumbnail( $_post_id, 'blog-archive-width' );
		} else {
			$post_content = get_post_field( 'post_content', $_post_id );
			preg_match( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches );
			if ( ! empty( $matches ) ) {
				$first_img_url = $matches[1];
				echo '<img src="' . esc_url( $first_img_url ) . '" class="wp-post-thumbnail" />';
			} else {
				echo '<img src="' . esc_url( get_theme_mod( 'dd_default_blog_image' ) ) . '" class="wp-post-thumbnail" />';
			}
		}
	}
}

if ( ! function_exists( 'dd_offset_archive' ) ) {
	/**
	 * Modifies the main query to adjust the offset and ensure only published posts are queried.
	 *
	 * This function targets the main query on the homepage or archive pages, setting the post status
	 * to 'publish' and applying an offset to exclude the first post.
	 *
	 * @param WP_Query $query The main query object to modify. This should be passed by reference.
	 *
	 * @return void The function directly modifies the passed query object and does not return a value.
	 */
	function dd_offset_archive( $query ) {
		if ( $query->is_main_query() && ( $query->is_home() || $query->is_archive() ) ) {
			$query->set( 'post_status', 'publish' );
			$query->set( 'offset', '1' );
		}
	}
}
add_action( 'pre_get_posts', 'dd_offset_archive' );

if ( ! function_exists( 'dd_breadcrumbs' ) ) {
	/**
	 * Generates breadcrumb navigation for the given post.
	 *
	 * This function creates a hierarchical breadcrumb trail for a given post ID. It includes links to ancestor categories
	 * (for posts) or parent pages (for pages), and always starts with a link to the Home page.
	 *
	 * @param int|null $post_id The ID of the post to generate breadcrumbs for. Defaults to null, in which case it uses
	 *                          the global post object/queried object where appropriate.
	 *
	 * @return string The HTML output for the breadcrumb navigation.
	 */
	function dd_breadcrumbs( $post_id = null ) {
		$parts      = array();
		$separator  = ' / ';
		$home_label = __( 'Home', 'dd_theme' );
		$home_url   = home_url( '/' );
		if ( apply_filters( 'dd_breadcrumbs_show_home', true ) ) {
			$parts[] = '<a class="breadcrumb-home" href="' . esc_url( $home_url ) . '">' . esc_html( $home_label ) . '</a>';
		}
		$parts[] = '<a class="breadcrumb-home" href="' . esc_url( $home_url ) . '">' . esc_html( $home_label ) . '</a>';

		// Try to resolve the context if no explicit post_id is provided.
		if ( null === $post_id && is_singular() ) {
			$post_id = get_the_ID();
		}

		// Category archive context.
		if ( is_category() ) {
			$cat = get_queried_object();
			if ( $cat && ! is_wp_error( $cat ) ) {
				$ancestors = get_ancestors( $cat->term_id, 'category' );
				$ancestors = array_reverse( $ancestors );
				foreach ( $ancestors as $ancestor_id ) {
					$name    = get_cat_name( $ancestor_id );
					$parts[] = '<a href="' . esc_url( get_category_link( $ancestor_id ) ) . '">' . esc_html( $name ) . '</a>';
				}
				$parts[] = '<span class="breadcrumb-current">' . esc_html( $cat->name ) . '</span>';
			}
		}
		// Single post context.
		elseif ( $post_id && is_singular( 'post' ) ) {
			$categories = get_the_category( $post_id );
			if ( ! empty( $categories ) ) {
				// Choose a category. Prefer the one with the deepest hierarchy.
				$chosen    = null;
				$max_depth = -1;
				foreach ( $categories as $cat ) {
					$depth = count( get_ancestors( $cat->term_id, 'category' ) );
					if ( $depth > $max_depth ) {
						$max_depth = $depth;
						$chosen    = $cat;
					}
				}
				if ( $chosen ) {
					$ancestors = get_ancestors( $chosen->term_id, 'category' );
					$ancestors = array_reverse( $ancestors );
					foreach ( $ancestors as $ancestor_id ) {
						$name    = get_cat_name( $ancestor_id );
						$parts[] = '<a href="' . esc_url( get_category_link( $ancestor_id ) ) . '">' . esc_html( $name ) . '</a>';
					}
					$parts[] = '<a href="' . esc_url( get_category_link( $chosen->term_id ) ) . '">' . esc_html( $chosen->name ) . '</a>';
				}
			}
			$parts[] = '<span class="breadcrumb-current">' . esc_html( get_the_title( $post_id ) ) . '</span>';
		}
		// Page (and other singular non-post) context.
		elseif ( $post_id && is_singular() ) {
			$ancestors = get_post_ancestors( $post_id );
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $ancestor_id ) {
				$parts[] = '<a href="' . esc_url( get_permalink( $ancestor_id ) ) . '">' . esc_html( get_the_title( $ancestor_id ) ) . '</a>';
			}
			$parts[] = '<span class="breadcrumb-current">' . esc_html( get_the_title( $post_id ) ) . '</span>';
		}
		// Fallback: leave only Home for other contexts (search, author, etc.).

		$output = '<nav class="dd-breadcrumbs" aria-label="Breadcrumbs">' . implode( '<span class="breadcrumb-sep">' . esc_html( $separator ) . '</span>', $parts ) . '</nav>';
		return $output;
	}
}


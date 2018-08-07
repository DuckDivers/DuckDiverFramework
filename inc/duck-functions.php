<?php // Theme Specific Functions 

// Add Category Column to FAQ

add_filter( 'manage_taxonomies_for_faq_columns', 'faq_type_columns' );
function faq_type_columns( $taxonomies ) {
    $taxonomies[] = 'faq_category';
    return $taxonomies;
}
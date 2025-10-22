<?php
/**
 * The template for displaying search forms in Duck Diver Custom
 *
 * @package Duck Diver Framework
 */
?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search" class="form-inline d-flex flex-nowrap">
	<div class="form-group">
		<input type="text" class="form-control" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'dd_theme' ); ?>"/>
	</div>
	<button type="submit" class="btn btn-default"><?php echo wp_kses_post( apply_filters( 'dd_search_button', '<i class="fa fa-search"></i>' ) ); ?></button>
</form>

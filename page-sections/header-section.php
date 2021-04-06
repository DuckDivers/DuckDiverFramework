<?php /*section name : Header Section */ ?>
	<section id="logo" class="container">
		<div class="site-branding">
        <?php if ( get_theme_mod( 'theme_logo' ) ) : ?>
		    <div class="site-logo">
            	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url( get_theme_mod( 'theme_logo' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
        </div>
		<?php else : ?>
                    <?php if ( is_front_page() || is_home() ) : ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php else : ?>
                        <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                    <?php endif;
        endif;
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
			<div class="tagline"><p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p></div>
			<?php endif; ?>
		</div><!-- .site-branding -->
	</section>
    <!-- Main Navigation -->
    <?php get_template_part('page-sections/navigation', 'section'); ?>
    <!-- .Main Navigation -->
